

<form method="post">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="Your email" value="<?php echo isset($email) ? esc_html($email) : ''; ?>">
