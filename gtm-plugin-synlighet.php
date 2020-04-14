<?php
/**
 * Plugin Name: GTM Plugin
 * Plugin URI: https://www.synlighet.no/folk/stian-wiik-insteb%C3%B8/
 * Description: Integrate GTM on all pages
 * Version: 1.0
 * Author: Stian W. Instebø
 * Author URI: http://www.synlighet.no
 */

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'salcode_gtm_add_plugin_page_settings_link');
function salcode_gtm_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'admin.php?page=gtm-plugin' ) . '">' . __('Settings') . '</a>';
	return $links;
}



add_action('admin_menu', 'synlighet_gtm_menu');
function synlighet_gtm_menu(){
        add_menu_page( 'GTM Plugin', 'GTM Plugin', 'manage_options', 'gtm-plugin', 'admin_init_gtm' );
}

function admin_init_gtm(){
    ?>
        
        <style>
        
            boxes {
              margin: auto;
              padding: 50px;
              background: #999;
            }

            /*Checkboxes styles*/
            input[type="checkbox"] { display: none; }

            input[type="checkbox"] + label {
              display: block;
              position: relative;
              padding-left: 35px;
              margin-bottom: 20px;
              font: 14px/20px 'Open Sans', Arial, sans-serif;
              color: #000;
              cursor: pointer;
              -webkit-user-select: none;
              -moz-user-select: none;
              -ms-user-select: none;
            }

            input[type="checkbox"] + label:last-child { margin-bottom: 0; }

            input[type="checkbox"] + label:before {
              content: '';
              display: block;
              width: 20px;
              height: 20px;
              border: 1px solid #999;
                border-radius: 50%;
              position: absolute;
              left: 0;
              top: 0;
              opacity: .6;
              -webkit-transition: all .12s, border-color .08s;
              transition: all .12s, border-color .08s;
            }

            input[type="checkbox"]:checked + label:before {
              width: 10px;
              top: -5px;
              left: 5px;
              border-radius: 0;
              opacity: 1;
              border-top-color: transparent;
              border-left-color: transparent;
              -webkit-transform: rotate(45deg);
              transform: rotate(45deg);
            }
            .admin-btns {
                color: #000;
                transition: 0.3s;
                text-transform: uppercase;
                border: 1px solid #000;
            }
            .admin-btns:hover {
                background-color: #6B2886; 
                color: #fff;
                border: 1px solid #6B2886;
            }
            
            .credit-btns {
                color: #000;
                transition: 0.3s;
                text-transform: uppercase;
                border: 1px solid #000;
            }
            .credit-btns:hover {
                background-color: #BFE8E1; 
                color: #fff;
                border: 1px solid #BFE8E1;
            }
            
            .settings-input {
                padding-left: 15px; padding-right: 15px; background: none; width: 500px; height: 50px; border: 1px solid #000; border-radius: 25px;
            }
            
        </style>

        <div class="" style="width: 60%; margin: 0 auto; padding: 25px;">
            <h1>Google Tag Manager Plugin</h1>
            <p>Implement Google Tag Manager the correct way</p>
            <br><br>
            <h2>Options</h2>
            <form action="options.php" method="post">
                <?php
                    settings_fields( 'wpse61431_token_settings' );
                    do_settings_sections( __FILE__ );

                    //get the older values, wont work the first time
                    $options = get_option( 'wpse61431_token_settings' ); 
                ?>
                <fieldset>
                    <label>
                        <input class="settings-input" name="wpse61431_token_settings[wpse_array_field]" type="text" id="wpse_array_field" value="<?php echo (isset($options['wpse_array_field']) && $options['wpse_array_field'] != '') ? $options['wpse_array_field'] : ''; ?>" style="background: none; border-radius: 25px; padding-left: 15px; padding-right: 15px;" placeholder="GTM-XXXXXX"/>
                        <br>
                        <br>
                        <span class="description">Insert your GTM code</span>
                    </label>
                    <br>
                    <br>
                    <br>
                    <br>
                    
                </fieldset>
                <br>
                <br>
                <input style="color: #000; transition: 0.3s; text-transform: uppercase; border: 1px solid #000; width: 100px; height: 50px; border-radius: 25px; background: none;" type="submit" value="Save" />
            </form>
            
            <br><br>
            
            
            <a href="https://www.synlighet.no" target="_blank" class="admin-btns" style=" width: 150px; height: 50px; padding-left: 30px; padding-right: 30px; padding-top: 15px; padding-bottom: 15px;  text-decoration: none; border-radius: 25px;">Feedback</a> <a href="https://www.synlighet.no/folk/stian-wiik-insteb%C3%B8/" target="_blank" class="credit-btns" style=" width: 150px; height: 50px; padding-left: 30px; padding-right: 30px; padding-top: 15px; padding-bottom: 15px;  text-decoration: none; border-radius: 25px;">Credits</a>
            <br>
            <br>
            <br>
            <br>
            <center>
                <img src="https://www.synlighet.no/dynamic/upload/bilder/02-synlighet-logo-svart-rgb.png" height="100" />
                <p style="color: #ccc;">&copy; Synlighet 2020 - Developed by Stian W. Instebø</p>
            </center>
        </div>


    <?php
}

/*
 * Register the settings
 */
add_action('admin_init', 'wpse61431_token_register_settings');
function wpse61431_token_register_settings(){
    //this will save the option in the wp_options table as 'wpse61431_settings'
    //the third parameter is a function that will validate your input values
    register_setting('wpse61431_token_settings', 'wpse61431_token_settings', 'wpse61431_token_settings_validate');
}

function wpse61431_token_settings_validate($args){
    return $args;
}

//Display the validation errors and update messages
/*
 * Admin notices
 */
add_action('admin_notices', 'wpse61431_token_admin_notices');
function wpse61431_token_admin_notices(){
   settings_errors();
}

add_action ( 'wp_head', 'hook_inHeaderGTM' );
function hook_inHeaderGTM() {
    $phpJsArrayConv = '';

    foreach( get_option('wpse61431_token_settings') as $key => $value) {
        $phpJsArrayConv = $value;        
    }
    $gtm_token = get_option('wpse61431_token_settings');

    echo "<!-- Google Tag Manager plugin by Synlighet -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','".$phpJsArrayConv."');</script>
    <!-- End Google Tag Manager plugin by Synlighet -->";
}

add_filter('body_class', 'wps_add_tracking_body', PHP_INT_MAX); // make sure, that's the last filter in the queue
function wps_add_tracking_body($classes) {
    $phpJsArrayConvBody = '';

    foreach( get_option('wpse61431_token_settings') as $key => $value) {
        $phpJsArrayConvBody = $value;        
    }
    
    $gtmbody = '<!-- Google Tag Manager plugin by Synlighet (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$phpJsArrayConvBody.'"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->';
  // close <body> tag, insert stuff, open some other tag with senseless variable      
  $classes[] = '"><script>'.$gtmbody.'</script>';

  return $classes;
}

?>