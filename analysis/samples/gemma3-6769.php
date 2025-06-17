        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" required>
        <br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required>
        <br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required>
        <br><br>

        <button type="submit">Place Order</button>
    </form>

    <br>

    <h2>Order History (Example - Replace with your actual database query)</h2>
    <?php
    //This is just an example, replace with your query.
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM orders";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h3>Order History:</h3>";
        while($row = $result->fetch_assoc()) {
            echo "<div>Product ID: " . $row["product_id"] . ", Quantity: " . $row["quantity"] . ", Customer Name: " . $row["customer_name"] . ", Customer Email: " . $row["customer_email"] . "</div>";
        }
    } else {
        echo "No orders found.";
    }
    $conn->close();
    ?>
