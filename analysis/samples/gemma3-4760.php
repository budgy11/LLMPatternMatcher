    <span style="color: red;"><?php if (isset($username_error)) echo $username_error; ?></span>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
