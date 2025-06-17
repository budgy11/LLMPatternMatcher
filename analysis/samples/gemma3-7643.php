    <label for="cart">Select Items to Purchase (Product IDs):</label>
    <input type="text" id="cart" name="cart" value="<?php echo htmlspecialchars($_POST['cart'] ?? ''); ?>" placeholder="Product IDs (comma-separated)">
