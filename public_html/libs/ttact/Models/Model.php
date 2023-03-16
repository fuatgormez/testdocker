<?php

namespace ttact\Models;

abstract class Model
{
    /**
     * database
     */
    protected $db;

    /**
     * logic methods
     */
    public function __construct(\ttact\Database $db, array $data)
    {
        $this->db = $db;
        
        foreach ($data as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }
}
