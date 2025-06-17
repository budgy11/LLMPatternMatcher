    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username"><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email"><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password"><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
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

  // 2. Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Prepare and execute the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $username, $hashed_password, $email);
    $stmt->execute();
    return true; // Registration successful
  } else {
    return false; // Failed to prepare statement
  }

  // 4. Close the connection
  $stmt->close();
  $conn->close();
}

// Example usage (This would likely come from a form submission)
// Assuming you have a form with fields username, password, and email

// Let's assume these values have been submitted and validated (VERY IMPORTANT!)
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];


// **IMPORTANT:  VALIDATION HERE!  DO NOT USE UNSANITIZED INPUT**
// Validate that username, password and email are not empty,
// and that the email is in a valid format.  This is crucial to prevent
// security vulnerabilities like SQL injection and cross-site scripting (XSS).

if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required.";
} else {
    // Call the registration function
    if (registerUser($username, $password, $email)) {
        echo "Registration successful! Please check your email to verify.";
        // Redirect to a welcome page or login page
    } else {
        echo "Registration failed. Please try again.";
    }
}

?>
