<?php

class MessageBox
{
	static function Error($message)
	{
		return '
		<div class="notice error">
			<span class="icon medium" data-icon="X"></span>
            ' . $message . '
			<a class="icon close" data-icon="x" href="#close"></a>
		</div>';
	}
	
	static function Warning($message)
	{
		return '
		<div class="notice warning">
			<span class="icon medium" data-icon="!"></span>
            ' . $message . '
			<a class="icon close" data-icon="x" href="#close"></a>
		</div>';
	}
	
	static function Success($message)
	{
		return '
		<div class="notice success">
			<span class="icon medium" data-icon="C"></span>
            ' . $message . '
			<a class="icon close" data-icon="x" href="#close"></a>
		</div>';
	}

}

?>