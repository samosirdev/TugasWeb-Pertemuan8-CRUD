<?php /* Variabel: $data, $errors, $kategori, $supplier, $action, $btn */ ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e($action) ?>">
    <label>Nama Produk</label>
    <input type="text" name="nama_produk" value="<?= e($data['nama_produk']) ?>" required>

    <label>Harga (Rp)</label>
    <input type="number" name="harga" min="0" step="0.01" value="<?= e($data['harga']) ?>" required>

    <label>Stok</label>
    <input type="number" name="stok" min="0" value="<?= e($data['stok']) ?>" required>

    <label>Kategori</label>
    <select name="id_kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($kategori as $k): ?>
            <option value="<?= e($k['id_kategori']) ?>"
                <?= (string)$data['id_kategori'] === (string)$k['id_kategori'] ? 'selected' : '' ?>>
                <?= e($k['nama_kategori']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Supplier</label>
    <select name="id_supplier" required>
        <option value="">-- Pilih Supplier --</option>
        <?php foreach ($supplier as $s): ?>
            <option value="<?= e($s['id_supplier']) ?>"
                <?= (string)$data['id_supplier'] === (string)$s['id_supplier'] ? 'selected' : '' ?>>
                <?= e($s['nama_supplier']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <div class="actions">
        <button type="submit" class="btn btn-primary"><?= e($btn) ?></button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </div>
</form>
