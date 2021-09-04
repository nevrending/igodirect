// disable form submission if there are invalid fields
(function () {
  'use strict';

  // fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = $('.needs-validation');

  // loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add('was-validated');
      }, false);
    });
})();
