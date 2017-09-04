# queens-ga
Eight Queens GA

This is an example of a specific implementation of a simple genetic algorithm using https://github.com/bartvig/simple-ga

The eight queens puzzle is the problem of placing 8 queens on a chessboard, so that no two queens threaten each other, i.e. so that no two queens are on the same row, column, or diagonal.

## Installation
Make sure SimpleGA is in the right folder or change the folder in line 3 of `queens.php`. Then run `composer install`. Then you might have to run `composer dump-autoload -o` to make autoloading work properly.
 
How to install composer is out of scope for this documentation.

## How to run
`php queens.php`

Execution will stop when a correct solution is found, or the genetic algorithm has run for the configured number of generations.

For each generation the most fit solution is output to the screen.


## Configuration
The following variables can be configured in `queens.php` (see https://github.com/bartvig/simple-ga for an explanation of the variables): `population_size`, `population_random`, `elite_count`, and `mutation_promille`. If you want to change the max executed number of generations, change the variable `$generations`.
