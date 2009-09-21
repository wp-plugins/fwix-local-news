<?

function fwix_widget_control(){
    if($_POST){
        $options = get_option('fwix_options');
        $options['geo']          =  $_POST['fwix_options_geo'];
        $options['story_count']  =  $_POST['fwix_options_story_count'];
	    $options['descriptions'] = isset($_POST['fwix_options_descriptions']);
	    $options['new_window']   = isset($_POST['fwix_options_new_window']);
        update_option('fwix_options', $options);
    }
    $options = get_option('fwix_options');

    ## CREATE THE DROPDOWN FOR THE GEOS 
    $selected_val = '';
    if(isset($options['geo']))
        $selected_val = $options['geo'];
    $json = json_decode(send_request(API_URL . 'general/geos.json'), true);
    $json = $json['result'];
    $geos[''] = 'Auto Detect City';
    foreach($json as $geo)
        $geos[$geo['id']] = $geo['pretty'];
    
    echo display_select('City', 'fwix_options_geo', $geos, $selected_val);
    ### CREATE TEXT FIELDS
    $story_count = isset($options['story_count']) ? $options['story_count'] : '5';
    echo "<p class = 'fwix_input'><label for='fwix_options_story_count'>Story Count: </label><input type='text' name='fwix_options_story_count' size=2 value='$story_count' />\n";

    ## CREATE THE CHECKBOXES FOR DESCRIPTION AND NEW WINDOW
    echo display_check_box('Show Descriptions', 'fwix_options_descriptions', $options['descriptions']);
}


function display_select($label, $name, $values, $selected_val){
    $str = '';
    $str .= "<p class = 'fwix_input'>\n<label for='$name'>$label: </label>\n";
    $str .= "<select name='$name'>\n";
    foreach($values as $key => $value){
        $selected = '';
        if($key == $selected_val)
            $selected = 'selected="selected"';
        $str .= "<option value='$key' $selected>$value</option>\n";
    }
    $str .= "</select>\n</p>\n";
    return $str;
}


function display_check_box($label, $name, $option){
    $checked = '';
    if(!isset($option) || $option)
        $checked = 'checked="checked"';
    return "<p class='fwix_input'><label for='$name'>$label: </label><input type=checkbox name='$name' $checked /></p>\n";
}
