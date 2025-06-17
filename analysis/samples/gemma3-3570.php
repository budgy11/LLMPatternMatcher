    <?php if (isset($username_error)) echo "<span style='color:red;'>$username_error</span>"; ?>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
