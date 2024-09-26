<?php
require_once 'db_connection.php'; // Ensure this file has your database connection

$featuredProperties = [
    1, 2, 3, 4, 5, 6, 7, 8, 9 // IDs of your exclusive properties
];

try {
    $pdo->beginTransaction();

    // Clear existing exclusive properties
    $pdo->exec("TRUNCATE TABLE exclusive_properties");

    // Insert new exclusive properties
    $stmt = $pdo->prepare("INSERT INTO exclusive_properties (property_id) VALUES (?)");
    foreach ($featuredProperties as $id) {
        $stmt->execute([$id]);
    }

    $pdo->commit();
    echo "Exclusive properties synced successfully.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error syncing exclusive properties: " . $e->getMessage();
}