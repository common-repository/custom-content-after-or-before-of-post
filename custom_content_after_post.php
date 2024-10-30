<?php
/*
Plugin Name: Custom content after or before post
Plugin URI: http://blog.casanova.vn/plugin-custom-content-afterbefore-of-the-post/
Description: Custom content after post. Support all HTML tags 
Author: Nguyen Duc Manh
Version: 1.0
Author URI: http://casanova.vn
*/

//add setting menu
add_action('admin_menu', 'csnv_menu');
/* What to do when the plugin is activated? */
register_activation_hook(__FILE__,'csnv_plugin_install');
/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'csnv_plugin_remove' );


/************Admin Panel***********/
function csnv_plugin_remove(){
	delete_option('csnv_html_code');
	delete_option('csnv_position');
}
function csnv_plugin_install(){
	add_option('csnv_html_code','<blockquote>Your html code will go here</blockquote>');
	add_option('csnv_position','after');
}

function csnv_menu() {
	add_options_page( __('Custom content after or before post',''), __('Custom content after or before post',''), 8, basename(__FILE__), 'my_setting');
}
function my_setting(){
		if($_POST['status_submit']==1){			
			update_option('csnv_html_code',(stripslashes($_POST['csnv_html_code'])));
			update_option('csnv_position',trim($_POST['csnv_position']));
			
			echo '<div id="message" class="updated fade"><p>Your settings were saved !</p></div>';
		}
		elseif($_POST['status_submit']==2){// Reset to default
			update_option('csnv_html_code','<blockquote>Your html code will go here</blockquote>');
			update_option('csnv_position','after');
			echo '<div id="message" class="updated fade"><p>Your settings were reset !</p></div>';
		}

	?>
	<h2>Custom content after or before post Setting</h2>
	<form method="post" id="csnv_options">	
    	<input type="hidden" name="status_submit" id="status_submit" value="2"  />
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
        	<tr valign="top"> 
				<td  scope="row" width="150">Display position</td> 
				<td scope="row">			
					<label><input type="radio" name="csnv_position" value="before" <?php if(get_option('csnv_position')=='before') echo "checked"; ?> /> Before of the post</label><br />
                    <label><input type="radio" name="csnv_position" value="after" <?php if(get_option('csnv_position')=='after') echo "checked"; ?> /> After of the post</label><br />
                    <label><input type="radio" name="csnv_position" value="both" <?php if(get_option('csnv_position')=='both') echo "checked"; ?> /> Both</label>	
				</td> 
			</tr>
            
            <tr valign="top"> 
				<td  scope="row">HTML Code:<br/><small>Put HTML code here</small></td> 
				<td scope="row">			
					<textarea name="csnv_html_code" rows="7" cols="60"><?php echo (get_option('csnv_html_code'));?></textarea>	
				</td> 
			</tr>
             <tr valign="top"> 
				<td  scope="row"></td> 
				<td scope="row">			
					<input type="button" name="save" onclick="document.getElementById('status_submit').value='1'; document.getElementById('csnv_options').submit();" value="Save setting" class="button-primary" />
				</td> 
			</tr>
            <tr><td colspan="2"><br /><br /></td></tr>
            <tr valign="top"> 
				<td  scope="row"></td> 
				<td scope="row">			
					<input type="button" name="reset" onclick="document.getElementById('status_submit').value='2'; document.getElementById('csnv_options').submit();" value="Reset to default setting" class="button" />
				</td> 
			</tr>
		</table>
        
	</form>	
	<?php
}

function custom_content_after_post($content){
	if (is_single()) { 
		if(get_option('csnv_position')=="after"){
	    	$content .= (get_option('csnv_html_code'));
		}
		elseif(get_option('csnv_position')=="before"){
			$content = (get_option('csnv_html_code')).$content;	
		}
		elseif(get_option('csnv_position')=="both"){
			$content = (get_option('csnv_html_code')).$content.(get_option('csnv_html_code'));		
		}
	}
	return $content;	
}

add_filter( "the_content", "custom_content_after_post" );
?>