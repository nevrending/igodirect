function initialize() {
  var options = {
    componentRestrictions: {country: 'au'}
  };
  var input = document.getElementById('address');
  var autocomplete = new google.maps.places.Autocomplete(input, options);
}

$(document).ready(function () {
  google.maps.event.addDomListener(window, 'load', initialize);
});
