    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register here</a></p> <!-- Replace with your registration page -->

</body>
</html>


<?php
// ... (database connection setup)

// Example of a prepared statement (replace the string concatenation with this)
$stmt = mysqli_prepare(mysqli_connect($db_host, $db_user, $db_password), "SELECT * FROM users WHERE username = ? AND password = ?");

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password); // "ss" means two strings
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // User authentication successful!
        session_start();
        $_SESSION["username"] = $username;
        header("Location: secure_page.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }

    mysqli_stmt_close($stmt);
} else {
    // Handle the error if the prepared statement could not be prepared.
    $error_message = "Error preparing statement.";
}
?>
