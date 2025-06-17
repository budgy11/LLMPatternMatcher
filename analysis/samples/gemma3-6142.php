
</body>
</html>


if (
    stripos($item['title'], $search_term) !== false ||
    stripos($item['description'], $search_term) !== false
) {
    $results[] = $item;
}


<?php

// Example: Simple Search Bar

// 1. Get Input from the Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = isset($_POST["search_term"]) ? htmlspecialchars($_POST["search_term"]) : ""; // Sanitize input
} else {
  $searchTerm = "";
}

?>
