    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Not have an account? <a href="register.php">Register here</a></p>

</body>
</html>


<?php
// This is a simplified example.  A real registration system would be more complex.
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // **VERY IMPORTANT:**  In a real application, you would hash the password
  // using password_hash() and store the hash in the database.
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // **Simulated Database Insertion (Replace with actual database code)**
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    // Redirect to login page
    header("Location: login.php");
    exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
?>
