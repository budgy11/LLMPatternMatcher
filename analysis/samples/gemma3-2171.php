

<?php
// ... (database connection code as above) ...

// Prepare the statement
$stmt = $conn->prepare("INSERT INTO reviews (product_id, username, rating, comment) VALUES (?, ?, ?, ?)");

// Bind parameters
$stmt->bind_param("siiii", $productId, $username, $rating, $comment); // s = string, i = integer

// Execute the statement
if ($stmt->execute()) {
  return true;
} else {
  error_log("Error inserting review: " . $conn->error);
  return false;
}

// Close the statement
$stmt->close();

?>
