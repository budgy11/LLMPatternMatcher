
</body>
</html>


<?php
// Assuming you have a database connection established (e.g., $conn)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$searchTerm = htmlspecialchars($searchTerm);

$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
$result = mysqli_query($conn, $query);  // Use your database connection here

$products = array();
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
?>
