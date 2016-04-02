/**
*  @author    Prestapro
*  @copyright Prestapro
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*
*/

// <![CDATA[
$('document').ready(function() {
	$( "#amazing_search_select" ).select2();
	var $input_search = $("#amazingsearch_query_top");
	$input_search.on('keyup', function() {
		if ($(this).val().length > 4){
			var $select_c = $("#amazing_search_select").val();
			var search_value = $(this).val();
			ajaxQuery = $.ajax({
				type: 'GET',
				url: baseDir + 'modules/amazingsearch/amazingsearch-ajax.php',
				data: 's='+search_value.toLowerCase()+'&id_lang='+lang_id+'&id_shop='+shop_id+'&id_category='+$select_c,
				dataType: 'json',
				cache: false,
				success: function(result){
					if(result.length > 0){
						$(".amazingsearch_result").html(result);
						$(".amazingsearch_result").show();
					}else{
						$(".amazingsearch_result").html('');
						$(".amazingsearch_result").hide();
					}
				}					
			});	
		}
	});
	$("#amazing_search_select").on("change", function(){
		if ($("#amazingsearch_query_top").val().length > 4){
			var $select_c = $("#amazing_search_select").val();
			var search_value = $(this).val();
			ajaxQuery = $.ajax({
				type: 'GET',
				url: baseDir + 'modules/amazingsearch/amazingsearch-ajax.php',
				data: 's='+search_value.toLowerCase()+'&id_lang='+lang_id+'&id_shop='+shop_id+'&id_category='+$select_c,
				dataType: 'json',
				cache: false,
				success: function(result){
					if(result.length > 0){
						$(".amazingsearch_result").html(result);
						$(".amazingsearch_result").show();
					}else{
						$(".amazingsearch_result").html('');
						$(".amazingsearch_result").hide();
					}
				}					
			});	
		}
	});

	$(document).mouseup(function (e){
	    var container = $("#amazing_block_top");
		if (!container.is(e.target) && container.has(e.target).length === 0){
	        $("#amazing_block_top .amazingsearch_result").hide().html('');
	    }
    });
});
// ]]>