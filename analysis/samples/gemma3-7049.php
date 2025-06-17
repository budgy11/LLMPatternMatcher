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
                    foreach ($cart as $item) {
                        $product_name = $item['name'];
                        $product_price = $item['price'];
                        $quantity = $item['quantity'];
                        $item_total = $product_price * $quantity;
                        $total += $item_total;

                        echo "<tr class='cart-item'>
                                <td>" . $product_name . "</td>
                                <td>$" . $product_price . "</td>
                                <td>" . $quantity . "</td>
                                <td>$" . $item_total . "</td>
                                <td>
                                    <form method='post'>
                                        <input type='hidden' name='product_id' value='" . $item['id'] . "'>
                                        <button type='submit'>Remove</button>
                                    </form>
                                </td>
                            </tr>";
                    } ?>
