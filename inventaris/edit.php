<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/Database.php';
$pdo = Database::getInstance()->getConnection();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM produk WHERE id_produk = :id');
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();
if (!$row) {
    set_flash('error', 'Produk tidak ditemukan.');
    redirect('index.php');
}

$kategori = $pdo->query('SELECT * FROM kategori ORDER BY nama_kategori')->fetchAll();
$supplier = $pdo->query('SELECT * FROM supplier ORDER BY nama_supplier')->fetchAll();

// Form pre-filled: isi awal dari database (Requirement #6)
$data   = $row;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$data, $errors] = validate_produk($_POST);
    if (!$errors) {
        try {
            $stmt = $pdo->prepare('UPDATE produk SET nama_produk=:nama, harga=:harga, stok=:stok,
                                   id_kategori=:kat, id_supplier=:sup WHERE id_produk=:id');
            $stmt->execute([
                ':nama'  => $data['nama_produk'],
                ':harga' => $data['harga'],
                ':stok'  => $data['stok'],
                ':kat'   => $data['id_kategori'],
                ':sup'   => $data['id_supplier'],
                ':id'    => $id,
            ]);
            set_flash('success', 'Produk berhasil diperbarui.');
        } catch (PDOException $ex) {
            set_flash('error', 'Gagal memperbarui produk.');
        }
        redirect('index.php');
    }
}

$action = 'edit.php?id=' . $id;
$btn    = 'Update';
require_once __DIR__ . '/includes/header.php';
echo '<h2>Edit Produk</h2>';
require __DIR__ . '/includes/form.php';
require_once __DIR__ . '/includes/footer.php';
