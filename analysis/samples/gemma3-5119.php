
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
  // Get the search term from the GET request
  $searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; // Use htmlspecialchars for security
}


?>
