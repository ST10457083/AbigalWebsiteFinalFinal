<?php
require 'includes/db.php';

function back_with_error(string $message): void
{
    header('Location: booking.php?error=' . urlencode($message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: booking.php');
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$serviceId = (int)($_POST['service_id'] ?? 0);
$date = trim($_POST['appointment_date'] ?? '');
$time = trim($_POST['appointment_time'] ?? '');
$notes = trim($_POST['notes'] ?? '');

// --- validation ---
if ($fullName === '' || $phone === '' || $serviceId <= 0 || $date === '' || $time === '') {
    back_with_error('Please fill in your name, phone, service, date and time.');
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    back_with_error('That email address doesn\'t look right — please check it and try again.');
}
$dateObj = DateTime::createFromFormat('Y-m-d', $date);
if (!$dateObj || $dateObj < new DateTime('today')) {
    back_with_error('Please choose today or a future date.');
}

try {
    $conn = get_db_connection();

    // Look up the service so we store its name and price even if it's edited later.
    $stmt = $conn->prepare('SELECT name, price FROM services WHERE id = ?');
    $stmt->bind_param('i', $serviceId);
    $stmt->execute();
    $service = $stmt->get_result()->fetch_assoc();
    if (!$service) {
        back_with_error('That service could not be found — please choose again.');
    }

    // Prevent double bookings: reject if the same date/time slot is already taken
    // (and not cancelled).
    $check = $conn->prepare(
        "SELECT id FROM bookings WHERE appointment_date = ? AND appointment_time = ? AND status != 'cancelled'"
    );
    $check->bind_param('ss', $date, $time);
    $check->execute();
    if ($check->get_result()->fetch_assoc()) {
        back_with_error('That time slot is already booked — please pick a different time.');
    }

    // A simple starting deposit: 20% of the service price. Adjust to match
    // Abigail's real deposit policy.
    $depositAmount = round(((float)$service['price']) * 0.20, 2);

    $insert = $conn->prepare(
        'INSERT INTO bookings (full_name, phone, email, service_id, service_name, appointment_date, appointment_time, notes, deposit_amount)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $emailOrNull = $email !== '' ? $email : null;
    // Types: full_name(s), phone(s), email(s), service_id(i), service_name(s),
    // date(s), time(s), notes(s), deposit_amount(d)
    $insert->bind_param(
        'sssissssd',
        $fullName,
        $phone,
        $emailOrNull,
        $serviceId,
        $service['name'],
        $date,
        $time,
        $notes,
        $depositAmount
    );
    $insert->execute();

    header('Location: booking.php?success=1');
    exit;

} catch (Throwable $e) {
    back_with_error('Something went wrong saving your booking. Please try again.');
}