
    <a href="index.php">Continue Shopping</a> <!-- Link to the product listing page -->

</body>
</html>


<?php
// cart.php

$cart_file = 'cart.txt'; // Name of the file to store the cart

// Function to read the cart
function readCart() {
    if (file_exists($cart_file)) {
        $cart = array();
        $lines = file($cart_file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            list($product_id, $quantity) = explode(',', $line);
            $cart[$product_id] = $quantity;
        }
        return $cart;
    } else {
        return array(); // Return an empty array if the file doesn't exist
    }
}

// Function to write the cart
function writeCart($cart) {
    $data = '';
    foreach ($cart as $product_id => $quantity) {
        $data .= $product_id . ',' . $quantity . '
';
    }
    file_put_contents($cart_file, $data);
}
?>
