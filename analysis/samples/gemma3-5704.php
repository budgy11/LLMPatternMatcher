
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (still needed for HTML escaping in the output)
$searchTerm = htmlspecialchars($searchTerm);

// Escape the search term for the query - prepared statements handle the escaping
$sql = "SELECT * FROM products WHERE name LIKE :search";
$stmt = $conn->prepare($sql);  //Use prepare()
$stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR); //Bind the parameter
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
