<?php
session_start();
require "../config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['table_number']) || !isset($_SESSION['outlet_code'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid session']);
    exit;
}

$table_number = $_SESSION['table_number'];
$outlet_code  = $_SESSION['outlet_code'];

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'No data received']);
        exit;
    }

    $order_id       = $data['order_id'];
    $payment_method = $data['payment_method'];
    $cart           = $data['cart'];

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['unitPrice'] * $item['quantity'];
    }

    mysqli_begin_transaction($conn);

    foreach ($cart as $item) {
        if($item['type'] == "small"){
            $stmt_stock = mysqli_prepare($conn, "
                SELECT stock_small 
                FROM product 
                WHERE product_name = ?
                FOR UPDATE
            ");

            mysqli_stmt_bind_param(
                $stmt_stock,
                "s",
                $item['name'],
            );

            mysqli_stmt_execute($stmt_stock);
            $result_stock = mysqli_stmt_get_result($stmt_stock);
            $product = mysqli_fetch_assoc($result_stock);

            if (!$product) {
                mysqli_rollback($conn);
                echo json_encode([
                    'success' => false,
                    'message' => "Produk {$item['name']} tidak ditemukan"
                ]);
                exit;
            }

            if ($product['stock_small'] < $item['quantity']) {
                mysqli_rollback($conn);
                echo json_encode([
                    'success' => false,
                    'message' => "Stok {$item['name']} ukuran {$item['type']} tidak mencukupi"
                ]);
                exit;
            }
        } else if($item['type'] == "large"){
            $stmt_stock = mysqli_prepare($conn, "
                SELECT stock_large 
                FROM product 
                WHERE product_name = ?
                FOR UPDATE
            ");

            mysqli_stmt_bind_param(
                $stmt_stock,
                "s",
                $item['name'],
            );

            mysqli_stmt_execute($stmt_stock);
            $result_stock = mysqli_stmt_get_result($stmt_stock);
            $product = mysqli_fetch_assoc($result_stock);

            if (!$product) {
                mysqli_rollback($conn);
                echo json_encode([
                    'success' => false,
                    'message' => "Produk {$item['name']} tidak ditemukan"
                ]);
                exit;
            }

            if ($product['stock_large'] < $item['quantity']) {
                mysqli_rollback($conn);
                echo json_encode([
                    'success' => false,
                    'message' => "Stok {$item['name']} ukuran {$item['type']} tidak mencukupi"
                ]);
                exit;
            }
        }
    }

    $stmt = mysqli_prepare($conn, "
        INSERT INTO checkout 
        (order_id, outlet_code, table_number, payment_method, total_price, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'pending', NOW())
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "ssisi",
        $order_id,
        $outlet_code,
        $table_number,
        $payment_method,
        $total
    );

    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) === 0) {
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => 'Failed to create order']);
        exit;
    }


    foreach ($cart as $item) {
        $stmt_item = mysqli_prepare($conn, "
            INSERT INTO order_items (order_id, product_name, size, quantity)
            VALUES (?, ?, ?, ?)
        ");

        mysqli_stmt_bind_param(
            $stmt_item,
            "sssi",
            $order_id,
            $item['name'],
            $item['type'],
            $item['quantity']
        );

        mysqli_stmt_execute($stmt_item);

        if($item['type'] == "small"){
            $stmt_update_stock = mysqli_prepare($conn, "
                UPDATE product
                SET stock_small = stock_small - ?
                WHERE product_name = ?
            ");

            mysqli_stmt_bind_param(
                $stmt_update_stock,
                "is",
                $item['quantity'],
                $item['name'],
            );

            mysqli_stmt_execute($stmt_update_stock);
        } else if($item['type'] == "large"){
            $stmt_update_stock = mysqli_prepare($conn, "
                UPDATE product
                SET stock_large = stock_large - ?
                WHERE product_name = ?
            ");

            mysqli_stmt_bind_param(
                $stmt_update_stock,
                "is",
                $item['quantity'],
                $item['name'],
            );

            mysqli_stmt_execute($stmt_update_stock);
        }
    }

    mysqli_commit($conn);

    echo json_encode([
        'success'  => true,
        'message'  => 'Order created successfully',
        'order_id' => $order_id
    ]);

} catch (Exception $e) {
    mysqli_rollback($conn);

    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>