<?php
// Oracle credentials
$username = 'vibeena';            // replace with your Oracle username
$password = '1234';      // replace with your Oracle password
$connection_string = 'localhost/XEPDB1'; // default XE connection string

// Connect to Oracle
$conn = oci_connect($username, $password, $connection_string);

if (!$conn) {
    $e = oci_error();
    echo "Connection failed: " . $e['message'];
} else {
    echo "Connected to Oracle Database successfully!";
    oci_close($conn);
}
?>
