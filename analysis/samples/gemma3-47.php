
    <br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php
// ... (Database configuration) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important!)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    }
  }
} else {
  $error = "";
}
?>
