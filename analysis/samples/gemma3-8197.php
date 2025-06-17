
    <table id="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($cart_items as $item) {
                $product = $products[$item['id']];
                $item_total = $item['quantity'] * $product['price'];
                $total += $item_total;
                ?>
