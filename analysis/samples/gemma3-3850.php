    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <br><br>

    <button type="submit">Register</button>
  </form>

  <?php if (isset($message)) {
    echo "<p style='color: green;'>$message</p>";
  } ?>
