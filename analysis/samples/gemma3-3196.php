

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the form data
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors["username"] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors["username"] = "Username must be at least 3 characters long.";
  }
  // Email validation (basic)
  if (empty($email)) {
    $errors["email"] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Invalid email format.";
  }
  // Password validation
  if (empty($password)) {
    $errors["password"] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors["password"] = "Password must be at least 8 characters long.";
  }
  // Confirm password validation
  if (empty($confirm_password)) {
    $errors["confirm_password"] = "Confirm password cannot be empty.";
  } elseif ($password != $confirm_password) {
    $errors["confirm_password"] = "Passwords do not match.";
  }

  // Check for errors
  if (!empty($errors)) {
    // Display errors (e.g., in a form with error highlighting)
    echo "<div class='errors'>";
    foreach ($errors as $error_key => $error_value) {
      echo "<span class='error'>" . $error_value . "</span>";
    }
    echo "</div>";

  } else {
    // Insert the user into the database
    try {
      // Establish database connection
      $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Prepare SQL statement
      $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

      // Prepare statement
      $stmt = $conn->prepare($sql);

      // Bind parameters
      $stmt->bind_param("sss", $username, $email, password_hash($password, PASSWORD_DEFAULT));

      // Execute statement
      if ($stmt->execute()) {
        echo "Registration successful! You have been redirected.";
        // Redirect to a success page or home page
        header("Location: success.php"); // Replace success.php with your desired redirect
        exit();
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }

    } catch (Exception $e) {
      echo "An error occurred: " . $e->getMessage();
    } finally {
      // Close database connection
      if ($conn) {
        $conn->close();
      }
    }
  }
}
?>
