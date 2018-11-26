jQuery(document).ready(function($) {
  $('.mos-testimonial-wrapper .tab-nav > a').click(function(event) {
      event.preventDefault();
      var id = $(this).data('id');
      //alert('#mos-testimonial-' + id);
      set_mos_testimonial_cookie('mos_testimonial_active_tab',id,1);
      $('#mos-testimonial-'+id).addClass('active').show();
      $('#mos-testimonial-'+id).siblings('div').removeClass('active').hide();
      $(this).closest('.tab-nav').addClass('active');
      $(this).closest('.tab-nav').siblings().removeClass('active');
      //$(this).closest('.tab-nav').css("background-color", "red");
  });
});
