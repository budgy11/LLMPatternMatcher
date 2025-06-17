        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Include the database connection file (assuming you have one)
require_once 'config.php'; // Replace 'config.php' with your actual connection file

/**
 * Registers a new user to the database.
 *
 * @param string $username The desired username.
 * @param string $password The user's password.
 * @param string $email The user's email address.
 * @return bool True if registration was successful, false otherwise.
 */
function registerUser($username, $password, $email) {
  // Sanitize and validate input
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  if (empty($username)) {
    return false; // Username cannot be empty
  }
  $username = trim($username); // Remove whitespace
  if (strlen($username) > 50) {
    return false; // Username too long
  }

  // Password validation (basic example -  enhance for security)
  if (strlen($password) < 8) {
    return false; // Password too short
  }
  if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]*?><{}.])/', $password)) {
