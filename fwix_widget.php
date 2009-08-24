<?

function fwix_widget($args){
    fwix_show_feed($args);
}


function fwix_show_feed($args){
    $options = get_option('fwix_options');
    $story_count = $options['story_count'];
    $geo_id = $options['geo'];
    $stories = json_decode(send_request(FEED_URL, array('geo_id' => $geo_id, 'page' => 1, 'page_size' => $story_count, 'search_ip' => $_SERVER['REMOTE_ADDR'])), true);
    $geo_id = $stories['feed'][0]['geo'];
    $geo_data = json_decode(send_request(DATA_URL, array('method'=>'geo_data', 'geo_id' => $geo_id)), true);

    echo $args['before_widget'].$args['before_title'].$geo_data['pretty']." Local News".$args['after_title']."\n";
    echo "<ul id = 'fxw_feed'>\n";
    foreach($stories['feed'] as $story){
        echo "<li>\n";
        echo "<a href = http://fwix.com/share/{$story['geo']}_{$story['storyid']}>{$story['title']}</a><br/>\n";
        echo "<span class = 'time_ago'>".time_ago($story['print_time'])."</span> - <abbr class = 'pretty'>{$story['pretty']}</abbr>\n";
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
