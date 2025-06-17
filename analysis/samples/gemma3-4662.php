    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" placeholder="John" required>

    <br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" placeholder="Doe" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="example@email.com" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" minlength="6" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Form handling (registration form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validation (important to prevent security issues)
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (preg_match('/@|\./|"/|[`!#$%&()*+,-_=+/|?^`{|}~]/', $email)) {
        $error = "Invalid email format.";
    }

    // Hash the password (IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password, $email);

    // Execute the query
    if ($stmt->execute()) {
        $success = "Registration successful! You have been logged in.";
    } else {
        $error = "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
