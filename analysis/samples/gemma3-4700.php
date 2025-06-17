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

// Database configuration (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Check if the email is already registered
    $emailExists = false;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $check_query = "SELECT email FROM users WHERE email = '$email'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $emailExists = true;
    }

    $conn->close();

    // If the email doesn't exist, register the user
    if (!$emailExists) {
        // Hash the password (Important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $insert_query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

        // Execute the query
        if ($conn = new mysqli($host, $username, $password, $database)) {
            if ($conn->query($insert_query)) {
                echo "Registration successful! Please check your email to activate your account.";
                // Optionally redirect to a success page
                // header("Location: success.php");
                // die();
            } else {
                echo "Error: " . $insert_query . "<br>" . $conn->error;
            }
            $conn->close();
        } else {
            echo "Error: Connection failed!";
        }
    } else {
        echo "This email is already registered. Please log in.";
    }
}
?>
