<?php

require __DIR__ . '/vendor/autoload.php';

use \SimpleGA\Islands;

$container = new \Pimple\Container();

$container['random_generator'] = function ($c) {
  return function() {
    $rnd = rand(1, 8);
    return $rnd;
  };
};
$container['genome'] = $container->factory(function ($c) {
  return new \QueensGA\QueensGenome($c);
});

$container['population_size'] = 20;
$container['population_random'] = 1;
$container['elite_count'] = 1;
$container['mutation_promille'] = 1;
$container['islands_count'] = 10;
$container['islands_exchange_count'] = 10;
$container['islands_exchange_generations'] = 20;

$generations = 10000;
$shortcut_fitness = 0;

$islands = new Islands($container);
$islands->initialize();

$fittest = $islands->getFittestGenome();
print "Initial fittest genome:\n";
print "Fitness: " . $fittest->getFitness() . "\n";
print $fittest->toString();

$best_fitness = -1;
while ($islands->nextGeneration() <= $generations && $best_fitness != $shortcut_fitness) {
  print "Generation " . $islands->getGeneration() . "\n";
  if ($islands->getGeneration() % $container['islands_exchange_generations'] == 0) {
    print "Exchanging genomes between islands.";
  }
  print "Most fit genome:\n";
  $fittest = $islands->getFittestGenome();
  $best_fitness = $fittest->getFitness();
  print "Fitness: " . $fittest->getFitness() . "\n";
  print $fittest->toString() . "\n";
  print "\n\n";
}

