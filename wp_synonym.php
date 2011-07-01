<?php
/* 
Plugin Name: WP Synonym Plugin
Plugin URI: http://wordpress.org/extend/plugins/wp-synonym-plugin/
Description: Go to your post editing page and look up a word using the form to the right (under <em>WP Synonym Plugin</em>).
Version: 1.5
Author: Ben Koren	
*/

/*  Copyright 2010 Ben Koren
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function install_wp_synonym_plugin_js_file() {
	?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		
		function mySynonymSetText(c_name, value) {
			$("#wp_synonym_from_" + c_name + "_container").text(value);
		}
		
		function mySynonymGetText(c_name) {
			return $("#wp_synonym_from_" + c_name + "_container").text();
		}
		
		function get_list_from_xml_response(xml){ 
			if(xml.length==0) return false;
			
			if($(xml).find('w').length==0) return false;

			response = '';
			
			response+= '<style type="text/css">';
			response+= '.response-word-list-container{ font-family: Helvetica, Arial, sans-serif; font-size: 12px; margin: 5px; padding: 0; } ';
			response+= '.response-word-list { padding: 0; max-height: 300px; overflow: auto; } ';
			response+= '.response-word { margin:0; padding: 0; list-style: none; } ';
			response+= '.synonymous-word { color: #21759b; text-decoration: none; } ';
			response+= '.synonymous-word:hover { border-bottom: 1px dotted #21759b; } ';
			response+= '.response-word-list-container p { padding: 0; margin: 5px; color: #7c7c7c; font-size: 13px; font-style: italic; font-family: Georgia, Garamond, serif; text-align: center; } ';
			response+= '.synoynm-word-decoration { font-size: 30px; } ';
			response+= '.synonym-list-copyright { font-size: 9px; vertical-align: top; } ';
			response+= '</style>';
			response+= '<div class="response-word-list-container"><ul class="response-word-list">';
			
			$(xml).find('w').each(function(){
				if($(this).attr('r')!='ant')
					response+= '<li class="response-word"><a title="synonym" class="synonymous-word" href="#">'+$(this).text()+'</a></li>';
			});
			response+= '</ul></div>';
			$('#wp_synonym_list_container').text("");
			$('#wp_synonym_list_container').append(response);
			
			return true;
		}
		
		function get_synonyms(text){ 
			var url = '<?php echo get_bloginfo('wpurl').'/'. PLUGINDIR .'/' . dirname( plugin_basename(__FILE__)); ?>/xml_response.php?word='+text;
			$.get(url, {}, get_list_from_xml_response);
		} 

		$("#wp_synonym_submit").click(function() {
			get_synonyms($("#wp_synonym_lookup").val());
		});

	});
	</script>
	<?php
}
add_action('admin_head','install_wp_synonym_plugin_js_file',99);

function WP_Synonym_Plugin_meta_box_contents($post,$box) {
    ?>
	<div id="wp_synonym_form">
		<input type="text" value="Type a word" size="35" name="wp_synonym_lookup" id="wp_synonym_lookup" style="color: #888" onclick="if(this.value == 'Type a word') $(this).val('');" onblur="if(this.value == '') $(this).val('Type a word');" />
		<input type="button" value="Search" class="button" id="wp_synonym_submit" />
	</div>
	<div id="wp_synonym_list_container">
	</div>
	<?php
}

# meta box functions for adding the meta box and saving the data
function WP_Synonym_Plugin_load_meta_box() {
    # create our custom meta box
    add_meta_box('WP_Synonym_Plugin_meta_box' ,__('WP Synonym Plugin',I18N), 'WP_Synonym_Plugin_meta_box_contents','post','side','high'); 
}

# add a call to meta box init
add_action('admin_init','WP_Synonym_Plugin_load_meta_box');

