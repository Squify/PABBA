require ('../scss/base.scss')
require('bootstrap/js/src/dropdown');
require('bootstrap/js/src/collapse');
require('bootstrap/js/src/alert');
import '@fortawesome/fontawesome-free/js/all'
import './_alert.js'

import $ from 'jquery';
import 'select2';                       // globally assign select2 fn to $ element

$(document).ready(function() {
    $('.select2').select2();
});
