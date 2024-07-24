import './bootstrap';
import './theme-manager.js';

import Alpine from 'alpinejs';
import InputMask from 'inputmask';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", function(){
    var cnpjMask = new InputMask("99.999.999/9999-99");
    cnpjMask.mask(document.querySelector('#cnpj'));
})
