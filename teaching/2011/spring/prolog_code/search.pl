% The search graph
arc(a, b).
arc(a, c).
arc(b, d).
arc(b, e).
arc(c, f).
arc(c, g).

% The objective node
objective(f).

% neighbors(+Node, -NeighborList)
neighbors(N, Ns) :-
	% findall(X, P, L) returns a list L with all values for X that satisfy predicate P.
	% P should contain the same variable X somewhere.
	% See http://www.swi-prolog.org/pldoc/man?predicate=findall%2F3
	findall(X, arc(N, X), Ns).

% select(-Node, +List, -RestList)
select(N, [N|Ns], Ns).% :- write(N), nl.

% expand_frontier_dfs(+NeighborList, +Frontier, -NewFrontier)
expand_frontier_dfs(Ns, F, F1) :-
	% The frontier is a stack
	append(Ns, F, F1).

% expand_frontier_bfs(+NeighborList, +Frontier, -NewFrontier)
expand_frontier_bfs(Ns, F, F1) :-
	% The frontier is a queue
	append(F, Ns, F1).

% search(+Frontier)
search(F) :-
	select(N, F, _),
	objective(N).
search(F) :-
	select(N, F, F1),
	neighbors(N, Ns),
	expand_frontier_bfs(Ns, F1, F2),
	search(F2).

%% Cost searches

% Uncomment the following lines to create more paths to the objective
% node
%arc(e, f).
%cost(e, f, 1).
%arc(d, c).
%cost(d, c, 5).

% Add costs to the edges
cost(a, b, 2).
cost(a, c, 3).
cost(b, d, 4).
cost(b, e, 1).
cost(c, f, 7).
cost(c, g, 5).

% expand_frontier_mcs(+Neighbors, +Node, -PathList)
expand_frontier_mcs(Ns, F1, F3) :-
	append(Ns, F1, F2),
	% Sort the frontier by the path costs in ascending order so the first neighbor we're going to expand is the "closest"
	sort_frontier_by_cost(F2, F3).

% sort_frontier_by_cost(+Frontier, -SortedFrontier)
sort_frontier_by_cost(F, R) :-
	% Create an association list using path costs as keys and the nodes themselves as values
	% See http://www.swi-prolog.org/pldoc/man?predicate=map_list_to_pairs%2F3
	map_list_to_pairs(extract_cost, F, Aux),
	% Sort the association list by the key, i.e. the path cost
	% See http://www.swi-prolog.org/pldoc/doc_for?object=keysort/2
	keysort(Aux, Aux1),
	% Extract the values, i.e. nodes, since the key were only needed for sorting
	% See http://www.swi-prolog.org/pldoc/doc_for?object=pairs_values/2
	pairs_values(Aux1, R).
% extract_cost(+Node, -Cost)
extract_cost(node(_, _, PC), PC).

% add_paths(+NeighborList, +CurrentNode, -NewPaths)
add_paths([], _, []).
add_paths([M|R], node(N, P, PC), [node(M, [N|P], NPC)|FR]) :-
	cost(N, M, C),
	NPC is PC + C,
	add_paths(R, node(N, P, PC), FR).

% csearch(+List, -PathList, -Cost)
csearch(F, [N|P], PC) :-
	select(node(N, P, PC), F, _),
	objective(N).
csearch(F, Path, RPC) :-
	select(node(N, P, PC), F, F1),
	neighbors(N, Ns),
	add_paths(Ns, node(N, P, PC), Ns1),
	expand_frontier_mcs(Ns1, F1, F2),
	csearch(F2, Path, RPC).







