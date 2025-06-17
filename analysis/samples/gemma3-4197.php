    <span class="error" style="color: red;"><?php if (isset($email_error)) echo $email_error; ?></span>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <span class="error" style="color: red;"><?php if (isset($password_error)) echo $password_error; ?></span>
