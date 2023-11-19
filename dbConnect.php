<?php
$host = '127.0.0.1'; // Replace this with your database server address or IP
$port = 3306; // Replace this with the appropriate port number
$username = 'root';
$password = '2626#26Vsl';
$database = 'pay_txn_book';

try {
    $conn = new mysqli($host, $username, $password, $database, $port);
    // Your code to interact with the database goes here
} catch (mysqli_sql_exception $e) {
    // Handle the exception (e.g., display an error message)
    echo "Connection failed: " . $e->getMessage();
}
?>


