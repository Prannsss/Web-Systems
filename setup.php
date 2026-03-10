<?php
/**
 * setup.php — Run this ONCE to initialise the database from schema.sql.
 * After running, delete or restrict access to this file.
 */

$host    = 'localhost';
$dbUser  = 'root';
$dbPass  = '';

// Admin seed credentials — must match what schema.sql uses as the email
$adminEmail    = 'admin@websys.com';
$adminPassword = password_hash('Testing@123', PASSWORD_BCRYPT);

$schemaFile = __DIR__ . '/database/schema.sql';

if (!file_exists($schemaFile)) {
    die("<p style='color:red;font-family:sans-serif;'>schema.sql not found at: $schemaFile</p>");
}

// Read schema and substitute the bcrypt placeholder
$sql = file_get_contents($schemaFile);
$sql = str_replace('{BCRYPT_PASSWORD}', $adminPassword, $sql);

try {
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Execute each statement in the schema file
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        $pdo->exec($statement);
    }

    echo "<p style='color:green;font-family:sans-serif;'>&#10003; Schema applied from <strong>database/schema.sql</strong></p>";
    echo "<p style='color:green;font-family:sans-serif;'>&#10003; Admin account ready: <strong>$adminEmail</strong></p>";
    echo "<p style='font-family:sans-serif;color:red;'><strong>Important:</strong> Delete or restrict access to this file after setup.</p>";
    echo "<p style='font-family:sans-serif;'><a href='index.php'>Go to Login &rarr;</a></p>";

} catch (PDOException $e) {
    error_log("Setup Error: " . $e->getMessage());
    echo "<p style='color:red;font-family:sans-serif;'>Setup failed. Check error log for details.</p>";
}
