

function registerUser($username, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL statement
  $sql = "INSERT INTO users (username, email) VALUES (?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $email);

  // 3. Execute the statement
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }

  // 4. Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (with password hashing)
// $username = $_POST["username"];
// $email = $_POST["email"];

// if (isset($username) && isset($email)) {
//   $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

//   if (registerUser($username, $hashed_password, $email)) {
//     echo "Registration successful! Please check your email for verification.";
//   } else {
//     echo "Registration failed. Please try again.";
//   }
// }


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Input validation - IMPORTANT!  Always validate user input.
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (
      !preg_match("/^[a-zA-Z0-9_]+$/", $username) // Validate username (alphanumeric and underscore only)
    ) {
    $errors = "Username must contain only letters, numbers, and underscores.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }
  // Hash the password (IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // Success message
    $success_message = "Registration successful! You have been created.";

  } catch (PDOException $e) {
    // Error handling
    $error_message = "An error occurred while registering: " . $e->getMessage();
  }
}
?>
