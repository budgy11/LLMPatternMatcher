    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="confirm_email">Confirm Email:</label>
    <input type="email" id="confirm_email" name="confirm_email" required><br><br>

    <button type="submit">Register</button>
</form>

<?php
// Display error messages
if (!empty($errors)) {
    echo "<div style='color: red;'>";
    foreach ($errors as $key => $value) {
        echo $key . ": " . $value . "<br>";
    }
    echo "</div>";
}
?>
