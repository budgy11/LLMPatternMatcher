    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php
//... (Database connection code)

function login($username, $password) {
  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Using a prepared statement
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["username"] = $row["username"];
    return true;
  } else {
    return false;
  }

  $stmt->close();
  $conn->close();
}
?>
