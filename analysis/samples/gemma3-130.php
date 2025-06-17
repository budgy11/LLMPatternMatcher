    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <?php
    if (!empty($error)) {
      echo "<div style='color: red;'>Error: " . implode(", ", $error) . "</div>";
    }
  ?>
