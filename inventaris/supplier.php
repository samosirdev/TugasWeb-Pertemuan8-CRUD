<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/Database.php';
$pdo = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi    = $_POST['aksi'] ?? '';
    $id      = (int)($_POST['id'] ?? 0);
    $nama    = trim($_POST['nama_supplier'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');

    try {
        if ($aksi === 'tambah' || $aksi === 'update') {
            if ($nama === '' || $telepon === '') {
                set_flash('error', 'Nama supplier dan telepon wajib diisi.');
            } elseif ($aksi === 'tambah') {
                $pdo->prepare('INSERT INTO supplier (nama_supplier, telepon) VALUES (:n, :t)')
                    ->execute([':n' => $nama, ':t' => $telepon]);
                set_flash('success', 'Supplier berhasil ditambahkan.');
            } else {
                $pdo->prepare('UPDATE supplier SET nama_supplier = :n, telepon = :t WHERE id_supplier = :id')
                    ->execute([':n' => $nama, ':t' => $telepon, ':id' => $id]);
                set_flash('success', 'Supplier berhasil diperbarui.');
            }
        } elseif ($aksi === 'hapus') {
            $pdo->prepare('DELETE FROM supplier WHERE id_supplier = :id')->execute([':id' => $id]);
            set_flash('success', 'Supplier berhasil dihapus.');
        }
    } catch (PDOException $ex) {
        if ($ex->getCode() === '23000') {
            set_flash('error', 'Supplier tidak bisa dihapus karena masih dipakai oleh produk.');
        } else {
            set_flash('error', 'Terjadi kesalahan pada database.');
        }
    }
    redirect('supplier.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM supplier WHERE id_supplier = :id');
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $edit = $stmt->fetch() ?: null;
}

$list = $pdo->query('SELECT * FROM supplier ORDER BY id_supplier DESC')->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
<h2><?= $edit ? 'Edit Supplier' : 'Tambah Supplier' ?></h2>
<form method="post" class="inline-form">
    <input type="hidden" name="aksi" value="<?= $edit ? 'update' : 'tambah' ?>">
    <input type="hidden" name="id" value="<?= e($edit['id_supplier'] ?? '') ?>">
    <div>
        <label>Nama Supplier</label>
        <input type="text" name="nama_supplier" value="<?= e($edit['nama_supplier'] ?? '') ?>" required>
    </div>
    <div>
        <label>Telepon</label>
        <input type="text" name="telepon" value="<?= e($edit['telepon'] ?? '') ?>" required>
    </div>
    <button class="btn btn-primary"><?= $edit ? 'Update' : 'Simpan' ?></button>
    <?php if ($edit): ?><a href="supplier.php" class="btn btn-gray">Batal</a><?php endif; ?>
</form>

<table>
    <thead><tr><th>No</th><th>Nama Supplier</th><th>Telepon</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (!$list): ?><tr><td colspan="4" class="center">Belum ada data.</td></tr><?php endif; ?>
    <?php foreach ($list as $i => $s): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($s['nama_supplier']) ?></td>
            <td><?= e($s['telepon']) ?></td>
            <td class="aksi">
                <a href="supplier.php?edit=<?= e($s['id_supplier']) ?>" class="btn btn-warn">Edit</a>
                <form method="post" onsubmit="return confirm('Yakin ingin menghapus supplier ini?');">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= e($s['id_supplier']) ?>">
                    <button class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
