%% A compound term consists of a functor (the 'name' of the compound term) and
%% a list of arguments, which can be compound terms themselves.
%%
%% person('Alex', location(craiova, romania))

%% Pattern matching on compound terms

% name(+Person, -Name)
name(person(Name, _), Name).

location(person(_, Location), Location).

city(person(_, location(City)), City).

%% We can represent simply linked lists with the aid of compound terms.

% cons(42, cons(69, cons(613, nil))).

% length1(+List, -Length)
length1(nil, 0).
length1(cons(_, Tail), Length) :-
	length1(Tail, TailLength),
	Length is TailLength + 1.

%% Prolog has syntactic sugar for simply linked lists.

% length2(+List, +Counter, -Length)
length2([], Acc, Acc).
length2([_|Xs], Acc, Length) :-
	NewAcc is Acc + 1,
	length2(Xs, NewAcc, Length).

length3(Xs, Length) :- length2(Xs, 0, Length).

% copy_list(+List1, -List2)
copy_list([], []).
copy_list([X|Xs], [X|Ys]) :- copy_list(Xs, Ys).

% cat(+List1, +List2, -List1andList2)
cat([], L, L).
cat([X|Xs], Ys, [X|Zs]) :-
	cat(Xs, Ys, Zs).

% reverse_acc(+List, +Acc, -RevesedList)
reverse_acc([], Acc, Acc).
reverse_acc([X|Xs], Acc, Ys) :-
  reverse_acc(Xs, [X|Acc], Ys).
