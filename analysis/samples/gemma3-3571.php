    <?php if (isset($email_error)) echo "<span style='color:red;'>$email_error</span>"; ?>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <?php if (isset($password_error)) echo "<span style='color:red;'>$password_error</span>"; ?>
