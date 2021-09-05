function session_expiry(expiry) {
  var current_time = Math.floor(Date.now() / 1000); //get current timestamp in seconds
  var remaining_lifetime = expiry - current_time;
  var remind_in = 300; // 5 minutes in seconds
  var interval = setInterval(function() {
    remaining_lifetime--;
    // check if 5 minutes of session lifetime remaining
    if (remaining_lifetime < remind_in) {
      clearInterval(interval);
      // display confirmation dialog
      if (confirm('Your session will expire in 5 minutes. Do you want to extend for another 30 minutes?')) {
        // yes, extend the session
        window.location.href = '/controllers/session.php';
      }
    }
  }, 1000);
}
