    <table class="cart-items">
        <thead>
            <tr>
                <th>Product Name</th>
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
                $total += $item['price'] * $item['quantity'];
                echo '<tr class="cart-item">';
                echo '<td>' . $item['product_name'] . '</td>';
                echo '<td>' . $item['price'] . '</td>';
                echo '<td>' . $item['quantity'] . '</td>';
                echo '<td>' . $item['price'] * $item['quantity'] . '</td>';
                echo '<td><button onclick="removeFromCart(' . $item['product_id'] . ')">Remove</button></td>';
                echo '</tr>';
            }
            ?>
