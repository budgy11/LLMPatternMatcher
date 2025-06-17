    Username: <input type="email" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Replace with your register page -->

</body>
</html>


<?php
// Example of securely hashing the password
$password = $_POST["password"];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Store $hashed_password in the 'password' column of the 'users' table.
// NEVER store passwords in plain text!
?>
