<?php
/*
Plugin Name: Better Widgets
Description: This makes widgets better. Trust me. Or don't.
Version: 0.3
Author: Shaun Andrews
Author URI: http://wordpress.org
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (strpos($_SERVER["REQUEST_URI"], "widgets.php") === FALSE)
	return;

function better_widgets_enqueue_scripts() {

	wp_enqueue_script( 'bw-mp6-widgets', plugins_url( 'scripts.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_style( 'bw-mp6-widgets', plugins_url( 'bw-styles.css', __FILE__ ) );

}
add_action( 'admin_enqueue_scripts', 'better_widgets_enqueue_scripts' );

/* Adds the widget icon and moves the description.
 * This requires that the wp_widget_control() function is pluggable.
 * See here for more info: https://gist.github.com/shaunandrews/7989121

function wp_widget_control( $sidebar_args ) {
	global $wp_registered_widgets, $wp_registered_widget_controls, $sidebars_widgets;

	$widget_id = $sidebar_args['widget_id'];
	$sidebar_id = isset($sidebar_args['id']) ? $sidebar_args['id'] : false;
	$key = $sidebar_id ? array_search( $widget_id, $sidebars_widgets[$sidebar_id] ) : '-1'; // position of widget in sidebar
	$control = isset($wp_registered_widget_controls[$widget_id]) ? $wp_registered_widget_controls[$widget_id] : array();
	$widget = $wp_registered_widgets[$widget_id];

	$id_format = $widget['id'];
	$widget_number = isset($control['params'][0]['number']) ? $control['params'][0]['number'] : '';
	$id_base = isset($control['id_base']) ? $control['id_base'] : $widget_id;
	$multi_number = isset($sidebar_args['_multi_num']) ? $sidebar_args['_multi_num'] : '';
	$add_new = isset($sidebar_args['_add']) ? $sidebar_args['_add'] : '';

	$query_arg = array( 'editwidget' => $widget['id'] );
	if ( $add_new ) {
		$query_arg['addnew'] = 1;
		if ( $multi_number ) {
			$query_arg['num'] = $multi_number;
			$query_arg['base'] = $id_base;
		}
	} else {
		$query_arg['sidebar'] = $sidebar_id;
		$query_arg['key'] = $key;
	}

	// We aren't showing a widget control, we're outputting a template for a multi-widget control
	if ( isset($sidebar_args['_display']) && 'template' == $sidebar_args['_display'] && $widget_number ) {
		// number == -1 implies a template where id numbers are replaced by a generic '__i__'
		$control['params'][0]['number'] = -1;
		// with id_base widget id's are constructed like {$id_base}-{$id_number}
		if ( isset($control['id_base']) )
			$id_format = $control['id_base'] . '-__i__';
	}

	$wp_registered_widgets[$widget_id]['callback'] = $wp_registered_widgets[$widget_id]['_callback'];
	unset($wp_registered_widgets[$widget_id]['_callback']);

	$widget_title = esc_html( strip_tags( $sidebar_args['widget_name'] ) );
	$has_form = 'noform';

	echo $sidebar_args['before_widget']; ?>
	<div class="widget-top">
	<?php
	$icon_path = sprintf( plugin_dir_path( __FILE__ ) . 'icons/icon-%s.png', $control['id_base'] );
	$icon_url  = plugin_dir_url( __FILE__ ) . 'icons/icon-default.png';
	if ( file_exists( $icon_path ) ) {
		$icon_url = sprintf( plugin_dir_url( __FILE__ ) . 'icons/icon-%s.png', $control['id_base'] );
	}
	?>
	<?php if ( ! empty( $icon_url ) ): ?>
		<img src="<?php echo esc_url( $icon_url ) ?>" height="24" width="24" alt="Icon" class="widget-icon">
	<?php endif; ?>
	<div class="widget-title-action">
		<a class="widget-action hide-if-no-js" href="#available-widgets"></a>
		<a class="widget-control-edit hide-if-js" href="<?php echo esc_url( add_query_arg( $query_arg ) ); ?>">
			<span class="edit"><?php _ex( 'Edit', 'widget' ); ?></span>
			<span class="add"><?php _ex( 'Add', 'widget' ); ?></span>
			<span class="screen-reader-text"><?php echo $widget_title; ?></span>
		</a>
	</div>
	<div class="widget-title">
		<h4><?php echo $widget_title ?><span class="in-widget-title"></span></h4>
		<div class="widget-description">
			<?php echo ( $widget_description = wp_widget_description($widget_id) ) ? "$widget_description\n" : "$widget_title\n"; ?>
		</div>
	</div>
	</div>

	<div class="widget-inside">
	<form action="" method="post">
	<div class="widget-content">
<?php
	if ( isset($control['callback']) )
		$has_form = call_user_func_array( $control['callback'], $control['params'] );
	else
		echo "\t\t<p>" . __('There are no options for this widget.') . "</p>\n"; ?>
	</div>
	<input type="hidden" name="widget-id" class="widget-id" value="<?php echo esc_attr($id_format); ?>" />
	<input type="hidden" name="id_base" class="id_base" value="<?php echo esc_attr($id_base); ?>" />
	<input type="hidden" name="widget-width" class="widget-width" value="<?php if (isset( $control['width'] )) echo esc_attr($control['width']); ?>" />
	<input type="hidden" name="widget-height" class="widget-height" value="<?php if (isset( $control['height'] )) echo esc_attr($control['height']); ?>" />
	<input type="hidden" name="widget_number" class="widget_number" value="<?php echo esc_attr($widget_number); ?>" />
	<input type="hidden" name="multi_number" class="multi_number" value="<?php echo esc_attr($multi_number); ?>" />
	<input type="hidden" name="add_new" class="add_new" value="<?php echo esc_attr($add_new); ?>" />

	<div class="widget-control-actions">
		<div class="alignleft">
		<a class="widget-control-remove" href="#remove"><?php _e('Delete'); ?></a> |
		<a class="widget-control-close" href="#close"><?php _e('Close'); ?></a>
		</div>
		<div class="alignright<?php if ( 'noform' === $has_form ) echo ' widget-control-noform'; ?>">
			<?php submit_button( __( 'Save' ), 'button-primary widget-control-save right', 'savewidget', false, array( 'id' => 'widget-' . esc_attr( $id_format ) . '-savewidget' ) ); ?>
			<span class="spinner"></span>
		</div>
		<br class="clear" />
	</div>
	</form>
	</div>
<?php
	echo $sidebar_args['after_widget'];
	return $sidebar_args;
}
 */