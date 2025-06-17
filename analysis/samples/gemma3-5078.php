        <button type="submit">Search</button>
    </form>
    <?php
}

// Handle the search submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search_term = isset($_GET['search_term']) ? trim($_GET['search_term']) : '';

    if (!empty($search_term)) {
        // SQL query (use prepared statements for security)
        $query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";  // Use LIKE for partial matches
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<h2>Search Results:</h2>";
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>" . htmlspecialchars($row['name']) . "</li>"; // Replace 'name' with your column
            }
            echo "</ul>";
        } else {
            echo "<p>Error querying the database.</p>";
        }
    }
}
?>
