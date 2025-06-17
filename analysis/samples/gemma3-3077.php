    <?php if (isset($password_error)) echo "<span class=\"error\">" . $password_error . "</span>"; ?>

    <br><br>

    <input type="submit" value="Register">
  </form>

  <?php if (isset($registration_message)) echo "<p>" . $registration_message . "</p>"; ?>
