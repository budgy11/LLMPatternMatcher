    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
  </form>

</body>
</html>


function loginUser($username, $password) {
  // ... (Database connection code) ...

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Use prepared statement
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $hashed_password); // Pass the hashed password

  // Execute the query
  if ($stmt->execute()) {
    // ... (rest of the loginUser function) ...
  }

  // ... (close statements and connection) ...
}


<?php

// Database credentials (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate inputs (Important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password must be filled.";
    } elseif (mysqli_stmt_prepare($conn, "SELECT username, password FROM users WHERE username = ?") === false) {
        die("Error preparing statement: " . print_r(mysqli_stmt_error($conn), true));
    }


    // Attempt to authenticate
    if (mysqli_stmt_bind_param($conn, "s", $username) === false) {
        die("Error binding parameter: " . print_r(mysqli_stmt_error($conn), true));
    }
    if (mysqli_stmt_execute($conn) === false) {
        die("Error executing statement: " . print_r(mysqli_stmt_error($conn), true));
    }

    $result = mysqli_stmt_get_result($conn);
    if (mysqli_num_rows($result) == 0) {
        $error = "Invalid username or password.";
    } else {
        mysqli_stmt_close($result);

        // If authentication is successful, set a session variable
        session_start();
        $_SESSION["username"] = $username;
        $error = "";  // Clear any previous errors
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();

    }
}
?>
