<?php
/*
	Plugin Name: Easy Favicon
	Plugin URI: http://emyl.fr/ongame/poker/plugin-favicons/
	Version: 1
	Author: loane
	Author URI: http://www.webonews.fr
	Description: Choose a favicon to make your site eye-catching and easily recognizable by visitors. 
	You can choose to use  as favicon your gravatar, a private icon or an image from the Icon Set included.
	*/

	/*
	Copyright 2011  Loan  (http://www.webonews.fr)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
	/**
	 * Called upon activation of the plugin. Sets some options.
	 */

	function initfavicons(){
		add_option('myfavicon', 'http://');  	
		add_option('icontype', '1');  	
		add_option('iconset', '1264280850_wordpress.ico');
	}

	/**
	 * Called upon deactivation of the plugin. Cleans our mess.
	 */

	function destroyfavicons(){
		delete_option('myfavicon');
		delete_option('icontype');
		delete_option('iconset');
	}	

	/**
	 * For the header.
	 */

	function headerfavicons(){
		if (get_option('icontype') == '1') {
			$avatar = get_avatar( get_bloginfo('admin_email'), '16' );
			$openPos = strpos($avatar, 'src=\'');
			$closePos = strpos(substr($avatar, ($openPos+5)), '\'');
			$iconimage = substr($avatar, ($openPos+5), ($closePos-($openPos+5)) );
		} else {
			$iconimage = get_option('myfavicon');
		};

		switch (get_option('icontype')) {
			case 1 :
			echo '<!--[if IE]><link rel="SHORTCUT ICON" type=\'image/x-icon\' href="'.get_bloginfo('wpurl').'/wp-content/plugins/easy-favicon/icons/'.get_option('iconset').'" /><![endif]-->';
			echo '<link rel="ICON" type=\'image/png\' href="'.$iconimage.'" />';
			break;

			case 2 :
			echo '<link rel="SHORTCUT ICON" type=\'image/x-icon\' href="'.$iconimage.'" />';
			break;

			case 3 :
			echo '<link rel="SHORTCUT ICON" type=\'image/x-icon\' href="'.get_bloginfo('wpurl').'/wp-content/plugins/easy-favicon/icons/'.get_option('iconset').'" />';
			break;
			}
	}

	/**
	 * Outputs the HTML form for the admin area. Also updates the options.
	 */

	function adminFormfavicons(){
		if($_POST['action'] == 'save'){
			$ok = false;
			if($_POST['icontype']){
				update_option('icontype', $_POST['icontype']);
				$ok = true;
			}	
			if($_POST['iconset']){
				update_option('iconset', $_POST['iconset']);
				$ok = true;
			}
			if($_POST['myfavicon']){
				update_option('myfavicon', $_POST['myfavicon']);
				$ok = true;
			}
			if($ok){
				?>
				<div id="message" class="updated fade">
					<p>Changes have been saved </p>
				</div>
				<?php
			}
			else{
				?>
				<div id="message" class="error fade">
					<p>An error has occurred</p>
				</div>
				<?php
			}
		}

		// get the options values
		$myfavicon = get_option('myfavicon'); 
		$icontype = get_option('icontype');
		$iconset = get_option('iconset');
		?>

		<div class="wrap">
			<img src="<?php get_bloginfo('wpurl') ?>/wp-content/plugins/easy-favicon/favicons.gif">
			<div id="dashboard-widgets-wrap">
				<div id='dashboard-widgets' class='metabox-holder'>
				<div class='postbox-container' style='width:99%;'>
					<div id='normal-sortables' class='meta-box-sortables'>
						<div id="dashboard_right_now" class="postbox " >
							<h3 class='hndle'><span>easy-favicon settings</span></h3>
							<form method="post">
								<table class="form-table">
									<tr valign="top">
										<th scope="row" width="30%">
											<label for="stylenu"><b>Use your gravatar as favicon</b></label><br/>
											<span style="font-size:10px;"><b>Important :</b> Your gravatar will be displayed by all navigators except by Internet Explorer that accepts only images with format .ico.<br/>
												The plugin will make appear on IE the icon you chose in the Icon Set. On others, your gravatar will appear.</span>
										</th>
										<td width="60%">
											<label>
												<input <?php echo (($icontype == "1")? 'checked="checked"' : ""); ?> name="icontype" id="icontype" type="radio" value="1" />
											</label>
											<label>
												<?php echo get_avatar( get_bloginfo('admin_email'), '32' ); ?>
											</label>
										</td>
										<td width="10%">
											<a href="http://www.gravatar.com/" target="_blank" rel="nofollow">Get a Gravatar</a>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="show"><b>If you want to use your own icon</b></label><br/>
											16*16px recomended
										</th>
										<td>
											<input <?php echo (($icontype == "2")? 'checked="checked"' : ""); ?> name="icontype" id="icontype" type="radio" value="2" />
											<label>
												<input name="myfavicon" type="text" id="myfavicon" value="<?php echo $myfavicon ;?>" class="regular-text code" />
											</label>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="show"><b>Use an icon from the icon set</b></label><br>
											(icons will be displayed bigger in navigators)
										</th>
										<td>
											<label>
												<input <?php echo (($icontype == "3")? 'checked="checked"' : ""); ?> name="icontype" id="icontype" type="radio" value="3" />
											</label>
											<label>
												<?php $rep = WP_CONTENT_DIR . '/plugins/easy-favicon/icons/';
												$dir = opendir($rep); $xligne = 0;
												echo '<table><tr>';
												while ($ficon = readdir($dir)) { 
													if(is_file($rep.$ficon)) {
														$xligne = $xligne+1;
														echo '<td>';
														echo '<img src="'.get_bloginfo('wpurl').'/wp-content/plugins/easy-favicon/icons/'.$ficon.'" width="16"><br/>';
														echo '<input '.(($iconset == $ficon)? 'checked="checked"' : "").' name="iconset" id="iconset" type="radio" value="'.$ficon.'" />';
														echo '<td>';
														if ($xligne=="7") { echo '</tr><tr>'; $xligne=0; };
													}
												};
												echo '</tr></table>';
												closedir($dir);
												?>
											</label>
										</td>
									</tr>
								</table>
								<br/>
								<br/>
								<p class="submit">
									<input type="hidden" name="action" value="save" />
									<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
								</p>
							</form>
						</div>
					</div>
				</div>
				<div class='postbox-container' style='display:none;'>
					<div id='column3-sortables' class='meta-box-sortables'>
					</div>
				</div>
				<div class='postbox-container' style='display:none;'>
					<div id='column4-sortables' class='meta-box-sortables'>
					</div>
				</div>
			</div>
			<div class="clear">
			</div>
		</div><!-- dashboard-widgets-wrap -->
		<b>You can ask support on <a href="http://emyl.fr/ongame/poker/plugin-favicons/" target="_blank">Plugin Page</a>. <br/><br/></b>
		<b>If you appreciate the plugin, please <a href="http://wordpress.org/extend/plugins/easy-favicon/" target="_blank">Rate it</a> on Wordpress. <br/><br/></b>
		<b>My other wordpress plugin : <a href="http://wordpress.org/extend/plugins/fixed-social-buttons/" target="_blank">Fixed social buttons</a><br/><br/></b>
		<ul><li>Let your visitors make your site popular by sharing your pages thanks to fixed social buttons. This plugin will add colorfull and attractiv social buttons on right side : twitter, facebook, myspace, reddit, delicious, technorati, digg, linkedin, flickr and rss feed buttons.
		</li></ul>
		</div>
		<?php
	}

	/**
	 * Adds the sub menu in the admin panel
	 */

	function adminfavicons(){
		add_options_page('Easy Favicon Administration', 'Easy Favicon', 'manage_options', __FILE__, 'adminFormfavicons');
	}
	// upon activation of the plugin, calls the initfavicons function
	register_activation_hook(__FILE__, 'initfavicons');
	// upon deactivation of the plugin, calls the destroyfavicons function
	register_deactivation_hook(__FILE__, 'destroyfavicons');
	// what to add in the header, calls the headerfavicons function
	add_action('wp_head', headerfavicons, 1);
	add_action('admin_head', headerfavicons, 1);
	add_action('login_head', headerfavicons, 1);	
	// ads the submenu in the admin menu
	add_action('admin_menu', 'adminfavicons');
?>

