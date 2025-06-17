    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php
// ... (Database connection code) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (Input validation) ...

    $username = $_POST["username"];
    $password = $_POST["password"];

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User found
            session_start();
            $_SESSION["username"] = $username;
            // Add any other data
            // $_SESSION["role"] = "admin";
            header("Location: secure_page.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $error_message = "Error preparing statement.";
    }
?>
