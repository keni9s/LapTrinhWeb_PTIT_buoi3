<?php
require __DIR__ . '/includes/init.php';

$sort = $_GET['sort'] ?? '';

$orderBy = 'id';
if ($sort === 'price_asc') {
    $orderBy = 'price ASC';
} elseif ($sort === 'price_desc') {
    $orderBy = 'price DESC';
}

$stmt = $pdo->query('SELECT id, name, price, image FROM products ORDER BY ' . $orderBy);
$products = $stmt->fetchAll();

$pageTitle = 'Trang chủ';
require __DIR__ . '/includes/header.php';
?>
<section>
    <h2>Sản phẩm nổi bật</h2>

    <form method="get" class="sort-form">
        <label for="sort">Sắp xếp theo giá:</label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="" <?= $sort === '' ? 'selected' : '' ?>>Mặc định</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
        </select>
        <noscript><button type="submit" class="btn">Sắp xếp</button></noscript>
    </form>

    <p class="product-count">Tìm thấy <?= count($products) ?> sản phẩm</p>

    <div class="product-list">
        <?php foreach ($products as $product): ?>
         <article class="product-card">
            <img src="<?= url($product['image']) ?>" alt="<?= e($product['name']) ?>">
            <h3><?= e($product['name']) ?></h3>
            <p class="price"><?= format_price($product['price']) ?></p>
            <a href="<?= url('product.php?id=' . $product['id']) ?>" class="btn">Xem chi tiết</a>
            <button type="button" class="btn btn-accent add-to-cart"
                    data-id="<?= $product['id'] ?>">Thêm vào giỏ</button>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>