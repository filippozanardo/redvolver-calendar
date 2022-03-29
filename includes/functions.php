<?php

function get_posts_years_array($post_type = 'post') {

    global $wpdb;
    $result = array();

    $query_prepare = $wpdb->prepare("SELECT YEAR(post_date) as year FROM ($wpdb->posts) WHERE  post_type = %s GROUP BY YEAR(post_date) DESC", $post_type);

    $years = $wpdb->get_results($query_prepare);

    if ( is_array( $years ) && count( $years ) > 0 ) {
        foreach ( $years as $year ) {
            $result[] = json_decode(json_encode($year), true);
        }
    }

    return $result;
}
