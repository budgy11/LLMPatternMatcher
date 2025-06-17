    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and you're using a secure database connection method (e.g., PDO)

function registerUser($username, $password, $email, $db) {
  // 1. Input Validation & Sanitization - VERY IMPORTANT!
  // Prevent SQL injection and ensure data is clean
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email    = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Basic username validation (length and character restrictions)
  if (strlen($username) < 3 || strlen($username) > 20) {
    return "Username must be between 3 and 20 characters.";
  }

  // Check if username already exists
  $stmt = $db->prepare("SELECT id, username, email FROM users WHERE username = ? OR email = ?");
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    return "Username or email already exists.";
  }

  // 2. Password Hashing -  Critical for Security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Use a strong hashing algorithm

  // 3. Insert into Database
  $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
  $stmt->bind_param("ssi", $username, $hashed_password, $email);
  $stmt->execute();

  if ($stmt->error) {
    return "Error creating user: " . $stmt->error;
  }

  // 4. Success!
  return "User registered successfully!  You have been sent a verification email."; // You should send a real verification email here.
}



// Example Usage (in a form submission handler)
//  (This is a simplified example and needs to be integrated into a real web application)

// Assuming you have a form with fields: username, password, email

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email    = $_POST["email"];

  // Call the registration function
  $result = registerUser($username, $password, $email, $db); // Replace $db with your database connection

  // Display the result
  echo "<p>" . $result . "</p>";
}
?>
