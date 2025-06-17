        <?php if (isset($username_error)) echo "<p style='color:red;'>$username_error</p>"; ?>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
