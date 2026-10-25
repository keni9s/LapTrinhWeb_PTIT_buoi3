<?php
require __DIR__ . '/includes/init.php';

$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

$product = false;
$relatedProducts = [];
if ($productId !== false && $productId !== null) {
    $stmt = $pdo->prepare(
        'SELECT id, name, price, image, category_id FROM products WHERE id = :id'
    );
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch();

    if ($product !== false) {
        $stmt = $pdo->prepare(
            'SELECT id, name, price, image
             FROM products
             WHERE category_id = :category_id AND id <> :id
             LIMIT 3'
        );
        $stmt->execute([
            'category_id' => $product['category_id'],
            'id' => $product['id'],
        ]);
        $relatedProducts = $stmt->fetchAll();
    }
}

if ($product === false) {
    http_response_code(404);
}

$pageTitle = $product === false ? 'Không tìm thấy sản phẩm' : $product['name'];
require __DIR__ . '/includes/header.php';
?>
<?php if ($product === false): ?>
    <h2>Không tìm thấy sản phẩm</h2>
    <p>Sản phẩm không tồn tại hoặc liên kết không hợp lệ.</p>
<?php else: ?>
    <article class="product-detail">
        <div class="product-image">
            <img src="<?= url($product['image']) ?>" alt="<?= e($product['name']) ?>">
        </div>

        <div class="product-info">
            <h2><?= e($product['name']) ?></h2>
            <p class="price">Giá: <strong><?= format_price($product['price']) ?></strong></p>
            <p><a href="<?= url('index.php') ?>">&laquo; Quay lại danh sách sản phẩm</a></p>
        </div>
    </article>

    <section class="related-products">
        <h2>Sản phẩm cùng danh mục</h2>
        <?php if ($relatedProducts): ?>
            <div class="product-list">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                    <article class="product-card">
                        <img src="<?= url($relatedProduct['image']) ?>" alt="<?= e($relatedProduct['name']) ?>">
                        <h3><?= e($relatedProduct['name']) ?></h3>
                        <p class="price"><?= format_price($relatedProduct['price']) ?></p>
                        <a href="<?= url('product.php?id=' . $relatedProduct['id']) ?>" class="btn">Xem chi tiết</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Chưa có sản phẩm khác cùng danh mục.</p>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
