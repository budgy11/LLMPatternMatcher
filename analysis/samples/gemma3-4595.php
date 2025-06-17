    <span class="error"><?php if (isset($email_error)) echo $email_error; ?></span>

    <br><br>

    <input type="submit" value="Register">
  </form>

  <?php if (isset($registration_message)) {
    echo "<p class='error'>" . $registration_message . "</p>";
  } ?>
