

<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "your_user", "your_password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

    $result = createOrUpdateReview('product123', 'user456', 4, 'Great product!');

    if ($result['success']) {
        echo "Review created: " . json_encode($result['review']) . "
";
    } else {
        echo "Error creating review: " . $result['message'] . "
";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "
";
}
?>
