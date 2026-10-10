<?php
require_once __DIR__ . '/includes/header.php';
$pdo = Database::getInstance()->getConnection();

// Bonus: pencarian + pagination
$q       = trim($_GET['q'] ?? '');
$perPage = 5;
$page    = max(1, (int)($_GET['page'] ?? 1));
$like    = '%' . $q . '%';

$stmt = $pdo->prepare('SELECT COUNT(*) FROM produk WHERE nama_produk LIKE :q');
$stmt->execute([':q' => $like]);
$total      = (int)$stmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

// JOIN 2 tabel: produk + kategori + supplier (Requirement #4)
$sql = 'SELECT p.id_produk, p.nama_produk, p.harga, p.stok,
               k.nama_kategori, s.nama_supplier
        FROM produk p
        JOIN kategori k ON p.id_kategori = k.id_kategori
        JOIN supplier s ON p.id_supplier = s.id_supplier
        WHERE p.nama_produk LIKE :q
        ORDER BY p.id_produk DESC
        LIMIT :limit OFFSET :offset';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':q', $like);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$produk = $stmt->fetchAll();
?>
<div class="toolbar">
    <a href="create.php" class="btn btn-primary">+ Tambah Produk</a>
    <form method="get" class="search">
        <input type="text" name="q" placeholder="Cari nama produk..." value="<?= e($q) ?>">
        <button class="btn btn-gray">Cari</button>
    </form>
</div>

<table>
    <thead>
        <tr><th>No</th><th>Nama Produk</th><th>Kategori</th><th>Supplier</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php if (!$produk): ?>
        <tr><td colspan="7" class="center">Data tidak ditemukan.</td></tr>
    <?php endif; ?>
    <?php foreach ($produk as $i => $p): ?>
        <tr>
            <td><?= $offset + $i + 1 ?></td>
            <td><?= e($p['nama_produk']) ?></td>
            <td><?= e($p['nama_kategori']) ?></td>
            <td><?= e($p['nama_supplier']) ?></td>
            <td>Rp <?= e(number_format($p['harga'], 0, ',', '.')) ?></td>
            <td><?= e($p['stok']) ?></td>
            <td class="aksi">
                <a href="edit.php?id=<?= e($p['id_produk']) ?>" class="btn btn-warn">Edit</a>
                <form method="post" action="delete.php"
                      onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                    <input type="hidden" name="id" value="<?= e($p['id_produk']) ?>">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&q=<?= urlencode($q) ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
