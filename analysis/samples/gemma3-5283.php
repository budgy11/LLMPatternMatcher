        <input type="text" name="search" value="<?php echo $searchTerm; ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (empty($searchResults)): ?>
