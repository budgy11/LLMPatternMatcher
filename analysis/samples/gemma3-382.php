    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <?php if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
  } ?>
