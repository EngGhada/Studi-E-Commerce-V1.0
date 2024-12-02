<?php

     // Check if Composer's autoloader is available. If not, include it manually.  // This prevents your PHP files from failing to load if the Composer autoloader is not available.

    $autoloadPath = __DIR__. '/vendor/autoload.php';
    
    if (file_exists($autoloadPath)) {
        require_once $autoloadPath;
    } else {
        die('Composer dependencies are not installed. Run "composer install".');
    }

// Modify connect.php file to use the .env variables:
// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// MySQL Configuration
// you can access them in your PHP code using the getenv() or $_ENV superglobal:

$dsn   = "mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_DATABASE'];
$user  = $_ENV['DB_USERNAME'];
$pass  = $_ENV['DB_PASSWORD'];
$option = [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
];

try {
    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Failed to connect: " . $e->getMessage();
}

// MongoDB Configuration
try {
    $client = new MongoDB\Client($_ENV['MONGO_URI']);
    $db = $client->selectDatabase($_ENV['MONGO_DATABASE']);
    $collection = $db->selectCollection('Activities_USER_Log');
} catch (Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}

?>
