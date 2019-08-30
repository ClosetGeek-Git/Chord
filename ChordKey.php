<?php

include('Hash.php');

class ChordKey {

  public $keylen = 32;
  
  /**
    * @var string
    */
  public $identifier = "";

  /**
    * @var int
    */
  public $key = 0;

  function __construct($arg)
  {
    if(is_int($arg)) {
      $this->identifier = (string)$arg;
      $this->key = $arg;
      if(HASHKEYS) {
        $hash = new Hash(0);
        $this->key = $hash->hash($this->key);
      }
    }else if(is_string($arg)) {
      $this->identifier = $arg;
      $hash = new Hash(0);
      $this->key = $hash->hash($arg);
    }
  }

  public function isBetween($fromKey, $toKey) {
    if ($fromKey->compareTo($toKey) < 0) {
      if ($this->compareTo($fromKey) > 0 && $this->compareTo($toKey) < 0) {
        return true;
      }
    } else if ($fromKey->compareTo($toKey) > 0) {
      if ($this->compareTo($toKey) < 0 || $this->compareTo($fromKey) > 0) {
        return true;
      }
    }
    return false;
  }

  
  public function createStartKey($index) {
    $newKey = ($this->key + pow(2, $index )) % pow(2, KEYSIZE);
    return new ChordKey($newKey);
    
  }

  public function compareTo($target) {
    if ($this->key == $target->key) 
      return 0;
    if ($this->key > $target->key)
      return 1;
    if ($this->key < $target->key)
      return -1;
  }

  public function __toString() {
    return (string)$this->key;
  }

  public function getIdentifier() {
    return $this->identifier;
  }

  public function setIdentifier($identifier) {
    $this->identifier = $identifier;
  }

  public function getKey() {
    return $this->key;
  }

  public function setKey($key) {
    $this->key = $key;
  }
}
