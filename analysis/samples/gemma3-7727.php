    </select>
    <br>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required>
    <br>
    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Configuration (You'll likely want to move this to a config file)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200.00],
    2 => ['name' => 'Mouse', 'price' => 25.00],
    3 => ['name' => 'Keyboard', 'price' => 75.00],
];

$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $product_name = $product['name'];
        $product_price = $product['price'];

        // Check if the item is already in the cart
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = [
                'name' => $product_name,
                'quantity' => $quantity,
                'price' => $product_price,
            ];
        }
        
        // Optionally, you could log this action.
        // log_action("Item added to cart: " . $product_name . " x " . $quantity);
    } else {
        // Handle invalid product ID
        echo "<p style='color:red;'>Invalid product ID: " . $product_id . "</p>";
    }
}

// Function to view the cart
function viewCart()
{
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $id => $item) {
            echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
        }
        echo "</ul>";
    }
}


// Function to calculate the total price
function calculateTotalPrice()
{
    $total = 0;
    foreach ($cart as $id => $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

// ---  Example usage (This would typically be part of a form/interface)  ---

// Handle adding to cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];  // Important: Cast to integer to avoid vulnerabilities
    $quantity = (int)$_POST['quantity'];       //  Important: Cast to integer

    addToCart($product_id, $quantity);
}

// Display the cart
viewCart();

// Display the total price
echo "<p><strong>Total: $" . calculateTotalPrice() . "</strong></p>";
?>
