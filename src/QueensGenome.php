<?php
/**
 * Created by PhpStorm.
 * User: morten
 * Date: 8/31/17
 * Time: 9:53 AM
 */

namespace QueensGA;

use SimpleGA\Genome;
use SimpleGA\SimpleGAException;

class QueensGenome extends Genome {

  public function __construct($container) {
    parent::__construct($container);
  }

  /**
   * Generate genome.
   *
   * Either use parent class to generate from existing genes or generate
   * randomly.
   *
   * @param array $parts
   *   Existing gene parts.
   *
   * @throws \SimpleGA\SimpleGAException
   */
  public function generate($parts = []) {
    if (!empty($parts)) {
      parent::generate($parts);
      if (count($this->genome) > 8) {
        throw new SimpleGAException('There are too many genes in genome', SIMPLEGA_TOO_MANY_GENES);
      }
      return;
    }

    // Generate new gene by random.
    $this->genome = [];
    /** @var \closure $random_generator */
    $random_generator = $this->container['random_generator'];
    for ($i = 0; $i < 8; $i++) {
      $random = $random_generator();
      $this->genome[$i] = $random;
    }
  }

  /**
   * Mutate genome.
   *
   * There are three different mutations:
   *  1. Move random queen up or down in column.
   *  2. Move random queen to a random position in column.
   *  3. Swap two random columns.
   */
  public function mutate() {
    $rnd = rand(1,4);

    switch ($rnd) {
      case 1:
        // Change one queen one position.
        /** @var \closure $random */
        $random = $this->container['random_generator'];
        $rnd = $random();
        $direction = rand(0,1);
        if ($direction == 0) {
          $direction = -1;
        }
        $new_val = ($this->genome[$rnd - 1] - 1 + $direction) % 8;
        if ($new_val < 0) {
          $new_val += 8;
        }
        $this->genome[$rnd - 1] = $new_val + 1;
        break;

      case 2:
        // Change one queen randomly in same column.
        /** @var \closure $random */
        $random = $this->container['random_generator'];
        $rnd1 = $random();
        $rnd2 = $random();
        $this->genome[$rnd1 - 1] = $rnd2;
        break;

      case 3:
        // Change two columns randomly.
        /** @var \closure $random */
        $random = $this->container['random_generator'];
        $rnd1 = $random() - 1;
        $rnd2 = $random() - 1;
        $swap = $this->genome[$rnd1];
        $this->genome[$rnd1] = $this->genome[$rnd2];
        $this->genome[$rnd2] = $swap;
        break;

      case 4:
        // Swap two neighbour columns.
        $random = rand(0,6);
        $swap = $this->genome[$random];
        $this->genome[$random] = $this->genome[$random + 1];
        $this->genome[$random + 1] = $swap;
        break;

      default:
        break;
    }
  }

  /**
   * Fitness evaluation function.
   */
  public function evaluate($test) {
    $fitness = 0;
    $rows = [];
    $column = 0;
    $diagonals_1 = [];
    $diagonals_2 = [];
    foreach ($this->genome as $gene) {
      // Use 0 as lowest value for gene.
      $gene--;
      if (!isset($rows[$gene])) {
        $rows[$gene] = 0;
      }
      else {
        $rows[$gene]++;
      }

      // Find diagonals.
      $diagonal = $column + $gene;
      if (!isset($diagonals_1[$diagonal])) {
        $diagonals_1[$diagonal] = 0;
      }
      else {
        $diagonals_1[$diagonal]++;
      }

      $diagonal = 7 - $column + $gene;
      if (!isset($diagonals_2[$diagonal])) {
        $diagonals_2[$diagonal] = 0;
      }
      else {
        $diagonals_2[$diagonal]++;
      }

      $column++;
    }

    // Sum all bad queens.
    foreach ($rows as $val) {
      $fitness += $val;
    }
    foreach ($diagonals_1 as $val) {
      $fitness += $val;
    }
    foreach ($diagonals_2 as $val) {
      $fitness += $val;
    }

    $this->fitness = $fitness;
  }

  /**
   * Produce string output of genome.
   *
   * @return string
   *   8x8 table with "Q" or ".".
   *
   * @throws \SimpleGA\SimpleGAException
   *   Throws exception if gene value is wrong or doesn't exist.
   */
  public function toString() {
    $output = ['', '', '', '', '', '', '', ''];

    for ($column = 0; $column < 8; $column++) {
      if ($this->genome[$column] < 1 || $this->genome[$column] > 8) {
        throw new SimpleGAException('The value is not between 1 and 8. Value: ' . $this->genome[$column], SIMPLEGA_WRONG_GENE_VALUE);
      }
      for ($row = 0; $row < 8; $row++) {
        if (!isset($this->genome[$column])) {
          throw new SimpleGAException('Gene doesn\'t exist.', SIMPLEGA_GENE_NOT_EXISTS);
        }
        if ($this->genome[$column]-1 == $row) {
          $output[$row] .= 'Q';
        }
        else {
          $output[$row] .= '.';
        }
      }
    }

    return join ("\n", $output) . "\n";
  }

}
