
<form action="register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// For this example, we'll use a placeholder: $db

// Function to register a new user
function registerUser($username, $password, $email) {
  global $db; // Access the database connection

  // 1. Sanitize and Validate Input
  $username = trim($username);  // Remove leading/trailing whitespace
  $password = trim($password);
  $email = trim($email);

  // Check if username, password, and email are empty
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  // Password validation (Basic - consider stronger validation)
  if (strlen($password) < 8) {
    return "Error: Password must be at least 8 characters long.";
  }

  // Email validation (Basic - consider more robust validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  // 2. Hash the password (IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3.  Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // 4. Execute the query (Use prepared statements to prevent SQL injection!)
  try {
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password, $email); // "ss" indicates two strings
    $stmt->execute();

    // 5.  Handle success
    return "Registration successful!  You have been registered. Check your email for a verification link.";

  } catch (Exception $e) {
    // Handle database errors
    return "Error: " . $e->getMessage(); // Display error message for debugging
  } finally {
    // Close the statement (Important for resource management)
    $stmt->close();
  }
}


// Example usage (In a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Assuming form data is submitted in fields named 'username', 'password', 'email'
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $registration_result = registerUser($username, $password, $email);

  if ($registration_result == "Registration successful!") {
    echo "<p>" . $registration_result . "</p>";
  } else {
    echo "<p>" . $registration_result . "</p>"; // Display error message
  }
}


//Example HTML Form (Place this in your HTML page)
/*
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
