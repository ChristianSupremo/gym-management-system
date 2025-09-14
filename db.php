<?php
$servername = getenv('DB_HOST');     // e.g. gym-management.mysql.database.azure.com
$username   = getenv('DB_USER');     // e.g. myadmin@gym-management
$password   = getenv('DB_PASS');     // your MySQL password
$dbname     = getenv('DB_NAME');     // e.g. gym_management

// Initialize connection
$conn = mysqli_init();

// Enable SSL (Azure requires secure connections by default)
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// Connect with SSL flag
mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

// Check connection
if (mysqli_connect_errno()) {
    die("❌ DB connection failed: " . mysqli_connect_error());
} else {
    echo "✅ DB Connected Successfully";
}
?>
