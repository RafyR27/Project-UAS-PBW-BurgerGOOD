<?php
    session_start();
    require "../config/db.php";

    if( !isset($_SESSION["id"]) ){
        header("Location: " . $BASE_URL . 'auth.php');
        exit;
    }

    if ($_SESSION['role'] === "admin") {
        header("Location: " . $BASE_URL . 'admin/dashboard.php');
        exit;
    }

    $outlet_code = $_SESSION['outlet_code'];

    if (isset($_POST['complete_order'])) {
        $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
        
        $update_query = "UPDATE checkout SET status = 'finished' WHERE order_id = '$order_id' AND outlet_code = '$outlet_code'";
        
        if (mysqli_query($conn, $update_query)) {
            header("Location: " . $BASE_URL . "kasir/dashboard.php?status=completed");
            exit;
        }
    }

    $query_orders = mysqli_query($conn, "SELECT * FROM checkout WHERE outlet_code = '$outlet_code' AND status = 'pending' ORDER BY created_at ASC");
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Kasir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/styles.css" />
    <link
      rel="stylesheet"
      href="../assets/bootstrap-icons/bootstrap-icons.css"
    />
    <link rel="icon" href="../public/logo.png" />
  </head>
  <body class="bg-cream">
    <div class="w-100 p-4">
      <button
        class="btn btn-dark rounded-5"
        type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasExample"
        aria-controls="offcanvasExample"
      >
        <i class="bi bi-list fs-5"></i>
      </button>
    </div>

    <div
      class="offcanvas offcanvas-start fredoka-font"
      tabindex="-1"
      id="offcanvasExample"
      aria-labelledby="offcanvasExampleLabel"
    >
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Kasir</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
          aria-label="Close"
        ></button>
      </div>
      <div
        class="offcanvas-body px-4 d-flex flex-column justify-content-between"
      >
        <div class="d-flex flex-column gap-2 justify-content-start">
          <a
            href="dashboard.php"
            class="w-100 link-underline link-underline-opacity-0"
          >
            <button
              class="w-100 btn btn-warning text-start px-4 py-3 d-flex align-items-center gap-3"
            >
              <i class="bi bi-archive-fill"></i>
              Dashboard
            </button>
          </a>
          <a
            href="finish.php"
            class="w-100 link-underline link-underline-opacity-0"
          >
            <button
              class="w-100 btn btn-light text-start px-4 py-3 d-flex align-items-center gap-3"
            >
              <i class="bi bi-calendar2-check"></i>
              Finish
            </button>
          </a>
        </div>
        <a href="../controller/logout.php" class="link-underline link-underline-opacity-0">
          <button
            class="w-100 btn btn-outline-danger text-start px-4 py-3 d-flex align-items-center gap-3"
          >
            <i class="bi bi-box-arrow-left"></i>
            Logout
          </button>
        </a>
      </div>
    </div>

    <div class="container py-4 pt-0">
      <!-- Outlet code -->
      <div class="d-flex justify-content-end mb-4">
        <div class="bg-dark text-white px-4 py-2 rounded-4 fredoka-font-medium">
          Outlet: <?= $outlet_code; ?>
        </div>
      </div>

      <!-- Page title -->
      <div class="mb-4">
        <h2 class="fredoka-font-medium">Incoming Orders</h2>
      </div>

      <!-- Order list -->
      <div class="row g-4 d-flex">
        <?php if(mysqli_num_rows($query_orders) > 0): ?>
          <?php while($order = mysqli_fetch_assoc($query_orders)): ?>
            <div class="col-lg-4 col-md-6">
              <div class="bg-white rounded-4 shadow-sm p-4 h-100 d-flex flex-column">
                
                <!-- table number & ID -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <h4 class="fredoka-font-medium mb-0">Table <?= str_pad($order['table_number'], 2, '0', STR_PAD_LEFT); ?></h4>
                  <span class="badge text-bg-warning px-3 py-2 text-capitalize"><?= $order['status']; ?></span>
                </div>
                <small class="text-muted mb-3 d-block">Order ID: <?= $order['order_id']; ?></small>

                <!-- ordered items -->
                <div class="order-list-wrapper flex-grow-1">
                  <?php 
                    $order_id = $order['order_id'];
                    $query_items = mysqli_query($conn, "
                      SELECT oi.*, p.price 
                      FROM order_items oi 
                      LEFT JOIN product p ON oi.product_name = p.product_name COLLATE utf8mb4_general_ci
                      WHERE oi.order_id = '$order_id'
                    ");

                    while($item = mysqli_fetch_assoc($query_items)):
                      $unit_price = $item['price'];
                      if ($item['size'] === 'large') {
                        $unit_price += 10000;
                      }
                      $subtotal_item = $unit_price * $item['quantity'];
                  ?>

                  <div class="mb-3 pb-2 border-bottom border-light">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <p class="mb-0 fredoka-font-medium"><?= $item['product_name']; ?></p>
                        <small class="text-muted text-capitalize"><?= $item['size']; ?> (Rp <?= number_format($unit_price, 0, ',', '.'); ?>)</small>
                      </div>
                      <div class="text-end d-flex flex-column align-items-end">
                        <span class="fredoka-font-medium">x<?= $item['quantity']; ?></span>
                        <small class="fredoka-font-medium">Rp <?= number_format($subtotal_item, 0, ',', '.'); ?></small>
                      </div>
                    </div>
                  </div>
                  <?php endwhile; ?>
                </div>

                <div class="mt-auto pt-3">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <p class="fredoka-font-medium mb-0 text-muted" style="font-size: 0.85rem;">Payment Method:</p>
                    <span class="badge bg-light text-dark border fredoka-font" style="font-size: 0.75rem;">
                      <i class="bi bi-wallet2 me-1"></i> <?= $order['payment_method']; ?>
                    </span>
                  </div>
                  <div class="d-flex justify-content-between mt-auto mb-3">
                    <p class="fredoka-font-medium mb-0">Total Paid:</p>
                    <p class="fredoka-font-bold mb-0 text-success">Rp <?= number_format($order['total_price'], 0, ',', '.'); ?></p>
                  </div>
                </div>

                <!-- finish button (Form) -->
                <form method="POST" action="">
                  <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                  <button type="submit" name="complete_order" class="btn btn-success w-100 rounded-3 py-2 fw-semibold">
                    <i class="bi bi-check-circle me-2"></i>
                    Complete Order
                  </button>
                </form>

              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="col-12 text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <h4 class="mt-3 fredoka-font text-muted">No pending orders</h4>
            <p class="text-muted">New incoming orders will appear here.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/script.js"></script>
  </body>
</html>
