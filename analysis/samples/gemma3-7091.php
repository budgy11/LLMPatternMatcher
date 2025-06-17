    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email"><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" value="1"><br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" min="1" value="1"><br><br>


    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Configuration (Adjust these to your needs)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

$cart = []; // Initialize an empty cart

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    global $cart;

    if (array_key_exists($product_id, $products)) {
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = ['quantity' => $quantity, 'price' => $products[$product_id]['price']];
        }
    } else {
        echo "Product ID $product_id not found.";
    }
}

// Function to display the cart
function displayCart()
{
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        $product_name = $products[$product_id]['name'];
        $product_price = $products[$product_id]['price'];
        $quantity = $item['quantity'];
        $total_price = $product_price * $quantity;

        echo "<li>" . $product_name . " - $" . $product_price . " x " . $quantity . " = $" . $total_price . "</li>";
    }
    echo "</ul>";

    // Calculate total cart value
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    echo "<p><strong>Total: $" . $total . "</strong></p>";
}

// Function to calculate the total
function calculateTotal() {
    $total = 0;
    foreach ($cart as $item) {
        $total += $items[$item['id']]['price'] * $item['quantity'];
    }
    return $total;
}

// Handle form submission (e.g., from a web form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Example:  Assume a form with a product ID and quantity
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    if (is_numeric($product_id) && is_numeric($quantity)) {
        addToCart($product_id, $quantity);
    } else {
        echo "<p>Invalid input. Please enter a number for product ID and quantity.</p>";
    }
}


// Example Usage (Display the cart)
displayCart();

?>
