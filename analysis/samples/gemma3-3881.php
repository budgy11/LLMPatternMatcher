    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., via mysqli)
//  $conn = new mysqli("localhost", "username", "password", "database_name");
//  if ($conn->connect_error) {
//      die("Connection failed: " . $conn->connect_error);
//  }

//  $conn = null; // Close the connection

//  // OR if using PDO
//  // $pdo = new PDO("mysql:host=localhost;dbname=database_name", "username", "password");

// --- Registration Function ---
function registerUser($username, $password, $email) {
  // Input validation (Basic - customize for your needs)
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  // Hash the password (VERY IMPORTANT for security!)
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // SQL Query (sanitize to prevent SQL injection)
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $hashedPassword, $email);

  if ($stmt->execute()) {
    return "Registration successful! You've been redirected.";
  } else {
    return "Error: Could not register. " . $conn->error;
  }

  // Close the statement
  $stmt->close();
}


// --- Example Usage (Simulated - replace with your form handling) ---
//  In a real application, this would be handled by a form submission.

// Assuming you have the form data in $_POST:
// $username = $_POST['username'];
// $password = $_POST['password'];
// $email = $_POST['email'];

// $result = registerUser($username, $password, $email);
// echo $result;


// ---  Example Database Table Structure (MySQL) ---
/*
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);
*/

?>
