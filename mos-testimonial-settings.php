<?php
function mos_testimonial_settings_init() {
	register_setting( 'mos_testimonial', 'mos_testimonial_options' );
	add_settings_section('mos_testimonial_section_top_nav', '', 'mos_testimonial_section_top_nav_cb', 'mos_testimonial');
	add_settings_section('mos_testimonial_section_dash_start', '', 'mos_testimonial_section_dash_start_cb', 'mos_testimonial');
	add_settings_section('mos_testimonial_section_dash_end', '', 'mos_testimonial_section_end_cb', 'mos_testimonial');

	add_settings_section('mos_testimonial_section_layout_start', '', 'mos_testimonial_section_layout_start_cb', 'mos_testimonial');
	add_settings_section('mos_testimonial_section_layout_end', '', 'mos_testimonial_section_end_cb', 'mos_testimonial');

	add_settings_section('mos_testimonial_section_scripts_start', '', 'mos_testimonial_section_scripts_start_cb', 'mos_testimonial');
	add_settings_field( 'field_jquery', __( 'JQuery', 'mos_testimonial' ), 'mos_testimonial_field_jquery_cb', 'mos_testimonial', 'mos_testimonial_section_scripts_start', [ 'label_for' => 'jquery'] );
	add_settings_field( 'field_owl_carousel', __( 'Owl Carousel', 'mos_testimonial' ), 'mos_testimonial_field_owl_carousel_cb', 'mos_testimonial', 'mos_testimonial_section_scripts_start', [ 'label_for' => 'owl_carousel' ] );
	add_settings_field( 'field_css', __( 'Custom Css', 'mos_testimonial' ), 'mos_testimonial_field_css_cb', 'mos_testimonial', 'mos_testimonial_section_scripts_start', [ 'label_for' => 'mos_testimonial_css' ] );
	add_settings_field( 'field_js', __( 'Custom Js', 'mos_testimonial' ), 'mos_testimonial_field_js_cb', 'mos_testimonial', 'mos_testimonial_section_scripts_start', [ 'label_for' => 'mos_testimonial_js' ] );
	add_settings_section('mos_testimonial_section_scripts_end', '', 'mos_testimonial_section_end_cb', 'mos_testimonial');

}
add_action( 'admin_init', 'mos_testimonial_settings_init' );

