<?php

require __DIR__ . '/../simple-ga/vendor/autoload.php';
require __DIR__ . '/vendor/autoload.php';

use \SimpleGA\Population;

$container = new \Pimple\Container();

$container['random_generator'] = function ($c) {
  return function() {
    $rnd = rand(1, 8);
    return $rnd;
  };
};
$container['genome'] = $container->factory(function ($c) {
  return new \QueensGA\QueensGenome($c['random_generator']);
});

$container['population_size'] = 1000;
$container['population_random'] = 0;
$container['elite_count'] = 1;
$container['mutation_promille'] = 1;

$generations = 10000;
$shortcut_fitness = 0;

$population = new Population($container);
$population->initialize();

$fittest = $population->getFittestGenome();
print "Initial fittest genome:\n";
print "Fitness: " . $fittest->getFitness() . "\n";
print $fittest->toString();

$best_fitness = -1;
while ($population->nextGeneration() <= $generations && $best_fitness != $shortcut_fitness) {
  print "Generation " . $population->getGeneration() . "\n";
  print "Most fit genome:\n";
  $fittest = $population->getFittestGenome();
  $best_fitness = $fittest->getFitness();
  print "Fitness: " . $fittest->getFitness() . "\n";
  print $fittest->toString() . "\n";
  print "\n\n";
}

