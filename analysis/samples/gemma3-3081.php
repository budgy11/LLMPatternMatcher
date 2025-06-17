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
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Data Validation
    $errors = [];

    // Check if username is empty
    if (empty($username)) {
        $errors['username'] = "Username cannot be empty.";
    }

    // Check if username is already taken (optional, but recommended)
    global $db_name;
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username_query = "SELECT username FROM users WHERE username = ?";
    $stmt = $conn->prepare($username_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors['username'] = "Username already exists.";
    }

    // Validate email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // 2. Password Hashing (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // 3. Insert into Database
    if (empty($errors)) { // Only proceed if there are no errors
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $email);

        if ($stmt->execute()) {
            // Registration successful
            $stmt->close();
            $conn->close();
            return true;
        } else {
            // Error during insertion
            $stmt->close();
            $conn->close();
            return false;
        }
    } else {
        // Return errors
        return $errors;
    }
}


// --- Example Usage (Form Handling - Simplified) ---
// Assuming you have a form with username, password, and email fields

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Call the registration function
    $registration_result = registerUser($username, $password, $email);

    if ($registration_result === true) {
        echo "Registration successful! You have been redirected.";
        // Redirect to a success page or login page
    } else if (is_array($registration_result)) {
        // Display errors
        echo "Registration failed:<br>";
        foreach ($registration_result as $error) {
            echo $error . "<br>";
        }
    } else {
        echo "Registration failed.";
    }
}

?>
