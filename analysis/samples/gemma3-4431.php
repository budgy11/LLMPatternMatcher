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

// Database configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Connect to the Database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Sanitize and Validate Inputs
    $username = trim($username); // Remove leading/trailing whitespace
    $password = trim($password);
    $email = trim($email);

    // Basic validation (You should add more robust validation here)
    if (empty($username) || empty($password) || empty($email)) {
        return false; // Return false if any field is empty
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Return false if email is not a valid format
    }

    // Hash the password (IMPORTANT - Never store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Insert Data into the Database
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $username, $hashed_password, $email);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        echo "Error preparing statement.";
        return false;
    }
}

// Example Usage (This part would typically be within a form submission handler)
// Assuming you have a form with fields 'username', 'password', and 'email'

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST["username"];
//     $password = $_POST["password"];
//     $email = $_POST["email"];

//     if (registerUser($username, $password, $email)) {
//         echo "Registration successful!  Please check your email to verify.";
//     } else {
//         echo "Registration failed. Please check your inputs and try again.";
//     }
// }

?>
