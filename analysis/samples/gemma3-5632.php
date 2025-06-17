        <input type="text" name="search" placeholder="Enter search term..." value="<?php echo $searchTerm; ?>">
        <button type="submit">Search</button>
    </form>

    <?php

    // Perform the search (example: searching in a table called 'products')
    if ($searchTerm != '') {
        $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<br><br><h2>Search Results:</h2>";
            while($row = $result->fetch_assoc()) {
                echo "<p>Product Name: " . $row["name"] . ", Price: " . $row["price"] . "</p>";
            }
        } else {
            echo "<p>No results found for '" . $searchTerm . "'.</p>";
        }
    }

    ?>
