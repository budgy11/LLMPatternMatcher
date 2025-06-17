    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>

<?php
// Display any error messages
if (!empty($username_error)) {
    echo "<p style='color: red;'>$username_error</p>";
}
if (!empty($email_error)) {
    echo "<p style='color: red;'>$email_error</p>";
}
if (!empty($password_error)) {
    echo "<p style='color: red;'>$password_error</p>";
}
?>
