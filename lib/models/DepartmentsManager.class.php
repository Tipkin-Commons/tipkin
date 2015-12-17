<?php

abstract class DepartmentsManager extends Manager 
{
	abstract public function get($id);
	abstract public function getListOf();
}

?>