    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" min="1" required><br><br>


    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// 1. Product Data (Simulated Database)
$products = [
    1 => ["name" => "T-Shirt", "price" => 20.00, "quantity" => 5],
    2 => ["name" => "Jeans", "price" => 50.00, "quantity" => 3],
    3 => ["name" => "Hat", "price" => 15.00, "quantity" => 10],
];

// 2. Shopping Cart
$cart = [];

// 3. Add to Cart Function
function addToCart($product_id, $quantity) {
    if (isset($products[$product_id])) {
        if ($quantity > 0) {
            $product = $products[$product_id];
            if (isset($cart[$product_id])) {
                $cart[$product_id]['quantity'] += $quantity;
            } else {
                $cart[$product_id] = ["name" => $product['name'], "price" => $product['price'], "quantity" => $quantity];
            }
        }
    }
}


// 4. Checkout Function
function checkout() {
    $order_summary = [];
    $total_amount = 0.00;

    foreach ($cart as $item_id => $item_details) {
        $item = $products[$item_id];
        $subtotal = $item['price'] * $item['quantity'];
        $total_amount += $subtotal;

        $order_summary[$item_id] = [
            "name" => $item['name'],
            "quantity" => $item['quantity'],
            "price" => $item['price'],
            "subtotal" => $subtotal,
        ];
    }

    echo "<h2>Order Summary</h2>";
    echo "<ul>";
    foreach ($order_summary as $id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . " - Subtotal: $" . $item['subtotal'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Amount: $" . number_format($total_amount, 2) . "</strong></p>";
}


// 5. Example Usage (Simulating User Interaction)
echo "<h1>Shopping Cart</h1>";

// Add some items to the cart
addToCart(1, 2); // Add 2 T-shirts
addToCart(2, 1); // Add 1 pair of Jeans
addToCart(3, 3); // Add 3 Hats

//Display cart contents
echo "<h2>Cart Contents</h2>";
echo "<p>You have the following items in your cart:</p>";
echo "<p>Items added to your cart:</p>";

// Display the cart contents
echo "<h2>Cart Contents</h2>";
echo "<p>You have the following items in your cart:</p>";
echo "<p>Items added to your cart:</p>";
echo "<p>Items added to your cart:</p>";

// Trigger the checkout process
echo "<br>";
echo "<a href='checkout.php'>Proceed to Checkout</a>";

// Call the checkout function
checkout();
?>
