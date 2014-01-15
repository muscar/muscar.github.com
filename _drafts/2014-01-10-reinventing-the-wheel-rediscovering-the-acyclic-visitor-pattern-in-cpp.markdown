---
layout: post
date: 2014-01-10 17:52
title: Reinventing the wheel---rediscovering the acyclic visitor pattern in C++ 
tags: c++, programming, design patterns, templates, generic programming, metaprogramming
---

Recently, I've returned to C++, mostly because I've been spending too much time (not) programming in functional languages. Since I'm prone to "death by lack of constraints", I figured C++ would be a good choice---I've always thought of it as a pragmatic, "get things done" kind of language. Being a language geek and all, the choice of project was obvious: a language targeting LLVM. I also figured that I'll kill two birds with one stone: get up to speed with C++11, and also get some experience with LLVM's APIs.

In this post I won't focus on the design of my little language. Instead, I'll talk about a more mundane subject: the visitor pattern.

<!-- more -->

# The problem

If you're already familiar with the visitor pattern, you can safely skip to the [next section](#decomposing-the-problem).

To illustrate the issues I'm trying to address, let's assume we're implementing a simple language featuring only integers and the addition operator:

{% highlight cpp linenos %}

struct expr { };
 
struct num : expr
{
    int value;
 
    num(int value) : value(value) { }
};
 
struct plus : expr
{
    std::unique_ptr<expr> lhs, rhs;
    
    plus(std::unique_ptr<expr> lhs, std::unique_ptr<expr> rhs)
    : lhs(std::move(lhs)), rhs(std::move(rhs))
    { }
};

{% endhighlight %}

The above code could serve as the Abstract Syntax Tree (AST) in an evaluator or compiler for our simple language. The important thing is that, once we have such an AST we would like to do something with it, i.e. pretty print it, type check it, generate code. There are many ways to do it. The simplest would be declare the operations we need as virtual methods in the `expr` base class, and implement them in each node. While this is conceptually simple, it's also not the right way to do it, because it breaks the [Single Responsibility Principle (SRP)](https://en.wikipedia.org/wiki/Single_responsibility_principle), which says that a class should do only one thing. The usual approach is to use the [visitor pattern](https://en.wikipedia.org/wiki/Visitor_pattern), which allows the addition of operations to a class hierarchy without modifying the hierarchy members. Let's implement the visitor pattern infrastructure for our simple language:

{% highlight cpp linenos %}

struct visitor;

struct expr
{
    virtual void accept(visitor &visitor) = 0;
};
 
struct num : expr
{
    int value;
 
    num(int value) : value(value) { }

    void accept(visitor &visitor);
};
 
struct plus : expr
{
    std::unique_ptr<expr> lhs, rhs;
    
    plus(std::unique_ptr<expr> lhs, std::unique_ptr<expr> rhs)
    : lhs(std::move(lhs)), rhs(std::move(rhs))
    { }

    void accept(visitor &visitor);
};

struct visitor
{
    virtual void visit(num &node) = 0;
    virtual void visit(plus &node) = 0;
};

void num::accept(visitor &visitor)
{
    visitor.visit(*this);
}

void plus::accept(visitor &visitor)
{
    visitor.visit(*this);
}

{% endhighlight %}

The key point to note is that we use [double dispatch](https://en.wikipedia.org/wiki/Double_dispatch) to apply the right operation to the right member of the class hierarchy. The first part of the double dispatch is done by the virtual method `expr::accept`, which selects the right object at runtime, while the second part is done by one of the overloads of the virtual method `visitor::visit`. While this is a clever way to use dynamic dispatch, it also creates a cyclic dependency between the visitor and visitee. It's easy to see this in the previous code listing: `expr`, and all its subclasses, use `visitor` in the signature of `accept`, and `visitor` uses each of the sublcasses of `expr` that are meant to be visited in the overloads of `visit`. It's this cyclic dependency that makes the visitor pattern awkward to use. The two main drawbacks of the visitor pattern are the considerable boilerplate that we need to write in order to implement it, and, more seriously, the fact that we need to modify every visitor when we add a new member of the class hierarchy we want to visit. Let's see if we can address these issues.

# <a name="decomposing-the-problem">Decomposing the problem</a>
