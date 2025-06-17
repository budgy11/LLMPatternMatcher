    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password">
    <br><br>

    <button type="submit">Login</button>
  </form>

  <?php
  if (isset($errors)) {
    echo "<div class='error'>";
    foreach ($errors as $error) {
      echo $error . "<br>";
    }
    echo "</div>";
  }
  ?>
