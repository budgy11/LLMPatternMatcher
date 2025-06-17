    Username: <input type="text" name="username"><br><br>
    Email: <input type="email" name="email"><br><br>
    Password: <input type="password" name="password"><br><br>
    <input type="submit" value="Register">
</form>

<?php
if (isset($errors)) {
    echo "<div style='color:red;'>";
    foreach ($errors as $error_key => $error_message) {
        echo $error_message . "<br>";
    }
    echo "</div>";
}
?>
