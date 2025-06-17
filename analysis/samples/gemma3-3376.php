

<?php

// Database connection details - Replace with your actual credentials
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate inputs (Crucial for security -  Expand this validation!)
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
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
    if (strlen($password) < 8) {  //Example minimum password length
        $errors[] = "Password must be at least 8 characters long.";
    }


    // Check if there are any errors
    if (count($errors) > 0) {
        // Display errors to the user (Important for debugging and user feedback)
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
    } else {
        // Prepare the SQL query - Use prepared statements to prevent SQL injection
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password); // 's' for string, 'i' for integer, etc.
        $stmt->execute();

        // Success message
        echo "Registration successful! You can now login.";
        // Redirect the user (optional)
        header("Location: login.php"); // Assuming you have a login.php page
        exit();

    }
}

// Database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
