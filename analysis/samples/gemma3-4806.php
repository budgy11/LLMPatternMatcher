    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established 
// (e.g., using mysqli or PDO)
// We'll use a simplified example with mysqli for demonstration.
// Remember to adapt this to your database setup.

// Replace with your database credentials
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to register a user
function registerUser($username, $password, $email) {
  try {
    // 1. Establish Database Connection (if not already done)
    $conn = new mysqli($host, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
      throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // 2. Prepare the SQL Query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
      throw new Exception("Prepare statement failed.");
    }

    // 3. Bind Parameters
    $stmt->bind_param("ss", $username, $password);

    // 4. Execute the Query
    if (!$stmt->execute()) {
      throw new Exception("Query failed: " . $stmt->error);
    }

    // 5. Close the Statement
    $stmt->close();

    // 6. Close the Connection
    $conn->close();

    return true; // Registration successful
  } catch (Exception $e) {
    // Handle errors appropriately (e.g., display an error message)
    error_log("Registration error: " . $e->getMessage()); // Log the error for debugging
    return false; // Registration failed
  }
}

// --- Example Usage (from a form submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize inputs (VERY IMPORTANT - see security notes below)
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

  // Validate inputs (add more validation as needed)
  if (empty($username) || empty($password) || empty($email)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email address.";
  } else {
      // Register the user
      if (registerUser($username, $password, $email)) {
          $success = "Registration successful!  You have been sent an email verification link.";
      } else {
          $error = "Registration failed. Please try again.";
      }
  }
}

?>
