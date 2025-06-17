
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php
// Database credentials - REPLACE WITH YOUR ACTUAL CREDENTIALS!
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$username = $_POST["username"];
$password = $_POST["password"];

// 1. Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. SQL Query -  IMPORTANT: Use parameterized queries to prevent SQL Injection!
$sql = "SELECT id, username, password FROM users WHERE username = ?;";
$stmt = $conn->prepare($sql); // Prepare the statement
$stmt->bind_param("s", $username); // "s" indicates a string, bind the username parameter
$stmt->execute();

// 3. Process the result
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // No user found with that username
    echo "Invalid username or password.";
} else {
    $user = $result->fetch_assoc(); // Fetch the result as an associative array

    // 4. Password Verification (VERY IMPORTANT - Use Password Hashing)
    // In a real application, you should NEVER store passwords in plain text!
    // Instead, hash them when storing and verify against the hash.
    // This example uses a simple comparison, but it's insecure.  Use a library like password_hash() and password_verify()
    if (password_verify($password, $user["password"])) {
        // 5. Authentication Successful -  Set session variables
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        // Redirect to a secure page
        header("Location: secure_page.php"); // Replace with your secure page
        exit();
    } else {
        // Password mismatch
        echo "Invalid username or password.";
    }
}

// 6. Close the connection
$stmt->close();
$conn->close();
?>
