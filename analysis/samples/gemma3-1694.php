

<?php
session_start(); // Start a session to track the reset process

// Check if the token is present in the URL
if (isset($_GET['token']) && isset($_GET['new_password'])) {
    $token = $_GET['token'];
    $new_password = $_GET['new_password'];

    // 1. Validate Token & Expiry
    $stmt = $db->prepare("SELECT id, password_token, password_token_expiry FROM users WHERE email = ? AND password_token = ?");
    $stmt->execute([$_SESSION['email'], $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['password_token_expiry'] < datetime('now')) {
        echo "Invalid token or token has expired.";
        exit;
    }

    // 2. Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Update the user's password
    $stmt = $db->prepare("UPDATE users SET password = ?, password_token = NULL, password_token_expiry = NULL WHERE id = ?");
    $stmt->execute([$hashed_password, $user['id']]);

    // 4. Redirect the user to the login page or a success page.
    echo "Password reset successful! Please log in.";
    header("Location: login.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
