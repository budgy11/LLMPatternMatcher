
    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Assuming you're using a database connection established elsewhere
// (e.g., via a config file or global variable)
// For demonstration purposes, let's assume:
// $db_connection is a valid database connection object.
// $table_name = 'users'; // Name of the table to store user data

function registerUser($username, $password, $email) {
  // Input Validation (Important for security)
  $username = trim($username);
  $password = trim($password);
  $email = trim($email);

  // Check if the username, email, and password are empty
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  // Sanitize input (Prevent SQL Injection!)
  $username = mysqli_real_escape_string($db_connection, $username);
  $password = mysqli_real_escape_string($db_connection, $password);
  $email = mysqli_real_escape_string($db_connection, $email);


  // Check if the username or email already exists
  $sql = "SELECT * FROM $table_name WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($db_connection, $sql);

  if (mysqli_num_rows($result) > 0) {
    return "Error: Username or email already exists.";
  }

  // Hash the password (VERY IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the user into the database
  $sql = "INSERT INTO $table_name (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  if (mysqli_query($db_connection, $sql)) {
    return "User registered successfully!";
  } else {
    return "Error: " . mysqli_error($db_connection);
  }
}


// Example usage (This part would be in your HTML form handling)
//  This is just for demonstrating how to call the function.
//  You would typically process the form data and call this function.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $registration_message = registerUser($username, $password, $email);
  echo $registration_message; // Display the registration message
}
?>
