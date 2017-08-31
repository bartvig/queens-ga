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

}
