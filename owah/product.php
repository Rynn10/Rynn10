<?php
session_start();
include 'config.php';

// Ambil ID produk dari URL
$id = $_GET['id'];

// Query untuk mengambil detail produk
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// Tambahkan produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $quantity = $_POST['quantity'];

    // Cek apakah produk sudah ada di keranjang
    $sql = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update jumlah jika produk sudah ada
        $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = $user_id AND product_id = $id";
    } else {
        // Tambahkan produk baru ke keranjang
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $id, $quantity)";
    }

    if ($conn->query($sql) === TRUE) {
        header('Location: cart.php');
        exit;
    } else {
        $error = "Gagal menambahkan produk ke keranjang!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="images/<?php echo $product['image']; ?>" class="img-fluid" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="col-md-6">
                <h1><?php echo $product['name']; ?></h1>
                <p><?php echo $product['description']; ?></p>
                <p><strong>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></strong></p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="quantity">Jumlah</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
                    </form>
                <?php else: ?>
                    <p>Silakan <a href="login.php">login</a> untuk menambahkan produk ke keranjang.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>