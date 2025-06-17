    <label for="remove_item">Remove Item:</label>
    <input type="text" id="remove_item" name="remove_item"><br><br>
    <button type="submit" name="remove_item">Remove Item</button>
</form>


<?php
// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["add_to_cart"])) {
    add_to_cart($_POST["item_name"], $_POST["item_price"], $_POST["item_quantity"]);
  } elseif (isset($_POST["purchase"])) {
    purchase();
  } elseif (isset($_POST["remove_item"])) {
    remove_from_cart($_POST["remove_item"]);
  }
}
?>
