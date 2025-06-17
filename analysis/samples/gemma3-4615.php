    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate input (Important for security!)
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
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) { // Minimum password length (adjust as needed)
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Check if there are any errors
    if (count($errors) > 0) {
        // Display errors
        echo "<h2>Error:</h2>";
        foreach ($errors as $error) {
            echo "<p>" . $error . "</p>";
        }
    } else {
        // Hash the password (VERY IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (firstname, lastname, email, password)
                VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

        // Execute the query
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($conn->query($sql) === TRUE) {
            echo "<p>User registered successfully!</p>";
            // Redirect to a success page or the homepage
            header("Location: success.php"); // Replace success.php with your desired page
            exit();
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }

        $conn->close();
    }
}
?>
