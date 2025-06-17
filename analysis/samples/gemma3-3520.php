    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
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

  // Validate the data (Important!)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters long.";
  }

  // Hash the password - NEVER store plain text passwords!
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Attempt to insert the data into the database
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)");

    // Bind the parameters
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute the statement
    $stmt->execute();

    // Display success message
    echo "Registration successful! Please verify your email.";

  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>
