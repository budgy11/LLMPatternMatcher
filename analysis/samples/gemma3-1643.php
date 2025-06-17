        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Your Email Address" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
