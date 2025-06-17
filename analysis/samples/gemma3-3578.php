    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" placeholder="Your First Name" required>

    <br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" placeholder="Your Last Name" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Your Password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Collect form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate input (Important for security - add more validation as needed)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  }
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $firstname, $lastname, $hashed_password); // s = string
  $stmt->execute();

  // Check if the query was successful
  if ($stmt) {
    $success = true;
    $message = "Registration successful!  Please check your email to verify your account.";
  } else {
    $success = false;
    $message = "Registration failed.  Check your database connection or the error in the logs.";
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>
