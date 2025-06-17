    <label for="firstName">First Name:</label>
    <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" value="<?php echo isset($firstName) ? htmlspecialchars($firstName) : ''; ?>">
