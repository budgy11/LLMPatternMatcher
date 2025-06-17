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

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Validate Input (Essential for security)
    $username = trim($username);
    $password = trim($password);
    $email = trim($email);

    // Check for empty fields
    if (empty($username) || empty($password) || empty($email)) {
        return "Error: All fields are required.";
    }

    // Basic username validation (can be extended)
    if (strlen($username) < 3 || strlen($username) > 20) {
        return "Error: Username must be between 3 and 20 characters.";
    }

    // Password validation (consider using password_verify() for comparison)
    if (strlen($password) < 8) {
        return "Error: Password must be at least 8 characters long.";
    }

    // Email validation (basic check - consider using a library for more robust validation)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email address.";
    }

    // 2. Database Interaction (Using prepared statements - VERY IMPORTANT for security!)
    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling

        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");

        // Bind parameters (using placeholders) -  This prevents SQL injection!
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, password_hash($password, PASSWORD_DEFAULT)); // Hash the password
        $stmt->bindParam(3, $email);

        // Execute the statement
        $stmt->execute();

        return "Registration successful!  You have been logged in.";

    } catch (PDOException $e) {
        // Handle database errors
        return "Error: " . $e->getMessage(); // Useful for debugging
    }
}

// Example Usage (for demonstration purposes -  this will run in a web browser)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $registrationResult = registerUser($username, $password, $email);
    echo $registrationResult;
}
?>
