    </select>
    <input type="submit" value="Purchase">
</form>

</body>
</html>


<?php
// purchase.php

// Include database connection
require_once 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product ID from the form
    $product_id = $_POST["product_id"];

    // Retrieve product information (replace with a database query if needed)
    $product = null;
    foreach ($products as $p) {
        if ($p['id'] == $product_id) {
            $product = $p;
            break;
        }
    }

    if ($product) {
        // Display order details (e.g., save to a database)
        echo "<h2>Order Confirmation</h2>";
        echo "<p>You purchased: " . $product['name'] . " for $" . $product['price'] . "</p>";
        //  In a real application, you would save this order to your database.
    } else {
        echo "<p>Invalid Product ID.</p>";
    }
} else {
    // Handle other cases or redirect if needed
    header("Location: index.php"); // Redirect to the main page.
}
?>
