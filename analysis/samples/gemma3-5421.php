      <input type="text" name="search_term" placeholder="Enter search term">
      <button type="submit">Search</button>
    </form>
  </div>

</body>
</html>


<?php
// Connect to your database (replace with your credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // Set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Sanitize (still important for other reasons, like preventing XSS)
  $searchTerm = htmlspecialchars($searchTerm);

  // Prepare the SQL statement
  $stmt = $conn->prepare("SELECT * FROM your_table WHERE your_column LIKE :search_term");  // Replace your_table and your_column

  // Bind the parameter
  $stmt->bindParam(':search_term', $searchTerm, PDO::PARAM_STR);

  // Execute the query
  $stmt->execute();

  // Fetch the results
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if ($results) {
    echo "<p>Search Results:</p>";
    echo "<ul class='results'>";
    foreach ($results as $row) {
      echo "<li>" . $row["your_column"] . "</li>";  // Replace your_column
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }
}
?>
