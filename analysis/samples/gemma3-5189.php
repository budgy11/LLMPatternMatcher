    <input type="text" name="search" value="<?php echo $search_term; ?>" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php if (empty($search_results)) { ?>
