<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/Database.php';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD Inventaris</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>📦 CRUD Inventaris</h1>
    <nav class="nav">
        <a href="index.php">Produk</a>
        <a href="kategori.php">Kategori</a>
        <a href="supplier.php">Supplier</a>
    </nav>
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
