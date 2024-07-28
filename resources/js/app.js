import './bootstrap';
import './theme-manager.js';

import Alpine from 'alpinejs';
import InputMask from 'inputmask';
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

// Registra o plugin
FilePond.registerPlugin(FilePondPluginImagePreview);

window.FilePond = FilePond;
window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    var cnpjMask = new InputMask("99.999.999/9999-99");
    cnpjMask.mask(document.querySelector('#cnpj'));

    var cepMask = new InputMask("99999-999");
    cepMask.mask(document.querySelector('#cep'))

    var telefoneMask = new InputMask("9999-9999");
    telefoneMask.mask(document.querySelector("#telefone"));

})


