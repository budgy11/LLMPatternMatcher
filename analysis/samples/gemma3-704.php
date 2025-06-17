    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php
// (Previous code for form and database connection)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
       // Use prepared statement to prevent SQL injection
       $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters
       $stmt->execute();
       $result = $stmt->get_result();

       if ($result->num_rows == 1) {
           session_start();
           $_SESSION["username"] = $username;
           $result->fetch_assoc();
           echo "Login successful!";
           header("Location: welcome.php");
           exit();
       } else {
           $error = "Invalid username or password.";
       }
       $stmt->close();
    }
}
?>
