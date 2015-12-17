<?php
class AlternateCurrencyPostalCode extends Record {
	
	protected $postalCode,
			  $alternateCurrencyId;

	public function isValid(){
		if(
			empty($this->postalCode) ||
			empty($this->alternateCurrency)
		){
			return false;
		}
		
		return true;
	}
	
	public function getPostalCode()
	{
		return $this->postalCode;
	}
	
	public function setPostalCode($postalCode)
	{
		$this->postalCode = $postalCode;
	}
	
	public function getAlternateCurrencyId()
	{
		return $this->alternateCurrencyId;
	}
	
	public function setAlternateCurrencyId($alternateCurrencyId)
	{
		$this->alternateCurrencyId = $alternateCurrencyId;
	}
}