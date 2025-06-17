    <br><br>
    Password: <input type="password" name="password" />
    <br><br>
    <input type="submit" value="Register" />
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Input validation function
function validateRegistrationData($username, $password, $email, $existingUsername, $existingEmail) {
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (strlen($username) < 3 || strlen($username) > 20) {
        $errors[] = "Username must be between 3 and 20 characters long.";
    }
    // You can add more username validation here (e.g., alphanumeric, special characters)

    // Password validation
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Email validation (using a simple regex - improve with a proper library)
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if username or email already exists
    if (isset($existingUsername) && !empty($existingUsername)) {
        $errors[] = "Username '$existingUsername' is already taken.";
    }
    if (isset($existingEmail) && !empty($existingEmail)) {
        $errors[] = "Email '$existingEmail' is already registered.";
    }

    return $errors;
}

// Registration function
function registerUser($username, $password, $email) {
    // 1. Validate the data
    $errors = validateRegistrationData($username, $password, $email, null, null); // Pass null for existing data

    if (!empty($errors)) {
        return ["success" => false, "errors" => $errors];
    }

    // 2. Hash the password (VERY IMPORTANT - NEVER store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    // 4. Execute the query
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($conn->query($sql) === TRUE) {
        // 5. Return success response
        return ["success" => true];
    } else {
        // 6. Return error response
        echo "Error: " . $sql . "<br>" . $conn->error; // For debugging - don't show in production!
        $conn->close();
        return ["success" => false, "errors" => ["Error: " . $sql . "<br>" . $conn->error]];
    }

    $conn->close();
}

// Example Usage (This would typically be handled by a form submission)
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Register the user
    $registration_result = registerUser($username, $password, $email);

    // Display the result
    if ($registration_result["success"]) {
        echo "Registration successful!  You have been logged in."; // Replace with actual login logic
    } else {
        echo "Registration failed: ";
        foreach ($registration_result["errors"] as $error) {
            echo $error . "<br>";
        }
    }
} else {
    // If not a POST request, display the registration form
    echo "<form method='post' action='" . $_SERVER["PHP_SELF"] . "'>";
    echo "Username: <input type='text' name='username'><br>";
    echo "Password: <input type='password' name='password'><br>";
    echo "Email: <input type='email' name='email'><br>";
    echo "<input type='submit' value='Register'>";
    echo "</form>";
}

?>
