

<?php

// Assuming you have a database connection established (e.g., $db) using PDO

// Function to handle the search query
function searchProducts($query) {
    global $db;

    try {
        $query = "%" . $db->real_escape_string($query) . "%"; // Still need to escape the query for full-text search.

        $sql = "SELECT * FROM products WHERE name LIKE :query";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':query', $query, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo '<form method="GET" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
            echo '<input type="text" name="search" value="' . htmlspecialchars($query) . '" placeholder="Search Products">';
            echo '<button type="submit">Search</button>';
            echo '</form>';

            echo '<ul>';
            foreach ($result as $row) {
                echo '<li>' . htmlspecialchars($row['name']) . ' - ' . htmlspecialchars($row['description']) . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No products found matching your search.</p>';
        }
    } catch (PDOException $e) {
        echo '<p>Error: ' . $e->getMessage() . '</p>';
    }
}

// Example usage:
searchProducts($_GET['search']);
?>
