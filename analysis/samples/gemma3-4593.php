    <span class="error"><?php if (isset($username_error)) echo $username_error; ?></span>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
