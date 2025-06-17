        </select>
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>


    <hr>

    <a href="cart.php?view_cart=1">View Cart</a>

</body>
</html>


<?php

// This is a simplified example.  A real-world purchase functionality would be significantly more complex.

// Configuration (replace with your actual values)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00],
    2 => ['name' => 'Jeans', 'price' => 50.00],
    3 => ['name' => 'Hat', 'price' => 15.00],
];

$cart = []; // Array to store items and quantities

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $cart[] = ['product_id' => $product_id, 'name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity];
    } else {
        echo "Product ID $product_id not found.";
    }
}

// Function to view the cart
function viewCart()
{
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Calculate total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    echo "<p><b>Total: $" . $total . "</b></p>";
}

// Function to remove an item from the cart
function removeFromCart($product_id)
{
    global $cart; // Access the global $cart array

    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($cart[$key]);
            echo "Item removed: " . $item['name'] . "
";
            return;
        }
    }
    echo "Product ID $product_id not found in cart.
";
}


// Handle user input (example - using GET method)
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;
    if ($product_id) {
        addToCart($product_id);
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;
    if ($product_id) {
        removeFromCart($product_id);
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'view') {
    viewCart();
}
// Example HTML for displaying the cart
?>
