    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>New user? <a href="register.php">Register here</a></p>

</body>
</html>


   <?php
   // ... (Database connection code)

   if ($result->num_rows == 1) {
     $user_row = $result->fetch_assoc();
     $hashed_password = $user_row["password"];  // Get the hashed password from the database
     if (password_verify($password, $hashed_password)) {
       // Password matches, proceed with login
       // ... (Set session variables)
     } else {
       $error_message = "Invalid password.";
     }
   }
   ?>
