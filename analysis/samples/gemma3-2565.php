

<?php

// ... (process_review function - as above) ...

try {
  $pdo = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For error handling

  $review = [
    'username' => $username,
    'rating' => $formatted_rating,
    'comment' => $formatted_comment,
    'timestamp' => time(),
    'review_id' => $review_id
  ];

  $sql = "INSERT INTO reviews (username, rating, comment, timestamp, review_id) VALUES (:username, :rating, :comment, :timestamp, :review_id)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($review);

  return true;

} catch (PDOException $e) {
  // Handle database error (e.g., log the error, display an error message)
  echo "Database error: " . $e->getMessage() . "
";
  return false;
}

?>
