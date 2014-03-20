---
layout: page
title: Programming Techniques -- Assignment 2
tags: [teaching]
modified: 2014-03-20
---

# Assignment 2 -- Mergesort, Quicksort

Pick one of the following exercises:

1. Implement a version of `merge()` that copies the second half of `a[]` to `aux[]`
in decreasing order and then does the merge back to `a[]`.

2. Develop a merge implementation that reduces the extra space requirement to 
_max(M, N/M)_, based on the following idea: Divide the array into _N/M_ blocks
of size _M_ (for simplicity in this description, assume that N is a multiple of
_M_). Then, (i) considering the blocks as items with their first key as the sort
key, sort them using selection sort; and (ii) run through the array merging the
first block with the second, then the second block with the third, and so forth.

3. Write a version of bottom-up mergesort that takes advan- tage of order in the
array by proceeding as follows each time it needs to find two arrays to merge:
find a sorted subarray (by incrementing a pointer until finding an entry that is
smaller than its predecessor in the array), then find the next, then merge them. 

4. Develop a mergesort implementation based on the idea of doing k-way merges
(rather than 2-way merges).

5. Modify the quicksort algorithm from the lab to remove both bounds checks in
the inner while loops. The test against the left end of the subarray is redundant
since the partitioning item acts as a sentinel (`pivot` is never less than`a[left]`).
To enable removal of the other test, put an item whose key is the largest in the
whole array into `a[length-1]` just after the shuffle. This item will never move
(except possibly to be swapped with an item having the same key) and will serve
as a sentinel in all subarrays involving the end of the array.
**Note**: When sorting interior subarrays, the leftmost entry in the subarray to
the right serves as a sentinel for the right end of the subarray.

6. Implement a nonrecursive version of quicksort based on a main loop where a
subarray is popped from a stack to be partitioned, and the resulting subarrays
are pushed onto the stack. 

7. Implement quicksort with a cutoff to insertion sort for subarrays with less
than _M_ elements, and empirically determine the value of _M_ for which quicksort
runs fastest in your computing environment to sort random arrays of _N_ doubles,
for N = 10<sup>3</sup>, 10<sup>4</sup>, 10<sup>5</sup>, and 10<sup>6</sup>.
