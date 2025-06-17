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

// Database connection details - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Validate Input (Crucial for security!)
    if (empty($username) || empty($password) || empty($email)) {
        return "Error: All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email format.";
    }

    // Sanitize input (Important for security)
    $username = trim($username);
    $password = trim($password);
    $email = trim($email);


    // 2. Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Database Query
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $email]);

        return "Registration successful!  You've been redirected."; // Or return a success message

    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    } finally {
        // Close the connection (Good practice, but often handled by the framework)
        $pdo = null;
    }
}

// Example Usage (This would be in your form processing code)
// Assume this is triggered by a form submission.  Don't put this directly in your HTML form.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $registration_result = registerUser($username, $password, $email);
    echo $registration_result;
}
?>
