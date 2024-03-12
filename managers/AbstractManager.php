<?php

/**
 * Class AbstractManager
 * Abstract class for managing database interactions.
 */
abstract class AbstractManager
{
    /**
     * @var PDO The PDO instance for database connection.
     */
    protected PDO $db;
    
    /**
     * AbstractManager constructor.
     * Initializes the database connection using the provided environment variables.
     */
    public function __construct()
    {
        // Construct the connection string
        $connectionString = "mysql:host=" . $_ENV["DB_HOST"] .
            ";port=3306; charset=" . $_ENV["DB_CHARSET"] . ";dbname=" . $_ENV["DB_NAME"];
        
        // Initialize the PDO instance for database connection
        $this->db = new PDO(
            $connectionString,
            $_ENV["DB_USER"],
            $_ENV["DB_PASSWORD"]
        );
    }
}