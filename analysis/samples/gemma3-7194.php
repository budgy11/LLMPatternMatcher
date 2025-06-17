    <ul>
        <?php
        foreach ($order as $item_id => $item_data) {
            echo "<li>" . $item_data['name'] . " - Quantity: " . $item_data['quantity'] . ", Price: $" . $item_data['price'] . ", Total: $" . $item_data['total'] . "</li>";
        }
        ?>
