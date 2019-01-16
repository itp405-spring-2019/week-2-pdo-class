<?php
  $pdo = new PDO('sqlite:chinook.db');
  $sql = "
    SELECT
      invoices.InvoiceDate,
      invoices.Total,
      customers.FirstName,
      customers.LastName,
      customers.Email
    FROM invoices
    INNER JOIN customers
    ON invoices.CustomerId = customers.CustomerId
  ";

  if (isset($_GET['search'])) {
    $sql = $sql . ' WHERE customers.Email = ?';
  }

  $statement = $pdo->prepare($sql);

  if (isset($_GET['search'])) {
    $statement->bindParam(1, $_GET['search']);
  }

  $statement->execute();
  $invoices = $statement->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Week 2</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
  <form action="index.php" method="get">
    <input
      type="text"
      name="search"
      value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>">
    <button type="submit">Search</button>
    <a href="/" class="btn btn-default">Clear</a>
  </form>
  <table class="table">
    <tr>
      <th>Date</th>
      <th>Total</th>
      <th>Customer</th>
      <th>Email</th>
    </tr>
    <?php foreach($invoices as $invoice) : ?>
      <tr>
        <td>
          <?php echo $invoice->InvoiceDate ?>
        </td>
        <td>
          <?php echo $invoice->Total ?>
        </td>
        <td>
          <?php echo $invoice->FirstName . ' ' . $invoice->LastName ?>
        </td>
        <td>
          <?php echo $invoice->Email ?>
        </td>
      </tr>
    <?php endforeach ?>
    <?php if(count($invoices) === 0) : ?>
      <tr>
        <td colspan="4">No invoices found</td>
      </tr>
    <?php endif ?>
  </table>
</body>
</html>
