        <input type="text" name="search_term" placeholder="Enter a fruit name">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php
// Assuming you have a database connection established ($conn)
// ... your database connection code ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = trim($_POST["search_term"]);

    if (!empty($searchTerm)) {
        $sql = "SELECT * FROM fruits WHERE name LIKE '%" . $searchTerm . "%'";
        $result = mysqli_query($conn, $sql);  // Adjust for your database library

        if ($result) {
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>" . $row["name"] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Error executing query.</p>";
        }
    } else {
        echo "<p>Please enter a search term.</p>";
    }
}
?>
