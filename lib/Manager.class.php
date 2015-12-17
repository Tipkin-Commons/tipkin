<?php
    abstract class Manager
    {
        /**
         * @var string The database table used by the model.
         */
        protected $table;

        protected $dao;

        public function __construct($dao)
        {
            $this->dao = $dao;
        }

        /**
         * Add prefix to table name.
         *
         * @param string $table
         * @return string
         */
        protected function table($table = null)
        {
            global $tipkin_prefix;

            if (!isset($table)) $table = $this->table;

            return $tipkin_prefix.$table;
        }
    }
?>