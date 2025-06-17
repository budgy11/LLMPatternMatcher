    <h2>Search Results for "<?php echo htmlspecialchars($search_term); ?>"</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Description</th>
          <!-- Add more columns based on your table structure -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($results as $row) { ?>
