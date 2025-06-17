    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data (Assume this is coming from an HTML form)
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];


// Validate the form data (Important for security!)
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    // Handle empty field errors (e.g., display a form with error messages)
    echo "Error: All fields are required.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: Invalid email format.";
    exit;
}

if ($password != $confirm_password) {
    echo "Error: Passwords do not match.";
    exit;
}

// Hash the password (Crucial for security)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL query
$query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

// Execute the query
try {
    $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable exception handling for better error reporting

    $db->query($query);
    echo "User registered successfully!";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
