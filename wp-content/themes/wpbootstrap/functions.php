<?php 

function wpbootstrap_scripts_with_jquery()
{
	// Register the script like this for a theme:
	wp_register_script( 'custom-script', get_template_directory_uri() . '/bootstrap/js/bootstrap.js', array( 'jquery' ) );
	// For either a plugin or a theme, you can then enqueue the script:
	wp_enqueue_script( 'custom-script' );
}
add_action( 'wp_enqueue_scripts', 'wpbootstrap_scripts_with_jquery' );


if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));


//Clean Up WordPress Shortcode Formatting - important for nested shortcodes
//adjusted from http://donalmacarthur.com/articles/cleaning-up-wordpress-shortcode-formatting/
function parse_shortcode_content( $content ) {

   /* Parse nested shortcodes and add formatting. */
    $content = trim( do_shortcode( shortcode_unautop( $content ) ) );

    /* Remove '' from the start of the string. */
    if ( substr( $content, 0, 4 ) == '' )
        $content = substr( $content, 4 );

    /* Remove '' from the end of the string. */
    if ( substr( $content, -3, 3 ) == '' )
        $content = substr( $content, 0, -3 );

    /* Remove any instances of ''. */
    $content = str_replace( array( '<p></p>' ), '', $content );
    $content = str_replace( array( '<p>  </p>' ), '', $content );

    return $content;
}

//move wpautop filter to AFTER shortcode is processed
/*
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99);
add_filter( 'the_content', 'shortcode_unautop',100 );
*/

add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
function special_nav_class($classes, $item)
{
     if( in_array('current-menu-item', $classes) ||
     	 in_array('current_page_item', $classes)){
             $classes[] = 'active';
     }

     return $classes;
}


add_shortcode( 'hero-unit', 'bs_hero_unit' );
function bs_hero_unit($atts, $content)
{
	$html = '';

	$html .= "<div class='hero-unit'>";
	$html .= do_shortcode( $content );
	$html .= "</div>";

	return $html;
}


add_shortcode( 'row', 'bs_row' );
function bs_row($atts, $content)
{
	$html = '';

	$html .= "<div class='row'>";
	$html .= do_shortcode( $content );
	$html .= "</div>";

	return $html;
}


add_shortcode( 'column', 'bs_column' );
function bs_column($atts, $content)
{
	$html = '';

	extract(shortcode_atts(array(
		'span' => 12,
		'offset' => ''
	), $atts));

	$html .= "<div class='span$span";
	$html .= ( !empty($offset) ) ? " offset$offset" : "";
	$html .= "'>";

	$html .= do_shortcode( $content );

	$html .= "</div>";

	return $html;
}



add_shortcode( 'button', 'bs_btn' );
function bs_btn($atts, $content)
{
	$html = '';

	extract(shortcode_atts(array(
		'type' => '',
		'link' => '#',
		'target' => '',
		'size' => ''
	), $atts));

	$html .= "<a href='$link'";
	$html .= ( !empty($target) ) ? " target='$target'" : "";

	$html .= " class='btn";

	$html .= ( !empty($type) ) ? " btn-$type" : "";
	$html .= ( !empty($size) ) ? " btn-$size" : "";
	$html .= "'>";

	$html .= do_shortcode( $content );

	$html .= "</a>";

	return $html;
}


add_shortcode( 'alert', 'bs_alert' );
function bs_alert($atts, $content)
{
	$html = '';

	extract(shortcode_atts(array(
		'type' => '',
		'block' => false,
		'dismiss' => true
	), $atts));

	$html .= "<div class='alert";
		if ( $block ) $html .= " alert-block";
		$html .= ( !empty($type) ) ? " alert-$type'" : "";
	$html .= "'>";

	if ( $dismiss ) $html .= "<button type='button' class='close' data-dismiss='alert'>&times;</button>";

	$html .= do_shortcode( $content );

	$html .= "</div>";

	return $html;

}


add_shortcode( 'accordion', 'bs_accordion' );
function bs_accordion($atts, $content)
{
	extract( shortcode_atts( array(
		'id' => ''
	), $atts ));

	$html = "<div class='accordion' id='$id'>";

	$html .= do_shortcode( $content );

	$html .= "</div>";

	return $html;
}


