    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>

<?php
    if (isset($username_error)) {
        echo "<div style='color: red;'>".$username_error."</div>";
    }
    if (isset($email_error)) {
        echo "<div style='color: red;'>".$email_error."</div>";
    }
    if (isset($password_error)) {
        echo "<div style='color: red;'>".$password_error."</div>";
    }
?>
