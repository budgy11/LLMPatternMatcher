
</body>
</html>


<?php
// ... (Database connection, etc.)

$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Use prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE name LIKE ?");

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $search_query); // 's' indicates a string parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $results = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
}
?>
