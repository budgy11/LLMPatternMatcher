    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>


    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., via mysqli or PDO)
// Replace these with your actual connection details
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_database_name";

// Form data from the registration form
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];


// Input validation (IMPORTANT - ALWAYS validate user input!)
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    echo "Error: All fields are required.";
    exit; // Stop execution if any field is empty
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: Invalid email format.";
    exit;
}

if ($password != $confirm_password) {
    echo "Error: Passwords do not match.";
    exit;
}

// Hash the password (VERY IMPORTANT - NEVER store passwords in plain text)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL query
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql); // Use prepared statements to prevent SQL injection

// Bind the parameters
$stmt->bind_param("ss", $username, $hashed_password);

// Execute the query
if ($stmt->execute()) {
    // Registration successful
    echo "Registration successful! Please verify your email.";
    // Redirect the user (optional)
    // header("Location: login.php");
} else {
    // Registration failed
    echo "Error: " . $stmt->error; // Display the error message from the database
}

// Close the statement and connection
$stmt->close();
$conn->close();

?>
