    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  try {
    // Connect to the database
    $pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For error handling

    // Hash the password (important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");

    // Bind the parameters (important to prevent SQL injection)
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $hashed_password);
    $stmt->bindParam(3, $email);

    // Execute the query
    $stmt->execute();

    echo "Registration successful!  You've been redirected."; // Replace with a proper redirect

  } catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
  }
}


// Example usage (handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (VERY IMPORTANT - add more thorough validation here)
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

  // Basic validation (customize based on your requirements)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
  } else {
    // Call the registerUser function
    registerUser($username, $password, $email);
  }
}

?>
