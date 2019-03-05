<?php
function mos_testimonial_admin_enqueue_scripts(){
	$page = @$_GET['page'];
	global $pagenow, $typenow;
	/*var_dump($pagenow); //options-general.php(If under settings)/edit.php(If under post type)
	var_dump($typenow); //post type(If under post type)
	var_dump($page); //mos_testimonial_settings(If under settings)*/
	
	if ($pagenow == 'edit.php' AND $typenow == 'testimonial') {
		wp_enqueue_style( 'mos-testimonial-admin', plugins_url( 'css/mos-testimonial-admin.css', __FILE__ ) );

		//wp_enqueue_media();

		wp_enqueue_script( 'jquery' );
		
		/*Editor*/
		//wp_enqueue_style( 'docs', plugins_url( 'plugins/CodeMirror/doc/docs.css', __FILE__ ) );
		wp_enqueue_style( 'codemirror', plugins_url( 'plugins/CodeMirror/lib/codemirror.css', __FILE__ ) );
		wp_enqueue_style( 'show-hint', plugins_url( 'plugins/CodeMirror/addon/hint/show-hint.css', __FILE__ ) );

		wp_enqueue_script( 'codemirror', plugins_url( 'plugins/CodeMirror/lib/codemirror.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'css', plugins_url( 'plugins/CodeMirror/mode/css/css.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'javascript', plugins_url( 'plugins/CodeMirror/mode/javascript/javascript.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'show-hint', plugins_url( 'plugins/CodeMirror/addon/hint/show-hint.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'css-hint', plugins_url( 'plugins/CodeMirror/addon/hint/css-hint.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'javascript-hint', plugins_url( 'plugins/CodeMirror/addon/hint/javascript-hint.js', __FILE__ ), array('jquery') );
		/*Editor*/

		wp_enqueue_script( 'mos-testimonial-functions.min', plugins_url( 'js/mos-testimonial-functions.min.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'mos-testimonial-admin', plugins_url( 'js/mos-testimonial-admin.js', __FILE__ ), array('jquery') );
	}

}
add_action( 'admin_enqueue_scripts', 'mos_testimonial_admin_enqueue_scripts' );

function mos_testimonial_ajax_scripts(){
	wp_enqueue_script( 'mos-testimonial-ajax.min', plugins_url( 'js/mos-testimonial-ajax.min.js', __FILE__ ), array('jquery') );
	$ajax_params = array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'ajax_nonce' => wp_create_nonce('mos_testimonial_verify'),
	);
	wp_localize_script( 'mos-testimonial-ajax.min', 'ajax_obj', $ajax_params );
}
add_action( 'admin_enqueue_scripts', 'mos_testimonial_ajax_scripts' );
function mos_testimonial_enqueue_scripts(){
	global $mos_testimonial_options;
	if (@$mos_testimonial_options['jquery']) {
		wp_enqueue_script( 'jquery' );
	}
	if (@$mos_testimonial_options['owl-carousel']) {
		wp_enqueue_style( 'owl.carousel.min', plugins_url( 'plugins/owlcarousel/owl.carousel.min.css', __FILE__ ) );
		wp_enqueue_style( 'owl.theme.default.min', plugins_url( 'plugins/owlcarousel/owl.theme.default.min.css', __FILE__ ) );

		wp_enqueue_script('owl.carousel.min', plugins_url( 'plugins/owlcarousel/owl.carousel.min.js', __FILE__ ), array('jquery'));
		wp_enqueue_script( 'owl.carousel.min' );
	}
	wp_enqueue_style( 'mos-testimonial.min', plugins_url( 'css/mos-testimonial.min.css', __FILE__ ) );
	wp_enqueue_script( 'mos-testimonial-functions.min', plugins_url( 'js/mos-testimonial-functions.min.js', __FILE__ ), array('jquery') );
	wp_enqueue_script( 'mos-testimonial.min', plugins_url( 'js/mos-testimonial.min.js', __FILE__ ), array('jquery') );
	$ajax_params = array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'ajax_nonce' => wp_create_nonce('mos_testimonial_verify'),
	);
	wp_localize_script( 'mos-testimonial', 'ajax_obj', $ajax_params );
}
add_action( 'wp_enqueue_scripts', 'mos_testimonial_enqueue_scripts' );

add_action( 'admin_enqueue_scripts', 'mos_testimonial_ajax_scripts' );
function mos_testimonial_scripts() {
	global $mos_testimonial_options;
	if (@$mos_testimonial_options['css']) {
		?>
		<style>
			<?php echo $mos_testimonial_options['css'] ?>
		</style>
		<?php
	}
	if (@$mos_testimonial_options['js']) {
		?>
		<script>
			<?php echo $mos_testimonial_options['js'] ?>
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'mos_testimonial_scripts', 100 );


function testimonial_func( $atts = array(), $content = '' ) {
	global $mos_testimonial_options;
	$html = '';
	$atts = shortcode_atts( array(
		'limit'				=> '-1',
		'offset'			=> 0,
		'category'			=> '',
		'tag'				=> '',
		'orderby'			=> '',
		'order'				=> '',
		'author'			=> '',
		'container'			=> 0,
		'container_class'	=> '',
		'class'				=> '',
		'singular'			=> 0,
		'pagination'		=> 0,
		'view'				=> 'block', //carousel
		'grid'				=> 1,
		'template'			=> 'template-1',
	), $atts, 'testimonials' );

	$cat = ($atts['category']) ? preg_replace('/\s+/', '', $atts['category']) : '';
	$tag = ($atts['tag']) ? preg_replace('/\s+/', '', $atts['tag']) : '';

	$args = array( 
		'post_type' 		=> 'testimonial',
		'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
	);
	$args['posts_per_page'] = $atts['limit'];
	if ($atts['offset']) $args['offset'] = $atts['offset'];

	if ($atts['category'] OR $atts['tag']) {
		$args['tax_query'] = array();
		if ($atts['category'] AND $atts['tag']) {
			$args['tax_query']['relation'] = 'OR';
		}
		if ($atts['category']) {
			$args['tax_query'][] = array(
					'taxonomy' => 'testimonial-category',
					'field'    => 'term_id',
					'terms'    => explode(',', $cat),
				);
		}
		if ($atts['tag']) {
			$args['tax_query'][] = array(
					'taxonomy' => 'testimonial-tag',
					'field'    => 'term_id',
					'terms'    => explode(',', $tag),
				);
		}
	}
	if ($atts['orderby']) $args['orderby'] = $atts['orderby'];
	if ($atts['order']) $args['order'] = $atts['order'];
	if ($atts['author']) $args['author'] = $atts['author'];	
	if ($atts['grid'] > 5 ) $atts['grid'] = 5;
	elseif ($atts['grid'] < 1 ) $atts['grid'] = 1;
	if ($atts['view'] == 'carousel') {
		$con_cls = ' owl-carousel owl-theme';
	}
	else {
		$con_cls = ' block-view' ;
	}
	$template_slice = $slices = explode("-",trim($atts['template']));
	$identity = $template_slice[1];
	$mos_testimonial_templates = get_option( 'mos_testimonial_templates' );
	$top_con = $mos_testimonial_templates['template'][$identity]['top_con'];
	$mid_lef_con = $mos_testimonial_templates['template'][$identity]['mid_lef_con'];
	$mid_cen_con = $mos_testimonial_templates['template'][$identity]['mid_cen_con'];
	$mid_right_con = $mos_testimonial_templates['template'][$identity]['mid_rig_con'];
	$bot_con = $mos_testimonial_templates['template'][$identity]['bot_con'];
	$query = new WP_Query( $args );
	$total_post = $query->post_count;
	$single_col = round( $total_post / $atts['grid'] );
	if ( $query->have_posts() ) :
		$idenfier = rand(10,1000);
		$n = 0;
		$html .= '<div id="mos-testimonial-'.$idenfier.'" class="mos-testimonial-container' . $con_cls . $atts['container_class'] . '">';
		if ($atts['view'] == 'block') $html .= '<div class="mos-testimonial-col-'.$atts['grid'] . '">';
		while ( $query->have_posts() ) : $query->the_post();			

			$html .= testimonial_print ( get_the_ID(), $atts['template'] );

			$n++;
			if ($n % $single_col == 0 AND $n < $total_post AND $atts['view'] == 'block') $html .= '</div><!--/.mos-testimonial-col-'.$atts['grid'] . '-->' . '<div class="mos-testimonial-col-'.$atts['grid'] . '">';
		endwhile;
		if ($atts['view'] == 'block') $html .= '</div><!--/.mos-testimonial-col-'.$atts['grid'] . '-->';
		$html .= '</div><!--/.mos-testimonial-container-->';

		wp_reset_postdata();
		if ($atts['pagination'] AND $atts['view'] == 'block') :
		    $html .= '<div class="pagination-wrapper testimonial-pagination">'; 
		        $html .= '<nav class="navigation pagination" role="navigation">';
		            $html .= '<div class="nav-links">'; 
		            $big = 999999999; // need an unlikely integer
		            $html .= paginate_links( array(
		                'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
		                'format' => '?paged=%#%',
		                'current' => max( 1, get_query_var('paged') ),
		                'total' => $query->max_num_pages,
		                'prev_text'          => __('Prev'),
		                'next_text'          => __('Next')
		            ) );
		            $html .= '</div>';
		        $html .= '</nav>';
		    $html .= '</div>';
		endif;
		if ($atts['view'] == 'carousel') :
			$layout = ($atts['grid']) ? $atts['grid'] : 3;
			$html .= '<script>';
				$html .= 'jQuery(document).ready(function($) {';
					$html .= 'var owl_testimonial_owl = $("#mos-testimonial-'.$idenfier.'");';
					$html .= 'owl_testimonial_owl.owlCarousel({loop: true, nav: true, dots: true, margin: 0, azyLoad: true,autoplay: true,autoplayTimeout: 6000,autoplayHoverPause: true,';
					if($layout ==1) :
						$html .= 'items:1,';
					else :
						$html .= 'responsive:{ 0: { items:1, }, 992: { items:2, }, 1200: { items: '.$layout.', } }';
					endif;
					$html .= '});';
				$html .= '});';
			$html .= '</script>';
		endif;
	endif;
	return $html;
}
add_shortcode( 'testimonials', 'testimonial_func' );
function testimonial_print ($post_id, $template) {
	$options = get_option( 'mos_testimonial_options' );
	$output = '';
	$disable = $header = $main = $left = $right = $footer = array();
	$elements = array('plasbo', 'Feature Image', 'Content', 'Video', 'Title', 'Designation', 'Rating', 'Excerpt');
	if ($template == "template-1") {
		$main = array(1,2,3,4,5,6);
	}
	elseif ($template == "template-2") {
		$main = array(1,7,3,4,5,6);
	}
	else {
		foreach ($options[$template] as $value) {
			$slice = explode(',', $value);
			if ($slice[0] == 'disable') $disable[] = $slice[1];
			elseif ($slice[0] == 'header') $header[] = $slice[1];
			elseif ($slice[0] == 'left') $left[] = $slice[1];
			elseif ($slice[0] == 'main') $main[] = $slice[1];
			elseif ($slice[0] == 'right') $right[] = $slice[1];
			elseif ($slice[0] == 'footer') $footer[] = $slice[1];
		}
	} 
	$output .= '<div class="testimonial-unit">'; 
		if (sizeof($header)) {
			$output .= '<div class="mos-testimonial-header">';
				foreach ($header as $value) {
					$output .= element_print ($value, get_the_ID());
				}
			$output .= '</div>';	
		}
		if (sizeof($left)) {
			$output .= '<div class="mos-testimonial-left">';
				foreach ($left as $value) {
					$output .= element_print ($value, get_the_ID());
				}
			$output .= '</div>';	
		}
		if (sizeof($main)) {
			$output .= '<div class="mos-testimonial-middle">';
				foreach ($main as $value) {
					$output .= element_print ($value, get_the_ID());
				}
			$output .= '</div>';	
		}
		if (sizeof($right)) {
			$output .= '<div class="mos-testimonial-right">';
				foreach ($right as $value) {
					$output .= element_print ($value, get_the_ID());
				}
			$output .= '</div>';	
		}
		if (sizeof($footer)) {
			$output .= '<div class="mos-testimonial-footer">';
				foreach ($footer as $value) {
					$output .= element_print ($value, get_the_ID());
				}
			$output .= '</div>';	
		}
	$output .= '</div>';
	return $output;
}
function element_print ($e_id, $post_id) {
	$output = '';
	if ($e_id == 1 AND has_post_thumbnail($post_id)) {
		$output .= '<div class="testimonial-image">'.get_the_post_thumbnail($post_id).'</div>'; 
	}
	elseif ($e_id == 2) {
		$content_post = get_post($post_id);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$output .= '<div class="testimonial-desc">'.$content.'</div>';
	}
	elseif ($e_id == 3) {
		if (get_post_meta( $post_id, '_mos_testimonial_oembed', true ))	{
			$output .= '<div class="embed-responsive embed-responsive-16by9">';
			$output .= '<iframe class="embed-responsive-item" src="'.get_post_meta( $post_id, '_mos_testimonial_oembed', true ).'"></iframe>';
			$output .= '</div>'; 
		}
	}
	elseif ($e_id == 4) {
		$url = get_post_meta( $post_id, $key = '_mos_testimonial_url', true );
		if ($url)
			$output = '<a href="'.$url.'"><h3 class="testimonial-title">' . get_the_title($post_id) . '</h3></a>';
		else
			$output = '<h3 class="testimonial-title">' . get_the_title($post_id) . '</h3>';
	}
	elseif ($e_id == 5) {
		$output .= '<span class="testimonial-designation">'.get_post_meta( $post_id, '_mos_testimonial_designation', true ).'</span>'; 
	}
	elseif ($e_id == 6) {
		$output .= '<span class="testimonial-rating">'.get_post_meta( $post_id, '_mos_testimonial_rating', true ).'</span>';
	}
	elseif ($e_id == 7) {
		$content_post = get_post($post_id);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = wp_trim_words($content, 25, '...');
		//$content = str_replace(']]>', ']]&gt;', $content);
		$output .= '<div class="testimonial-desc">'.$content.'</div>'; 
	}
	return $output;
}

