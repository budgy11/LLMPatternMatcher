
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <span class="error" style="color: red;"><?php if (isset($email_error)) echo $email_error; ?></span>