function get_mos_testimonial_active_tab () {
	$output = array(
		'option_prefix' => admin_url() . "/options-general.php?page=mos_testimonial_settings&tab=",
//'option_prefix' = "?post_type=p_file&page=mos_testimonial_settings&tab=",
	);
	if (isset($_GET['tab'])) $active_tab = $_GET['tab'];
	elseif (isset($_COOKIE['testimonial_active_tab'])) $active_tab = $_COOKIE['testimonial_active_tab'];
	else $active_tab = 'dashboard';
	$output['active_tab'] = $active_tab;
	return $output;
}
function mos_testimonial_section_top_nav_cb( $args ) {
	$data = get_mos_testimonial_active_tab ();
	?>
	<ul class="nav nav-tabs">
		<li class="tab-nav <?php if($data['active_tab'] == 'dashboard') echo 'active';?>"><a data-id="dashboard" href="<?php echo $data['option_prefix'];?>dashboard">Dashboard</a></li>
		<li class="tab-nav <?php if($data['active_tab'] == 'layout') echo 'active';?>"><a data-id="layout" href="<?php echo $data['option_prefix'];?>dashboard">Layout</a></li>
		<li class="tab-nav <?php if($data['active_tab'] == 'scripts') echo 'active';?>"><a data-id="scripts" href="<?php echo $data['option_prefix'];?>scripts">Scripts</a></li>
	</ul>
	<?php
}
function mos_testimonial_section_dash_start_cb( $args ) {
	$data = get_mos_testimonial_active_tab ();
	$options = get_option( 'mos_testimonial_options' );
	?>
	<div id="mos-testimonial-dashboard" class="tab-con <?php if($data['active_tab'] == 'dashboard') echo 'active';?>">
		<?php //var_dump($options) ?>
		<h2><?php esc_html_e('Dashboard') ?></h2>
		<p><strong>For using testimonials in your post or page use this shortcode</strong></p>
		<p>[testimonials]</p>
		<h3>Properties</h3>
		<dl>
			<dt>
				<tt>limit</tt>
			</dt>
			<dd>(int) - number of post to show per page. Use 'limit'=-1 to show all posts (the 'offset' parameter is ignored with a -1 value).</dd>
			<dt>
				<tt>offset</tt>
			</dt>
			<dd>(int) - number of post to displace or pass over. Warning: Setting the offset parameter overrides/ignores the paged parameter and breaks pagination. The 'offset' parameter is ignored when 'limit'=-1 (show all posts) is used.</dd>	
								
			<dt>
				<tt>category</tt>
			</dt>
			<dd>(int) - category ids from where you like to display posts. Please seperate ids by comma (,). </dd>
								
			<dt>
				<tt>tag</tt>
			</dt>
			<dd>(int) - tag ids from where you like to display posts. Please seperate ids by comma (,). </dd>
								
			<dt>
				<tt>order</tt>
			</dt>
			<dd>
				(string | array) - Designates the ascending or descending order of the 'orderby' parameter. Defaults to 'DESC'. An array can be used for multiple order/orderby sets
				<ol>
					<li>'ASC' - ascending order from lowest to highest values (1, 2, 3; a, b, c).</li>
					<li>'DESC' - descending order from highest to lowest values (3, 2, 1; c, b, a).</li>
				</ol>
			</dd>
								
			<dt>
				<tt>orderby</tt>
			</dt>
			<dd>
				(string | array) - Sort retrieved posts by parameter. Defaults to 'date (post_date)'. One or more options can be passed.
				<ol>
					<li>'none' - No order</li>
					<li>'ID' - Order by post id. Note the capitalization.</li>
					<li>'author' - Order by author. ('post_author' is also accepted.)</li>
					<li>'title' - Order by title. ('post_title' is also accepted.)</li>
					<li>'name' - Order by post name (post slug). ('post_name' is also accepted.)</li>
					<li>'type' - Order by post type. ('post_type' is also accepted.)</li>
					<li>'date' - Order by date. ('post_date' is also accepted.)</li>
					<li>'modified' - Order by last modified date. ('post_modified' is also accepted.)</li>
					<li>'parent' - Order by post/page parent id. ('post_parent' is also accepted.)</li>
					<li>'rand' - Random order. You can also use 'RAND(x)' where 'x' is an integer seed value.</li>
					<li>'comment_count' - Order by number of comments.</li>
				</ol>
			</dd>
								
			<dt>
				<tt>author</tt>
			</dt>
			<dd>(int | string) - use author id or comma-separated list of IDs.</dd>
								
			<dt>
				<tt>container</tt>
			</dt>
			<dd>(boolean) - Whether or not to include wrapper.</dd>
								
			<dt>
				<tt>container_class</tt>
			</dt>
			<dd>(string) - Class that is applied to the container.</dd>
								
			<dt>
				<tt>class</tt>
			</dt>
			<dd>(string) - Class that is applied to the faq body.</dd>
								
			<dt>
				<tt>singular</tt>
			</dt>
			<dd>(boolean) - Whether or not to allow to open singularly.</dd>
								
			<dt>
				<tt>pagination</tt>
			</dt>
			<dd>(boolean) - Whether or not to include pagination.</dd>
								
			<dt>
				<tt>grid</tt>
			</dt>
			<dd>(string) - Range from 1 to 5.</dd>
								
			<dt>
				<tt>view</tt>
			</dt>
			<dd>(string) - testimonials can be viewed in like block or carousel.</dd>
								
			<dt>
				<tt>template</tt>
			</dt>
			<dd>(string) - template-1, template-3, template-3.</dd>
		</dl>
		<?php
}
function mos_testimonial_section_layout_start_cb( $args ) {
	$data = get_mos_testimonial_active_tab ();
	?>
	<div id="mos-testimonial-layout" class="tab-con <?php if($data['active_tab'] == 'layout') echo 'active';?>">		
		<h2><?php esc_html_e('Templates') ?></h2>
		<div class="gruop-container">
			<?php 
				$templates = get_option( 'mos_testimonial_templates' );
				foreach ($templates['template'] as $key => $value) : ?>
				<div class="group-unit">
					<table border="1">
						<caption>template-<?php echo $key ?></caption>
						<tr>
							<td colspan="3" valign="middle"><?php if ($value['top_con']) echo $value['top_con']; else echo '&nbsp;'; ?></td>
						</tr>
						<tr>
							<td valign="middle"><?php if ($value['mid_lef_con']) echo $value['mid_lef_con']; else echo '&nbsp;'; ?></td>
							<td valign="middle"><?php if ($value['mid_cen_con']) echo $value['mid_cen_con']; else echo '&nbsp;'; ?></td>
							<td valign="middle"><?php if ($value['mid_rig_con']) echo $value['mid_rig_con']; else echo '&nbsp;'; ?></td>
						</tr>
						<tr>
							<td colspan="3" valign="middle"><?php if ($value['bot_con']) echo $value['bot_con']; else echo '&nbsp;'; ?></td>
						</tr>
					</table>  
				</div>
			<?php endforeach; ?>
			<input type="hidden" value="<?php echo $key + 1 ?>">
		</div>
		<?php
}
function mos_testimonial_section_scripts_start_cb( $args ) {
	$data = get_mos_testimonial_active_tab ();
	?>
	<div id="mos-testimonial-scripts" class="tab-con <?php if($data['active_tab'] == 'scripts') echo 'active';?>">		
		<h2><?php esc_html_e('Scripts') ?></h2>
		<?php
}
function mos_testimonial_field_jquery_cb( $args ) {
	$options = get_option( 'mos_testimonial_options' );
	?>
	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"><input name="mos_testimonial_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="1" <?php echo isset( $options[ $args['label_for'] ] ) ? ( checked( $options[ $args['label_for'] ], 1, false ) ) : ( '' ); ?>><?php esc_html_e( 'Yes I like to add JQuery from Plugin.', 'mos_testimonial' ); ?></label>
	<?php
}
function mos_testimonial_field_owl_carousel_cb( $args ) {
	$options = get_option( 'mos_testimonial_options' );
	?>
	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"><input name="mos_testimonial_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="1" <?php echo isset( $options[ $args['label_for'] ] ) ? ( checked( $options[ $args['label_for'] ], 1, false ) ) : ( '' ); ?>><?php esc_html_e( 'Yes I like to add Owl Carousel from Plugin.', 'mos_testimonial' ); ?></label>
	<?php
}
function mos_testimonial_field_css_cb( $args ) {
	$options = get_option( 'mos_testimonial_options' );
	?>
	<textarea name="mos_testimonial_options[<?php echo esc_attr( $args['label_for'] ); ?>]" id="<?php echo esc_attr( $args['label_for'] ); ?>" rows="10" class="regular-text"><?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?></textarea>
	<script>
		var editor = CodeMirror.fromTextArea(document.getElementById("mos_testimonial_css"), {
			lineNumbers: true,
			mode: "text/css",
			extraKeys: {"Ctrl-Space": "autocomplete"}
		});
	</script>
	<?php
}
function mos_testimonial_field_js_cb( $args ) {
	$options = get_option( 'mos_testimonial_options' );
	?>
	<textarea name="mos_testimonial_options[<?php echo esc_attr( $args['label_for'] ); ?>]" id="<?php echo esc_attr( $args['label_for'] ); ?>" rows="10" class="regular-text"><?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?></textarea>
	<script>
		var editor = CodeMirror.fromTextArea(document.getElementById("mos_testimonial_js"), {
			lineNumbers: true,
			mode: "text/css",
			extraKeys: {"Ctrl-Space": "autocomplete"}
		});
	</script>
	<?php
}
function mos_testimonial_section_end_cb( $args ) {
	$data = get_mos_testimonial_active_tab ();
	?>
</div>
<?php
}


function mos_testimonial_admin_menu () {
	add_submenu_page( 'edit.php?post_type=testimonial', 'Settings', 'Settings', 'manage_options', 'mos_testimonial_settings', 'mos_testimonial_admin_page' );
//add_submenu_page( 'options-general.php', 'Settings', 'Settings', 'manage_options', 'mos_testimonial_settings', 'mos_testimonial_admin_page' );
}
add_action("admin_menu", "mos_testimonial_admin_menu");
function mos_testimonial_admin_page () {
	$option_prefix = "?post_type=testimonial&page=mos_testimonial_settings&tab=";
	if (@$_GET['tab']) $active_tab = $_GET['tab'];
	elseif (@$_COOKIE['testimonial_active_tab']) $active_tab = $_COOKIE['testimonial_active_tab'];
	else $active_tab = 'dashboard';
	$mos_testimonial_options = get_option( 'mos_testimonial_options' );

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'mos_testimonial_messages', 'mos_testimonial_message', __( 'Settings Saved', 'mos_testimonial' ), 'updated' );
	}
	settings_errors( 'mos_testimonial_messages' );
	?>
	<div class="wrap mos-testimonial-wrapper">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'mos_testimonial' );
			do_settings_sections( 'mos_testimonial' );
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
	<?php
}