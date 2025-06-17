

<?php

// ... (storeUserReview function from above)

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_user';
$password = 'your_db_password';
$database = 'your_db_name';

try {
  $mysqli = new mysqli($host, $username, $password, $database);

  if ($mysqli->connect_error) {
    throw new Exception("Connection failed: " . $mysqli->connect_error);
  }

  // Store the review
  $reviewData = storeUserReview("Awesome Widget", "This widget is fantastic!", "user123");

  if (is_array($reviewData)) {
    $sql = "INSERT INTO reviews (product_name, review_text, user_id, date)
            VALUES ('" . $mysqli->real_escape_string($reviewData['product_name']) . "',
                    '" . $mysqli->real_escape_string($reviewData['review_text']) . "',
                    '" . $mysqli->real_escape_string($reviewData['user_id']) . "',
                    '" . $mysqli->real_escape_string($reviewData['date']) . "')";

    if ($mysqli->query($sql) === TRUE) {
      echo "Review saved successfully!";
    } else {
      echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
  } else {
    echo "Error saving review: " . $reviewData;
  }

  $mysqli->close();

} catch (Exception $e) {
  echo "An error occurred: " . $e->getMessage();
}
?>
