<?php
/**
 * One-off CLI migration: hashes every plaintext password in the users table.
 *
 * Usage (from the project root):  php migrate_passwords.php
 * Safe to re-run: rows that are already hashed are skipped.
 */

if (PHP_SAPI !== 'cli') {
    exit("This script must be run from the command line.\n");
}

require __DIR__ . '/includes/DBconn.php';

$db = new DBconnection();
$dbConn = $db->getConnection();

$rows = $dbConn->query("SELECT user_id, email, password FROM users")->fetchAll(PDO::FETCH_ASSOC);

$migrated = 0;
$skipped = 0;

foreach ($rows as $row) {
    if ($row['password'] === '') {
        echo "SKIP empty password for user_id {$row['user_id']} ({$row['email']}) - set one manually\n";
        $skipped++;
        continue;
    }

    $info = password_get_info($row['password']);
    if ($info['algo'] !== 0) { // already a recognised hash
        $skipped++;
        continue;
    }

    $stmt = $dbConn->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
    $stmt->execute(array(
        ':password' => password_hash($row['password'], PASSWORD_DEFAULT),
        ':user_id'  => $row['user_id'],
    ));

    echo "Hashed password for user_id {$row['user_id']} ({$row['email']})\n";
    $migrated++;
}

echo "Done. {$migrated} migrated, {$skipped} already hashed or skipped.\n";
