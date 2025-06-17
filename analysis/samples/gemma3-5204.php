    <input type="text" name="search_term" placeholder="Enter search term..." value="<?php echo htmlspecialchars($_GET['search_term']); ?>">
    <input type="submit" value="Search">
  </form>

  <?php
  //  Example:  Displaying the search term (for demonstration)
  if (isset($_GET['search_term'])) {
    $searchTerm = $_GET['search_term'];
    echo "<p>You searched for: " . htmlspecialchars($searchTerm) . "</p>";
  }
  ?>
