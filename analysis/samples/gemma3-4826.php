    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username">

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email">

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validation (important to prevent security vulnerabilities)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $errors[] = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters long.";
  }

  // Hash the password before storing it in the database
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Database insertion
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)");
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // Success message
    $success_message = "Registration successful! Please log in.";

  } catch (PDOException $e) {
    // Error message
    $error_message = "Error: " . $e->getMessage();
  }
}
?>
