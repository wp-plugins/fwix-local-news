<?

/*
Plugin Name: Fwix Local News
Plugin URI: http://fwix.com
Version: 1.0
Author: Fwix
Description: Displays locals news as aggregated by http://fwix.com
*/

require_once 'fwix_admin.php';
require_once 'fwix_widget.php';

define(API_URL,'http://api.fwix.com/');
add_action('widgets_init', init_fwix);
add_action('wp_head', 'addHeaderCode');
add_action('admin_head', 'addHeaderCode');

function init_fwix(){
    if(!function_exists('register_sidebar_widget') || !function_exists('register_widget_control'))
        return;
    register_sidebar_widget('Fwix', 'fwix_widget');
    register_widget_control('Fwix', 'fwix_widget_control');
}


function addHeaderCode(){
    echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/fwix/wp-fwix.css" />' . "\n";
}


function send_request($url, $params = NULL){
    if($params){
        $url .= '?';
        $tempStr = '';
        foreach($params as $k => $v)
            $tempStr.= '&'.urlencode($k).'='.urlencode($v);
        $url .= substr($tempStr, 1);
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($curl);
    if(!$response)
        $response = curl_error($curl);
    curl_close($curl);
    return $response;
}


