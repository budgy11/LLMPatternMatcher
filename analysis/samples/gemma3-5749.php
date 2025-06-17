
  <script>
    // Optional: Add some basic styling and validation
    //  This is just an example; customize as needed.
    document.getElementById("search_term").placeholder = "Enter search term";
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established and assigned to $conn

$search_term = htmlspecialchars($_GET['search_term']);

// Example query - replace with your actual query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

$stmt = $conn->prepare($sql);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ... (rest of your code to display the results)
?>
