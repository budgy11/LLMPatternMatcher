    <?php if (isset($email_error)) echo "<span class=\"error\">" . $email_error . "</span>"; ?>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
