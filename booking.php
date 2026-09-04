<?php
$pageTitle = 'Book an Appointment — Abigail Beauty Bar';
$current = 'booking';
$baseUrl = '';
require 'includes/header.php';
require 'includes/db.php';

$services = [];
$dbError = null;
try {
    $conn = get_db_connection();
    $result = $conn->query('SELECT id, category, name, price, duration_minutes FROM services ORDER BY category, name');
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
} catch (Throwable $e) {
    $dbError = "The booking form needs the database set up first — import sql/schema.sql, then reload this page.";
}

// process_booking.php redirects back here with ?success=1 or ?error=... on failure.
$success = isset($_GET['success']);
$formError = $_GET['error'] ?? null;
?>

<section class="section">
  <div class="wrap" style="max-width:720px;">
    <div class="section-head">
      <h2>Book an appointment</h2>
    </div>

    <?php if ($success): ?>
      <div class="alert alert-success">
        Thanks — your appointment request has been received. Abigail will confirm your slot and any deposit
        needed by WhatsApp or phone shortly.
      </div>
    <?php elseif ($formError): ?>
      <div class="alert alert-error"><?= htmlspecialchars($formError) ?></div>
    <?php endif; ?>

    <?php if ($dbError): ?>
      <div class="alert alert-error"><?= htmlspecialchars($dbError) ?></div>
    <?php else: ?>
      <form action="process_booking.php" method="POST" novalidate>
        <div class="form-grid">
          <div>
            <label for="full_name">Full name</label>
            <input type="text" id="full_name" name="full_name" required>
          </div>
          <div>
            <label for="phone">Phone number</label>
            <input type="tel" id="phone" name="phone" required>
          </div>
          <div class="full">
            <label for="email">Email (optional)</label>
            <input type="email" id="email" name="email">
          </div>
          <div>
            <label for="service_id">Service</label>
            <select id="service_id" name="service_id" required>
              <option value="" disabled selected>Choose a service</option>
              <?php foreach ($services as $s): ?>
                <option value="<?= (int)$s['id'] ?>">
                  <?= htmlspecialchars($s['category'] . ' — ' . $s['name']) ?> (R<?= number_format((float)$s['price'], 2) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="appointment_date">Date</label>
            <input type="date" id="appointment_date" name="appointment_date" required>
          </div>
          <div>
            <label for="appointment_time">Time</label>
            <input type="time" id="appointment_time" name="appointment_time" required>
          </div>
          <div class="full">
            <label for="notes">Anything Abigail should know? (optional)</label>
            <textarea id="notes" name="notes" placeholder="Hair length, inspiration photos you'll bring, allergies, etc."></textarea>
          </div>
        </div>
        <p class="form-note">A deposit may be required to confirm bridal and hair installation bookings —
        Abigail will let you know the amount when she confirms your slot.</p>
        <div style="margin-top:24px;">
          <button type="submit" class="btn btn-primary" style="border:none;cursor:pointer;">Request appointment</button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</section>

<?php require 'includes/footer.php'; ?>