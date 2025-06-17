
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php
    $cart_items = get_cart_contents();

    if (empty($cart_items)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart_items as $item) {
            echo "<li>";
            echo "Item ID: " . $item['item_id'] . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            // Add code here to display the product name or other details from a 'products' table
            echo "Price: $5.00 per item (Example)<br>";
            echo "Total Price: $5.00 * " . $item['quantity'] . " = $";
            echo $item['quantity'] * 5.00;
            echo "<form method='get' action=''>";
            echo "<input type='hidden' name='update_quantity' value='" . $item['item_id'] . "'>";
            echo "<input type='number' name='quantity' value='" . $item['quantity'] . "' min='1'>";
            echo "<input type='submit' value='Update'>";
            echo "<input type='hidden' name='remove_from_cart' value='" . $item['item_id'] . "'>";
            echo "</form>";
            echo "</li>";
        }
        echo "</ul>";
        $total = calculate_total();
        echo "<p>Total: $" . $total . "</p>";
    }
    ?>
