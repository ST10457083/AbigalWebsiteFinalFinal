// Mobile nav toggle
document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var isOpen = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  // Stop the booking date picker from offering past dates.
  var dateInput = document.getElementById('appointment_date');
  if (dateInput) {
    var today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
  }
});