        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required> <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required> <br><br>

        <label for="total_price">Total Price:</label>
        <input type="number" id="total_price" name="total_price" step="0.01" required> <br><br>

        <input type="submit" value="Add to Cart">
    </form>

</body>
</html>


<?php

// --- Database Configuration (Replace with your actual values) ---
$db_host = "localhost";
$db_name = "shop_db";
$db_user = "your_username";
$db_password = "your_password";

// --- Product Data (For demonstration purposes - replace with database query) ---
$products = [
    1 => ["id" => 1, "name" => "T-Shirt", "price" => 20.00],
    2 => ["id" => 2, "name" => "Jeans", "price" => 50.00],
    3 => ["id" => 3, "name" => "Hat", "price" => 15.00],
];


// --- Function to handle the purchase process ---
function handlePurchase($cart, $products) {
    $total = 0;
    $order_details = [];

    foreach ($cart as $product_id => $quantity) {
        if (isset($products[$product_id])) {
            $product = $products[$product_id];
            $total += $product["price"] * $quantity;
            $order_details[] = [
                "product_id" => $product_id,
                "name" => $product["name"],
                "price" => $product["price"],
                "quantity" => $quantity
            ];
        } else {
            // Handle invalid product ID
            echo "<p>Error: Product ID '$product_id' not found.</p>";
            return false;
        }
    }

    //  Simulate saving the order to a database (replace with your actual database logic)
    echo "<p>Order Summary:</p>";
    echo "<ul>";
    foreach ($order_details as $detail) {
        echo "<li>" . $detail["name"] . " - " . $detail["quantity"] . " x $" . $detail["price"] . " = $" . ($detail["price"] * $detail["quantity"]) . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . $total . "</p>";

    //  You would normally store this order information in your database here.
    return true;
}



// ---  Handling the purchase request (GET method) ---

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if the "submit" button was clicked
    if (isset($_GET["submit"])) {
        // Get the cart data from the session (or other storage)
        $cart = $_SESSION["cart"] ?? [];  // Default to an empty array if not set

        // Validate cart data (e.g., check for valid product IDs)
        // Example: Check if product IDs exist in the $products array
        $valid_cart = $cart;  // In a real application, you'd perform more rigorous validation

        // Handle the purchase
        if (handlePurchase($valid_cart, $products)) {
            //  Clear the cart after successful purchase (optional)
            session_destroy();
            session_unset();
            session_start(); // Restart the session
            echo "<p>Order placed successfully!</p>";
        } else {
            echo "<p>Order failed.</p>";
        }
    } else {
        // Display the purchase form
        echo "<h1>Purchase</h1>";
        echo "<form method='GET'>";
        echo "<label for='product_id'>Product ID:</label>";
        echo "<select id='product_id' name='product_id'>";
        foreach ($products as $id => $product) {
            echo "<option value='" . $id . "'>" . $product["name"] . "</option>";
        }
        echo "</select><br><br>";

        echo "<label for='quantity'>Quantity:</label>";
        echo "<input type='number' id='quantity' name='quantity' value='1'>";
        echo "<br><br>";

        echo "<input type='submit' name='submit' value='Purchase'>";
        echo "</form>";
    }
} else {
    // Handle the case where the request method is not GET (e.g., POST) -  This is usually handled by the form correctly.
    echo "Invalid request method.";
}
?>
