        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the data (Crucial for security)
    $errors = [];

    if (empty($firstname)) {
        $errors[] = "First name cannot be empty.";
    }
    if (empty($lastname)) {
        $errors[] = "Last name cannot be empty.";
    }
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Check if there are any errors
    if (count($errors) > 0) {
        // Display the errors to the user
        echo "<h2>Error:</h2><ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($host, $username, $password, $database);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("sss", $firstname, $lastname, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<h2>Registration Successful!</h2>";
            echo "<p>You have successfully registered.  Please check your email to verify your account.</p>";

            // Optionally, redirect to a success page
            // header("Location: success.php");
            // exit();
        } else {
            echo "<h2>Registration Failed</h2>";
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
