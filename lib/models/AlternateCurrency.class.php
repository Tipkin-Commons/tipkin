<?php

class AlternateCurrency extends Record
{
	protected $name,
			  $imageUrl,
			  $rate; 
	
	static  $CURRENCY_PATH = '/images/currencies/';

	
	public function isValid(){
		if( 
			empty($this->name) ||
			empty($this->imageUrl) ||
			empty($this->rate)
		) {
			return false;
		}
		
		return true;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getImageUrl()
	{
		return $this->imageUrl;
	}
	
	public function setImageUrl($imageUrl)
	{
		$this->imageUrl = $imageUrl;
	}
	
	public function getRate()
	{
		return $this->rate;
	}
	
	public function setRate($rate)
	{
		$this->rate = $rate;
	}
}