import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
document.addEventListener("DOMContentLoaded", function(event) {
    document.getElementById('successButton').click();
});
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('myCartDropdownButton1').click();
});
