<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" ) {  
    if ($_POST['mos_testimonial_submit'] == 'Save Changes') {
	    $mos_testimonial_option = array();
	    foreach ($_POST as $field => $value) {
	    	$mos_testimonial_option[$field] = trim($value);
	    }
	    update_option( 'mos_testimonial_option', $mos_testimonial_option, false );
	}
}

function mos_testimonial_admin_menu () {
    add_submenu_page( 'edit.php?post_type=testimonial', 'Settings', 'Settings', 'manage_options', 'mos_testimonial_settings', 'mos_testimonial_admin_page' );
    //add_submenu_page( 'options-general.php', 'Settings', 'Settings', 'manage_options', 'mos_testimonial_settings', 'mos_testimonial_admin_page' );
}
add_action("admin_menu", "mos_testimonial_admin_menu");
function mos_testimonial_admin_page () {
  $option_prefix = "?post_type=testimonial&page=mos_testimonial_settings&tab=";
  //$option_prefix = admin_url() . "/options-general.php?page=mos_testimonial_settings&tab=";
  if (@$_GET['tab']) $active_tab = $_GET['tab'];
  elseif (@$_COOKIE['testimonial_active_tab']) $active_tab = $_COOKIE['testimonial_active_tab'];
  else $active_tab = 'dashboard';
  //echo $active_tab;
	$mos_testimonial_option = get_option( 'mos_testimonial_option' );
	?>
	<div class="wrap mos-testimonial-wrapper">
        <h1><?php _e("Settings") ?></h1>
        <ul class="nav nav-tabs">
            <li class="tab-nav <?php if($active_tab == 'dashboard') echo 'active';?>"><a data-id="dashboard" href="<?php echo $option_prefix;?>dashboard">Dashboard</a></li>
            <li class="tab-nav <?php if($active_tab == 'layouts') echo 'active';?>"><a data-id="layouts" href="<?php echo $option_prefix;?>layouts">Layouts</a></li>
            <li class="tab-nav <?php if($active_tab == 'scripts') echo 'active';?>"><a data-id="scripts" href="<?php echo $option_prefix;?>scripts">Scripts</a></li>
        </ul>
        <form method="post">
          <div id="mos-testimonial-dashboard" class="tab-con <?php if($active_tab == 'dashboard') echo 'active';?>">
            Plugin Details
          </div>
        	<div id="mos-testimonial-layouts" class="tab-con <?php if($active_tab == 'layouts') echo 'active';?>">
            <div class="gruop-container">
            <?php 
            global $mos_testimonial_option;
            foreach ($mos_testimonial_option['template'] as $key => $value) : ?>
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
          </div>
          <div id="mos-testimonial-scripts" class="tab-con <?php if($active_tab == 'scripts') echo 'active';?>">
            <table class="form-table">
              <tbody>
                  <tr>
                    <th scope="row">JQuery</th>
                    <td>
                      <fieldset>
                        <legend class="screen-reader-text"><span>JQuery</span></legend>
                        <label for="jquery"><input name="jquery" type="checkbox" id="jquery" value="1" <?php checked( $mos_testimonial_option['jquery'], 1 ) ?>>Yes I like to add JQuery from Plugin.</label>
                    </fieldset>
                    </td> 
                  </tr>
                  <tr>
                    <th scope="row">Owl Carousel</th>
                    <td>
                      <fieldset>
                        <legend class="screen-reader-text"><span>Owl Carousel</span></legend>
                        <label for="owl-carousel"><input name="owl-carousel" type="checkbox" id="owl-carousel" value="1" <?php checked( $mos_testimonial_option['owl-carousel'], 1 ) ?>>Yes I like to add Owl Carousel from Plugin.</label>
                    </fieldset>
                    </td> 
                  </tr>
                  <tr>
                    <th scope="row"><label for="css">Css</label></th>
                    <td>
                      <textarea name="css" id="css"><?php echo @$mos_testimonial_option['css']; ?></textarea>
                      <script>
                        var editor = CodeMirror.fromTextArea(document.getElementById("css"), {
                          lineNumbers: true,
                          mode: "text/css",
                          extraKeys: {"Ctrl-Space": "autocomplete"}
                        });
                      </script>
                    </td>
                  </tr>
                  <tr>
                    <th scope="row"><label for="js">Js</label></th>
                    <td>
                      <textarea name="js" id="js"><?php echo @$mos_testimonial_option['js']; ?></textarea>

                      <script>
                        var editor = CodeMirror.fromTextArea(document.getElementById("js"), {
                          lineNumbers: true,
                          mode: "text/javascript",
                          extraKeys: {"Ctrl-Space": "autocomplete"}
                        });
                      </script>
                    </td>
                  </tr>
                </tbody> 
              </table>          
          </div>
	    	<p class="submit"><input type="submit" name="mos_testimonial_submit" id="submit" class="button button-primary" value="Save Changes"></p>
        </form>
    </div>
	<?php
}