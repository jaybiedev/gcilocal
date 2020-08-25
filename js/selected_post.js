( function ( $ ) {
    'use strict';
    $( document ).ready( function () {
				//$(".dynamic_cpt").live("change", function(){
				$(document).on("change", ".dynamic_cpt", function(){
					var cpt = $(this).val();
					var selected_cat = $("#selected_cat").val();
					jQuery.ajax({
            type: 'POST',
            url: cats.ajaxurl,
            data: {
                action: 'nativechurch_dynamic_category_list',
                cpt: cpt,
								selected_cat: selected_cat
            },
            success: function(data) {
							$(".dynamic_cat").empty();
							$(".dynamic_cat").append(data);
            },
            error: function(errorThrown) {
            }
        });
				});
    });
} ( jQuery ) );

