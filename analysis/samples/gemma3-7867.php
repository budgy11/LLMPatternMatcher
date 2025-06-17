        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1"><br><br>

        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>

    <h2>Cart Contents</h2>
    <?php getCartContents($conn); ?>
