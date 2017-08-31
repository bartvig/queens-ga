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

  public function __construct($randomGenerator) {
    parent::__construct($randomGenerator);
  }

  public function generate(array $parts = []) {
    if (!empty($parts)) {
      parent::generate($parts);
      if (count($this->genome) > 8) {
        throw new SimpleGAException('There are too many genes in genome', SIMPLEGA_TOO_MANY_GENES);
      }
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
    $rnd = rand(1,3);

    switch ($rnd) {
      case 1:
        // Change one queen one position.
        $random = $this->randomGenerator;
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
        // Change one queen randomly in same row.
        $random = $this->randomGenerator;
        $rnd1 = $random();
        $rnd2 = $random();
        $this->genome[$rnd1 - 1] = $rnd2;
        break;

      case 3:
        // Change two columns randomly.
        $random = $this->randomGenerator;
        $rnd1 = $random() - 1;
        $rnd2 = $random() - 1;
        $swap = $this->genome[$rnd1];
        $this->genome[$rnd1] = $this->genome[$rnd2];
        $this->genome[$rnd2] = $swap;
        break;
    }
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
      if ($this->genome[$column] < 1 || $this->genome[$column] > 8) {
        print "ERROR! The value is: " . $this->genome[$column] . "\n";
      }
      for ($row = 0; $row < 8; $row++) {
        if (!isset($this->genome[$column])) {
          print "something is wrong. genome: " . print_r($this->genome, 1) . "\n";
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
