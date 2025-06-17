    <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
    <button type="submit">Search</button>
</form>

<?php if (!empty($search_results)): ?>
