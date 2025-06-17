

<?php
// ... (Your login function code) ...

if ($result->num_rows > 0) {
    // User found!
    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];

    if (password_verify($password, $hashed_password)) {
        // Password matches!
        session_start();
        $_SESSION["username"] = $username;
        // ... (Redirect, welcome message, etc.) ...
    } else {
        $error = "Invalid username or password.";
    }
}
?>
