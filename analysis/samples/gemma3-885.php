        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <br>
    <a href="logout.php">Logout</a>  <!-- Link to logout.php -->
</body>
</html>


<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page (or homepage)
header("Location: login.php");
exit;
?>
