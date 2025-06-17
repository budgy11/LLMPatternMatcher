

<?php

//... (Database Connection code - use mysqli) ...

// SQL query with placeholders
$sql = "SELECT id, username FROM users WHERE username = ? AND password = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User found, retrieve data
    $user_data = $result->fetch_assoc();
    $user_id = $user_data['id'];
    $username = $user_data['username'];

    // Session management
    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;

    // Redirect (optional)
    // header("Location: /home.php");
    return $user_id;
} else {
    return "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>
