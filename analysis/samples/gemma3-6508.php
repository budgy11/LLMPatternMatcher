        <table border="1">
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
                foreach ($cartContents as $productId => $item) {
                    $productName = 'Product ' . $productId; //Replace with actual product name retrieval
                    $price = $products[$productId]['price'];
                    $quantity = $item;
                    $totalItem = $price * $quantity;
                    $total += $totalItem;

                    echo '<tr>';
                    echo '<td>' . $productName . '</td>';
                    echo '<td>$' . $price . '</td>';
                    echo '<td>' . $quantity . '</td>';
                    echo '<td>$' . $totalItem . '</td>';
                    echo '<td><a href="?remove_from_cart=' . $productId . '">Remove</a></td>';
                    echo '</tr>';
                }
                ?>
