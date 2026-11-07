<?php
require_once __DIR__ . '/../config/config.php';
function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    if (!is_dir(dirname(DB_PATH))) mkdir(dirname(DB_PATH), 0775, true);
    $pdo = new PDO('sqlite:' . DB_PATH, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    return $pdo;
}
