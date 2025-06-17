    <label for="user_id">User ID:</label>
    <input type="text" id="user_id" name="user_id" required><br><br>

    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit">Add to Cart</button>
</form>

<?php
// Display user's purchases (if any)
$userId = 1; // Replace with the actual user ID for display
$purchases = getPurchasesByUser($userId);

if ($purchases) {
    echo "<h2>Your Purchases:</h2>";
    echo "<table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Purchase Date</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = $purchases->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["product_id"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . $row["purchase_date"] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>
        </table>";
} else {
    echo "<p>No purchases found for this user.</p>";
}
?>
