    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_username';
$db_pass = 'your_db_password';

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (Important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        //  Hashes for Password Storage - DO NOT STORE PLAINTEXT PASSWORDS!
        // In a real application, you would hash and salt the password before storing it in the database.
        // This is a simplified example for demonstration purposes.

        // Attempt to authenticate the user
        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");

        if (mysqli_num_rows($result) > 0) {
            // User found, set session variables and redirect
            $_SESSION["username"] = $username;
            // Optionally, store user information in the session:
            // $_SESSION['user_id'] = $result['id'];
            //  ... other user details ...

            // Redirect to a secure page (e.g., the homepage)
            header("Location: homepage.php");
            exit(); // Very important to stop further execution after redirect
        } else {
            $error_message = "Incorrect username or password.";
        }
    }
}
?>
