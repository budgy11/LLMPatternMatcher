      return false; // Password must contain at least one lowercase, one uppercase, one number and one special character
  }

  // Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Check if the username already exists
  $check_query = "SELECT id, username, email FROM users WHERE username = ? OR email = ?";
  $stmt = $conn->prepare($check_query);
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $stmt->close();
    return false; // Username or email already exists
  }

  // Insert the new user into the database
  $insert_query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($insert_query);
  $stmt->bind_param("sss", $username, $hashed_password, $email);
  if ($stmt->execute()) {
    $stmt->close();
    return true; // Registration successful
  } else {
    $error_msg = $stmt->error;
    $stmt->close();
    // Handle database errors appropriately (e.g., log them)
    error_log("Registration error: " . $error_msg);
    return false; // Registration failed
  }
}


// Example Usage (Simulated - Replace with your actual form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "Registration successful!  You have been redirected.";
    // Redirect to a success page or log in page
  } else {
    echo "Registration failed. Please try again.";
  }
}
?>
