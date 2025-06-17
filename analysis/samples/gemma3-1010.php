  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter your username">
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password">
    <br><br>
    <button type="submit">Login</button>
  </form>
  <?php
}

// Start the session
session_start();

// Display the login form
displayLoginForm();

// If a user is logged in (session variable exists), you can display other content
if (isset($_SESSION["username"])) {
    echo "<p>Welcome, " . $_SESSION["username"] . "!</p>";
}
?>
