    <span class="error" style="color: red;"><?php if (isset($username_error)) echo $username_error; ?></span>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>">
