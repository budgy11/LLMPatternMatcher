

<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Create a connection to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Securely hash the password
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // 3. Prepare the statement
  $stmt = $conn->prepare($sql);

  // 4. Bind the parameters
  $stmt->bind_param("ss", $username, $hashed_password, $email);

  // 5. Execute the query
  if ($stmt->execute()) {
    return true; // Registration successful
  } else {
    // Handle errors
    echo "Error: " . $stmt->error;
    return false;
  }

  // 6. Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (handling form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate input (very important - sanitize and validate everything!)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required.";
  } else {
      // Sanitize input (example - you'll need more robust sanitization)
      $username = filter_var($username, FILTER_SANITIZE_STRING);
      $email = filter_var($email, FILTER_SANITIZE_EMAIL);

      // Validate email format
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          echo "Invalid email address.";
      } else {
          // Call the registerUser function
          if (registerUser($username, $password, $email)) {
            echo "Registration successful!  You have been redirected.";
            // Redirect to a success page or login form
          }
        }
      }
  }
}
?>
