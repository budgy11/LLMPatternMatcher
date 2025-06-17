        <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
        <input type="submit" value="Search">
    </form>

    <?php

    if (isset($_GET['search_term'])) {
        $search_term = htmlspecialchars($_GET['search_term']);

        // Example: Searching in a 'products' table
        // This is just a demonstration; adapt to your actual table and columns
        $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<ul>";
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<li>" . $row["name"] . " - " . $row["description"] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "No results found.";
        }
    }
    ?>
