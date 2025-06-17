        <input type="text" name="search_term" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php
// Assuming you have a database connection established ($db)

function searchData($searchTerm) {
  global $db; // Assuming $db is your database connection

  $query = "SELECT * FROM my_table WHERE title LIKE '%" . $searchTerm . "%'";
  $result = $db->query($query);

  $results = array();
  while ($row = $result->fetch_assoc()) {
    $results[] = $row['title']; // Or however your data is structured
  }
  return $results;
}
?>
