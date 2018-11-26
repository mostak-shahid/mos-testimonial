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
    //add_submenu_page( 'edit.php?post_type=p_file', 'Settings', 'Settings', 'manage_options', 'mos_testimonial_settings', 'mos_testimonial_admin_page' );
    add_submenu_page( 'options-general.php', 'Settings', 'Settings', 'manage_options', 'mos_testimonial_settings', 'mos_testimonial_admin_page' );
}
add_action("admin_menu", "mos_testimonial_admin_menu");
function mos_testimonial_admin_page () {
  //$option_prefix = "?post_type=p_file&page=mos_testimonial_settings&tab=";
  $option_prefix = admin_url() . "/options-general.php?page=mos_testimonial_settings&tab=";
  if (@$_GET['tab']) $active_tab = $_GET['tab'];
  elseif (@$_COOKIE['mos_testimonial_active_tab']) $active_tab = $_COOKIE['mos_testimonial_active_tab'];
  else $active_tab = 'dashboard';
  echo $active_tab;
	$mos_testimonial_option = get_option( 'mos_testimonial_option' );
	?>
	<div class="wrap mos-testimonial-wrapper">
        <h1><?php _e("Settings") ?></h1>
        <ul class="nav nav-tabs">
            <li class="tab-nav <?php if($active_tab == 'dashboard') echo 'active';?>"><a data-id="dashboard" href="<?php echo $option_prefix;?>dashboard">Dashboard</a></li>
            <li class="tab-nav <?php if($active_tab == 'scripts') echo 'active';?>"><a data-id="scripts" href="<?php echo $option_prefix;?>scripts">Scripts</a></li>
        </ul>
        <form method="post">
        	<div id="mos-testimonial-dashboard" class="tab-con <?php if($active_tab == 'dashboard') echo 'active';?>">
        		Plugin Details
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
                    <th scope="row">Bootstrap</th>
                    <td>
                      <fieldset>
                        <legend class="screen-reader-text"><span>Bootstrap</span></legend>
                        <label for="bootstrap"><input name="bootstrap" type="checkbox" id="bootstrap" value="1" <?php checked( $mos_testimonial_option['bootstrap'], 1 ) ?>>Yes I like to add Bootstrap from Plugin.</label>
                    </fieldset>
                    </td> 
                  </tr>
                  <tr>
                    <th scope="row">Font Awesome</th>
                    <td>
                      <fieldset>
                        <legend class="screen-reader-text"><span>Font Awesome</span></legend>
                        <label for="awesome"><input name="awesome" type="checkbox" id="awesome" value="1" <?php checked( $mos_testimonial_option['awesome'], 1 ) ?>>Yes I like to add Font Awesome from Plugin.</label>
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