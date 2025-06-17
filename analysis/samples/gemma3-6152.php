        <input type="text" name="search" placeholder="Enter search term" value="<?php echo $searchTerm; ?>">
        <input type="submit" value="Search">
    </form>

    <?php if (empty($searchResults)): ?>
