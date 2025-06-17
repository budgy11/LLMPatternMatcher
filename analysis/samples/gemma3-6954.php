        <p>Total:  <?php echo $total = array_sum(array_map(function($item) {return $item['quantity'] * $item['price'];})) ; ?></p>

        <form method="post" action="">
            <input type="submit" name="purchase" value="Purchase">
        </form>
    <?php } else {
        echo "<p>Your cart is empty.</p>";
    } ?>
