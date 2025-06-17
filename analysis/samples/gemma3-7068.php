
<form method="post" action="index.php">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php
//This is just an example.  You'd connect to your database
//and display the product information here.

$product_id = $_GET["product_id"];  //Get product id from URL

//Simulate fetching product details (replace with actual database query)
$product_name = "Awesome Product";
$product_price = 25.00;

echo "<p>Product Name: " . $product_name . "</p>";
echo "<p>Price: $" . $product_price . "</p>";

//This would link to your product table
//echo "<p>Product ID: " . $product_id . "</p>";

?>
