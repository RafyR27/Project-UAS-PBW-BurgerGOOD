<?php
    session_start();
    require "../config/db.php";

    if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
        header("Location: " . $BASE_URL . "auth.php");
        exit;
    }

    $outlet_code = $_SESSION['outlet_code'];

    $query = mysqli_query($conn, "SELECT * FROM outlet WHERE outlet_code = '$outlet_code'");
    $outlet = mysqli_fetch_assoc($query);

    $error = false;
    $err_message = "";

    if (isset($_POST['updateOutlet'])) {
        $total_tables = htmlspecialchars($_POST['total_tables']);

        if (empty($total_tables) || $total_tables < 1) {
            $error = true;
            $err_message = "Please enter a valid number of seats (minimum 1).";
        } else {
            $update = mysqli_query($conn, "UPDATE outlet SET total_tables = '$total_tables' WHERE outlet_code = '$outlet_code'");

            if ($update) {
                header("Location:" . $BASE_URL . "admin/dashboard.php");
                exit;
            } else {
                $error = true;
                $err_message = "Failed to update outlet data.";
            }
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Outlet | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/styles.css" />
    <link rel="stylesheet" href="../assets/bootstrap-icons/bootstrap-icons.css" />
    <link rel="icon" href="../public/logo.png" />
</head>
<body class="bg-cream">
    <!-- Alert -->
    <div class="position-fixed z-3 alert-main end-0 top-0">
      <div class="p-2">
        <?php if($error): ?>
          <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert"> 
            <div class="d-flex align-items-center gap-3">
              <i class="bi bi-exclamation-octagon"></i>
              <p class="my-0"><?= $err_message; ?></p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="container py-5 px-4">
        <div class="mb-4 d-flex align-items-center gap-3">
            <a href="dashboard.php" class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fredoka-font-medium mb-0">Edit Outlet Settings</h2>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="bg-white shadow rounded-4 p-4">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label text-muted">Outlet Code</label>
                            <input type="text" class="form-control bg-light" value="<?= $outlet['outlet_code'] ?>" readonly>
                            <small class="text-muted">*Outlet code cannot be changed.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fredoka-font-medium">Total Seats / Tables</label>
                            <input
                                type="number"
                                name="total_tables"
                                class="form-control rounded-3 py-2"
                                value="<?= $outlet['total_tables'] ?>"
                                placeholder="Enter total tables"
                                min="1"
                            />
                            <div class="form-text">This will determine the maximum table number allowed for customers.</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" name="updateOutlet" class="btn btn-warning px-5 rounded-3 fredoka-font-medium">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6 d-none d-md-block">
                <div class="h-100 d-flex flex-column justify-content-center align-items-center text-center p-4">
                    <i class="bi bi-shop text-warning" style="font-size: 5rem;"></i>
                    <h4 class="fredoka-font-medium mt-3">Outlet Management</h4>
                    <p class="text-muted">Adjusting the total seats will automatically update the validation in the customer's access menu.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.js"></script>
</body>
</html>