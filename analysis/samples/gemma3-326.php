    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <?php
}


// Example usage:
// if (isset($_POST['username']) && isset($_POST['password'])) {
//   if (loginUser($_POST['username'], $_POST['password'])) {
//     echo "Login successful!";
//     // Redirect to a protected page or display a welcome message
//   } else {
//     echo "Invalid username or password.";
//   }
// }

// Display the login form
displayLoginForm();

?>
