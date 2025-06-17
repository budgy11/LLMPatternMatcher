        <input type="text" name="search_term" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php
// Assuming you have a database connection established ($conn)
// and a table named 'products' with a 'name' column.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = htmlspecialchars($_POST["search_term"]);

    // Construct the SQL query (using a parameterized query for security)
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Display the results
    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . $row["name"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No results found.";
    }

    mysqli_close($conn);
}
?>
