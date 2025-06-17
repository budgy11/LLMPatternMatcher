    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required><br><br>

    <input type="submit" value="Place Order">
</form>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>Error: " . $error . "</p>";
}
?>
