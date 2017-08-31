<?php
/**
 * Created by PhpStorm.
 * User: morten
 * Date: 8/31/17
 * Time: 9:53 AM
 */

namespace QueensGA;

use SimpleGA\Genome;

class QueensGenome extends Genome {

  public function __construct($randomGenerator) {
    parent::__construct($randomGenerator);
  }

  public function generate(array $parts = []) {
    if (!empty($parts)) {
      parent::generate($parts);
      return;
    }

    $this->genome = [];
    $random_generator = $this->randomGenerator;
    for ($i = 0; $i < 8; $i++) {
      $random = $random_generator();
      $this->genome[$i] = $random;
    }
  }

  public function mutate() {
    // TODO: Implement mutate() method.
  }

  /**
   * QOD fitness evaluation function.
   */
  public function evaluate() {
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

  public function toString() {
    $output = ['', '', '', '', '', '', '', ''];

    for ($column = 0; $column < 8; $column++) {
      for ($row = 0; $row < 8; $row++) {
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
