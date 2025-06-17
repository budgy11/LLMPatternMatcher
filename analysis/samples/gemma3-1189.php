    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// ... (Your login function code) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (Get input values) ...

    if (empty($username) || empty($password)) {
        // ... (Error handling) ...
    } else {
        // Connect to the database (as before)
        $conn = new mysqli($servername, $username, $password, $dbname);
        // ... (Check connection) ...

        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT); // Use password_hash()

        // Query to select the user from the database
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found!
            session_start();
            $_SESSION["username"] = $username;
            // ... (Redirect, welcome message, etc.) ...

        } else {
            $error = "Invalid username or password.";
        }
    }
}

?>
