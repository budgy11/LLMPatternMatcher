    Username: <input type="text" name="username" <?php if (isset($username)) echo 'value="' . htmlspecialchars($username) . '"'; ?>/>
    <br><br>
    Password: <input type="password" name="password" />
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Not have an account? Register here.</a>

</body>
</html>


// Example of password hashing during registration (from register.php)
$password = $_POST["password"];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Then, when inserting into the database, use $hashed_password instead of $password.


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Input validation (again, expand this!)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } else {
        // Database connection (same as login.php)
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            $success = true;
        } else {
            $error = "Error inserting user. " . $conn->error;
        }
    }
}
?>
