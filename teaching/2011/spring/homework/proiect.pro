% Intrebarea 1
% elimin(+Lista,+Element,?ListaFaraElement)
% ------------
%      Predicatul este adevarat daca si numai daca ListaFaraElement este lista 
%      rezultata din lista initiala dupa eliminarea elementului Element. 
%
% Exemplu:
% ?- elimin([1,2,1,3],1,X).
% X = [2,3]

elimin([],X,[]).
elimin([X | Xs],X,Ys) :-
  elimin(Xs,X,Ys).
elimin([Y | Xs],X,[Y | Ys]) :-
  X \== Y,
  elimin(Xs,X,Ys).



% Intrebarea 2
% prim_rest(+Lista,?PrimElement,?RestElemente)
% ------------
%      Predicatul este adevarat daca si numai daca PrimElement este primul 
%      element din Lista si RestElemente este lista rezultata din Lista 
%      prin inlaturarea primului elelment. 
%
% Exemplu:
% ?- prim_rest([1,2,1,3],X,Y).
% X=1, Y = [2,1,3]

prim_rest([X|Xs],X,Xs).