    <div class="cart-table">
        <table>
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_contents as $item) {
                    $product_id = $item['product_id'];
                    $quantity = $item['quantity'];
                    $product_name = $item['product_name']; // Assuming you have a product_name field
                    $product_price = $item['product_price'];

                    echo '<tr class="cart-item">
                                <td class="cart-item"><img src="images/' . $product_id . '.jpg" alt="' . $product_name . '"></td>
                                <td class="cart-item">' . $product_name . '</td>
                                <td class="cart-item">' . $quantity . '</td>
                                <td class="cart-item">' . $product_price . '</td>
                            </tr>';
                } ?>
