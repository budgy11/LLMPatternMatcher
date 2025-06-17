    </form>

    <hr>

    <h2>Cart</h2>

    <?php
    if (isset($_SESSION["cart"])) {
        echo "<div class='cart'>";
        echo "<table>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th></tr>"; // Added header row

        foreach ($_SESSION["cart"] as $item) {
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>$" . $item['price'] . "</td>";
            // Optionally add quantity input here if needed
            echo "<td>" . "1" . "</td>"; // Default quantity of 1
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    ?>
