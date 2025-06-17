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

// Assuming you have a database connection established (e.g., mysqli)
// Let's call the database connection object $db

// Function to register a new user
function registerUser($username, $password, $email, $db) {
  // 1. Sanitize and Validate Inputs
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);
  $email = trim($email);

  // Basic validation - add more robust validation as needed
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  // Password hashing - IMPORTANT for security!  Use a strong hashing algorithm.
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


  // 2. Prepare the SQL Query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $db->prepare($sql); // Prepare statement to prevent SQL injection

  if ($stmt === false) {
    return "Error: Could not prepare statement.";
  }

  // 3. Bind Parameters
  $stmt->bind_param("sss", $username, $hashedPassword, $email); // "sss" indicates 3 string parameters

  // 4. Execute the Query
  if ($stmt->execute()) {
    $stmt->close();
    return "Registration successful! You have been sent a confirmation email."; // or redirect to a success page
  } else {
    $error = $stmt->error;
    $stmt->close();
    return "Error: " . $error; // Return the database error message
  }
}


// Example Usage (in your registration form processing)
// Assuming the form data is in the $_POST array

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Call the registration function
  $result = registerUser($username, $password, $email, $db); // Pass in your database connection

  // Display the result
  echo $result;
}
?>
