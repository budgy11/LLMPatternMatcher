  <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>">
  <button type="submit">Search</button>
</form>


function searchResults($searchTerm) {
  // Assuming you have a data source (e.g., an array of strings)
  $data = ["apple", "banana", "orange", "grape"];

  $results = [];
  foreach ($data as $item) {
    if (strpos($item, $searchTerm) !== false) {
      $results[] = $item;
    }
  }
  return $results;
}


function searchResults($searchTerm) {
  // Replace this with your database connection and query logic
  $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

  $query = "SELECT * FROM your_table WHERE title LIKE :searchTerm OR description LIKE :searchTerm";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
  $stmt->execute();

  $results = array();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $results[] = $row['title']; // Or whatever fields you want
  }

  return $results;
}


<?php

// Assuming you have a form to capture the search query.
// This example demonstrates a simple HTML form and PHP to process the search.

// Define the search term (default value)
$search_term = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $search_term = isset($_POST["search"]) ? $_POST["search"] : "";
}

?>
