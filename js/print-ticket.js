jQuery( document ).ready(function() {
jQuery("body").addClass("single-event");
jQuery('head').append('<style type="text/css" media="print">div.body, .modal-footer, .modal-header .close{display:none!important;}@page{size-auto;margin:5mm 5mm 5mm 5mm}body{margin:0;}</style>');
jQuery('#ticketModal').modal('show');
});