    <br><br>

    <button type="submit">Place Order</button>
  </form>

  <a href="index.php">Back to Home</a> <!-- Link back to the main page -->

</body>
</html>


<?php

// Sample product data (replace with a database connection in a real application)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00]
];

// Function to handle the purchase process
function handlePurchase($cart, $products) {
    $total = 0;
    $orderSummary = "";

    foreach ($cart as $product_id => $quantity) {
        if (isset($products[$product_id])) {
            $product = $products[$product_id];
            $total += $product['price'] * $quantity;
            $orderSummary .= $quantity . " x " . $product['name'] . " ($ " . $product['price'] . ")
";
        } else {
            echo "Error: Product ID " . $product_id . " not found.
";
            return false; // Indicate an error
        }
    }

    // Display order summary
    echo "<h2>Order Summary</h2>
";
    echo $orderSummary;
    echo "<p>Total: $" . number_format($total, 2) . "</p>";

    //  (In a real application, you'd store this order in a database)
    return true;
}


// ---  Example Usage - This part handles the user input and calls the function ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Initialize the shopping cart
    $cart = [];

    // Populate the cart based on user input
    if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];

        if (isset($products[$product_id])) {
            if (isset($cart[$product_id])) {
                $cart[$product_id] += $quantity;
            } else {
                $cart[$product_id] = $quantity;
            }
        } else {
            echo "<p>Invalid product ID.</p>";
        }
    }

    // Handle the purchase
    if (empty($cart)) {
        echo "<p>Your cart is empty.  Add some items to purchase.</p>";
    } else {
        handlePurchase($cart, $products);
    }
}
?>
