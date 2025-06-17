    <button type="submit">Search</button>
  </form>
  <?php
}

// Example Usage (e.g., within your main page or a sidebar)
displaySearchForm();

//  If you want to handle the search results on the 'search.php' page:

// Example of processing the search query (search.php)
if (isset($_GET['q'])) {
  $searchTerm = $_GET['q'];

  // Sanitize the search term - IMPORTANT!  Prevent SQL injection or XSS.
  $searchTerm = filter_var($searchTerm, FILTER_SANITIZE_STRING);  
  $searchTerm = htmlspecialchars($searchTerm);

  // Your search logic here.  This is just an example.
  echo "<p>You searched for: " . $searchTerm . "</p>";

  // Example: Searching a database (replace with your actual database code)
  // $results = searchDatabase($searchTerm);
  // echo "<ul>";
  // foreach ($results as $result) {
  //   echo "<li>" . $result . "</li>";
  // }
  // echo "</ul>";
}
?>
