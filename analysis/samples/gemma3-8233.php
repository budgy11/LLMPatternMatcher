
<hr>

<h2>Add to Cart</h2>
<form method="post" action="cart.php">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="name">Product Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="price">Price:</label>
    <input type="number" step="0.01" id="price" name="price" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <input type="submit" value="Add to Cart">
</form>

</body>
</html>


<?php
session_start();

// (Include your database connection code here - same as cart.php)

// (Include your helper functions - addToCart, updateQuantity, removeFromCart, calculateTotal, getCartContents)

// Display cart contents - identical to the main cart.php page
?>
