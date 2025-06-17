        <table>
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
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    echo "<tr>";
                    echo "<td>" . $item['name'] . "</td>"; // Assume 'name' is stored with the item
                    echo "<td>" . $item['quantity'] . "</td>";
                    echo "<td>$" . $item['price'] . "</td>";
                    echo "<td>$" . ($item['quantity'] * $item['price']) . "</td>";
                    echo "<td><a href='add_to_cart.php?product_id=" . $product_id . "&quantity=1'>Add</a> | <a href='add_to_cart.php?product_id=" . $product_id . "&quantity=1'>Update</a> | <a href='add_to_cart.php?product_id=" . $product_id . "&quantity=1'>Remove</a></td>";
                    echo "</tr>";
                }
                ?>
