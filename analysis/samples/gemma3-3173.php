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
// and you've created a database table named 'users' with columns:
// - id (INT, PRIMARY KEY, AUTO_INCREMENT)
// - username (VARCHAR, UNIQUE)
// - password (VARCHAR)
// - email (VARCHAR)

// **Important Security Note:**  Never store passwords in plain text.  Always hash them using a strong hashing algorithm like bcrypt.
// This example uses a simplified hashing method for demonstration purposes *only*.  **DO NOT USE THIS IN PRODUCTION.**

// Function to register a new user
function registerUser($username, $password, $email) {
  global $db; // Assuming you have a global database connection named $db

  // Validate input (essential for security and data integrity)
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  // Sanitize input (VERY IMPORTANT - prevents SQL injection)
  $username = mysqli_real_escape_string($db, $username);
  $email = mysqli_real_escape_string($db, $email);

  // Check if the username already exists
  $result = mysqli_query($db, "SELECT id, username, password, email FROM users WHERE username = '$username'");
  if (mysqli_num_rows($result) > 0) {
    return "Error: Username already exists.";
  }

  // Hash the password (using a secure method - replace with bcrypt or similar)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // For more robust hashing

  // Insert the user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  if (mysqli_query($db, $sql)) {
    return "User registered successfully!";
  } else {
    return "Error: " . mysqli_error($db);
  }
}

// **Example Usage (Form Submission Handling - This part is outside the registerUser function)**
// Assuming you have a registration form (HTML) with fields username, password, and email.

// When the form is submitted:
// 1.  Get the form data.
// 2.  Call the registerUser function with the data.
// 3.  Handle the returned result (success or error message).

// Example:
/*
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

$registration_result = registerUser($username, $password, $email);

echo $registration_result; // Display the result message
*/



// **Error Handling and Feedback:**

// - Always provide informative error messages to the user.
// - Display success messages to confirm the registration.
// - Use a proper form validation library for more advanced validation (e.g., Form Validation PHP).
// - Logging errors to a file or database for debugging.

?>
