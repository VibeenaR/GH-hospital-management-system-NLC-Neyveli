<?php
session_start();

// Oracle DB connection
$username = "vibeena"; // Your Oracle username
$password = "1234";    // Your Oracle password
$connection_string = "localhost/XEPDB1"; // Oracle XE PDB connection

$conn = oci_connect($username, $password, $connection_string);

if (!$conn) {
    $e = oci_error();
    die("Connection failed: " . htmlentities($e['message']));
}

// If form is submitted
if (isset($_POST['login'])) {
    $login_id = $_POST['login_id'];
    $password_input = $_POST['password'];

    // Prepare SQL (use bind variables for security)
    $sql = "SELECT NR, NAME, LOGIN_ID FROM vibeena.NGES_HIS_USERS 
            WHERE LOGIN_ID = :login_id AND PASSWORD = :password_input";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':login_id', $login_id);
    oci_bind_by_name($stmt, ':password_input', $password_input);

    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);

    if ($row) {
        // Login successful
        $_SESSION['user'] = $row['NAME'];
        $_SESSION['user_nr'] = $row['NR'];
        header("Location: dashboard.php"); // redirect to dashboard page
        exit();
    } else {
        $error = "Invalid Login ID or Password!";
    }
}

oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Hospital System</title>
<style>
body { font-family: Arial; background-color: #f0f0f0; }
.login-container { width: 300px; margin: 100px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
input[type=text], input[type=password] { width: 100%; padding: 10px; margin: 5px 0 10px 0; border: 1px solid #ccc; border-radius: 4px; }
input[type=submit] { width: 100%; padding: 10px; background-color: #28a745; border: none; color: white; border-radius: 4px; cursor: pointer; }
input[type=submit]:hover { background-color: #218838; }
.error { color: red; margin-bottom: 10px; }
</style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <?php if(isset($error)) { echo "<div class='error'>$error</div>"; } ?>
    <form method="post" action="">
        <input type="text" name="login_id" placeholder="Login ID" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>
</div>
</body>
</html>
