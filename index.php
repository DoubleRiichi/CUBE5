<?php

namespace App;

/**
 * Application configuration
 * PHP version 7.0
 */
class Config
{
    /**
     * Database host
     * @var string
     */
    public static $DB_HOST = null;

    /**
     * Database name
     * @var string
     */
    public static $DB_NAME = null;

    /**
     * Database user
     * @var string
     */
    public static $DB_USER = null;

    /**
     * Database password
     * @var string
     */
    public static $DB_PASSWORD = null;

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * Initialize configuration values
     */
    public static function init()
    {
        self::$DB_HOST = getenv('DB_HOST');
        self::$DB_NAME = getenv('DB_DATABASE');
        self::$DB_USER = getenv('DB_USERNAME');
        self::$DB_PASSWORD = getenv('DB_PASSWORD');
    }
}

// Initialize the configuration
Config::init();

try {
    // Create PDO connection
    $dsn = "mysql:host=" . Config::$DB_HOST . ";dbname=" . Config::$DB_NAME;
    $pdo = new \PDO($dsn, Config::$DB_USER, Config::$DB_PASSWORD);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    // Query to fetch all todos
    $stmt = $pdo->query("SELECT * FROM todoList");
    $todos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // Display the todos
    echo "<h1>Todo List</h1>";
    echo "<ul>";
    foreach ($todos as $todo) {
        echo "<li>";
        echo "<h3>" . htmlspecialchars($todo['title']) . "</h3>";
        echo "<p>" . htmlspecialchars($todo['content']) . "</p>";
        echo "</li>";
    }
    echo "</ul>";

} catch (\PDOException $e) {
    if (Config::SHOW_ERRORS) {
        echo "Connection failed: " . $e->getMessage();
    } else {
        echo "An error occurred.";
    }
}
?>