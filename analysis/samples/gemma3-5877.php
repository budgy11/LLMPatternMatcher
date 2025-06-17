        <button type="submit">Search</button>
    </form>

    <?php
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Price</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $row['id'] . "</td><td>" . $row['name'] . "</td><td>" . $row['price'] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No products found matching your search.</p>";
        }
    } else {
        echo "<p>Error executing the query.</p>";
    }
    ?>
