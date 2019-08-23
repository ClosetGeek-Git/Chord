<?php

class Finger {

	public $start;
	public $node;

	function __construct($start, $node) {
		$this->node = $node;
		$this->start = $start;
	}

	function getStart() {
		return $this->start;
	}

	function setStart($start) {
		$this->start = $start;
	}

	function getNode() {
		return $this->node;
	}

	function setNode($node) {
		$this->node = $node;
	}

}
