
    <hr>

    <form method="post" action="">
        <button type="submit" name="action" value="checkout">Checkout</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and the connection is stored in $db

// Function to handle the purchase process
function handlePurchase($cart, $db) {
    // 1. Validate Input (Crucial for security!)
    if (empty($cart)) {
        return "Cart is empty.  Cannot complete purchase.";
    }

    // Example: Simple validation (expand for more robust validation)
    foreach ($cart as $item_id => $quantity) {
        // Add more validation here, e.g., check if item_id exists in your database
        if (!isset($db->query("SELECT id FROM products WHERE id = $item_id"))) {
            return "Invalid item ID: $item_id";
        }
    }


    // 2. Get User Information (Example - Adapt to your system)
    $userId = $_SESSION['user_id'];  // Assuming user is logged in and ID is in session
    if (!$userId) {
        return "User not logged in. Please log in.";
    }


    // 3. Create Order in the Database
    $order_id = $db->query("INSERT INTO orders (user_id, order_date) VALUES ($userId, NOW())");

    if (!$order_id) {
        return "Failed to create order in database.";
    }

    // Loop through the cart and add items to the order
    foreach ($cart as $item_id => $quantity) {
        // Get product information
        $product = $db->query("SELECT product_id, price FROM products WHERE id = $item_id");
        if (!$product) {
            $db->query("DELETE FROM orders WHERE id = $order_id"); // Rollback if product not found
            return "Product not found.";
        }

        // Calculate total for this item
        $item_total = $product->price * $quantity;

        // Add order item
        $db->query("INSERT INTO order_items (order_id, product_id, quantity, item_total) VALUES ($order_id, $item_id, $quantity, $item_total)");
    }

    // 4. Update Cart (Remove purchased items)
    // In a real application, you'd likely use a session to manage the cart
    // This example assumes the cart is stored in a session.
    session_start();
    $_SESSION['cart'] = [];  // Clear the cart

    // 5. Confirmation Message
    return "Purchase complete! Order ID: " . $db->query("SELECT id FROM orders WHERE id = $order_id");
}

// Example Usage (Illustrative - Call this from your purchase form)
// $cart = array(1 => 2, 2 => 1); // Example cart (item_id => quantity)
// $result = handlePurchase($cart);
// echo $result;


//  ---  Example Form (purchase_form.php)  ---

// <!DOCTYPE html>
// <html>
// <head>
//     <title>Purchase</title>
// </head>
// <body>
//
// <h1>Purchase</h1>
//
// <?php
// session_start();
// if (isset($_POST['submit'])) {
//     $cart = $_SESSION['cart'];
//     $result = handlePurchase($cart);
//     echo $result;
// }
// ?>
