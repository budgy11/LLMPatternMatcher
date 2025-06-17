        </select>

        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>

    <script>
      function updateCart() {
        // Get the current value of the input element
        const quantityInput = document.getElementById("product_id"); // Changed 'product_id' to 'product_id'
        const productId = parseInt(productId);

        // Update the cart (this function will be called from the HTML)
        updateCartData(productId);
      }
    </script>

</body>
</html>


<?php

// Sample Product Data (Replace with your database connection)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200.00],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25.00],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75.00],
];

// Session Handling (for storing cart data)
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if "add_to_cart" form was submitted
    if (isset($_POST["action"]) && $_POST["action"] == "add_to_cart") {
        $product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : null;

        if ($product_id && isset($products[$product_id])) {
            $product = $products[$product_id];

            if (!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = [];
            }

            $_SESSION["cart"][] = $product;
            echo "<p>Product '" . $product['name'] . "' added to cart.</p>";
        } else {
            echo "<p>Invalid product ID.</p>";
        }
    }

    // Handle checkout (example - you'll need to integrate payment processing)
    if (isset($_POST["action"]) && $_POST["action"] == "checkout") {
        // Validate cart, process payment, etc.
        // This is a simplified example; replace with actual payment integration.
        echo "<p>Checkout initiated! (Payment processing would happen here)</p>";

        // Clear the cart after checkout (optional)
        $_SESSION["cart"] = [];
        echo "<p>Cart emptied.</p>";
    }
}
?>
