<?php
session_start();
// Example for PHP (config.php):
    $db_host = 'sql105.infinityfree.com';    // MySQL Hostname
    $db_user = 'IfO_38794974';               // MySQL Username
    $db_pass = 'M3KsDkNndX';         // MySQL Password (set in Step 1)
    $db_name = 'IfO_38794974_okoa';           // Database Name (replace XXX)
    $db_port = 3306;                         // Port (optional)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
