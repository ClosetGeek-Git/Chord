<?php


class Hash {

	public $function = "RAND";
	public $keylen = 4;

	function hash($identifier) {
		$this->keylen = KEYSIZE;
		return rand(0, pow(2, KEYSIZE )-1);
	}

	function getFunction() {
		return $this->function;
	}

	function setFunction($function) {
		if ($function == "SHA-1") {
			$this->keylen = 160;
		}
		if ($function == "CRC32") {
			$this->keylen = 32;
		}
		if ($function == "RAND") {
			$this->keylen = 32;
		}
		$this->function = $function;
	}

	function getKeyLength() {
		return $this->keylen;
	}

	function setKeyLength($keyLength) {
		$this->keylen = $keyLength;
	}

}
