  <label for="search_term">Search Products:</label>
  <input type="text" id="search_term" name="search_term" value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($searchTerm) : ''; ?>">
