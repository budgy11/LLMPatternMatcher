

<?php
// ... (database configuration and connection)

// Prepare the statement
$stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");

// Bind the parameters
$stmt->bind_param("s", $login_username); // "s" indicates a string

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // User not found
    echo "Invalid username or password.";
} else {
    // User found, check password
    $user_data = $result->fetch_assoc();
    if (password_verify($login_password, $user_data["password"])) {
        // Password matches, successful login
        session_start();
        $_SESSION["username"] = $login_username;
        echo "Login successful!  Redirecting...";

        // Redirect to a protected page
        header("Location: /protected_page.php");
        exit();
    } else {
        // Password does not match
        echo "Invalid username or password.";
    }
}

$stmt->close();
$conn->close();
?>
