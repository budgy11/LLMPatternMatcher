    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php
// ... (Database credentials) ...

// Function to handle login (using prepared statements)
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" means two string parameters

  // Execute the query
  $result = $stmt->execute();

  if ($result) {
    $row = $stmt->fetch_assoc();
    if($row){
      $_SESSION['username'] = $row['username'];
      $_SESSION['user_id'] = $row['id'];
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }

  $stmt->close();
  $conn->close();
}
?>
