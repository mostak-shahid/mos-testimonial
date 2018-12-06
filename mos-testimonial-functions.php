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

		wp_enqueue_script( 'mos-testimonial-functions', plugins_url( 'js/mos-testimonial-functions.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'mos-testimonial-admin', plugins_url( 'js/mos-testimonial-admin.js', __FILE__ ), array('jquery') );
	}

}
add_action( 'admin_enqueue_scripts', 'mos_testimonial_admin_enqueue_scripts' );
function mos_testimonial_enqueue_scripts(){
	global $mos_testimonial_option;
	if ($mos_testimonial_option['jquery']) {
		wp_enqueue_script( 'jquery' );
	}
	if ($mos_testimonial_option['bootstrap']) {
		wp_enqueue_style( 'bootstrap.min', plugins_url( 'css/bootstrap.min.css', __FILE__ ) );
		wp_enqueue_script( 'bootstrap.min', plugins_url( 'js/bootstrap.min.js', __FILE__ ), array('jquery') );
	}
	if ($mos_testimonial_option['awesome']) {
		wp_enqueue_style( 'font-awesome.min', plugins_url( 'fonts/font-awesome-4.7.0/css/font-awesome.min.css', __FILE__ ) );
	}
	wp_enqueue_style( 'mos-testimonial', plugins_url( 'css/mos-testimonial.css', __FILE__ ) );
	wp_enqueue_script( 'mos-testimonial-functions', plugins_url( 'js/mos-testimonial-functions.js', __FILE__ ), array('jquery') );
	wp_enqueue_script( 'mos-testimonial', plugins_url( 'js/mos-testimonial.js', __FILE__ ), array('jquery') );
	$ajax_params = array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'ajax_nonce' => wp_create_nonce('mos_testimonial_verify'),
	);
	wp_localize_script( 'mos-testimonial', 'ajax_obj', $ajax_params );
}
add_action( 'wp_enqueue_scripts', 'mos_testimonial_enqueue_scripts' );
function mos_testimonial_ajax_scripts(){
	wp_enqueue_script( 'mos-testimonial-ajax', plugins_url( 'js/mos-testimonial-ajax.js', __FILE__ ), array('jquery') );
	$ajax_params = array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'ajax_nonce' => wp_create_nonce('mos_testimonial_verify'),
	);
	wp_localize_script( 'mos-testimonial-ajax', 'ajax_obj', $ajax_params );
}
add_action( 'wp_enqueue_scripts', 'mos_testimonial_ajax_scripts' );
add_action( 'admin_enqueue_scripts', 'mos_testimonial_ajax_scripts' );
function mos_testimonial_scripts() {
	global $mos_testimonial_option;
	if ($mos_testimonial_option['css']) {
		?>
		<style>
			<?php echo $mos_testimonial_option['css'] ?>
		</style>
		<?php
	}
	if ($mos_testimonial_option['js']) {
		?>
		<style>
			<?php echo $mos_testimonial_option['js'] ?>
		</style>
		<?php
	}
}
add_action( 'wp_footer', 'mos_testimonial_scripts', 100 );


function testimonial_func( $atts = array(), $content = '' ) {

	$html = '';
	$atts = shortcode_atts( array(
		'limit'				=> '-1',
		'offset'			=> 0,
		'category'			=> '',
		'tag'				=> '',
		'orderby'			=> '',
		'order'				=> '',
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
	if ($atts['view'] == 'carousel') {
		$con_cls = ' owl-carousel owl-theme';
	}
	else {
		$con_cls = '' ;
	}
	// var_dump($args);
	// die();
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		$idenfier = rand(10,1000);
		$html .= '<div id="mos-testimonial-'.$idenfier.'" class="mos-testimonial-container' . $con_cls . $atts['container_class'] . '">';
		while ( $query->have_posts() ) : $query->the_post();			

			$html .= '<div class="testimonial-unit">';
			$html .= '<div class="top">'.$top_con.'</div>';
			$html .= '<div class="middle">';
			$html .= '<div class="left">'.$mid_lef_con.'</div>';
			$html .= '<div class="center">'.$mid_cen_con.'</div>';
			$html .= '<div class="right">'.$mid_right_con.'</div>';
			$html .= '<div class="bottom">'.$bot_con.'</div>';
			$html .= '</div>';
			$html .= '</div>';

		endwhile;
		$html .= '</div><!--/.mos-testimonial-container-->';

		wp_reset_postdata();
		if ($atts['pagination'] AND $atts['view'] = 'block') :
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
	endif;
	return $html;
}
add_shortcode( 'testimonials', 'testimonial_func' );
