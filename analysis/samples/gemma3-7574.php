
  <a href="checkout.php">Proceed to Checkout</a>

</body>
</html>


<?php

// Shopping Cart (Array)
$cart = array();

// Order Data (Simulated)
$order_id = 1;
$order_date = date("Y-m-d H:i:s");

// Product Data (Simulated)
$products = array(
    1 => array("id" => 1, "name" => "T-Shirt", "price" => 20),
    2 => array("id" => 2, "name" => "Jeans", "price" => 50),
    3 => array("id" => 3, "name" => "Hat", "price" => 15)
);

// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1)
{
    global $cart;

    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $cart[] = array(
            "product_id" => $product_id,
            "name" => $product["name"],
            "price" => $product["price"],
            "quantity" => $quantity
        );
    } else {
        echo "Product ID " . $product_id . " not found.";
    }
}

// Function to update quantity in cart
function update_cart_quantity($product_id, $new_quantity) {
    global $cart;

    foreach ($cart as &$item) { // Use &$item to modify the original item
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id)
{
    global $cart;

    $i = 0;
    while ($i < count($cart)) {
        if ($cart[$i]["product_id"] == $product_id) {
            unset($cart[$i]);
            return;
        }
        $i++;
    }
}

// Function to display the cart
function display_cart()
{
    echo "<h2>Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
        }
        echo "</ul>";
        $total_amount = 0;
        foreach ($cart as $item) {
            $total_amount += ($item['price'] * $item['quantity']);
        }
        echo "<p><strong>Total: $" . $total_amount . "</strong></p>";
    }
}

// Function to create an order
function create_order()
{
    global $cart, $order_id, $order_date;

    //Save cart to order
    $order = array(
        "order_id" => $order_id,
        "order_date" => $order_date,
        "items" => $cart
    );

    // Clear the cart after order placement
    $cart = array();

    // You would typically save the order to a file or database here
    // For this example, we'll just display it.
    echo "<h2>Order Confirmation</h2>";
    echo "<p>Order ID: " . $order_id . "</p>";
    echo "<p>Order Date: " . $order_date . "</p>";
    echo "<p>Order Items:</p>";
    echo "<pre>";
    print_r($order);
    echo "</pre>";
}

// Example Usage (HTML)
?>
