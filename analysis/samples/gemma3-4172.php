
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" minlength="6">

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user registration
function registerUser($username, $password, $email) {
    // 1. Validate Input (Important!)
    if (empty($username) || empty($password) || empty($email)) {
        return "Error: All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email format.";
    }

    // 2. Hash the password (Crucial for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Connect to the database
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION); // Throw exceptions on errors
    } catch (PDOException $e) {
        return "Error: Database connection failed: " . $e->getMessage();
    }

    // 4. Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
    $stmt = $pdo->prepare($sql);

    // 5. Execute the query
    try {
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return "Registration successful!  You have been created.";
    } catch (PDOException $e) {
        return "Error: Registration failed: " . $e->getMessage();
    } finally {
        $pdo = null;  // Close the connection
    }
}

// Example usage (for demonstration - would normally be in a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $registration_result = registerUser($username, $password, $email);
    echo $registration_result;
} else {
    // If this is the initial page load, show the registration form
    ?>
