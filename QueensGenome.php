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

  public function __construct(\SimpleGA\RandomInterface $randomGenerator) {
    parent::__construct($randomGenerator);
  }

  public function generate($firstPart = NULL, $secondPart = NULL, $thirdPart = NULL) {
    if (!is_null($firstPart) && !is_null($secondPart) && !is_null($thirdPart)) {
      parent::generate($firstPart, $secondPart, $thirdPart);
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
