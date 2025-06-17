    <?php if (isset($username_error)) echo "<span class=\"error\">" . $username_error . "</span>"; ?>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
