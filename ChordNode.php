<?php

class ChordNode {

  public $nodeId;
  public $nodeKey;
  public $predecessor;
  public $successor;
  public $fingerTable;

  function __construct($nodeId) {
    $this->nodeId = $nodeId;
    $this->nodeKey = new ChordKey($nodeId);
    $this->fingerTable = new FingerTable($this);
    $this->create();
  }

  function findSuccessor($key) {

    if(is_string($key))
    {
      $key = new ChordKey($key);
    }
    
    if ($this == $this->successor) {
      return $this;
    }

    if ($key->isBetween($this->getNodeKey(), $this->successor->getNodeKey())
        || $key->compareTo($this->successor->getNodeKey()) == 0
        || $key->compareTo($this->getNodeKey()) == 0) {
      return $this->successor;
    } else {
      $node = $this->closestPrecedingNode($key);
      if ($node == $this) {
        return $this->successor->findSuccessor($key);
      }
      return $node->findSuccessor($key);
    }
  }

  function closestPrecedingNode($key) {
    for ($i = KEYSIZE - 1; $i >= 0; $i--) {
      $finger = $this->fingerTable->getFinger($i);
      $fingerKey = $finger->getNode()->getNodeKey();
      if ($fingerKey->isBetween($this->getNodeKey(), $key)) {
        return $finger->getNode();
      }
    }
    return $this;
  }

  function create() {
    $this->predecessor = null;
    $this->successor = $this;
  }

  function join($node) {
    $this->predecessor = null;
    $this->successor = $node->findSuccessor($this->getNodeKey());
  }

  function stabilize() {
    $node = $this->successor->getPredecessor();
    if ($node != null) {
      $key = $node->getNodeKey();
      if (($this == $this->successor)
          || $key->isBetween($this->getNodeKey(), $this->successor->getNodeKey())) {
        $this->successor = $node;
      }
    }
    $this->successor->notifyPredecessor($this);
  }

  function notifyPredecessor($node) {
    $key = $node->getNodeKey();
    if ($this->predecessor == null
        || $key->isBetween($this->predecessor->getNodeKey(), $this->getNodeKey())) {
      $this->predecessor = $node;
    }
  }

  function fixFingers() {
    for ($i = 0; $i < KEYSIZE; $i++) {
      $finger = $this->fingerTable->getFinger($i);
      $key = $finger->getStart();
      $finger->setNode($this->findSuccessor($key));
    }
  }

  function __toString() {
    $sb = "ChordNode[".
        "ID=" . $this->nodeId .
        ",KEY=" . $this->nodeKey .
        "]";
        
    return $sb;
  }

  function printFingerTable($printOut = false) {
    $out  = "=======================================================\n";
    $out .= "FingerTable:\t" . $this . "\n";  
    $out .= "-------------------------------------------------------\n";
    $out .= "Predecessor:\t" . $this->predecessor . "\n";
    $out .= "Successor:\t" . $this->successor . "\n";
    $out .= "-------------------------------------------------------\n";
    
    for ($i = 0; $i < KEYSIZE; $i++) {
      /*Finger*/ $finger = $this->fingerTable->getFinger($i);
      $out .= "finger[".$finger->getStart() . "]\t" . $finger->getNode() . "\n";
    }
    
    $out .= "=======================================================\n\n";
    
    if($printOut) {
      print($out);
    }
    return $out;
  }

  function getNodeId() {
    return $this->nodeId;
  }

  function setNodeId($nodeId) {
    $this->nodeId = $nodeId;
  }

  function getNodeKey() {
    return $this->nodeKey;
  }

  function setNodeKey($nodeKey) {
    $this->nodeKey = $nodeKey;
  }

  function getPredecessor() {
    return $this->predecessor;
  }

  function setPredecessor($predecessor) {
    $this->predecessor = $predecessor;
  }

  function getSuccessor() {
    return $this->successor;
  }

  function setSuccessor($successor) {
    $this->successor = $successor;
  }

  function getFingerTable() {
    return $this->fingerTable;
  }

  function setFingerTable($fingerTable) {
    $this->fingerTable = $fingerTable;
  }

}
