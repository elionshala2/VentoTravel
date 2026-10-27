document.querySelectorAll('.pw__toggle').forEach(function (b) {
  b.addEventListener('click', function () {
    var i = b.parentElement.querySelector('input');
    var show = i.type === 'password';
    i.type = show ? 'text' : 'password';
    b.textContent = show ? b.dataset.hide : b.dataset.show;
  });
});
