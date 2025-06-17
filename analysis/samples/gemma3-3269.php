        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required minlength="8">

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle user registration
function registerUser($username, $email, $password) {
  // 1. Validate Input (Important for Security)
  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  if (empty($username) || empty($email) || empty($password)) {
    return "Error: All fields are required.";
  }

  // Basic email validation (can be improved)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  // Password complexity check (example)
  if (strlen($password) < 8) {
    return "Error: Password must be at least 8 characters long.";
  }

  // 2. Database Connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    return "Error: Unable to connect to database - " . $conn->connect_error;
  }

  // 3. Prepare and Execute SQL Query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

  if ($conn->query($sql) === TRUE) {
    return "Registration successful!  You have been logged in.";
  } else {
    return "Error: " . $sql . "<br>" . $conn->error;
  }

  // 4. Close Connection
  $conn->close();
}

// --- Example Usage (Handle Form Submission) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  $registration_result = registerUser($username, $email, $password);

  echo $registration_result;
}

?>
