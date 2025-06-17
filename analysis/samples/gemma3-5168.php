</div>

<script>
  // Optional:  Add a button click to trigger the search.  Useful if you don't want to use the button.
  // document.getElementById('search-button').addEventListener('click', function() {
  //   document.getElementById('search-form').submit();
  // });
</script>

</body>
</html>


<?php
// ... (HTML and CSS) ...

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ... (Get search term from $_GET['search']) ...

// Construct the SQL query (use prepared statements to prevent SQL injection)
$searchTerm = $_GET['search'];
$sql = "SELECT * FROM items WHERE name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";

// Execute the query
$result = $conn->query($sql);

// ... (Process the results and display them) ...

// Close the database connection
$conn->close();
?>
