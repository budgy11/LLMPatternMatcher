        Username: <input type="text" name="username" />
        <br />
        Password: <input type="password" name="password" />
        <br />
        <button type="submit">Login</button>
    </form>

    <br>
    <a href="register.php">Don't have an account? Register here.</a>  <!-- Link to your registration page -->


</body>
</html>


<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // ... (other code) ...

        if ($password != "password123") {
            $error_message = "Incorrect password.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Now store $hashed_password in your database.  DO NOT store the plain text password.
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            // ... (database query) ...
        }
    }
?>
