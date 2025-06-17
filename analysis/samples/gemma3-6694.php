

<?php
// product_list.php
session_start();

echo "<h2>Available Products</h2>";
echo "<form method='post' action='cart.php'>";
echo "<ul>";
// Replace these with your product details
echo "<li><input type='hidden' name='product_id' value='1'> <img src='laptop.jpg' width='100'><br> Laptop - $" . number_format(1200, 2) . "<br><input type='submit' value='Add to Cart'></li>";
echo "<li><input type='hidden' name='product_id' value='2'> <img src='mouse.jpg' width='100'><br> Mouse - $" . number_format(25, 2) . "<br><input type='submit' value='Add to Cart'></li>";

echo "</ul>";
echo "<input type='hidden' name='action' value='add'>";
echo "</form>";
?>
