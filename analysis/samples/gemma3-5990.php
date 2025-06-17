        <input type="text" name="search_term" placeholder="Enter search term...">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php

function searchDatabase($searchTerm) {
    // Replace this with your actual database connection and query logic.
    // This is just a placeholder example.

    // Assuming you have a database connection established (e.g., $db_connection)

    $db_connection = mysqli_connect("localhost", "your_username", "your_password", "your_database");

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        return false;
    }

    $query = "SELECT title, description FROM products WHERE title LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

    $result = mysqli_query($db_connection, $query);

    if ($result) {
        $searchResults = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
        mysqli_free_result($result);
        return $searchResults;
    } else {
        return false;
    }

    mysqli_close($db_connection);
}
?>
