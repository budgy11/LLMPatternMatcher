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

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the SQL query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("sss", $username, $hashed_password, $email);  // "s" for string, "i" for integer (if needed)
    $stmt->execute();
    return true; // Successfully registered
  } else {
    return false; // Error preparing or executing the query
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage:  (This would typically come from a form submission)
//  You would likely receive these values from a form submitted via HTML
// $username = $_POST['username'];
// $password = $_POST['password'];
// $email = $_POST['email'];

// Check if the form was submitted (for demonstration purposes)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validate input (IMPORTANT - Add more robust validation here!)
    if (empty($username) || empty($password) || empty($email)) {
        echo "All fields are required!";
    } else {
        // Register the user
        if (registerUser($username, $password, $email)) {
            echo "Registration successful!  Please check your email for verification.";
            // Redirect to a success page or login page
        } else {
            echo "Registration failed.  Please try again.";
        }
    }
} else {
  // If this is not a POST request, you might want to display a form
  echo "<form action=\"register.php\" method=\"post\">";
  echo "Username: <input type=\"text\" name=\"username\"><br>";
  echo "Password: <input type=\"password\" name=\"password\"><br>";
  echo "Email: <input type=\"email\" name=\"email\"><br>";
  echo "<input type=\"submit\" value=\"Register\">";
  echo "</form>";
}

?>
