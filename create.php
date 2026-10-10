<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/Database.php';
$pdo = Database::getInstance()->getConnection();

// Data untuk dropdown (Requirement #5)
$kategori = $pdo->query('SELECT * FROM kategori ORDER BY nama_kategori')->fetchAll();
$supplier = $pdo->query('SELECT * FROM supplier ORDER BY nama_supplier')->fetchAll();

$data   = ['nama_produk' => '', 'harga' => '', 'stok' => '', 'id_kategori' => '', 'id_supplier' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$data, $errors] = validate_produk($_POST);
    if (!$errors) {
        try {
            $stmt = $pdo->prepare('INSERT INTO produk (nama_produk, harga, stok, id_kategori, id_supplier)
                                   VALUES (:nama, :harga, :stok, :kat, :sup)');
            $stmt->execute([
                ':nama'  => $data['nama_produk'],
                ':harga' => $data['harga'],
                ':stok'  => $data['stok'],
                ':kat'   => $data['id_kategori'],
                ':sup'   => $data['id_supplier'],
            ]);
            set_flash('success', 'Produk berhasil ditambahkan.');
        } catch (PDOException $ex) {
            set_flash('error', 'Gagal menambahkan produk.');
        }
        redirect('index.php'); // pola redirect (PRG)
    }
}

$action = 'create.php';
$btn    = 'Simpan';
require_once __DIR__ . '/includes/header.php';
echo '<h2>Tambah Produk</h2>';
require __DIR__ . '/includes/form.php';
require_once __DIR__ . '/includes/footer.php';
