<?php
/*
    Plugin Name: Workbox Google Analytics Plugin
    Author: Workbox Inc.
    Author URI: http://www.workbox.com/
    Plugin URI: http://blog.workbox.com/wordpress-plugin-google-analytics-track-files/
    Version: 1.0
    Description: Makes Google Analytics track clicks to any type of file you host on your web server.
    
    == Copyright ==
    Copyright 2008-2010 Workbox Inc (email: support@workbox.com)
    
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
*/



register_activation_hook( __FILE__, array('workbox_ga','activate') );
register_deactivation_hook( __FILE__, array('workbox_ga','deactivate') );

add_action('admin_menu', array('workbox_ga','admin_menu'));
add_filter('wp_head',array('workbox_ga','show_js'));
add_action('admin_init',array('workbox_ga','save_options'));

class workbox_ga
{
    public function activate()
    {
        $options = array(
            'ga_id'=>'',
            'include_jquery'=>false,
            'track_ext'=>array('pdf','doc','docx'),
            'is_enabled'=>false,
            'domain'=>$_SERVER['HTTP_HOST']
        );
        
        add_option("wb_ga_options", $options, 'Workbox Google Analytics Plugin Options', 'yes');
    }
    
    public function deactivate()
    {
        delete_option("wb_ga_options");
    }
    
    public function admin_menu()
    {
        add_options_page('Google Analytics Options', 'Google Analytics Options', 'manage_options', 'workbox_ga', array('workbox_ga','options'));
    }
    
    public function options()
    {
        $option = get_option('wb_ga_options');
        
        $html = '';
        
        $html.='<div class="wrap">';
	$html.='<h2>Google Analytics Options</h2><br>';
	$html.='
            <form name="optionsForm" method="post" onsubmit="return confirm(\'Do you really want to update these options?\')">
            <input type="hidden" name="workbox_options_ga_flag" value="set">
            <table border="0">
                <tr>
                    <td width="250" align="right"></b>Google Analytics ID</b>:</td>
                    <td>
                        <input type="text" name="ga_id" value="'.$option['ga_id'].'" size="50">
                    </td>
                </tr>
                <tr>
                    <td width="250" align="right"></b>Domain</b>:</td>
                    <td>
                        <input type="text" name="domain" value="'.$option['domain'].'" size="50">
                    </td>
                </tr>
                <tr>
                    <td width="250" align="right"></b>Include jQuery script?</b>:</td>
                    <td>
                        <input type="radio" '.(!$option['include_jquery']?'checked':'').' name="include_jquery" value="0" id="include_jquery_no">
                        <label for="include_jquery_no">No</label>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" '.($option['include_jquery']?'checked':'').' name="include_jquery" value="1" id="include_jquery_yes">
                        <label for="include_jquery_yes">Yes</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <small>
                            Choose "Yes" if jQuery script is not loading on the front-end.
                        </small>
                    </td>
                </tr>
                
                <tr>
                    <td width="250" align="right"></b>Files extensions you want to track (separate by comma)</b>:</td>
                    <td>
                        <input type="text" name="track_ext" value="'.strtolower(implode(", ",$option['track_ext'])).'" size="50">
                    </td>
                </tr>
                
                <tr>
                    <td width="250" align="right"></b>Enable Plugin?</b>:</td>
                    <td>
                        <input type="radio" '.(!$option['is_enabled']?'checked':'').' name="is_enabled" value="0" id="is_enabled_no">
                        <label for="is_enabled_no">No</label>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" '.($option['is_enabled']?'checked':'').' name="is_enabled" value="1" id="is_enabled_yes">
                        <label for="is_enabled_yes">Yes</label>
                    </td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" value="Update Options"></td>
                </tr>
            </table>
            </form>
        ';
        
        $html.='</div>';
        
        echo $html;
    }
    
    public function save_options()
    {
        if (isset($_POST['workbox_options_ga_flag']))
        {
            $option = get_option('wb_ga_options');
            
            if (isset($_POST['ga_id']))
            {
                $option['ga_id'] = stripcslashes(strip_tags($_POST['ga_id']));
            }
            
            if (isset($_POST['domain']))
            {
                $option['domain'] = stripcslashes(strip_tags($_POST['domain']));
            }
            
            if (isset($_POST['include_jquery']))
            {
                $option['include_jquery'] = intval($_POST['include_jquery'])?true:false;
            }
            
            if (isset($_POST['track_ext']))
            {
                $val = split(',',preg_replace('/[^0-9a-z_,]/','',strtolower($_POST['track_ext'])));
                $option['track_ext'] = $val;
            }
            
            if (isset($_POST['is_enabled']))
            {
                $option['is_enabled'] = intval($_POST['is_enabled'])?true:false;
            }
            
            update_option('wb_ga_options',$option);
            
            header('location: options-general.php?page=workbox_ga');
            die();
        }
    }
    
    public function show_js()
    {
        $option = get_option('wb_ga_options');
        
        if ($option['is_enabled'] && sizeof($option['track_ext'])>0)
        {
            
            if ($option['include_jquery'])
            {
                wp_register_script('jquery',WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)).'/jquery-1.4.2.min.js');
                wp_print_scripts('jquery');
            }
            
            // show script
            ?>
	    <script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	    </script>
	    <script type="text/javascript">
		try{
		    var pageTracker = _gat._getTracker("<?php echo $option['ga_id']?>");
		    pageTracker._trackPageview();
		    pageTracker._setDomainName(".<?php echo $option['domain'] ?>");
		} catch(err) {}
	    </script>
            <script type="text/javascript">
                jQuery(document).ready( function() {
                    jQuery('a').click(function(ev) {
                        var link = this.toString();
                        var aExt = new Array('<?php echo implode("','",$option['track_ext'])?>');
                        var aPieces = new Array();
                        var aPieces = link.split('\.');
                        if (aPieces.length>0)
                        {
                           var ext = aPieces[aPieces.length-1];
                           
                            for (var i = 0;i<aExt.length; i++)
                            {
                                if (aExt[i] == ext)
                                {
				    pageTracker._trackPageview(link);
				    break;
                                }
                            }
                        }
                    });
                });
            </script>
            <?php
        }
      
    }
}


?>