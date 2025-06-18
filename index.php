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

    // Display the todos with styled HTML
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ma Liste de Tâches</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                line-height: 1.6;
                margin: 0;
                padding: 20px;
                background-color: #f5f5f5;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            h1 {
                color: #2c3e50;
                text-align: center;
                margin-bottom: 30px;
                font-size: 2.5em;
                border-bottom: 2px solid #3498db;
                padding-bottom: 10px;
            }
            .todo-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
                padding: 20px 0;
            }
            .todo-card {
                background: white;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s ease-in-out;
            }
            .todo-card:hover {
                transform: translateY(-5px);
            }
            .todo-card h3 {
                color: #2c3e50;
                margin-top: 0;
                font-size: 1.4em;
                border-bottom: 1px solid #e0e0e0;
                padding-bottom: 10px;
            }
            .todo-card p {
                color: #666;
                margin: 10px 0 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>📋 Ma Liste de Tâches</h1>
            <div class="todo-grid">
                <?php foreach ($todos as $todo): ?>
                    <div class="todo-card">
                        <h3><?= htmlspecialchars($todo['title']) ?></h3>
                        <p><?= htmlspecialchars($todo['content']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </body>
    </html>
    <?php

} catch (\PDOException $e) {
    if (Config::SHOW_ERRORS) {
        echo "Connection failed: " . $e->getMessage();
    } else {
        echo "An error occurred.";
    }
}
?>