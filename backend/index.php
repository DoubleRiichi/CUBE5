<?php

namespace App;

class Config
{
    public static $DB_HOST = null;
    public static $DB_NAME = null;
    public static $DB_USER = null;
    public static $DB_PASSWORD = null;
    const SHOW_ERRORS = true;

    public static function init()
    {
        self::$DB_HOST = getenv('DB_HOST');
        self::$DB_NAME = getenv('DB_NAME');
        self::$DB_USER = getenv('DB_USER');
        self::$DB_PASSWORD = getenv('DB_PASSWORD');
    }
}

Config::init();

try {
    $dsn = "mysql:host=" . Config::$DB_HOST . ";dbname=" . Config::$DB_NAME;
    $pdo = new \PDO($dsn, Config::$DB_USER, Config::$DB_PASSWORD);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        if (!empty($title) && !empty($content)) {
            $stmt = $pdo->prepare("INSERT INTO todoList (title, content) VALUES (:title, :content)");
            $stmt->execute([
                ':title' => $title,
                ':content' => $content
            ]);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {

        $deleteId = (int) $_POST['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM todoList WHERE id = :id");
        $stmt->execute([':id' => $deleteId]);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO todoList (title, content) VALUES (:title, :content)");
        $stmt->execute([
            ':title' => $title,
            ':content' => $content
        ]);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}


    // 🔹 Fetch todos
    $stmt = $pdo->query("SELECT * FROM todoList");
    $todos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

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
                margin: 0;
                padding: 20px;
                background-color: #f5f5f5;
            }
            .container {
                max-width: 800px;
                margin: auto;
                padding: 20px;
            }
            h1 {
                text-align: center;
                border-bottom: 2px solid #3498db;
                padding-bottom: 10px;
            }
            form {
                background: #fff;
                padding: 20px;
                margin-bottom: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            form input[type="text"],
            form textarea {
                width: 100%;
                padding: 10px;
                margin-top: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 1em;
            }
            form button {
                background-color: #3498db;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
            }
            form button:hover {
                background-color: #2980b9;
            }
            .todo-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
            }
            .todo-card {
                background: white;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>📋 Ma Liste de Tâches</h1>

                <form method="POST" action="">
                <h2>Ajouter une tâche</h2>
                <label for="title">Titre:</label>
                <input type="text" name="title" id="title" required>

                <label for="content">Contenu:</label>
                <textarea name="content" id="content" rows="4" required></textarea>

                <button type="submit">Ajouter</button>
            </form>

            <div class="todo-grid">
                <?php foreach ($todos as $todo): ?>
                    <div class="todo-card">
                        <h3><?= htmlspecialchars($todo['title']) ?></h3>
                        <p><?= htmlspecialchars($todo['content']) ?></p>
                        <form method="POST" action="" onsubmit="return confirm('Voulez-vous vraiment supprimer cette tâche ?');">
                            <input type="hidden" name="delete_id" value="<?= (int)$todo['id'] ?>">
                            <button type="submit" style="background-color: #e74c3c; border:none; color:white; padding:5px 10px; border-radius:5px; cursor:pointer;">Supprimer</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </body>
    </html>
    <?php

} catch (\PDOException $e) {
    echo Config::SHOW_ERRORS ? "Connection failed: " . $e->getMessage() : "An error occurred.";
}
?>
