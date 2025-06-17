    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>
  <br>
  <a href="logout.php">Logout</a>

</body>
</html>


<?php

// Assuming you have a register() function that creates a new user account
// and securely hashes the password before inserting it into the database.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // ... (your register() function call here to create the user) ...

  echo "Registration successful!  Please check your email for verification."; // or redirect to login page
}
?>
