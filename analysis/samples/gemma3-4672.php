        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password">
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email">
        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $errors = "Password must be at least 6 characters long.";
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if there are any errors
  if ($errors) {
    // Display errors
    echo "<div class='error'>" . $errors . "</div>";
  } else {
    // Insert the user into the database
    try {
      $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Prepare the SQL query
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

      // Bind the parameters
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $hashed_password);

      // Execute the query
      $stmt->execute();

      // Display a success message
      echo "<div class='success'>Registration successful!</div>";
      // Optionally, redirect to a login page
      // header("Location: login.php");
      // exit;

    } catch (PDOException $e) {
      echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
    }
  }
}
?>
