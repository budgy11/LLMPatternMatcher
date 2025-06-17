

<hr>

<h2>Add Items to Cart</h2>
<?php
foreach ($items as $item_id => $item) {
    echo "<p><a href='?action=add_to_cart&item_id=" . $item_id . "&quantity=1'>Add " . $item['name'] . " to Cart</a></p>";
}
?>
