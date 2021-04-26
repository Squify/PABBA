require ('../scss/base.scss')
require('bootstrap/js/src/dropdown');
require('bootstrap/js/src/collapse');
require('bootstrap/js/src/alert');
import './_alert.js'

CKEDITOR.editorConfig = function( config ) {
    config.language = 'fr';
    config.width = 600;
};