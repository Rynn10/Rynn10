<?php
session_start();
// File: about.php

// Include file konfigurasi atau fungsi yang diperlukan
include 'config.php';

// Judul halaman
$page_title = "Tentang Kami - Nama E-Commerce Anda";

// Include header
include 'navbar.php';
?>

<!-- Konten Halaman About -->
<div class="container">
    <h1>Tentang Kami</h1>
    <p>Selamat datang di <strong>Owah Store</strong>, tempat terbaik untuk menemukan produk-produk berkualitas dengan harga terjangkau.</p>
    
    <h2>Visi Kami</h2>
    <p>Visi kami adalah memberikan pengalaman belanja online yang mudah, aman, dan menyenangkan bagi semua pelanggan kami.</p>
    
    <h2>Misi Kami</h2>
    <p>Misi kami adalah menyediakan produk-produk terbaik dari berbagai kategori, dengan layanan pelanggan yang ramah dan profesional.</p>
    
    <h2>Tim Kami</h2>
    <p>Kami adalah tim yang berdedikasi dan berpengalaman dalam bidang e-commerce. Kami selalu berusaha untuk memberikan yang terbaik bagi pelanggan kami.</p>
    
    <h2>Kontak Kami</h2>
    <p>Jika Anda memiliki pertanyaan atau masukan, jangan ragu untuk menghubungi kami melalui:</p>
    <ul>
        <li>Email: owah@gmail.com</li>
        <li>Telepon: +62 123 4567 890</li>
        <li>Alamat: Jl. No. 123, Kota Tangerang, Indonesia</li>
    </ul>
</div>

<?php
// Include footer
include 'footer.php';
?>