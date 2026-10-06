<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/Database.php';
$pdo = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id   = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama_kategori'] ?? '');

    try {
        if ($aksi === 'tambah' || $aksi === 'update') {
            if ($nama === '') {
                set_flash('error', 'Nama kategori wajib diisi.');
            } elseif ($aksi === 'tambah') {
                $pdo->prepare('INSERT INTO kategori (nama_kategori) VALUES (:n)')->execute([':n' => $nama]);
                set_flash('success', 'Kategori berhasil ditambahkan.');
            } else {
                $pdo->prepare('UPDATE kategori SET nama_kategori = :n WHERE id_kategori = :id')
                    ->execute([':n' => $nama, ':id' => $id]);
                set_flash('success', 'Kategori berhasil diperbarui.');
            }
        } elseif ($aksi === 'hapus') {
            $pdo->prepare('DELETE FROM kategori WHERE id_kategori = :id')->execute([':id' => $id]);
            set_flash('success', 'Kategori berhasil dihapus.');
        }
    } catch (PDOException $ex) {
        // Kode 23000 = melanggar foreign key (masih dipakai produk)
        if ($ex->getCode() === '23000') {
            set_flash('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh produk.');
        } else {
            set_flash('error', 'Terjadi kesalahan pada database.');
        }
    }
    redirect('kategori.php');
}

// Mode edit: ?edit=ID -> form terisi data lama
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM kategori WHERE id_kategori = :id');
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $edit = $stmt->fetch() ?: null;
}

$list = $pdo->query('SELECT * FROM kategori ORDER BY id_kategori DESC')->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
<h2><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h2>
<form method="post" class="inline-form">
    <input type="hidden" name="aksi" value="<?= $edit ? 'update' : 'tambah' ?>">
    <input type="hidden" name="id" value="<?= e($edit['id_kategori'] ?? '') ?>">
    <div>
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" value="<?= e($edit['nama_kategori'] ?? '') ?>" required>
    </div>
    <button class="btn btn-primary"><?= $edit ? 'Update' : 'Simpan' ?></button>
    <?php if ($edit): ?><a href="kategori.php" class="btn btn-gray">Batal</a><?php endif; ?>
</form>

<table>
    <thead><tr><th>No</th><th>Nama Kategori</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (!$list): ?><tr><td colspan="3" class="center">Belum ada data.</td></tr><?php endif; ?>
    <?php foreach ($list as $i => $k): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($k['nama_kategori']) ?></td>
            <td class="aksi">
                <a href="kategori.php?edit=<?= e($k['id_kategori']) ?>" class="btn btn-warn">Edit</a>
                <form method="post" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= e($k['id_kategori']) ?>">
                    <button class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
