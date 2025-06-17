    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <span class="error" style="color: red;"><?php if (isset($username_error)) echo $username_error; ?></span>
