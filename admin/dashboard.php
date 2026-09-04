<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$pageTitle = 'Admin Dashboard — Abigail Beauty Bar';
$baseUrl = '../';
require '../includes/header.php'; // Keep full header for dashboard
require '../includes/db.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $bookingId = (int)$_POST['booking_id'];
    $newStatus = $_POST['status'];
    
    try {
        $conn = get_db_connection();
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $newStatus, $bookingId);
        $stmt->execute();
        $updateSuccess = true;
    } catch (Exception $e) {
        $updateError = 'Failed to update status: ' . $e->getMessage();
    }
}

// Handle deposit payment update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deposit_action'])) {
    $bookingId = (int)$_POST['booking_id'];
    $depositPaid = (int)$_POST['deposit_paid'];
    
    try {
        $conn = get_db_connection();
        $stmt = $conn->prepare("UPDATE bookings SET deposit_paid = ? WHERE id = ?");
        $stmt->bind_param('ii', $depositPaid, $bookingId);
        $stmt->execute();
        $depositSuccess = true;
    } catch (Exception $e) {
        $depositError = 'Failed to update deposit: ' . $e->getMessage();
    }
}

// Get statistics
try {
    $conn = get_db_connection();
    
    // Total bookings
    $result = $conn->query('SELECT COUNT(*) as total FROM bookings');
    $totalBookings = $result->fetch_assoc()['total'];
    
    // Pending bookings
    $result = $conn->query("SELECT COUNT(*) as pending FROM bookings WHERE status = 'pending'");
    $pendingBookings = $result->fetch_assoc()['pending'];
    
    // Confirmed bookings
    $result = $conn->query("SELECT COUNT(*) as confirmed FROM bookings WHERE status = 'confirmed'");
    $confirmedBookings = $result->fetch_assoc()['confirmed'];
    
    // Completed bookings
    $result = $conn->query("SELECT COUNT(*) as completed FROM bookings WHERE status = 'completed'");
    $completedBookings = $result->fetch_assoc()['completed'];
    
    // Today's bookings
    $today = date('Y-m-d');
    $result = $conn->query("SELECT COUNT(*) as today FROM bookings WHERE appointment_date = '$today'");
    $todayBookings = $result->fetch_assoc()['today'];
    
    // Total deposit amount
    $result = $conn->query("SELECT SUM(deposit_amount) as total_deposits FROM bookings WHERE deposit_paid = 0");
    $totalDeposits = $result->fetch_assoc()['total_deposits'] ?? 0;
    
    // Upcoming bookings (next 7 days)
    $nextWeek = date('Y-m-d', strtotime('+7 days'));
    $upcomingBookings = $conn->query("
        SELECT * FROM bookings 
        WHERE appointment_date BETWEEN '$today' AND '$nextWeek' 
        AND status != 'cancelled'
        ORDER BY appointment_date, appointment_time
    ");
    
    // Recent bookings
    $recentBookings = $conn->query('SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10');
    
    // All bookings with filter
    $filter = $_GET['filter'] ?? 'all';
    $whereClause = '';
    if ($filter === 'pending') {
        $whereClause = "WHERE status = 'pending'";
    } elseif ($filter === 'confirmed') {
        $whereClause = "WHERE status = 'confirmed'";
    } elseif ($filter === 'completed') {
        $whereClause = "WHERE status = 'completed'";
    } elseif ($filter === 'cancelled') {
        $whereClause = "WHERE status = 'cancelled'";
    } elseif ($filter === 'today') {
        $whereClause = "WHERE appointment_date = '$today'";
    }
    
    $allBookings = $conn->query("
        SELECT * FROM bookings 
        $whereClause
        ORDER BY appointment_date DESC, appointment_time DESC 
        LIMIT 50
    ");
    
} catch (Exception $e) {
    $dbError = 'Database error: ' . $e->getMessage();
}
?>

<style>
.admin-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}
.stat-card {
    background: var(--ivory);
    padding: 20px;
    border-radius: 8px;
    border: 1px solid var(--line);
    text-align: center;
}
.stat-card h3 {
    font-size: 0.9rem;
    color: var(--plum);
    margin-bottom: 8px;
}
.stat-number {
    font-size: 2.2rem;
    font-weight: bold;
    color: var(--plum);
}
.stat-card.pending .stat-number { color: #C97A93; }
.stat-card.today .stat-number { color: #B98A4A; }
.stat-card.deposits .stat-number { color: #2E4A2A; }

.filter-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.filter-bar a {
    padding: 6px 16px;
    background: var(--ivory);
    border: 1px solid var(--line);
    border-radius: 4px;
    text-decoration: none;
    color: var(--ink);
    font-size: 0.9rem;
}
.filter-bar a.active {
    background: var(--plum);
    color: var(--ivory);
    border-color: var(--plum);
}
.filter-bar a:hover:not(.active) {
    background: var(--rose-pale);
}

.booking-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.booking-actions form {
    display: inline;
}
.booking-actions select {
    padding: 4px 8px;
    font-size: 0.8rem;
    border: 1px solid var(--line);
    border-radius: 4px;
}
.booking-actions button {
    padding: 4px 12px;
    font-size: 0.8rem;
    background: var(--plum);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.booking-actions button:hover {
    background: var(--rose);
}

.table-wrapper {
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}
th, td {
    padding: 10px 12px;
    text-align: left;
    border-bottom: 1px solid var(--line);
}
th {
    background: var(--rose-pale);
    font-weight: 600;
}
.status-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.78rem;
    font-weight: 500;
}
.status-pending { background: #F0DCE1; color: #3A1C2B; }
.status-confirmed { background: #E4EEE1; color: #2E4A2A; }
.status-completed { background: #D1E8E2; color: #1A4A3A; }
.status-cancelled { background: #F4DEDE; color: #6B2323; }

.deposit-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
}
.deposit-paid { background: #E4EEE1; color: #2E4A2A; }
.deposit-unpaid { background: #F4DEDE; color: #6B2323; }
</style>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <h2>Admin Dashboard</h2>
      <div style="display:flex;gap:12px;">
        <a href="logout.php" class="btn btn-secondary">Logout</a>
      </div>
    </div>

    <?php if (isset($updateSuccess)): ?>
      <div class="alert alert-success">Booking status updated successfully!</div>
    <?php endif; ?>
    <?php if (isset($depositSuccess)): ?>
      <div class="alert alert-success">Deposit status updated successfully!</div>
    <?php endif; ?>
    <?php if (isset($updateError) || isset($depositError)): ?>
      <div class="alert alert-error"><?= htmlspecialchars($updateError ?? $depositError) ?></div>
    <?php endif; ?>
    <?php if (isset($dbError)): ?>
      <div class="alert alert-error"><?= htmlspecialchars($dbError) ?></div>
    <?php else: ?>
      
      <!-- Statistics -->
      <div class="admin-stats">
        <div class="stat-card">
          <h3>Total Bookings</h3>
          <div class="stat-number"><?= $totalBookings ?></div>
        </div>
        <div class="stat-card pending">
          <h3>Pending</h3>
          <div class="stat-number"><?= $pendingBookings ?></div>
        </div>
        <div class="stat-card">
          <h3>Confirmed</h3>
          <div class="stat-number"><?= $confirmedBookings ?? 0 ?></div>
        </div>
        <div class="stat-card">
          <h3>Completed</h3>
          <div class="stat-number"><?= $completedBookings ?? 0 ?></div>
        </div>
        <div class="stat-card today">
          <h3>Today's Bookings</h3>
          <div class="stat-number"><?= $todayBookings ?></div>
        </div>
        <div class="stat-card deposits">
          <h3>Pending Deposits</h3>
          <div class="stat-number">R<?= number_format($totalDeposits ?? 0, 2) ?></div>
        </div>
      </div>

      <!-- Upcoming Bookings -->
      <div style="margin-bottom:40px;">
        <h3>Upcoming Bookings (Next 7 Days)</h3>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Client</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Deposit</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($upcomingBookings->num_rows > 0): ?>
                <?php while ($booking = $upcomingBookings->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($booking['full_name']) ?></td>
                    <td><?= htmlspecialchars($booking['service_name']) ?></td>
                    <td><?= date('M d, Y', strtotime($booking['appointment_date'])) ?></td>
                    <td><?= date('h:i A', strtotime($booking['appointment_time'])) ?></td>
                    <td>
                      <span class="status-pill status-<?= $booking['status'] ?>">
                        <?= ucfirst($booking['status']) ?>
                      </span>
                    </td>
                    <td>
                      R<?= number_format($booking['deposit_amount'], 2) ?>
                      <br>
                      <span class="deposit-badge <?= $booking['deposit_paid'] ? 'deposit-paid' : 'deposit-unpaid' ?>">
                        <?= $booking['deposit_paid'] ? '✓ Paid' : 'Unpaid' ?>
                      </span>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="6" style="text-align:center;padding:20px;">No upcoming bookings in the next 7 days.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- All Bookings -->
      <div>
        <h3>All Bookings</h3>
        
        <div class="filter-bar">
          <a href="?filter=all" class="<?= $filter === 'all' ? 'active' : '' ?>">All</a>
          <a href="?filter=pending" class="<?= $filter === 'pending' ? 'active' : '' ?>">Pending</a>
          <a href="?filter=confirmed" class="<?= $filter === 'confirmed' ? 'active' : '' ?>">Confirmed</a>
          <a href="?filter=completed" class="<?= $filter === 'completed' ? 'active' : '' ?>">Completed</a>
          <a href="?filter=cancelled" class="<?= $filter === 'cancelled' ? 'active' : '' ?>">Cancelled</a>
          <a href="?filter=today" class="<?= $filter === 'today' ? 'active' : '' ?>">Today</a>
        </div>

        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Phone</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Deposit</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($allBookings->num_rows > 0): ?>
                <?php while ($booking = $allBookings->fetch_assoc()): ?>
                  <tr>
                    <td>#<?= $booking['id'] ?></td>
                    <td><?= htmlspecialchars($booking['full_name']) ?></td>
                    <td><?= htmlspecialchars($booking['phone']) ?></td>
                    <td><?= htmlspecialchars($booking['service_name']) ?></td>
                    <td><?= date('M d, Y', strtotime($booking['appointment_date'])) ?></td>
                    <td><?= date('h:i A', strtotime($booking['appointment_time'])) ?></td>
                    <td>
                      <span class="status-pill status-<?= $booking['status'] ?>">
                        <?= ucfirst($booking['status']) ?>
                      </span>
                    </td>
                    <td>
                      R<?= number_format($booking['deposit_amount'], 2) ?>
                      <br>
                      <span class="deposit-badge <?= $booking['deposit_paid'] ? 'deposit-paid' : 'deposit-unpaid' ?>">
                        <?= $booking['deposit_paid'] ? '✓ Paid' : 'Unpaid' ?>
                      </span>
                    </td>
                    <td class="booking-actions">
                      <form method="POST">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        <select name="status">
                          <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                          <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Confirm</option>
                          <option value="completed" <?= $booking['status'] === 'completed' ? 'selected' : '' ?>>Complete</option>
                          <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Cancel</option>
                        </select>
                        <button type="submit" name="action" value="update">Update</button>
                      </form>
                      <form method="POST">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        <input type="hidden" name="deposit_paid" value="<?= $booking['deposit_paid'] ? 0 : 1 ?>">
                        <button type="submit" name="deposit_action" value="update" style="background:<?= $booking['deposit_paid'] ? '#6B2323' : '#2E4A2A' ?>;">
                          <?= $booking['deposit_paid'] ? 'Mark Unpaid' : 'Mark Paid' ?>
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="9" style="text-align:center;padding:20px;">No bookings found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      
    <?php endif; ?>
  </div>
</section>

<?php require '../includes/footer.php'; ?>