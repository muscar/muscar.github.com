---
layout: page
title: Artificial Intelligence
tags: [teaching]
modified: 2014-03-12
---
# Assignment 1

The following facts representing a family tree:

    % ioana and ioan have two children: andrei and andreea
    parent(ioana, andrei).
    parent(ioana, andreea).
    parent(ioan, andrei).
    parent(ioan, andreea).

    % andrei and cristina have two children: mihai and mihaela
    parent(andrei, mihai).
    parent(andrei, mihaela).
    parent(cristina, mihai).
    parent(cristina, mihaela).

    % andreea and cristian have two kids: alex and alexandra
    parent(andreea, alex).
    parent(andreea, alexandra).
    parent(cristian, alex).
    parent(cristian, alexandra).

    % alex and ana have two kids: maria and marius
    parent(alex, maria).
    parent(alex, marius).
    parent(ana, maria).
    parent(ana, marius).

    male(ioan).
    male(andrei).
    male(cristian).
    male(alex).
    male(marius).

    female(ioana).
    female(andreea).
    female(mihaela).
    female(cristina).
    female(ana).
    female(maria).


Given the facts above, define the following predicates: `father/2`, `mother/2`,
`siblings/2`, `brother/2`, `sister/2`, `ancestor/2`, `grandparent/2`,
`grandmother/2`, `grandfather/2`, `uncle/2`, `aunt/2`, `cousin/2`.
