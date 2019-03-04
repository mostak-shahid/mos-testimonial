jQuery(document).ready(function($) {
	$(window).load(function(){
		$('.mos-testimonial-wrapper .tab-con').hide();
		$('.mos-testimonial-wrapper .tab-con.active').show();
	});

	$('.mos-testimonial-wrapper .tab-nav > a').click(function(event) {
		event.preventDefault();
		var id = $(this).data('id');
		set_mos_testimonial_cookie('testimonial_active_tab',id,1);
		$('#mos-testimonial-'+id).addClass('active').show();
		$('#mos-testimonial-'+id).siblings('div').removeClass('active').hide();

		$(this).closest('.tab-nav').addClass('active');
		$(this).closest('.tab-nav').siblings().removeClass('active');
	});

	var position;
	var template;

	$( ".connected-sortable" ).sortable({
		connectWith: ".connected-sortable",
		stack: '.connected-sortable ul',
		update: function (event, ui) {
			//var data = $(this).sortable('serialize');
			//var data = $(this).sortable();
			$('.layout-manager').remove();
			$(this).children().each(function(index){
				position = $(this).data('position');
				parent = $(this).parent().data('name');
				template = $(this).closest('.wrapper').data('name');
				$(this).addClass('update');
				//console.log(index + ', ' + position + ', ' + parent + ', ' + template);
				$(this).find('input').val(parent + ',' + position);
				//$('#TB_ajaxContent .wrapper').append('<input name="layout-manager[]" type="hidden" class="layout-manager" value="'+ template + ',' + parent + ',' + position +'" />');

				//saveNewPositions();
			})
		}
	}).disableSelection();
	function saveNewPositions() {
		//$()
		// var positions = new Array();
		// $('.connected-sortable:has(.update)').each(function () {
		// 	//$(this).css('color', 'red');
		// 	template = $(this).closest('.wrapper').data('name'); //custom-template
		// 	container = $(this).data('name'); //header, main, left, right, footer, disable
		// 	$(this).find('li').each(function () {
		// 		positions.push($(this).data('position'));
		// 		$(this).removeClass('updated');
		// 	});
		// });
		// console.log(template + ', ' + container + ', ' + positions);
	}

});
