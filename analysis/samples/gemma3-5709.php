    <input type="text" name="search" placeholder="Enter search term" value="<?php echo htmlspecialchars($searchTerm); ?>" />
    <button type="submit">Search</button>
  </form>

  <?php if (isset($results) && isset($results['error'])) { ?>
