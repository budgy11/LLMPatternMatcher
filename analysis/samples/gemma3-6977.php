    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <input type="submit" value="Place Order">
</form>

<?php if (isset($error)) { ?>
