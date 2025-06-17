    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

  <?php
    if(isset($response)){
      echo "<p>" . htmlspecialchars($response) . "</p>";
    }
  ?>
