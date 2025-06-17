    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php
try {
  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
  // Set PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // ... your registration code using prepared statements ...
} catch(PDOException $e) {
  // Handle the exception (e.g., log the error)
  echo "Database connection failed: " . $e->getMessage();
  exit;
}
?>
