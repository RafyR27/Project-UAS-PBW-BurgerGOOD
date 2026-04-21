<?php
  session_start();
  require "config/db.php";

  $error = false;
  $err_message = "";

  if(isset($_POST['submit'])){
    $table_number = $_POST['table_number'];
    $outlet_code = $_POST['outlet_code'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM outlet WHERE outlet_code = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $outlet_code);
    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($query) == 1){
      $row = mysqli_fetch_assoc($query);

      if ($table_number >= 1 && $table_number <= $row['total_tables']) {
        $_SESSION['table_number'] = $table_number;
        $_SESSION['outlet_code'] = $outlet_code;

        $error = false;

        header("Location: " . $BASE_URL . "menu.php");
        exit;
      }

      $error = true;
      $err_message = "Invalid table number.";
    } else {
      $error = true;
      $err_message = "The outlet code is not valid.";
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Access Menu | BurgerGOOD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/bootstrap.css" />
    <link rel="stylesheet" href="assets/styles.css" />
    <link rel="stylesheet" href="assets/bootstrap-icons/bootstrap-icons.css" />
    <link rel="icon" href="public/logo.png" />
  </head>
  <body
    class="bg-cream d-flex justify-content-center align-items-center min-vh-100"
  >
    <!-- alert -->
    <div class="position-fixed z-3 alert-main end-0 top-0">
      <div class="p-2">
        <?php if($error): ?>
          <div
            id="alert-payment"
            class="alert alert-danger d-flex justify-content-between align-items-center"
            role="alert"
          > 
            <div class="d-flex align-items-center gap-3">
              <i class="bi bi-exclamation-octagon"></i>
              <p class="my-0"><?= $err_message; ?></p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="container" style="max-width: 500px">
      <div class="text-center mb-4">
        <img src="public/logo.png" alt="logo" style="width: 90px" />
        <h2 class="fredoka-font-medium mt-3">Welcome to BurgerGood</h2>
        <p class="text-muted fredoka-font">Please verify your table before ordering</p>
      </div>

      <div class="w-100 p-4 bg-white shadow rounded-4 fredoka-font">
        <form action="" method="POST">
          <p class="mb-4">
            Enter your table number and the unique outlet code located on your
            table to continue your order.
          </p>

          <div class="mb-3">
            <label class="form-label">Table Number</label>
            <input
              id="tableNumber"
              type="number"
              name="table_number"
              class="form-control rounded-3"
              placeholder="Enter your table number"
            />
            <div class="invalid-feedback">Please fill table number!</div>
          </div>

          <div class="mb-4">
            <label class="form-label">Outlet Code</label>
            <input
              id="outletCode"
              type="text"
              name="outlet_code"
              class="form-control rounded-3"
              placeholder="Enter your outlet code"
            />
            <div class="invalid-feedback">Please fill outlet code!</div>
          </div>

          <button type="submit" name="submit" class="btn btn-dark w-100 py-3 rounded-3">
            Continue to Menu
          </button>
        </form>
      </div>
    </div>

    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
