require ('../scss/base.scss')
require('bootstrap/dist/js/bootstrap.min');
import '@fortawesome/fontawesome-free/js/all';
import './_alert.js';

import $ from 'jquery';
import 'select2';                       // globally assign select2 fn to $ element
require('select2/dist/css/select2.min.css')

$(document).ready(function() {
    $('.select2').select2();
});
