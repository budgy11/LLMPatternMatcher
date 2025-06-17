    <span class="error"><?php if (isset($password_error)) echo $password_error; ?></span>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
