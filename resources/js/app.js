import './bootstrap';
import './theme-manager.js';

import Alpine from 'alpinejs';
import InputMask from 'inputmask';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", function(){
    var cnpjMask = new InputMask("99.999.999/9999-99");
    cnpjMask.mask(document.querySelector('#cnpj'));

    var cepMask = new InputMask("99999-999");
    cepMask.mask(document.querySelector('#cep'))

    var telefoneMask = new InputMask("9999-9999");
    telefoneMask.mask(document.querySelector("#telefone"));
})
