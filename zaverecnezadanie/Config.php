<?php

class Config extends PDO{
    public function __construct($db="mysql:host=localhost;dbname=Zaver",
                                $username = "xsochab",
                                $password = "U4IIQqq1mUB33kN",
                                $options = [])
    {
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        $options = array_replace($default_options, $options);
        parent::__construct($db,$username,$password,$options);
    }
    public function run($sql, $args = NULL)
    {
        if (!$args)
        {
            return $this->query($sql);
        }
        $stmt = $this->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}