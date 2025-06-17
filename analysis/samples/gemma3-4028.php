    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
