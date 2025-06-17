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

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to handle user registration
function registerUser($username, $email, $password) {
  // 1. Data Validation
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }
  if (alreadyExists($username, $db_name)) {
    $errors[] = "Username is already taken.";
  }

  // Email validation
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // If there are any errors, return them
  if (!empty($errors)) {
    return $errors;
  }

  // 2. Database Insertion
  try {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password for security

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("ss", $username, $email, $hashed_password);
      $stmt->execute();

      // Success
      $stmt->close();
      return true;
    } else {
      throw new Exception("Error preparing statement.");
    }
  } catch (Exception $e) {
    // Handle errors (e.g., database connection failure, SQL errors)
    error_log("Registration error: " . $e->getMessage()); // Log the error for debugging
    return false;
  } finally {
    // Close the connection
    if ($conn) {
      $conn->close();
    }
  }
}

// Example Usage (for demonstration - This would typically be from a form submission)
// $username = $_POST['username'];
// $email = $_POST['email'];
// $password = $_POST['password'];

// if (isset($username) && isset($email) && isset($password)) {
//   $registration_result = registerUser($username, $email, $password);

//   if ($registration_result) {
//     echo "User registration successful!";
//   } else {
//     // Display validation errors here based on the $errors array
//     print_r($errors); // Debugging -  Replace with proper error handling in a real application.
//   }
// } else {
//   echo "Please fill in all fields.";
// }

// Helper Function:  Check if username already exists
function alreadyExists($username, $db_name) {
  try {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $count = $result->fetch_row()[0];

    $stmt->close();

    return $count > 0;

  } catch (Exception $e) {
    error_log("Error checking username existence: " . $e->getMessage());
    return false;  // Or handle the error as appropriate
  } finally {
    if ($conn) {
      $conn->close();
    }
  }
}

?>
