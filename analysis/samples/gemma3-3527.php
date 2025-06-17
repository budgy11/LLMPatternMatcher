
    <br><br>

    <input type="submit" value="Register">
  </form>

  <?php if (isset($success_message)) echo "<p class='success'>" . $success_message . "</p>";
  if (isset($error_message)) echo "<p class='error'>" . $error_message . "</p>"; ?>