add_shortcode( 'accordion-group', 'bs_accordion_group' );
function bs_accordion_group($atts, $content)
{
	extract( shortcode_atts( array(
		'id'		=> '',
		'parent_id'	=> '',
		'heading' 	=> ''
	), $atts ));

	$html = "<div class='accordion-group'>
				<div class='accordion-heading'>
					<a class='accordion-toggle' data-toggle='collapse' data-parent='#$parent_id' href='#$id'>$heading<i class='icon-chevron-down pull-right'></i></a>
				</div>
				<div id='$id' class='accordion-body collapse'>
					<div class='accordion-inner'>";

	$html .= do_shortcode( $content );

	$html .= "</div></div></div>";

	return $html;
}



add_shortcode( 'icon', 'fa_icon' );
function fa_icon($atts)
{
	$html = '';

	extract(shortcode_atts(array(
		'icon' 	=> '',
		'name' 	=> '',
		'class' => '',
		'type' 	=> '',
		'size' 	=> '',
		'border' 	=> false,
		'spin' 		=> false,
		'shadow' 	=> false,
		'pull'	=> '',
		'muted'	=> false
	), $atts));

	if ( !empty($icon) ) 
		$html .= "<i class='icon-$icon";
	else if ( !empty($name) )
		$html .= "<i class='icon-$name";

	$html .= ( !empty($type) ) ? " icon-$type" : "";
	$html .= ( !empty($class) ) ? " icon-$class" : "";
	$html .= ( !empty($size) ) ? " icon-$size" : "";
	$html .= ( !empty($pull) ) ? " pull-$pull" : "";
	if ( $border ) 	$html .= " icon-border";
	if ( $shadow ) 	$html .= " icon-shadow";
	if ( $muted ) 	$html .= " icon-muted";
	if ( $spin) 	$html .= " icon-spin";

	$html .= "'></i>";

	return $html;
}

add_shortcode( 'unordered-list', 'fa_unordered_list' );
function fa_unordered_list($atts, $content)
{
	$html = '';

	extract(shortcode_atts(array(
		'class' => ''
	), $atts));

	$html .= "<div class='icons";
	$html .= ( !empty($class) ) ? " $class" : "";
	$html .= "'>";

	$html .= do_shortcode($content);

	$html .= "</div>";

	return $html;
}


add_shortcode( 'table', 'bs_table' );
function bs_table($atts, $content)
{
	$html = "
		<table class='table table-striped table-hover'>
              <thead>
                <tr>
                  <th>#</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Username</th>
                </tr>
              </thead>
              <tbody>
                <tr onClick=\"alert('Mark Otto (@mdo)');\">
                  <td>1</td>
                  <td>Mark</td>
                  <td>Otto</td>
                  <td>@mdo</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Jacob</td>
                  <td>Thornton</td>
                  <td>@fat</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Larry</td>
                  <td>the Bird</td>
                  <td>@twitter</td>
                </tr>
              </tbody>
            </table>
            ";

	return $html;
}


