
<?php

// Include the header and config files
include 'header.php';
require_once 'config.php';

if (isset($_GET['token'])) {
    // Check if token matches session token
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $token = $_GET['token'];
    
    if (isset($_SESSION['token']) && $_SESSION['token'] == $token) {
        
        // Check if user exists
        $email = $_POST['email'];
        $query = "SELECT * FROM users WHERE email='" . $email . "'";
        
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            // Update password
            $new_password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($new_password == $confirm_password) {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update the user's password in the database
                $query = "UPDATE users SET password='" . $hash . "' WHERE email='" . $email . "'";
                
                $conn->query($query);
                
                echo "<p>Password updated successfully!</p>";
            } else {
                echo "<p>Passwords don't match.</p>";
            }
        } else {
            echo "<p>User not found.</p>";
        }
    } else {
        echo "<p>Invalid token.</p>";
    }
    
    $conn->close();
}

?>

<!-- Display form to enter new password and confirm it -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required><br><br>
    <label for="password">New Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <label for="confirm_password">Confirm New Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>
    <button type="submit">Submit</button>
</form>

<?php include 'footer.php'; ?>
