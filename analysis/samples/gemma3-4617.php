    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";


// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the form data (Important for security)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters long.";
  } elseif (preg_match("/@/", $email)) {
    $errors = "Please enter a valid email address.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }

  // Hash the password (VERY IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the user data into the database
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // Redirect to a success page or display a success message
    echo "Registration successful! Please check your email for verification.";
    // Or: header("Location: success.php"); // Redirect to a success page
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }

}
?>
