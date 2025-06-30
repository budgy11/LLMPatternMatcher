```php
<?php
// products.php

require_once 'db_connect.php';

// SQL query
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

// Output data of each row
if ($result->num_rows > 0) {
    echo "<table>
          <thead>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td><img src='" . $row['image_url'] . "' width='100'></td>
                <td>" . $row['product_name'] . "</td>
                <td>" . $row['description'] . "</td>
                <td>$" . $row['price'] . "</td>
                <td><a href='product.php?id=" . $row['id'] . "'>View Details</a></td>
              </tr>";
    }

    echo "</tbody>
        </table>";

} else {
    echo "No products found.";
}

$conn->close();
?>
```