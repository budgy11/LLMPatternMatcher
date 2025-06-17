    <button type="submit">Login</button>
  </form>

  <p>Not have an account? <a href="register.php">Register</a></p>
</body>
</html>


<?php

// Database Credentials (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$username = $_POST["username"];
$password = $_POST["password"];

// 1. Connect to the Database
try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// 2. Sanitize and Prepare the Query
// This is CRUCIAL for security.  Avoid using echo or print directly on user input.
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);  // password_hash for secure storage


// 3. Fetch the Result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// 4. Check if a user exists and is authenticated
if ($result) {
    // User exists, login successful
    session_start();
    $_SESSION["user_id"] = $result["id"];  // Store user ID in session
    $_SESSION["username"] = $result["username"];
    echo "Login successful!  Welcome, " . $_SESSION["username"];

    // Redirect to a protected page or dashboard
    header("Location: /dashboard.php");
    exit(); // Important to stop further execution after redirect
} else {
    // User does not exist
    echo "Invalid username or password.";
}

// 5. Close the Connection (good practice)
$conn = null;
?>
