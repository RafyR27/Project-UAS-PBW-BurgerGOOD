<?php
    require 'config/db.php';

    $order_id = $_GET['order_id'];

    if(!$order_id){
        header("location: " . $BASE_URL . 'menu.php');
        exit;
    }

    $sql = "SELECT * FROM checkout WHERE order_id = '$order_id'";

    $query = mysqli_query(
        $conn,
        $sql
    );

    $order = mysqli_fetch_assoc($query);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Success | BurgerGOOD</title>
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
  <body class="bg-cream">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!-- Success Card -->
                <div class="bg-white shadow rounded-4 p-4 text-center">
                
                    <!-- Icon -->
                    <div class="mb-4">
                        <div class="mx-auto bg-success-subtle rounded-circle d-flex justify-content-center align-items-center"
                            style="width:90px;height:90px;">
                            <i class="bi bi-check-lg text-success" style="font-size: 3rem;"></i>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="fredoka-font-bold mb-2">Order Successful</h2>
                    <p class="text-muted mb-4">
                        Your order has been received and is being prepared by our kitchen.
                    </p>

                    <!-- Order Info -->
                    <div class="bg-light rounded-4 p-3 mb-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order ID</span>
                        <strong><?= $order['order_id']; ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Table Number</span>
                        <strong><?= $order['table_number']; ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Payment Method</span>
                        <strong><?= $order['payment_method']; ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Payment</span>
                        <strong>Rp. <?= number_format($order['total_price']); ?></strong>
                        </div>
                    </div>

                    <!-- Estimated Time -->
                    <?php if($order["status"] == "finished"): ?>
                        <div class="bg-success bg-opacity-10 rounded-4 p-3 mb-4">
                            <h4 class="fredoka-font-bold mb-0">Finished</h4>
                        </div>
                    <?php else: ?>
                        <div class="bg-warning bg-opacity-10 rounded-4 p-3 mb-4">
                            <p class="mb-1 text-muted">Estimated Ready</p>
                            <h4 class="fredoka-font-bold mb-0">10 - 15 Minutes</h4>
                        </div>
                    <?php endif; ?>

                    <!-- Buttons -->
                    <div class="d-grid gap-3">
                        <a href="menu.php" class="btn btn-dark rounded-3 py-3">
                        Order Again
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
