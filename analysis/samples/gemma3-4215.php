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

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a new user
function registerUser($username, $email, $password) {
  // 1. Validate Input (Essential for security)
  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  // Check for empty fields
  if (empty($username) || empty($email) || empty($password)) {
    return false; // Indicate failure
  }

  //  Add more robust validation here, e.g., email format, password complexity, etc.
  //  This is just a basic example.

  // 2. Hash the Password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. SQL Query - Prepare and Execute (Best Practice)
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION); // Throw exceptions on errors

    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

    // Bind the parameters (important for security - prevents SQL injection)
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $hashed_password);

    // Execute the query
    $stmt->execute();

    return true; // Indicate success
  } catch (PDOException $e) {
    // Handle database errors (e.g., duplicate entry, invalid data)
    error_log("Database error: " . $e->getMessage()); // Log for debugging
    return false; // Indicate failure
  }
}

// --- Example Usage (For testing - NOT for direct use in a web application) ---
// If you were on a web form, this would be handled by form submission.

// $username = $_POST["username"];
// $email = $_POST["email"];
// $password = $_POST["password"];

// if (isset($username) && isset($email) && isset($password)) {
//   if (registerUser($username, $email, $password)) {
//     echo "User registered successfully!";
//   } else {
//     echo "User registration failed.";
//   }
// } else {
//   echo "Missing registration information.";
// }

?>
