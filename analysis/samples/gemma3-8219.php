    

3.  **Modify `view_cart.php` to Fetch Product Details:**  In `view_cart.php`, you need to fetch the product details based on the `$product_id` (which is passed from `add_to_cart.php` and stored in `$_SESSION['cart']`).  Example:

    
    <?php
    session_start();

    $total = calculate_total();

    foreach ($_SESSION['cart'] as $product_id => $item) {
        $product_name = 'Product ' . $product_id;  // Example:  Replace with your data source
        echo "<td>" . $product_name . "</td>";
        // ... rest of the table rows
    }
    ?>
