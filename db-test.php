<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=u369272486_carswap_back", "u369272486_carswap_back", "Carswap@321");
    echo "Connection successful!\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
