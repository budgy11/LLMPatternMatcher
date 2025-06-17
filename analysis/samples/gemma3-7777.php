    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1" required>
    <br><br>

    <button type="submit">Purchase</button>
</form>

</body>
</html>


<?php
// Sample product data
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00, 'description' => 'A comfortable t-shirt.', 'image' => 'tshirt.jpg'],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00, 'description' => 'Classic denim jeans.', 'image' => 'jeans.jpg'],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00, 'description' => 'Stylish hat.', 'image' => 'hat.jpg'],
];

// Function to get product details by ID
function getProductDetails($productId) {
    global $products;  // Access the global $products array

    if (isset($products[$productId])) {
        return $products[$productId];
    } else {
        return null;
    }
}
?>
