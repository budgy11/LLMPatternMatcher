    Username: <input type="email" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <?php if (isset($success)) {
    echo "<p style='color:green;'>$success</p>";
  } ?>
