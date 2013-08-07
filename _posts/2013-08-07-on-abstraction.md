---
layout: post
date: 2013-08-07 09:56
title: On abstraction
tags: Lisp, abstraction, abstract data types, pattern matching, functional programming
---

Functional programming (FP) has gathered a lot of momentum in the past couple of years. There's an increasing number of job postings requiring FP skills in languages such as Scala, F#, Haskell or even OCaml. Everyone and their dog is writing blog posts or pushing tweets on how productive FP has made them, and why everyone should start using it. And it's good that FP has finally made it into mainstream, because it brings quite a few neat ideas to the game. But, as with any new fad, there are people bound to jump on the FP bandwagon just because it happens to be "the thing" this year. The same happened with OOP, Java, Ruby and so on.

One of the favourite taglines of FP advocacy is that FP languages allow you to write concise and robust software. To quote Haskell's website frontpage:

> Haskell is an advanced purely-functional programming language. An open-source product of more than twenty years of cutting-edge research, it allows rapid development of robust, concise, correct software.

Let's get over the fact that they don't give any proof for their claims, beside some user testimonials---which are inevitably highly context sensitive or biased or both. That's because it's hard to quantify such claims. For an in-depth discussion on this matter read [this essay](http://tagide.com/blog/2012/03/research-in-programming-languages/).

What I'd like to focus on for the rest of this post is one of the most common features that FP advocates like to mention when it comes to the concisencess of programs written in FP languages: the combination of Algebraic Data Types (ADT) and pattern matching (PM). While making programs shorter, PM makes the less resiliant to change, thus less robust. That is because _pattern matching breaks abstraction_.

## Pattern matching breaks abstraction

Pattern matching is one of those features that makes you go "Wow", when you first see it. It allows programmers to succintly decompose the values that they want to manipulate, according to the "shape" of their types. In order to illustrate PM, let's use one of the most common examples: writing an interpreter for a toy arithmetic language. I'll provide OCaml code for this post, but it's similar in Haskell and Scala.

We begin by defining the data type for expressions:

{% highlight ocaml %}

type exp =
  | Num of int
  | Sum of exp * exp
  | Div of exp * exp

{% endhighlight %}

This is simple enough. Even if you're not versed in OCaml the defition of the `exp` type is easy to read: an expression is either an integer, the sum of two other expressions, or the divion of two expressions. 

Now let's write an interpreter for expressions. This is trivial as well:

{% highlight ocaml %}

let rec eval = function
  | Num n -> n
  | Sum (e1, e2) -> (eval e1) + (eval e2)
  | Div (e1, e2) -> 
    let n1, n2 = eval e1, eval e2 in
    if n2 = 0 then report_error "division by 0"
    else n1 / n2

{% endhighlight %}

That's really neat. The definition of `eval` closely follows the definition of the `exp` data type. It's partly thanks to PM that FP languages excel at writing compilers and interpreters. This is by design: one of the first FP languages, ML as designed by Robin Milner back in the 70s, was the meta language of the LCF theorem prover, and as such it needed to make symbolic manipulation easy.

But as I said, _pattern matching breaks abstraction_. To see why, let's assume that we need to add the source location of each expression in order to make error reporting more helpful. That's easy, we can just alter our type for expressions like this:

{% highlight ocaml %}

type exp =
  | Num of int * location
  | Sum of (exp * exp) * location
  | Div of (exp * exp) * location
and location = int * int

{% endhighlight %}

Since we've changed the "shape" of the type, our evaluation function will have to change accordingly:

{% highlight ocaml %}

let rec eval = function
  | Num (n, _) -> n
  | Sum ((e1, e2), _) -> (eval e1) + (eval e2)
  | Div ((e1, e2), location) -> 
    let n1, n2 = eval e1, eval e2 in
    if n2 = 0 then report_error_at location "division by 0"
    else n1 / n2

{% endhighlight %}

This is why pattern matching breaks abstraction: when we pattern match on a value, we're taking it apart bit by bit so we need to know what it's "shape" is, i.e. we're working with _concrete types_. This is the exact opposite of an abstract type, a type whose representation does not matter.

This is actually a well known problem in the world of compilers and interpreters implemented in functional languages, known as the _AST Typing Problem_. For an in-depth discussion [head to LtU](http://lambda-the-ultimate.org/node/4170). More generally, the tension between PM and data abstraction has been noted [as far back as '87](http://www.cs.tufts.edu/~nr/cs257/archive/views/wadler.pdf). The proposed solution, called _views_, hasn't gained much traction in the FP community. F# has adopted _active patterns_, which are another approach for mixing PM and abstract data types, but PM is still predominant in F# programs.