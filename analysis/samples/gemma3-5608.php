    <input type="text" name="search_term" value="<?php echo $searchTerm; ?>" placeholder="Enter your search term...">
    <button type="submit">Search</button>
  </form>

  <?php if (!empty($searchResults)) { ?>
