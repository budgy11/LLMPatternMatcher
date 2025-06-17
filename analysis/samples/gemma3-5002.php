    <input type="text" name="searchTerm" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
    if (isset($message)) {
      echo "<p>" . $message . "</p>";
    }
  ?>
