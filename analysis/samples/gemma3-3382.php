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

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Prepare the SQL query
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password for security
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // 3. Prepare the statement
    $stmt = $conn->prepare($sql);

    // 4. Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password, $email);

    // 5. Execute the query
    if ($stmt->execute()) {
        return true; // Registration successful
    } else {
        return false; // Registration failed
    }

    // 6. Close the statement and connection
    $stmt->close();
    $conn->close();
}


// Example Usage (This is just a demonstration.  You'll need a form to submit data to this function.)
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validate the data (Important!  Add more robust validation here)
    if (empty($username) || empty($password) || empty($email)) {
        echo "All fields are required.";
    } elseif (strlen($username) < 3) {
        echo "Username must be at least 3 characters long.";
    } else {
        // Register the user
        if (registerUser($username, $password, $email)) {
            echo "Registration successful!  You have been redirected.";
            // Redirect the user (or display a success message)
        } else {
            echo "Registration failed. Please try again.";
        }
    }
}

?>
