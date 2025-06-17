    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate inputs (Important for security!)
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=[\]{}|;:'\"`<>/?.]]).{8,}$/", $password)) {
        $error_message = "Password must be at least 8 characters long and contain at least one number, one uppercase, and one special character.";
    } else {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to insert the new user
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "New user registered successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the connection
        $conn->close();
    }
}
?>
