<?php
/*
Plugin Name: BLT Counter
Version: 1.6
Plugin URI: http://shirasaka.tv
Description: Show total value influence of SNS at anywhere
Domain Path: /languages/
Author: shoshirasaka
Author URI: http://shirasaka.tv/
Text Domain: blt-counter
*/

load_plugin_textdomain( 'blt-counter', false, basename( dirname( __FILE__ ) ) . '/languages' );

function blt_counter_show($blt_url) {
    $blt_get_twitter = 'http://urls.api.twitter.com/1/urls/count.json?url=' . $blt_url;
    $blt_json = file_get_contents($blt_get_twitter);
    $blt_json = json_decode($blt_json);
    $blt_twitter = $blt_json->{'count'};
    $blt_get_facebook = 'http://api.facebook.com/restserver.php?method=links.getStats&urls=' . $blt_url;
    $blt_xml = file_get_contents($blt_get_facebook);
    $blt_xml = simplexml_load_string($blt_xml);
    $blt_facebook = $blt_xml->link_stat->total_count;
    $blt_get_hatebu = 'http://api.b.st-hatena.com/entry.count?url=' . $blt_url;
    $blt_hatebu = file_get_contents($blt_get_hatebu);
    if($blt_hatebu == ""){$blt_hatebu = 0;}
 
    $blt_counts = $blt_twitter + $blt_facebook + $blt_hatebu;
    if($blt_counts>=100){
    print("<span class='blt_counter color100'>".$blt_counts."</span>");
    }else if($blt_counts>=50){
    print("<span class='blt_counter color50'>".$blt_counts."</span>");
    }else if($blt_counts>=40){
    print("<span class='blt_counter color40'>".$blt_counts."</span>");
    }else if($blt_counts>=30){
    print("<span class='blt_counter color30'>".$blt_counts."</span>");
    }else if($blt_counts>=20){
    print("<span class='blt_counter color20'>".$blt_counts."</span>");
    }else if($blt_counts>=10){
    print("<span class='blt_counter color10'>".$blt_counts."</span>");
    }else if($blt_counts>=1){
    print("<span class='blt_counter'>".$blt_counts."</span>");
    }
}


add_action('admin_menu', 'blt_counter_menu');

function blt_counter_menu() {
	add_submenu_page(
	  'options-general.php',
		'BLT Counter',
		'BLT Counter',
		'administrator',
		'blt_counter',
		'blt_counter_setting'
	);
}

function blt_counter_setting() {
?>
<div class="wrap">
<h2>BLT Counter</h2>
<p><?php _e('Show total value influence of SNS(hatena Bookmark, Facebook Like and Tweets) at anywhere.', 'blt-counter');?></p>

<h3><?php _e('How to use', 'blt-counter');?></h3>
<?php _e('Add the tag &lt;?php blt_counter_show(get_permalink()); ?&gt; where you like.', 'blt-counter');?>

</div>

<?php
}


