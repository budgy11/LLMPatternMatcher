      <input type="text" name="search_term" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Enter search term">
      <button type="submit">Search</button>
    </form>
  </div>

  <?php if (count($results)) { ?>
