
    <h3>Items:</h3>
    <?php
    foreach ($order['items'] as $item) {
        echo "<ul><li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li></ul>";
    }
    ?>
