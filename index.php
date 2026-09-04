<?php
$pageTitle = 'Abigail Beauty Bar — Hair, Makeup, Bridal & Nails';
$current = 'home';
$baseUrl = '';
require 'includes/header.php';
?>

<section class="hero">
  <div class="wrap">
    <div>
      <p class="eyebrow">Book in minutes, not messages</p>
      <h1>Your look, booked&nbsp;properly.</h1>
      <p class="lede">Abigail Beauty Bar does hair installation, makeup, bridal styling and nails —
      now with online booking, so your slot is confirmed the moment you choose it.</p>
      <div class="hero-actions">
        <a href="booking.php" class="btn btn-primary">Book an appointment</a>
        <a href="services.php" class="btn btn-secondary">See services &amp; pricing</a>
        
        
      </div>
    </div>
    <div class="hero-photo">
      <img class="photo" src="images/Logo.jpeg" alt="Abigail Beauty Bar styling in progress"
           onerror="this.onerror=null;this.src='https://placehold.co/800x1000/3A1C2B/FBF4EC?text=Add+images%2Fhero.jpg';">
    </div>
  </div>
</section>



<section class="section">
  <div class="wrap">
    <div class="section-head">
      <h2>What we do</h2>
      <a href="services.php" class="btn btn-secondary">Full menu</a>
    </div>
    <div class="feature-grid">
      <div class="feature-card">
        <figure><img class="photo" src="images/Hair.jpeg" alt="Hair installation"
          onerror="this.onerror=null;this.src='https://placehold.co/400x400/F0DCE1/3A1C2B?text=Hair';"></figure>
        <h3>Hair installation</h3>
        <p>Sew-ins, frontals and closures, fitted and styled to last.</p>
      </div>
      <div class="feature-card">
        <figure><img class="photo" src="images/Makeup.jpeg" alt="Makeup application"
          onerror="this.onerror=null;this.src='https://placehold.co/400x400/F0DCE1/3A1C2B?text=Makeup';"></figure>
        <h3>Makeup</h3>
        <p>Everyday to full glam, matched to your skin and the occasion.</p>
      </div>
      <div class="feature-card">
        <figure><img class="photo" src="images/Bridal.jpeg" alt="Bridal hair styling"
          onerror="this.onerror=null;this.src='https://placehold.co/400x400/F0DCE1/3A1C2B?text=Bridal';"></figure>
        <h3>Bridal styling</h3>
        <p>Trials and wedding-day hair, timed around your schedule.</p>
      </div>
      <div class="feature-card">
        <figure><img class="photo" src="images/Nails.jpeg" alt="Nail services"
          onerror="this.onerror=null;this.src='https://placehold.co/400x400/F0DCE1/3A1C2B?text=Nails';"></figure>
        <h3>Nails</h3>
        <p>Manicures, gel and full acrylic sets.</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-dark">
  <div class="wrap">
    <div class="section-head">
      <h2>Why book online</h2>
    </div>
    <div class="info-grid">
      <div>
        <h3>No double bookings</h3>
        <p>Every slot is checked against the calendar automatically, so two clients never land on the same appointment.</p>
      </div>
      <div>
        <h3>Your details on file</h3>
        <p>Your contact info and service history are saved, so returning is faster the second time.</p>
      </div>
      <div>
        <h3>Deposits tracked</h3>
        <p>Deposits are recorded against your booking, so there's no confusion at checkout.</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="wrap" style="text-align:center;">
    <h2>Ready for your next appointment?</h2>
    <p style="margin:0 auto 24px;">Pick a service, choose a time that works, and you're booked.</p>
    <a href="booking.php" class="btn btn-primary">Book an appointment</a>
  </div>
</section>

<?php require 'includes/footer.php'; ?>

<!-- Floating Admin Button -->
<style>
.floating-admin-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: var(--plum);
    color: var(--ivory);
    padding: 10px 16px;
    border-radius: 30px;
    text-decoration: none;
    font-size: 0.85rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    z-index: 1000;
    opacity: 0.6;
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.floating-admin-btn:hover {
    opacity: 1;
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(0,0,0,0.3);
}
</style>

<!-- Add this before closing body tag -->
<a href="<?= $baseUrl ?>admin/login.php" class="floating-admin-btn">Admin</a>