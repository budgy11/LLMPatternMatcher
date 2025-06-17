
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <span class="error" style="color: red;"><?php if (isset($password_error)) echo $password_error; ?></span>
