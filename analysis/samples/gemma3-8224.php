    <table class="cart-items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($cart_contents as $product_id => $quantity) {
                $product_price = getProductPrice($product_id);
                $total_for_product = $product_price * $quantity;
                ?>
