    <span style="color: red;"><?php if (isset($email_error)) echo $email_error; ?></span>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
