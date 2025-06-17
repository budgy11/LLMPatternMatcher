
    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Data Validation (Crucial - Prevent Security Issues)
    $username = trim($username); //Remove leading/trailing spaces
    $password = trim($password);
    $email = trim($email);

    //Basic Validation - Enhance as needed
    if (empty($username) || empty($password) || empty($email)) {
        return "Error: All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email format.";
    }


    // 2. Database Interaction (Example using mysqli - Use PDO for better security)
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape data to prevent SQL injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);
    $email = $conn->real_escape_string($email);

    // Simple password hashing (VERY BASIC - Use a stronger library like Sodium for production)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // SQL Query (Use parameterized queries for better security)
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if ($conn->query($sql) === TRUE) {
        return "Registration successful!  You have been redirected.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return "Error: " . $conn->error;
    }

    $conn->close();
}

// --- Example Usage (Form Handling) ---

// Check if the registration form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Call the registration function
    $registration_result = registerUser($username, $password, $email);

    // Display the result
    echo $registration_result;
}

?>
