<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Escape output HTML (Requirement #8)
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Flash message (Requirement #9): simpan di session, tampil sekali, lalu hilang
function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

// Ambil & validasi input form produk
function validate_produk(array $post): array
{
    $data = [
        'nama_produk' => trim($post['nama_produk'] ?? ''),
        'harga'       => trim($post['harga'] ?? ''),
        'stok'        => trim($post['stok'] ?? ''),
        'id_kategori' => trim($post['id_kategori'] ?? ''),
        'id_supplier' => trim($post['id_supplier'] ?? ''),
    ];
    $errors = [];
    if ($data['nama_produk'] === '')                         $errors[] = 'Nama produk wajib diisi.';
    if (!is_numeric($data['harga']) || $data['harga'] < 0)   $errors[] = 'Harga harus angka dan tidak negatif.';
    if (!ctype_digit($data['stok']))                         $errors[] = 'Stok harus bilangan bulat tidak negatif.';
    if (!ctype_digit($data['id_kategori']))                  $errors[] = 'Kategori wajib dipilih.';
    if (!ctype_digit($data['id_supplier']))                  $errors[] = 'Supplier wajib dipilih.';
    return [$data, $errors];
}
