    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
