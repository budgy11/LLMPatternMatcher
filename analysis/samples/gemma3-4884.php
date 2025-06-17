        <input type="text" name="search_term" value="<?php echo htmlspecialchars($search_term); ?>">
        <button type="submit">Search</button>
    </form>

    <?php if (empty($results)): ?>
