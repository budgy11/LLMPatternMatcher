    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php
session_start();

if (isset($_SESSION["username"])) {
    echo "<h1>Welcome, " . $_SESSION["username"] . "!</h1>";
} else {
    echo "<p>You are not logged in.</p>";
}
?>
