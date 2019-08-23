<?php

include('ChordNode.php');
include('ChordKey.php');
include('FingerTable.php');
include('Finger.php');

define('KEYSIZE', 4);
define('HASHKEYS', false);
define('NUM_OF_NODES', 16/*pow(2, KEYSIZE)*/);

class Chord {

  public $nodeList = [];

  function createNode($nodeId) {
    $node = new ChordNode($nodeId);
    $this->nodeList[] = $node;

    usort($this->nodeList, function ($a, $b) {
      if ($a->nodeKey->key == $b->nodeKey->key) {
          return 0;
      }
      return ($a->nodeKey->key < $b->nodeKey->key) ? -1 : 1;
    });
  }

  function getNode($i) {
    return $this->nodeList[$i];
  }

  function size() {
    return count($this->nodeList);
  }
}

$chord = new Chord();

for($i = 0; $i < NUM_OF_NODES; $i++) {
  $chord->createNode($i);
}

print(NUM_OF_NODES . " nodes are created.\n");

for($i = 1; $i < NUM_OF_NODES; $i++) {
  $node = $chord->getNode($i);
  $node->join($chord->getNode(0));
  $preceding = $node->getSuccessor()->getPredecessor();
  $node->stabilize();
  if ($preceding == null) {
    $node->getSuccessor()->stabilize();
  } else {
    $preceding->stabilize();
  }
}

print("Chord ring is established.\n");

for($i = 0; $i < NUM_OF_NODES; $i++) {
  $node = $chord->getNode($i);
  $node->fixFingers();
}

print("Finger Tables are fixed.\n");

for($i = 0; $i < NUM_OF_NODES; $i++) {
  $node = $chord->nodeList[$i];
  $node->printFingerTable(true);;
}
