    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $username, $hashed_password, $email);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Error preparing statement: " . print_r($conn->error, true));
    }

    // 4.  Success message (optional)
    return "Registration successful! You can now log in.";
}

// -------------------- Example Usage (simulated form handling) --------------------

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validate the input (Basic validation - improve this!)
    if (empty($username) || empty($password) || empty($email)) {
        echo "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        // Call the registerUser function
        $result = registerUser($username, $password, $email);
        echo $result;
    }
}

?>
