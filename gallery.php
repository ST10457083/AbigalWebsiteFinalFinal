<?php
$pageTitle = 'Gallery — Abigail Beauty Bar';
$current = 'gallery';
$baseUrl = '';
require 'includes/header.php';

$photos = [
    ['file' => 'HairInstallResults.jpeg', 'alt' => 'Hair installation result'],
    ['file' => 'Makoti.jpeg', 'alt' => 'Bridal hairstyle'],
    ['file' => 'FullGMakeUp.jpeg', 'alt' => 'Full glam makeup look'],
    ['file' => 'Manala.jpeg', 'alt' => 'Gel nail set'],
    ['file' => 'LaceFrontal.jpeg', 'alt' => 'Lace frontal install'],
    ['file' => 'ChairSitting.jpeg', 'alt' => 'Client at the salon chair'],
];
?>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <h2>Gallery</h2>
    </div>
    <div class="gallery-grid">
      <?php foreach ($photos as $p): ?>
        <img class="photo" src="images/<?= htmlspecialchars($p['file']) ?>" alt="<?= htmlspecialchars($p['alt']) ?>"
             onerror="this.onerror=null;this.src='https://placehold.co/500x500/F0DCE1/3A1C2B?text=<?= urlencode($p['file']) ?>';">
      <?php endforeach; ?>
    
</section>

<?php require 'includes/footer.php'; ?>