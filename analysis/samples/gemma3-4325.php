
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate inputs (IMPORTANT - Add more validation as needed)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username (example - you can adjust the requirements)
  if (empty($username) || !preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
    $username_error = "Username must be alphanumeric and can only contain letters, numbers, and underscores.";
  }

  // Validate email
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Please enter a valid email address.";
  }

  // Validate password (example -  strong password requirements)
  if (empty($password) || strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // If validation passes, proceed to insert into the database
  if (empty($username_error) && empty($email_error) && empty($password_error)) {
    // Hash the password (IMPORTANT - use a strong hashing algorithm like password_hash())
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      $success_message = "Registration successful! Please log in.";
    } else {
      // Registration failed
      $error_message = "Registration failed.  Check your database connection or try again later.";
    }

    // Close the statement
    $stmt->close();
  }
}

// Function to connect to the database (replace with your connection details)
function connect_to_database() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}


?>
