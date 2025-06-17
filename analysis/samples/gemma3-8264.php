    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <table>
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart_items as $item): ?>
