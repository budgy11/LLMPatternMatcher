

<?php
// ... (view logic)
if ($hashed_password && isset($hashed_password)) {
  // Verify the temporary password
  if (password_verify('temp_password_' . md5($email), $hashed_password)) {
    // ...
  }
}
?>
