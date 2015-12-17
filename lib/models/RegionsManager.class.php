<?php

abstract class RegionsManager extends Manager
{
	abstract public function get($id);
	abstract public function getListOf();
}

?>