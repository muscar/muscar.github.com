# Intro to Functional Programming with OCaml

Let's start with a simple C function to compute the sum of the numbers between 1 and a given upper bound `n`:

    int count(int n)
    {
        int sum = 0;
        for (int i = 1; i <= n; ++i) {
            sum += i;
        }
        return sum;
    }
    
The code above is all about mutable state. In contrast, functional programming is all about _functions_. The same function in OCaml could be written as:

    let count n = sum (naturals n)
    
By the end of this chapter we will have all the pieces we need to write this function, but for now let's focus on the basics.

## Values

Much like in C, in OCaml you can work with various _basic values_ such as integers, characters and strings.

    # 1;;
    - : int = 1
    # 'x';;
    - : char = 'x'
    # "hello";;
    - : string = "hello"
    
The above listing is a snippet taken from an interactive OCaml session. It illustrates some of the basic values and their types.

## Bindings

To bind a value in OCaml we use the `let` keyword:

    # let n = 1 in n;;
    - : int = 1
    
The above snippet of code creates a binding between the variable `n` and value `1`. `n` is only visible in the scope introduced by the `in` keyword, i.e. if we try to evaluate `n` outside the scope of the binding OCaml will complain:

    # n;;
    Error: Unbound value n
    
Newer bindings of a name _shadow_ older ones:

    # let n = 1 in
      let m = 2 in
      let n = 3 in
      m + n;;
    - : int = 5

In this case, the second binding of `n`, the one with the value `3` shadows the first one.

## Functions as values

Let's more to more interesting stuff: functions. In OCaml functions are values just like integers, chars or strings:

    # fun x -> x + 1;;
    - : int -> int = <fun>

There are several interesting things to note. First, our function has the type `int -> int` which tells us that it expects and argument of type `int` and it returns a result of type `int`. OCaml has inferred the types for us.

Second, the function does not have a name. It's an _anonymous function_. We'll see later that anonymous functions are one of the most important aspects of OCaml, and functional programming in general. But for now, a function without a name is not very useful. Let's bind it to a name:

    # let inc = fun x -> x + 1;;
    val inc : int -> int = <fun>

We can do this because, remember, functions are regular values, just like integers or strings. Now that we have a name for our function, let's use it:

    # inc 1;;
    - : int = 2
    # inc 2;;
    - : int = 3

Great, it works. Not how in OCaml you _apply_ functions to values just by specifying the name of the function and then the value you want to apply it to. Since binding names to functions is so common, OCaml offers a special syntax just for this:

    # let inc x = x + 1;;
    val inc : int -> int = <fun>

This does exactly the same thing as the snippet above.

Ok, so we can define one argument functions, but what if we want a function with two, three or more arguments? We can just write them separated by spaces after the name of the function:

    # let add m n = m + n;;
    val add : int -> int -> int = <fun>

Hm, `add`'s type, `int -> int -> int`, is kind of funky. What does it mean? Maybe if we add a pair of parenthesis things will be clearer: `int -> (int -> int)`. Aha, it's a function taking a value of type `int` and returning another function, which also takes a value of type `int` and return a value of type `int`. The code above is equivalent with:

    # let add = fun m -> fun n -> m + n;;
    val add : int -> int -> int = <fun>
    
This is called _currying_. This is actually a neat trick. It allows us to _partially apply_ functions like this:

    # let inc = add 1;;
    val inc : int -> int = <fun>

We've just defined `inc` by feeding the first argument to `add`, a `1`. The type of `inc` tell us that applying `add` to `1` gave us a function that expects a value of type `int` which return a value of type `int`. We have just _specialised_ the `add` function to our purpose. And, sure enough, when we apply `inc` to another value, say `2`, it does what we expect:

    # inc 2;;
    - : int = 3

## Intermezzo: logging

We'll build a small logging library. Let's start with the log levels:

    # type log_level = 
        | Error
        | Warning
        | Info;;
    type log_level = Error | Warning | Info
    # let string_of_log_level = function
        | Error -> "error"
        | Warning -> "warning"
        | Info -> "info"
      ;;
    val string_of_log_level : log_level -> string = <fun>
    
<span style="color: red;">TODO: variant types</span>
    
Now that we have the basic log levels we can define the main logging function:
    
    # let log level msg = 
        print_endline @@ (string_of_log_level level) ^ ": " ^ msg
      ;;
    val log : log_level -> string -> unit = <fun>
    
Let's test it:
    
    # log Info "hello";;
    info: hello
    - : unit = ()
    
Finally, we can define specialised logging functions for each logging level:
    
    # let error = log Error;;
    val error : string -> unit = <fun>
    # let warning = log Warning;;
    val warning : string -> unit = <fun>
    # let info = log Info;;
    val info : string -> unit = <fun>

And, sure enough, they work:

    # warning "awesome overload!";;
    warning: awesome overload!
    - : unit = ()
