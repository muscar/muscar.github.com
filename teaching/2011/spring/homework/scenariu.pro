%
% Teste pentru intrebarea 1.
%
date_test(1,[1,2,3,3],2,[1,3,3]).
date_test(2,[1,2,3,4],5,[1,2,3,4]).
date_test(3,[1,2,3,3,2],3,[1,2,2,3]). % test incorect

%
% Teste pentru intrebarea 2.
%
date_test(4,[1,2,3],1,[2,3]).
date_test(5,[3,4],3,[4]).
date_test(6,[3,4],3,[3]). % test incorect


test(M,P,1) :-
  nl, write(M), write(': '),
  catch(P,X,(write('Eroare: '),write(X),nl)),
  !, 
  write('OK'),
  nl.

test(_,_,0) :- 
  write('EROARE'), nl.  

testID(ID,N) :- 
  1 =< ID,
  ID =< 2,
  test('Test'-ID,
        (date_test(ID,Li,E,Lf),elimin(Li,E,L1),L1 = Lf),
        N).

testID(3,N) :- 
  test('Test'-3,
        (date_test(3,Li,E,Lf),elimin(Li,E,L1),L1 \= Lf),
        N).

testID(ID,N) :- 
  4 =< ID,
  ID =< 5,
  test('Test'-ID,
        (date_test(ID,L,P,R),prim_rest(L,P1,R1),P = P1,R = R1),
        N).

testID(6,N) :- 
  test('Test'-6,
        (date_test(6,L,P,R),prim_rest(L,P1,R1),(P \= P1;R \= R1)),
        N).


testID(ID) :-
  testID(ID,_).

testTOT :- 
  nl,
  write('\n\nTESTE PENTRU elimin/1\n===================\n'),
  testID(1,N1),
  testID(2,N2),
  testID(3,N3),
  write('\n\nTESTE PENTRU prim_rest/1\n===================\n'),
  testID(4,N4),
  testID(5,N5),
  testID(6,N6),
  Nm is N1+N2+N3+N4+N5+N6,
  nl,write('Nr teste trecute: '),
  write(Nm),nl,nl.

%
% Suita de teste se lanseaza automat dupa consultare.
%
:- testTOT.
