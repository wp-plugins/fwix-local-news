<?

function fwix_widget($args){
    fwix_show_feed($args);
}


function fwix_show_feed($args){
    $options = get_option('fwix_options');
    $story_count = $options['story_count'];
    $geo_id = $options['geo'];

    if($geo_id == ''){
        $url = API_URL . 'general/getgeo.json';
        $params = array('ip_addr' => $_SERVER['REMOTE_ADDR']);
    }else{
        $url = API_URL . 'general/geoinfo.json';
        $params = array('geo_id' => $geo_id);
    }
    $geo_data = json_decode(send_request($url, $params), true);
    $geo_data = $geo_data['result'];
    $stories = json_decode(send_request(API_URL . 'fetch/recent.json', array('geo_id' => $geo_data['id'], 'page_num' => 1, 'page_size' => $story_count)), true);
    $stories = $stories['result'];

    echo $args['before_widget'].$args['before_title'].$geo_data['pretty']." Local News".$args['after_title']."\n";
    echo "<ul id = 'fxw_feed'>\n";
    foreach($stories as $story){
        echo "<li>\n";
        echo "<a href = '{$story['url']}'>{$story['title']}</a><br/>\n";
        echo "<span class = 'time_ago'>".time_ago($story['timestamp'])."</span> - <abbr class = 'pretty'>{$story['source']}</abbr>\n";
        if($options['descriptions'])
            echo shorten_description($story['summary'])."\n";
        echo $args['after_widget']."\n";
    }
    echo "</ul>\n";
    echo "<a class = 'fwix_link' href = 'http://fwix.com/'>More...</a></li>\n";
}


function time_ago($timestamp) {
    $difference = time() - $timestamp;

    if ( $difference < 0) {
        return "Just now";
    }

    if( $difference < 60 )
        if( $difference == 1 )
            return $difference." sec ago";
        else
            return $difference." sec ago";
    else {
        $difference = round( $difference / 60 );

        if( $difference < 60 )
            if( $difference == 1 )
                return $difference." min ago";
            else
                return $difference." min ago";
        else {
            $difference = round( $difference / 60 );

            if( $difference < 24 )
                if( $difference == 1 )
                    return $difference." hr ago";
                else
                    return $difference." hrs ago";
            else {
                $difference = round( $difference / 24 );

                if( $difference < 365 )
                    if( $difference == 1 )
                        return $difference." day ago";
                    else
                        return $difference." days ago";
                else {
                    return date("M jS, Y", $date);
                }
            }
        }
    }
}


function shorten_description($summary){
    $description = substr($summary, 0, 150);
    if(strlen($summary) > 150 && $story[149] != ' ')
        $description = substr($description, 0, strrpos($description, ' ')) . '...';
    
    return htmlentities($description);
}
