

<?php

// Assuming you have a database connection established (e.g., using mysqli)
// Replace 'your_database_connection' with your actual connection
$db = mysqli_connect("localhost", "your_username", "your_password", "your_database_name");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}


// Function to register a new user
function registerUser($username, $password, $email) {
    global $db; // Access the database connection

    // Sanitize and validate input (VERY IMPORTANT)
    $username = mysqli_real_escape_string($db, $username);
    $password = mysqli_real_escape_string($db, $password);
    $email = mysqli_real_escape_string($db, $email);

    // Check if username already exists
    $check = mysqli_query($db, "SELECT id, username, email FROM users WHERE username = '$username' OR email = '$email'");
    $result = mysqli_fetch_assoc($check);

    if ($result) {
        return "Error: Username or Email already exists.";
    }

    // Hash the password (Securely store passwords!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if (mysqli_query($db, $sql)) {
        return "Registration successful!  You have been sent a verification email.";
    } else {
        return "Error: " . mysqli_error($db);
    }
}

// Example Usage (This part is for demonstration - typically this would come from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $registration_result = registerUser($username, $password, $email);

    echo $registration_result;
} else {
    // If no form data is present, display a form
    ?>
