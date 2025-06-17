        </form>
    </div>

    <div class="product">
        <h2>Jeans</h2>
        <p>Price: $50.00</p>
        <form method="post">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1">
            <button type="submit" name="update_quantity" value="<?php echo $product_id = 2; ?>">Add to Cart</button>
