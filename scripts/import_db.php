<?php
/**
 * Crée la base `his` et importe his.sql.
 * Usage : php scripts/import_db.php
 */

if (PHP_SAPI !== 'cli') {
    exit("Ce script doit être exécuté en ligne de commande.\n");
}

$pdo = new PDO('mysql:host=127.0.0.1', 'root', '', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
));

$pdo->exec("CREATE DATABASE IF NOT EXISTS `his` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$pdo->exec("USE `his`");

$sql = file_get_contents(__DIR__ . '/../his.sql');
if ($sql === false) {
    exit("Impossible de lire his.sql\n");
}

$pdo->exec($sql);

echo "Base `his` importée avec succès.\n";
echo "Comptes de démonstration :\n";
echo "  - admin : brianmsyamboza@gmail.com / 123\n";
echo "  - user  : brianmsyamboza@gmail.com / 1234\n";
