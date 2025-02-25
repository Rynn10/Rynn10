<?php
session_start();
include 'config.php';

// Redirect ke halaman login jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data keranjang belanja
$sql = "SELECT cart.*, products.name, products.price 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);
$cart_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}

// Hitung total harga
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Proses form checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipient_name = $_POST['recipient_name'];
    $shipping_address = $_POST['shipping_address'];
    $payment_method = $_POST['payment_method']; // Ambil metode pembayaran

    // Simpan pesanan ke database
    $sql = "INSERT INTO orders (user_id, total, recipient_name, shipping_address, payment_method) 
            VALUES ($user_id, $total, '$recipient_name', '$shipping_address', '$payment_method')";
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;

        // Simpan item pesanan ke tabel order_items
        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                    VALUES ($order_id, $product_id, $quantity, $price)";
            $conn->query($sql);
        }

        // Kosongkan keranjang belanja
        $sql = "DELETE FROM cart WHERE user_id = $user_id";
        $conn->query($sql);

        // Redirect ke halaman sukses
        header('Location: order_success.php');
        exit;
    } else {
        $error = "Checkout gagal! Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2>Checkout</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Tampilkan detail produk di keranjang -->
        <div class="row">
            <div class="col-md-8">
                <h4>Detail Pesanan</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                <td>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h4>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></h4>
            </div>

            <!-- Form untuk mengisi nama penerima, alamat tujuan, dan metode pembayaran -->
            <div class="col-md-4">
                <h4>Informasi Pengiriman dan Pembayaran</h4>
                <form method="POST">
                    <div class="form-group">
                        <label for="recipient_name">Nama Penerima</label>
                        <input type="text" class="form-control" id="recipient_name" name="recipient_name" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_address">Alamat Tujuan</label>
                        <textarea class="form-control" id="shipping_address" name="shipping_address" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Metode Pembayaran</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="Cash on Delivery">Cash on Delivery (COD)</option>
                            <option value="Credit Card">Credit Card</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Konfirmasi Pembayaran</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>