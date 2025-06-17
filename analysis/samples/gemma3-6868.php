    <label for="item_id">Item ID:</label>
    <select name="item_id" id="item_id">
        <?php
        foreach ($items as $id => $item) {
            echo "<option value=\"$id\">$id</option>";
        }
        ?>
