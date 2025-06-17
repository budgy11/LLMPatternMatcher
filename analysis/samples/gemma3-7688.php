    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($cart as $item_id => $item) {
                $quantity = $item[$quantity_key];
                $item_name = $item[$item_name_key];
                $price = $item[$price_key];
                $total_item_price = $quantity * $price;
                echo "<tr>";
                echo "<td>" . $item_name . "</td>";
                echo "<td>" . $quantity . "</td>";
                echo "<td>$" . number_format($price, 2) . "</td>";
                echo "<td>$" . number_format($total_item_price, 2) . "</td>";
                echo "<td><button onclick=\"removeItemFromCart('{$item_id}')\">Remove</button></td>";
                echo "</tr>";
            }
            ?>
