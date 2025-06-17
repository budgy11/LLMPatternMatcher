        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" placeholder="Your Name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" placeholder="Your Email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Your Password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $username, $hashed_password, $email); // "ss" means string

        if ($stmt->execute()) {
            // Registration successful
            return true;
        } else {
            // Registration failed
            echo "Registration failed: " . $stmt->error;
            return false;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
        return false;
    }

    // Close the database connection (Important!)
    $stmt->close();
    $conn->close();
}

// Example Usage (Demonstration - in a real application, this would come from a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if (empty($username) || empty($password) || empty($email)) {
        echo "All fields are required.";
    } else {
        if (registerUser($username, $password, $email)) {
            echo "Registration successful! Please check your email for verification instructions.";
        } else {
            echo "Registration failed. Please try again.";
        }
    }
}
?>
