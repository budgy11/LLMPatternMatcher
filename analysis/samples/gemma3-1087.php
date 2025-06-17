    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php
// ... (rest of the login code)

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // ... (rest of the code, using $hashed_password in the SQL query)

?>
