

<?php

// Assuming you have a database connection established in $conn

function storeUserReview(string $reviewText, int $rating, string $productId) {
  // Validation (keep the same)

  $stmt = $conn->prepare("INSERT INTO reviews (review_text, rating, product_id, created_at) VALUES (?, ?, ?, NOW())");
  $stmt->bind_param("ssis", $reviewText, $productId, $rating);

  if ($stmt->execute()) {
    return true;
  } else {
    error_log("Error storing review: " . $stmt->error);
    return false;
  }
  $stmt->close();
}

// ... rest of the function code
?>
