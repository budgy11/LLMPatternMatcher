        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($results as $row) {
          echo "<tr>";
          foreach ($columns as $column) {
            echo "<td>" . htmlspecialchars($row[$column]) . "</td>";
          }
          echo "</tr>";
        }
        ?>
