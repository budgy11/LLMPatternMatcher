    <label for="cart_id">Cart ID:</label>
    <input type="hidden" id="cart_id" name="cart_id" value="<?php
        if (isset($_SESSION['cart_id'])) {
            echo $_SESSION['cart_id'];
        } else {
            echo "Invalid Cart ID";
        }
    ?>">
