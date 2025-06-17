

<?php
// Example of adding to cart

$product_id = 1;
$quantity = 2;

if(addToCart($product_id, $quantity)){
    echo "Product added to cart successfully!";
} else {
    echo "Failed to add product to cart.";
}

?>
