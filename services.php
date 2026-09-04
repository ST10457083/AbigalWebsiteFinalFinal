<?php
$pageTitle = 'Services & Pricing — Abigail Beauty Bar';
$current = 'services';
$baseUrl = '';
require 'includes/header.php';
require 'includes/db.php';

// Pull the menu from the database, grouped by category.
// Falls back to an empty menu (with a note) if the database isn't set up yet.
$menu = [];
$dbError = null;
try {
    $conn = get_db_connection();
    $result = $conn->query('SELECT category, name, price, duration_minutes FROM services ORDER BY category, name');
    while ($row = $result->fetch_assoc()) {
        $menu[$row['category']][] = $row;
    }
} catch (Throwable $e) {
    $dbError = "Menu isn't loading yet — import sql/schema.sql into your database to populate it.";
}
?>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <h2>Services &amp; pricing</h2>
      <a href="booking.php" class="btn btn-primary">Book now</a>
    </div>

    <?php if ($dbError): ?>
      <div class="alert alert-error"><?= htmlspecialchars($dbError) ?></div>
    <?php elseif (empty($menu)): ?>
      <p>No services have been added yet.</p>
    <?php else: ?>
      <?php foreach ($menu as $category => $items): ?>
        <div class="menu-category">
          <h3><?= htmlspecialchars($category) ?></h3>
          <?php foreach ($items as $item): ?>
            <div class="menu-item">
              <span class="name"><?= htmlspecialchars($item['name']) ?></span>
              <span class="fill"></span>
              <span class="meta"><?= (int)$item['duration_minutes'] ?> min</span>
              <span class="price">R<?= number_format((float)$item['price'], 2) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <p class="form-note">Prices are a starting guide — edit them any time in the <code>services</code> table
    or in phpMyAdmin. A deposit is required to confirm bridal and hair installation bookings.</p>
  </div>
</section>

<?php require 'includes/footer.php'; ?>