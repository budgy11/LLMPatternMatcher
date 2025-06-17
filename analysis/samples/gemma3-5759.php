    <input type="text" name="search_term" placeholder="Enter search term">
    <input type="submit" value="Search">
  </form>

  <?php
  // Display any error or message
  if (isset($message)) {
    echo "<p style='color: red;'>" . $message . "</p>";
  }
  ?>
