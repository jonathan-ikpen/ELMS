<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','elmsdb');
// Establish database connection.
try {
    // Set the PDO error mode to exception
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    //  PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                    );
    
    // Create a new PDO instance
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, $options);
}
catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>