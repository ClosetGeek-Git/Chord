<?php

class FingerTable {

  public $fingers = []; // 

  function __construct($node) {
    $this->fingers = [];

    for ($i = 0; $i < KEYSIZE; $i++) {
      $start = $node->getNodeKey()->createStartKey($i);
      $this->fingers[$i] = new Finger($start, $node);
    }
  }

  function getFinger($i) {
    return $this->fingers[$i];
  }

  function size() {
    return count($this->fingers);
  }
}
