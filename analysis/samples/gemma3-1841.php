    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="your.email@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
