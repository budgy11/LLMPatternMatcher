
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <?php if (isset($password_error)) {
      echo "<p style='color: red;'>$password_error</p>";
    } ?>