add_shortcode( 'icon-list', 'fa_icon_list' );
function fa_icon_list($atts, $content)
{
	$html = '
		<section id="icons-new" class="row">
		  <div class="span12">
		    <h2 class="page-header">New Icons in 3.0</h2>
		  </div>

		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-cloud-download"></i> icon-cloud-download</li>
		      <li><i class="icon-cloud-upload"></i> icon-cloud-upload</li>
		      <li><i class="icon-lightbulb"></i> icon-lightbulb</li>
		      <li><i class="icon-exchange"></i> icon-exchange</li>
		      <li><i class="icon-bell-alt"></i> icon-bell-alt</li>
		      <li><i class="icon-file-alt"></i> icon-file-alt</li>
		      <li><i class="icon-beer"></i> icon-beer</li>
		      <li><i class="icon-coffee"></i> icon-coffee</li>
		      <li><i class="icon-food"></i> icon-food</li>
		      <li><i class="icon-fighter-jet"></i> icon-fighter-jet</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-user-md"></i> icon-user-md</li>
		      <li><i class="icon-stethoscope"></i> icon-stethoscope</li>
		      <li><i class="icon-suitcase"></i> icon-suitcase</li>
		      <li><i class="icon-building"></i> icon-building</li>
		      <li><i class="icon-hospital"></i> icon-hospital</li>
		      <li><i class="icon-ambulance"></i> icon-ambulance</li>
		      <li><i class="icon-medkit"></i> icon-medkit</li>
		      <li><i class="icon-h-sign"></i> icon-h-sign</li>
		      <li><i class="icon-plus-sign-alt"></i> icon-plus-sign-alt</li>
		      <li><i class="icon-spinner"></i> icon-spinner</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-angle-left"></i> icon-angle-left</li>
		      <li><i class="icon-angle-right"></i> icon-angle-right</li>
		      <li><i class="icon-angle-up"></i> icon-angle-up</li>
		      <li><i class="icon-angle-down"></i> icon-angle-down</li>
		      <li><i class="icon-double-angle-left"></i> icon-double-angle-left</li>
		      <li><i class="icon-double-angle-right"></i> icon-double-angle-right</li>
		      <li><i class="icon-double-angle-up"></i> icon-double-angle-up</li>
		      <li><i class="icon-double-angle-down"></i> icon-double-angle-down</li>
		      <li><i class="icon-circle-blank"></i> icon-circle-blank</li>
		      <li><i class="icon-circle"></i> icon-circle</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-desktop"></i> icon-desktop</li>
		      <li><i class="icon-laptop"></i> icon-laptop</li>
		      <li><i class="icon-tablet"></i> icon-tablet</li>
		      <li><i class="icon-mobile-phone"></i> icon-mobile-phone</li>
		      <li><i class="icon-quote-left"></i> icon-quote-left</li>
		      <li><i class="icon-quote-right"></i> icon-quote-right</li>
		      <li><i class="icon-reply"></i> icon-reply</li>
		      <li><i class="icon-github-alt"></i> icon-github-alt</li>
		      <li><i class="icon-folder-close-alt"></i> icon-folder-close-alt</li>
		      <li><i class="icon-folder-open-alt"></i> icon-folder-open-alt</li>
		    </ul>
		  </div>
		</section>

		<section id="icons-web-app" class="row">
		  <div class="span12">
		    <h2 class="page-header">Web Application Icons</h2>
		  </div>

		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-adjust"></i> icon-adjust</li>
		      <li><i class="icon-asterisk"></i> icon-asterisk</li>
		      <li><i class="icon-ban-circle"></i> icon-ban-circle</li>
		      <li><i class="icon-bar-chart"></i> icon-bar-chart</li>
		      <li><i class="icon-barcode"></i> icon-barcode</li>
		      <li><i class="icon-beaker"></i> icon-beaker</li>
		      <li><i class="icon-beer"></i> icon-beer</li>
		      <li><i class="icon-bell"></i> icon-bell</li>
		      <li><i class="icon-bell-alt"></i> icon-bell-alt</li>
		      <li><i class="icon-bolt"></i> icon-bolt</li>
		      <li><i class="icon-book"></i> icon-book</li>
		      <li><i class="icon-bookmark"></i> icon-bookmark</li>
		      <li><i class="icon-bookmark-empty"></i> icon-bookmark-empty</li>
		      <li><i class="icon-briefcase"></i> icon-briefcase</li>
		      <li><i class="icon-bullhorn"></i> icon-bullhorn</li>
		      <li><i class="icon-calendar"></i> icon-calendar</li>
		      <li><i class="icon-camera"></i> icon-camera</li>
		      <li><i class="icon-camera-retro"></i> icon-camera-retro</li>
		      <li><i class="icon-certificate"></i> icon-certificate</li>
		      <li><i class="icon-check"></i> icon-check</li>
		      <li><i class="icon-check-empty"></i> icon-check-empty</li>
		      <li><i class="icon-circle"></i> icon-circle</li>
		      <li><i class="icon-circle-blank"></i> icon-circle-blank</li>
		      <li><i class="icon-cloud"></i> icon-cloud</li>
		      <li><i class="icon-cloud-download"></i> icon-cloud-download</li>
		      <li><i class="icon-cloud-upload"></i> icon-cloud-upload</li>
		      <li><i class="icon-coffee"></i> icon-coffee</li>
		      <li><i class="icon-cog"></i> icon-cog</li>
		      <li><i class="icon-cogs"></i> icon-cogs</li>
		      <li><i class="icon-comment"></i> icon-comment</li>
		      <li><i class="icon-comment-alt"></i> icon-comment-alt</li>
		      <li><i class="icon-comments"></i> icon-comments</li>
		      <li><i class="icon-comments-alt"></i> icon-comments-alt</li>
		      <li><i class="icon-credit-card"></i> icon-credit-card</li>
		      <li><i class="icon-dashboard"></i> icon-dashboard</li>
		      <li><i class="icon-desktop"></i> icon-desktop</li>
		      <li><i class="icon-download"></i> icon-download</li>
		      <li><i class="icon-download-alt"></i> icon-download-alt</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-edit"></i> icon-edit</li>
		      <li><i class="icon-envelope"></i> icon-envelope</li>
		      <li><i class="icon-envelope-alt"></i> icon-envelope-alt</li>
		      <li><i class="icon-exchange"></i> icon-exchange</li>
		      <li><i class="icon-exclamation-sign"></i> icon-exclamation-sign</li>
		      <li><i class="icon-external-link"></i> icon-external-link</li>
		      <li><i class="icon-eye-close"></i> icon-eye-close</li>
		      <li><i class="icon-eye-open"></i> icon-eye-open</li>
		      <li><i class="icon-facetime-video"></i> icon-facetime-video</li>
		      <li><i class="icon-fighter-jet"></i> icon-fighter-jet</li>
		      <li><i class="icon-film"></i> icon-film</li>
		      <li><i class="icon-filter"></i> icon-filter</li>
		      <li><i class="icon-fire"></i> icon-fire</li>
		      <li><i class="icon-flag"></i> icon-flag</li>
		      <li><i class="icon-folder-close"></i> icon-folder-close</li>
		      <li><i class="icon-folder-open"></i> icon-folder-open</li>
		      <li><i class="icon-folder-close-alt"></i> icon-folder-close-alt</li>
		      <li><i class="icon-folder-open-alt"></i> icon-folder-open-alt</li>
		      <li><i class="icon-food"></i> icon-food</li>
		      <li><i class="icon-gift"></i> icon-gift</li>
		      <li><i class="icon-glass"></i> icon-glass</li>
		      <li><i class="icon-globe"></i> icon-globe</li>
		      <li><i class="icon-group"></i> icon-group</li>
		      <li><i class="icon-hdd"></i> icon-hdd</li>
		      <li><i class="icon-headphones"></i> icon-headphones</li>
		      <li><i class="icon-heart"></i> icon-heart</li>
		      <li><i class="icon-heart-empty"></i> icon-heart-empty</li>
		      <li><i class="icon-home"></i> icon-home</li>
		      <li><i class="icon-inbox"></i> icon-inbox</li>
		      <li><i class="icon-info-sign"></i> icon-info-sign</li>
		      <li><i class="icon-key"></i> icon-key</li>
		      <li><i class="icon-leaf"></i> icon-leaf</li>
		      <li><i class="icon-laptop"></i> icon-laptop</li>
		      <li><i class="icon-legal"></i> icon-legal</li>
		      <li><i class="icon-lemon"></i> icon-lemon</li>
		      <li><i class="icon-lightbulb"></i> icon-lightbulb</li>
		      <li><i class="icon-lock"></i> icon-lock</li>
		      <li><i class="icon-unlock"></i> icon-unlock</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-magic"></i> icon-magic</li>
		      <li><i class="icon-magnet"></i> icon-magnet</li>
		      <li><i class="icon-map-marker"></i> icon-map-marker</li>
		      <li><i class="icon-minus"></i> icon-minus</li>
		      <li><i class="icon-minus-sign"></i> icon-minus-sign</li>
		      <li><i class="icon-mobile-phone"></i> icon-mobile-phone</li>
		      <li><i class="icon-money"></i> icon-money</li>
		      <li><i class="icon-move"></i> icon-move</li>
		      <li><i class="icon-music"></i> icon-music</li>
		      <li><i class="icon-off"></i> icon-off</li>
		      <li><i class="icon-ok"></i> icon-ok</li>
		      <li><i class="icon-ok-circle"></i> icon-ok-circle</li>
		      <li><i class="icon-ok-sign"></i> icon-ok-sign</li>
		      <li><i class="icon-pencil"></i> icon-pencil</li>
		      <li><i class="icon-picture"></i> icon-picture</li>
		      <li><i class="icon-plane"></i> icon-plane</li>
		      <li><i class="icon-plus"></i> icon-plus</li>
		      <li><i class="icon-plus-sign"></i> icon-plus-sign</li>
		      <li><i class="icon-print"></i> icon-print</li>
		      <li><i class="icon-pushpin"></i> icon-pushpin</li>
		      <li><i class="icon-qrcode"></i> icon-qrcode</li>
		      <li><i class="icon-question-sign"></i> icon-question-sign</li>
		      <li><i class="icon-quote-left"></i> icon-quote-left</li>
		      <li><i class="icon-quote-right"></i> icon-quote-right</li>
		      <li><i class="icon-random"></i> icon-random</li>
		      <li><i class="icon-refresh"></i> icon-refresh</li>
		      <li><i class="icon-remove"></i> icon-remove</li>
		      <li><i class="icon-remove-circle"></i> icon-remove-circle</li>
		      <li><i class="icon-remove-sign"></i> icon-remove-sign</li>
		      <li><i class="icon-reorder"></i> icon-reorder</li>
		      <li><i class="icon-reply"></i> icon-reply</li>
		      <li><i class="icon-resize-horizontal"></i> icon-resize-horizontal</li>
		      <li><i class="icon-resize-vertical"></i> icon-resize-vertical</li>
		      <li><i class="icon-retweet"></i> icon-retweet</li>
		      <li><i class="icon-road"></i> icon-road</li>
		      <li><i class="icon-rss"></i> icon-rss</li>
		      <li><i class="icon-screenshot"></i> icon-screenshot</li>
		      <li><i class="icon-search"></i> icon-search</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-share"></i> icon-share</li>
		      <li><i class="icon-share-alt"></i> icon-share-alt</li>
		      <li><i class="icon-shopping-cart"></i> icon-shopping-cart</li>
		      <li><i class="icon-signal"></i> icon-signal</li>
		      <li><i class="icon-signin"></i> icon-signin</li>
		      <li><i class="icon-signout"></i> icon-signout</li>
		      <li><i class="icon-sitemap"></i> icon-sitemap</li>
		      <li><i class="icon-sort"></i> icon-sort</li>
		      <li><i class="icon-sort-down"></i> icon-sort-down</li>
		      <li><i class="icon-sort-up"></i> icon-sort-up</li>
		      <li><i class="icon-spinner"></i> icon-spinner</li>
		      <li><i class="icon-star"></i> icon-star</li>
		      <li><i class="icon-star-empty"></i> icon-star-empty</li>
		      <li><i class="icon-star-half"></i> icon-star-half</li>
		      <li><i class="icon-tablet"></i> icon-tablet</li>
		      <li><i class="icon-tag"></i> icon-tag</li>
		      <li><i class="icon-tags"></i> icon-tags</li>
		      <li><i class="icon-tasks"></i> icon-tasks</li>
		      <li><i class="icon-thumbs-down"></i> icon-thumbs-down</li>
		      <li><i class="icon-thumbs-up"></i> icon-thumbs-up</li>
		      <li><i class="icon-time"></i> icon-time</li>
		      <li><i class="icon-tint"></i> icon-tint</li>
		      <li><i class="icon-trash"></i> icon-trash</li>
		      <li><i class="icon-trophy"></i> icon-trophy</li>
		      <li><i class="icon-truck"></i> icon-truck</li>
		      <li><i class="icon-umbrella"></i> icon-umbrella</li>
		      <li><i class="icon-upload"></i> icon-upload</li>
		      <li><i class="icon-upload-alt"></i> icon-upload-alt</li>
		      <li><i class="icon-user"></i> icon-user</li>
		      <li><i class="icon-user-md"></i> icon-user-md</li>
		      <li><i class="icon-volume-off"></i> icon-volume-off</li>
		      <li><i class="icon-volume-down"></i> icon-volume-down</li>
		      <li><i class="icon-volume-up"></i> icon-volume-up</li>
		      <li><i class="icon-warning-sign"></i> icon-warning-sign</li>
		      <li><i class="icon-wrench"></i> icon-wrench</li>
		      <li><i class="icon-zoom-in"></i> icon-zoom-in</li>
		      <li><i class="icon-zoom-out"></i> icon-zoom-out</li>
		    </ul>
		  </div>
		</section>

		<section id="icons-text-editor" class="row">
		  <div class="span12">
		    <h2 class="page-header">Text Editor Icons</h2>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-file"></i> icon-file</li>
		      <li><i class="icon-file-alt"></i> icon-file-alt</li>
		      <li><i class="icon-cut"></i> icon-cut</li>
		      <li><i class="icon-copy"></i> icon-copy</li>
		      <li><i class="icon-paste"></i> icon-paste</li>
		      <li><i class="icon-save"></i> icon-save</li>
		      <li><i class="icon-undo"></i> icon-undo</li>
		      <li><i class="icon-repeat"></i> icon-repeat</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-text-height"></i> icon-text-height</li>
		      <li><i class="icon-text-width"></i> icon-text-width</li>
		      <li><i class="icon-align-left"></i> icon-align-left</li>
		      <li><i class="icon-align-center"></i> icon-align-center</li>
		      <li><i class="icon-align-right"></i> icon-align-right</li>
		      <li><i class="icon-align-justify"></i> icon-align-justify</li>
		      <li><i class="icon-indent-left"></i> icon-indent-left</li>
		      <li><i class="icon-indent-right"></i> icon-indent-right</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-font"></i> icon-font</li>
		      <li><i class="icon-bold"></i> icon-bold</li>
		      <li><i class="icon-italic"></i> icon-italic</li>
		      <li><i class="icon-strikethrough"></i> icon-strikethrough</li>
		      <li><i class="icon-underline"></i> icon-underline</li>
		      <li><i class="icon-link"></i> icon-link</li>
		      <li><i class="icon-paper-clip"></i> icon-paper-clip</li>
		      <li><i class="icon-columns"></i> icon-columns</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-table"></i> icon-table</li>
		      <li><i class="icon-th-large"></i> icon-th-large</li>
		      <li><i class="icon-th"></i> icon-th</li>
		      <li><i class="icon-th-list"></i> icon-th-list</li>
		      <li><i class="icon-list"></i> icon-list</li>
		      <li><i class="icon-list-ol"></i> icon-list-ol</li>
		      <li><i class="icon-list-ul"></i> icon-list-ul</li>
		      <li><i class="icon-list-alt"></i> icon-list-alt</li>
		    </ul>
		  </div>
		</section>

		<section id="icons-directional" class="row">
		  <div class="span12">
		    <h2 class="page-header">Directional Icons</h2>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-angle-left"></i> icon-angle-left</li>
		      <li><i class="icon-angle-right"></i> icon-angle-right</li>
		      <li><i class="icon-angle-up"></i> icon-angle-up</li>
		      <li><i class="icon-angle-down"></i> icon-angle-down</li>
		      <li><i class="icon-arrow-down"></i> icon-arrow-down</li>
		      <li><i class="icon-arrow-left"></i> icon-arrow-left</li>
		      <li><i class="icon-arrow-right"></i> icon-arrow-right</li>
		      <li><i class="icon-arrow-up"></i> icon-arrow-up</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-caret-down"></i> icon-caret-down</li>
		      <li><i class="icon-caret-left"></i> icon-caret-left</li>
		      <li><i class="icon-caret-right"></i> icon-caret-right</li>
		      <li><i class="icon-caret-up"></i> icon-caret-up</li>
		      <li><i class="icon-chevron-down"></i> icon-chevron-down</li>
		      <li><i class="icon-chevron-left"></i> icon-chevron-left</li>
		      <li><i class="icon-chevron-right"></i> icon-chevron-right</li>
		      <li><i class="icon-chevron-up"></i> icon-chevron-up</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-circle-arrow-down"></i> icon-circle-arrow-down</li>
		      <li><i class="icon-circle-arrow-left"></i> icon-circle-arrow-left</li>
		      <li><i class="icon-circle-arrow-right"></i> icon-circle-arrow-right</li>
		      <li><i class="icon-circle-arrow-up"></i> icon-circle-arrow-up</li>
		      <li><i class="icon-double-angle-left"></i> icon-double-angle-left</li>
		      <li><i class="icon-double-angle-right"></i> icon-double-angle-right</li>
		      <li><i class="icon-double-angle-up"></i> icon-double-angle-up</li>
		      <li><i class="icon-double-angle-down"></i> icon-double-angle-down</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-hand-down"></i> icon-hand-down</li>
		      <li><i class="icon-hand-left"></i> icon-hand-left</li>
		      <li><i class="icon-hand-right"></i> icon-hand-right</li>
		      <li><i class="icon-hand-up"></i> icon-hand-up</li>
		      <li><i class="icon-circle"></i> icon-circle</li>
		      <li><i class="icon-circle-blank"></i> icon-circle-blank</li>
		    </ul>
		  </div>
		</section>

		<section id="icons-video-player" class="row">
		  <div class="span12">
		    <h2 class="page-header">Video Player Icons</h2>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-play-circle"></i> icon-play-circle</li>
		      <li><i class="icon-play"></i> icon-play</li>
		      <li><i class="icon-pause"></i> icon-pause</li>
		      <li><i class="icon-stop"></i> icon-stop</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-step-backward"></i> icon-step-backward</li>
		      <li><i class="icon-fast-backward"></i> icon-fast-backward</li>
		      <li><i class="icon-backward"></i> icon-backward</li>
		      <li><i class="icon-forward"></i> icon-forward</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-fast-forward"></i> icon-fast-forward</li>
		      <li><i class="icon-step-forward"></i> icon-step-forward</li>
		      <li><i class="icon-eject"></i> icon-eject</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-fullscreen"></i> icon-fullscreen</li>
		      <li><i class="icon-resize-full"></i> icon-resize-full</li>
		      <li><i class="icon-resize-small"></i> icon-resize-small</li>
		    </ul>
		  </div>
		</section>

		<section id="icons-social" class="row">
		  <div class="span12">
		    <h2 class="page-header">Social Icons</h2>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-phone"></i> icon-phone</li>
		      <li><i class="icon-phone-sign"></i> icon-phone-sign</li>
		      <li><i class="icon-facebook"></i> icon-facebook</li>
		      <li><i class="icon-facebook-sign"></i> icon-facebook-sign</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-twitter"></i> icon-twitter</li>
		      <li><i class="icon-twitter-sign"></i> icon-twitter-sign</li>
		      <li><i class="icon-github"></i> icon-github</li>
		      <li><i class="icon-github-alt"></i> icon-github-alt</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-github-sign"></i> icon-github-sign</li>
		      <li><i class="icon-linkedin"></i> icon-linkedin</li>
		      <li><i class="icon-linkedin-sign"></i> icon-linkedin-sign</li>
		      <li><i class="icon-pinterest"></i> icon-pinterest</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-pinterest-sign"></i> icon-pinterest-sign</li>
		      <li><i class="icon-google-plus"></i> icon-google-plus</li>
		      <li><i class="icon-google-plus-sign"></i> icon-google-plus-sign</li>
		      <li><i class="icon-sign-blank"></i> icon-sign-blank</li>
		    </ul>
		  </div>
		</section>

		<section id="icons-medical" class="row">
		  <div class="span12">
		    <h2 class="page-header">Medical Icons <small>Want to change healthcare? Work with me at <a href="#kyruus">Kyruus</a>.</small></h2>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-ambulance"></i> icon-ambulance</li>
		      <li><i class="icon-beaker"></i> icon-beaker</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-h-sign"></i> icon-h-sign</li>
		      <li><i class="icon-hospital"></i> icon-hospital</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-medkit"></i> icon-medkit</li>
		      <li><i class="icon-plus-sign-alt"></i> icon-plus-sign-alt</li>
		    </ul>
		  </div>
		  <div class="span3">
		    <ul class="icons">
		      <li><i class="icon-stethoscope"></i> icon-stethoscope</li>
		      <li><i class="icon-user-md"></i> icon-user-md</li>
		    </ul>
		  </div>
		</section>';

	return $html;

}



?>