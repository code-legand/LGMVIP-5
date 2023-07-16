const alertBox = document.querySelector('.alert-box');
const alertClose = document.querySelector('.close-button');
alertClose.addEventListener('click', function() {
  alertBox.remove();
});

