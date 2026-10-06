<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/Database.php';

// Hanya terima POST (aman dari penghapusan lewat link biasa)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id  = (int)($_POST['id'] ?? 0);
$pdo = Database::getInstance()->getConnection();

try {
    $stmt = $pdo->prepare('DELETE FROM produk WHERE id_produk = :id');
    $stmt->execute([':id' => $id]);
    if ($stmt->rowCount() > 0) {
        set_flash('success', 'Produk berhasil dihapus.');
    } else {
        set_flash('error', 'Produk tidak ditemukan.');
    }
} catch (PDOException $ex) {
    set_flash('error', 'Gagal menghapus produk.');
}
redirect('index.php');
