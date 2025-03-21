<?php
if ($argc < 3) {
    die("Usage:\n php cli.php <command> <name>\n");
}

$command = $argv[1];
$name = ucfirst($argv[2]);

switch ($command) {
    case 'make:all':
        createMigration($name);
        createModel($name);
        createController($name);
        createViews($name);
        break;

    case 'make:middleware':
        createMiddleware($name);
        break;

    default:
        die("Unknown command: $command\n");
}

// ===========================
// FUNCTION PEMBUATAN FILE
// ===========================

function createMigration($name) {
    $timestamp = date('Ymd_His');
    $filename = "migrations/{$timestamp}_create_{$name}_table.php";
    $className = "Create{$name}Table";

    $template = <<<PHP
<?php
class $className {
    private \$pdo;

    public function __construct(\$pdo) {
        \$this->pdo = \$pdo;
    }

    public function up() {
        \$this->pdo->exec("
            CREATE TABLE IF NOT EXISTS {$name} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
}
PHP;

    file_put_contents($filename, $template);
    echo "Migration file created: $filename\n";
}

function createController($name) {
    $filename = "Controllers/{$name}Controller.php";

    $template = <<<PHP
<?php
require_once 'models/{$name}.php';

class {$name}Controller {
    public function index() {
        \$data = {$name}::getAll();
        require 'views/{$name}/index.php';
    }

    public function create() {
        require 'views/{$name}/create.php';
    }

    public function store() {
        if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
            {$name}::insert(\$_POST);
            header("Location: /{$name}");
            exit();
        }
    }

    public function edit(\$id) {
        \$data = {$name}::getById(\$id);
        require 'views/{$name}/edit.php';
    }

    public function update(\$id) {
        if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
            {$name}::update(\$id, \$_POST);
            header("Location: /{$name}");
            exit();
        }
    }

    public function delete(\$id) {
        {$name}::delete(\$id);
        header("Location: /{$name}");
        exit();
    }
}
PHP;

    file_put_contents($filename, $template);
    echo "Controller created: $filename\n";
}

function createModel($name) {
    $filename = "Models/{$name}.php";

    $template = <<<PHP
<?php
require_once 'config/database.php';

class $name {
    public static function getAll() {
        global \$pdo;
        \$stmt = \$pdo->query("SELECT * FROM {$name}");
        return \$stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById(\$id) {
        global \$pdo;
        \$stmt = \$pdo->prepare("SELECT * FROM {$name} WHERE id = ?");
        \$stmt->execute([\$id]);
        return \$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function insert(\$data) {
        global \$pdo;
        \$columns = implode(", ", array_keys(\$data));
        \$placeholders = implode(", ", array_fill(0, count(\$data), "?"));
        \$stmt = \$pdo->prepare("INSERT INTO {$name} (\$columns) VALUES (\$placeholders)");
        return \$stmt->execute(array_values(\$data));
    }

    public static function update(\$id, \$data) {
        global \$pdo;
        \$sets = implode(", ", array_map(fn(\$key) => "\$key = ?", array_keys(\$data)));
        \$stmt = \$pdo->prepare("UPDATE {$name} SET \$sets WHERE id = ?");
        return \$stmt->execute([...array_values(\$data), \$id]);
    }

    public static function delete(\$id) {
        global \$pdo;
        \$stmt = \$pdo->prepare("DELETE FROM {$name} WHERE id = ?");
        return \$stmt->execute([\$id]);
    }
}
PHP;

    file_put_contents($filename, $template);
    echo "Model created: $filename\n";
}

function createViews($name) {
    $viewPath = "views/{$name}";
    if (!is_dir($viewPath)) {
        mkdir($viewPath, 0777, true);
    }

    $files = ['index.php', 'create.php', 'edit.php'];

    foreach ($files as $file) {
        $filePath = "{$viewPath}/{$file}";
        file_put_contents($filePath, "<!-- {$file} for {$name} -->\n");
        echo "View file created: $filePath\n";
    }
}

function createMiddleware($name) {
    $filename = "config/Middleware/{$name}.php";

    $template = <<<PHP
<?php
class {$name} {
    public static function handle(\$request, \$next) {
        // Tambahkan logika middleware di sini
        
        // Contoh: Periksa apakah pengguna sudah login
        if (!isset(\$_SESSION['user'])) {
            header("Location: /login");
            exit();
        }

        return \$next(\$request);
    }
}
PHP;

if (!is_dir("config/Middleware")) {
    mkdir("config/Middleware", 0777, true);
}

file_put_contents($filename, $template);
echo "Middleware created: $filename\n";
}
