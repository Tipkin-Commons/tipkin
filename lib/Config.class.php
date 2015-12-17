<?php
namespace Tipkin;

class Config
{
    protected $vars = null;
    protected static $instance;

    public static function get($var)
    {
        return self::singleton()->get_var($var);
    }

    public static function singleton()
    {
        if(!self::$instance instanceOf Config) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    /**
     * Instance methods
     */

    protected function __construct() {
    }

    public function get_var($var)
    {
        $this->ensure_vars_loaded();
        if (isset($this->vars[$var]))
        {
            return $this->vars[$var];
        }
    }

    protected function ensure_vars_loaded()
    {
        if(is_null($this->vars)) {
            $this->load_vars();
        }
    }

    protected function load_vars()
    {
        $file = dirname(__FILE__).'/../config/app.xml';

        if(!file_exists($file)) throw new \Exception("Config file {$file} not found");
        $xml = new \DOMDocument;
        $xml->load($file);

        $elements = $xml->getElementsByTagName('define');
        foreach ($elements as $element)
        {
            $this->vars[$element->getAttribute('var')] = $element->getAttribute('value');
        }

    }
}
