        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Prepare the statement (only needs to be done once)
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the statement
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

// Bind the parameters (important for security!)
$stmt->bind_param("ss", $username, $email, $hashed_password);

// Get form data
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

// Hash the password (VERY IMPORTANT for security)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Execute the statement
if ($stmt->execute([$username, $email, $hashed_password])) {
    echo "Registration successful!  Please check your email for verification instructions.";
} else {
    echo "Error registering user. " . mysqli_error($conn);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
