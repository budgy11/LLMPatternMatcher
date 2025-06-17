    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price per Unit:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Configuration (replace with your actual values)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];

$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1)
{
    if (isset($products[$product_id])) {
        if ($quantity <= 0) {
            echo "<p>Invalid quantity. Please enter a positive number.</p>";
            return;
        }

        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = ['name' => $products[$product_id]['name'], 'price' => $products[$product_id]['price'], 'quantity' => $quantity];
        }

        echo "<p>Added " . $quantity . " " . $products[$product_id]['name'] . " to your cart.</p>";
    } else {
        echo "<p>Product ID " . $product_id . " not found.</p>";
    }
}


// Function to view the cart
function view_cart()
{
    echo "<h2>Your Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $id => $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
        }
        echo "</ul>";
        echo "<p><strong>Total:</strong> $" .  total_cart_value($cart) . "</p>"; // Total calculation
    }
}


// Function to calculate total cart value
function total_cart_value($cart)
{
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return round($total, 2); // Round to 2 decimal places
}

// Function to remove an item from the cart
function remove_from_cart($product_id)
{
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        echo "<p>" . $products[$product_id]['name'] . " removed from your cart.</p>";
    } else {
        echo "<p>Product ID " . $product_id . " not found in your cart.</p>";
    }
}

// Function to clear the cart
function clear_cart()
{
    $cart = [];
    echo "<p>Your cart has been cleared.</p>";
}


// --- Example Usage (This section demonstrates how to use the functions) ---

// Add some items to the cart
add_to_cart(1, 2); // Add 2 T-Shirts
add_to_cart(2, 1); // Add 1 Jeans
add_to_cart(3, 3); // Add 3 Hats

// View the cart
view_cart();

// Remove an item from the cart
remove_from_cart(1);

// View the cart again
view_cart();

// Clear the cart
clear_cart();

// View the cart again
view_cart();

?>
