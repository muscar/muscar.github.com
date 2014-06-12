# No, Swift doesn't solve the null reference problem

There's been a lot of hype surrounding Apple's Swift language since its release.
Some have claimed that, thanks to its support for optional types, it solves the
null reference problem, Tony Hoare's "billion-dollar mistake". But that's not
quite true. Let's see why.

## Swift optionals, the good parts

While Swift is not the first language to have optional types--Haskell and ML
have had optional types for quite some time--they are more closely integrated
with the language. The language offers a terse notation for optional types `T?`,
and special syntax which makes working with them easier than in languages that
only offer library support for them. Let's look at an example

    func tryInc(n: Int?) -> Int? {
        if let x = n {
            return x + 1
        }
        return nil
    }

The `tryInc` function increments an integer value only if it's non `nil`. The
`if let` part is what Swift's designers call _optional binding_. It's a terser
way of working with optional values than having to pattern match on the value,
like you'd have to do in Haskell or ML. Nice as it may be, this approach doesn't
scale in Swift, and that's mainly because Swift has to play as nicely as
possible with Cocoa.

## Swift optionals, the bad parts

Most Cocoa types have reference semantics, with `nil` being the de facto way of
signaling the absence of a meaningful value. It's only natural for Swift to
model them as optional types. And that's a perfectly reasonable approach. But,
since such types are pervasive in Cocoa, it would become tedious to use optional
binding everywhere to test if the value has a type or not--kind of like
`null` checking in Java or C#. So the Swift designers introduced _implicitly
unwrapped optionals_, denoted as `T!`:

> Sometimes it is clear from a program’s structure that an optional will always
> have a value, after that value is first set. In these cases, it is useful to
> remove the need to check and unwrap the optional’s value every time it is
> accessed, because it can be safely assumed to have a value all of the time.

> Excerpt From: Apple Inc. "The Swift Programming Language." iBooks. https://itun.es/ro/jEUH0.l

Using implicitly unwrapped optionals, the `tryInc` function above becomes:

    func tryIncUnsafe(n: Int!) -> Int? {
        return n + 1
    }

That's a lot terser, but also _unsafe_--yeah, I know, the name gave it away. If
you pass `tryIncUnsafe` a value it behaves like you'd expect. But try passing it
`nil`, and you'll get a runtime exception: `fatal error: Can't unwrap Optional.None`.
This is just as bad as raw pointers in C--indeed, you might say that Swift's
`T!` is equivalent to C's `T *`.

## The value of optionals

When people say that optional values help solve the null reference problem, they
are referring to the fact that in languages like Haskell or ML, the compiler
forces you to check if you actually have a meaningful value. It's a tradeoff:
people don't use optional values because it's fun or becasue it makes the code
terser, but because it makes the code _safer_. By introducing implicitly
unwrapped optionals, the Swift designers try to have their cake and eat it too:
they want the safety of optional values with the ease of use of implicit
references. But that's not going to work. Statements like "Sometimes it is clear
from a program’s structure that an optional will always have a value" sound like
hand-waving to me. Nothing is "clear" unless you formally prove it, and not even
then. As soon as you cross the borders of your program into library-land, you
lose all control over the values of your optionals. The appeal of optionals lies
in the fact that the compiler forces you to check that they have a meaningful
value. By allowing the programmer to skip these checks the usefulness of
optionals is nullified. Implicitly unwrapped optionals rely too much on the
programmers' discipline, and let's face it, programmers are not the most
disciplined human beings. People will use implicitly unwrapped optionals becasue
the're easier to use and shorter to write--as a parallel, I've already seen
plenty of Swift tutorials that use `var` even when the value doesn't need to
change. It's natural to choose the path of least resistance, but in programming
it's not always the best choice.

## In conclusion

The intent of this post is not bash Swift--it's an interesting language--but to
straighten out the issue of Swift's solving the null reference problem, which
might lull some people into a false sense of security.