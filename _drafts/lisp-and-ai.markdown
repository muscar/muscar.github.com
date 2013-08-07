---
layout: post
title: "Lisp and AI"
date: 2013-07-27 15:07
tags: Lisp, ai
---

# Why Lisp is the _Right Thing<sup>™</sup>_ for AI

## Sentience

Is an empirical approach enough to make an AI become aware of itself? I poke myself, it hurts, thus I am the subject of my own action. How could an AI know that it is itself that it's poking? How can it realize the notion of "self"? Assuming some hardcoded mechanisms were available, could this _embodied cognition_ lead to the more abstract, symbolic, notion of "self"? 

Young children tend to play a lot with their hands and feet, studying them. Is this hardcoded in our DNA? Can this basic, "low-level" distinction between "me"/"not-me" be raised to the higher level of symbolic reasoning?

Assume that a child starts with a blank brain and some hardcoded rules for discovering the world (e.g. grasping). Then he will realtively soon discover its hands and feet. Experimenting with them generates abstract symbols: s<sub>0</sub>, s<sub>1</sub>, &hellip; . Initially, a good deal of these symbols will refer to the subject itself. At some point, these symbols could (and should) be merged in a meta-symbol, e.g. s<sub>0</sub>&prime;. This is how _abstraction_ works. 

Let's recap: on the one hand we have symbolic representation, and on the other we have abstraction. This is exactly what John McCarthy hand in mind when he developed Lisp. That's because Lisp was designed as a language for developing AI applications.

Back to symbolic representation. Once a symbol gets associated with a percept which resulted from an action, it won't be generated the next time when the same (or similar) percept is encountered, i.e. symbols get _interned_, in Lisp parlance. The source of the percept is an attribute of the perception, and it can be used for abstraction. Getting back to the young child analogy, let's say that the AI has two percepts: s<sub>hand</sub>, and s<sub>foot</sub>. These will eventually get abstracted as the set S<sub>self</sub> (together with other symbols):

<div style="margin: 25px;">
S<sub>self</sub> = { S<sub>hand</sub>, S<sub>foot</sub>, &hellip; }
</div>

So, any time AI encounters a percept that maps to a symbol s, such that &isin; S<sub>self</sub>, it can know it's talking about itself. 
