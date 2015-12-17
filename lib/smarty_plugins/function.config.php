<?php

/**
 * Project specific Smarty plugins
 */

function smarty_function_config($params, $template)
{
  if($params['name']){ trigger_error('Missing required name for smarty config function.'); }
  return Tipkin\Config::get($params['name']);
}
