<?php
/*
Plugin Name: Retina PHP
Plugin URI: http://mintthemes.com
Description: This plugin gives you a template tag which you can use to detact whether the screen is retina or not, and display the image accordingly.
Version: 1.0
Author: Phil Johnston
Author URI: http://mintthemes.com
License: GPL2
*/

/*  Copyright 2012  Phil Johnston  (email : phil@mintthemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* To use this function put the following in the src area of any image
<img src="<?php retina_php_load_image("http://YOURIMAGEURL.jpg"); ?>" />
*/

/**
 * Find the pixel ratio and create the image function accordingly
 */
function retina_php_get_pixelratio(){
    if( isset($_COOKIE["retina_php_pixel_ratio"]) ){
		//use the screen resolution in the cookie to load the right function
        $pixel_ratio = $_COOKIE["retina_php_pixel_ratio"];
        if( $pixel_ratio >= 2 ){
           //"Is HiRes Device";
        	
			/**
			* Include retina_php_load_image function for retina
			*/
			require ( 'inc/load_image@2x.php' );

		}else{
            //"Is NormalRes Device";
			
			/**
			* Include retina_php_load_image function for normal screens
			*/
			require ( 'inc/load_image.php' );
        }
    }else{
		//cookie not set yet
				
		/**
		* Include retina_php_load_image function for normal screens
		*/
		require ( 'inc/load_image.php' );
		
		//Detect screen resolution using js and set the cookie 
		//wp_enqueue_script( 'retina_php_js', plugins_url( '/js/retina_php_js.php', __FILE__ ), array( 'jquery' ) );
		?>
		<script language="javascript">
			writeCookie();
			function writeCookie()
			{
				the_cookie = document.cookie;
				if( the_cookie ){
					if( window.devicePixelRatio >= 2 ){
						the_cookie = "retina_php_pixel_ratio="+window.devicePixelRatio+";"+the_cookie;
						document.cookie = the_cookie;
						location = '<?php $_SERVER['PHP_SELF'] ?>';
					}
				}
			}
		</script>
		<?php 
    }//isset($_COOKIE["pixel_ratio"]) 
}//retina_php_get_pixelratio
add_action( 'wp_enqueue_scripts', 'retina_php_get_pixelratio' );



/**
 * Admin Page and options
 */ 

function retina_php_plugin_options_init() {
	register_setting(
		'retina_php_options',
		'retina_php_options',
		'retina_php_plugin_options_validate'
	);
	//
	add_settings_section(
		'settings',
		__( 'Settings', 'retina_php' ),
		'__return_false',
		'retina_php_options'
	);
	
	add_settings_field(
		'color',
		__( 'Color', 'retina_php' ), 
		'retina_php_settings_field_textbox',
		'retina_php_options',
		'settings',
		array(
			'name'        => 'color',
			'value'       => retina_php_get_plugin_option( 'color' ),
			'description' => __( 'Color', 'retina_php' )
		)
	);
	//
	add_settings_field(
		'text',
		__( 'Coupon Text', 'retina_php' ), 
		'retina_php_settings_field_textbox',
		'retina_php_options',
		'settings',
		array(
			'name'        => 'text',
			'value'       => retina_php_get_plugin_option( 'text' ),
			'description' => __( 'The text you would like to display', 'retina_php' )
		)
	);
	
	//
	add_settings_field(
		'url',
		__( 'URL', 'retina_php' ), 
		'retina_php_settings_field_textbox',
		'retina_php_options',
		'settings',
		array(
			'name'        => 'url',
			'value'       => retina_php_get_plugin_option( 'url' ),
			'description' => __( 'Enter a link.', 'retina_php' )
		)
	);
	//
	add_settings_field(
		'show-hide',
		__( 'Show or Hide', 'retina_php' ), 
		'retina_php_settings_field_select',
		'retina_php_options',
		'settings',
		array(
			'name'        => 'show-hide',
			'value'       => retina_php_get_plugin_option( 'show-hide' ),
			'options'     => array('show','hide'),
			'description' => __( 'Show or Hide the Retina PHP', 'retina_php' )
		)
	);
	

	
}
add_action( 'admin_init', 'retina_php_plugin_options_init' );

/**
 * Change the capability required to save the 'retina_php_options' options group.
 *
 * @see retina_php_plugin_options_init() First parameter to register_setting() is the name of the options group.
 * @see retina_php_plugin_options_add_page() The manage_options capability is used for viewing the page.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function retina_php_option_page_capability( $capability ) {
	return 'manage_options';
}
add_filter( 'option_page_capability_retina_php_options', 'retina_php_option_page_capability' );

/**
 * Add our plugin options page to the admin menu.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since Retina PHP 1.0
 */
function retina_php_plugin_options_add_page() {
	 add_options_page(
		__( 'Retina PHP Options', 'retina_php' ),
		__( 'Retina PHP Options', 'retina_php' ),
		'manage_options',
		'retina_php_options',
		'retina_php_plugin_options_render_page'
	);
	
}
add_action( 'admin_menu', 'retina_php_plugin_options_add_page' );

/**
 * Returns the options array for Retina PHP.
 *
 * @since Retina PHP 1.0
 */
function retina_php_get_plugin_options() {
	$saved = (array) get_option( 'retina_php_options' );
	
	$defaults = array(
		'color'     => '',
		'text' 	=> '',
		'url' 	=> '',
		'show-hide' 	=> '',
	);

	$defaults = apply_filters( 'retina_php_default_plugin_options', $defaults );

	$options = wp_parse_args( $saved, $defaults );
	$options = array_intersect_key( $options, $defaults );

	return $options;
}

/**
 * Get a single plugin option
 *
 * @since Retina PHP 1.0
 */
function retina_php_get_plugin_option( $key ) {
	$options = retina_php_get_plugin_options();
	
	if ( isset( $options[ $key ] ) )
		return $options[ $key ];
		
	return false;
}

/**
 * Renders the Theme Options administration screen.
 *
 * @since Retina PHP 1.0
 */
function retina_php_plugin_options_render_page() {
	
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( 'Retina PHP Options', 'retina_php' ), 'retina_php' ); ?></h2>
		<?php settings_errors(); ?>

		<form action="options.php" method="post">
			<?php
				settings_fields( 'retina_php_options' );
				do_settings_sections( 'retina_php_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see retina_php_plugin_options_init()
 * @todo set up Reset Options action
 *
 * @param array $input Unknown values.
 * @return array Sanitized plugin options ready to be stored in the database.
 *
 * @since Retina PHP 1.0
 */
function retina_php_plugin_options_validate( $input ) {
	$output = array();
	
	
	if ( isset ( $input[ 'color' ] ) )
		$output[ 'color' ] = esc_attr( $input[ 'color' ] );
		
	if ( isset ( $input[ 'text' ] ) )
		$output[ 'text' ] = esc_attr( $input[ 'text' ] );
		
	if ( isset ( $input[ 'url' ] ) )
		$output[ 'url' ] = esc_attr( $input[ 'url' ] );
		
	if ( $input[ 'show-hide' ] == 0 || array_key_exists( $input[ 'show-hide' ], retina_php_get_categories() ) )
		$output[ 'show-hide' ] = $input[ 'show-hide' ];
		
		
	
	$output = wp_parse_args( $output, retina_php_get_plugin_options() );	
		
	return apply_filters( 'retina_php_plugin_options_validate', $output, $input );
}

/* Fields ***************************************************************/
 
/**
 * Number Field
 *
 * @since Retina PHP 1.0
 */
function retina_php_settings_field_number( $args = array() ) {
	$defaults = array(
		'menu'        => '', 
		'min'         => 1,
		'max'         => 100,
		'step'        => 1,
		'name'        => '',
		'value'       => '',
		'description' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	$id   = esc_attr( $name );
	$name = esc_attr( sprintf( 'retina_php_options[%s]', $name ) );
?>
	<label for="<?php echo esc_attr( $id ); ?>">
		<input type="number" min="<?php echo absint( $min ); ?>" max="<?php echo absint( $max ); ?>" step="<?php echo absint( $step ); ?>" name="<?php echo $name; ?>" id="<?php echo $id ?>" value="<?php echo esc_attr( $value ); ?>" />
		<?php echo $description; ?>
	</label>
<?php
} 

/**
 * Textarea Field
 *
 * @since Retina PHP 1.0
 */
function retina_php_settings_field_textarea( $args = array() ) {
	$defaults = array(
		'name'        => '',
		'value'       => '',
		'description' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	$id   = esc_attr( $name );
	$name = esc_attr( sprintf( 'retina_php_options[%s]', $name ) );
?>
	<label for="<?php echo $id; ?>">
		<textarea name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="code large-text" rows="3" cols="30"><?php echo esc_textarea( $value ); ?></textarea>
		<br />
		<?php echo $description; ?>
	</label>
<?php
} 

/**
 * Image Upload Field
 *
 * @since Retina PHP 1.0
 */
function retina_php_settings_field_image_upload( $args = array() ) {
	$defaults = array(
		'name'        => '',
		'value'       => '',
		'description' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	$id   = esc_attr( $name );
	$name = esc_attr( sprintf( 'retina_php_options[%s]', $name ) );
?>
	<label for="<?php echo $id; ?>">
		<input type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo esc_attr( $value ); ?>">
        <input id="upload_image_button" type="button" value="<?php echo __( 'Upload Image', 'retina_php' ); ?>" />
		<br /><?php echo $description; ?>
	</label>
<?php
} 

/**
 * Textbox Field
 *
 * @since Retina PHP 1.0
 */
function retina_php_settings_field_textbox( $args = array() ) {
	$defaults = array(
		'name'        => '',
		'value'       => '',
		'description' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	$id   = esc_attr( $name );
	$name = esc_attr( sprintf( 'retina_php_options[%s]', $name ) );
?>
	<label for="<?php echo $id; ?>">
		<input type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo esc_attr( $value ); ?>">
		<br /><?php echo $description; ?>
	</label>
<?php
} 

/**
 * Single Checkbox Field
 *
 * @since Retina PHP 1.0
 */
function retina_php_settings_field_checkbox_single( $args = array() ) {
	$defaults = array(
		'name'        => '',
		'value'       => '',
		'compare'     => 'on',
		'description' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	$id   = esc_attr( $name );
	$name = esc_attr( sprintf( 'retina_php_options[%s]', $name ) );
?>
	<label for="<?php echo esc_attr( $id ); ?>">
		<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo esc_attr( $value ); ?>" <?php checked( $compare, $value ); ?>>
		<?php echo $description; ?>
	</label>
<?php
} 

/**
 * Radio Field
 *
 * @since Retina PHP 1.0
 */
function retina_php_settings_field_radio( $args = array() ) {
	$defaults = array(
		'name'        => '',
		'value'       => '',
		'options'     => array(),
		'description' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	$id   = esc_attr( $name );
	$name = esc_attr( sprintf( 'retina_php_options[%s]', $name ) );
?>
	<?php foreach ( $options as $option_id => $option_label ) : ?>
	<label title="<?php echo esc_attr( $option_label ); ?>">
		<input type="radio" name="<?php echo $name; ?>" value="<?php echo $option_id; ?>" <?php checked( $option_id, $value ); ?>>
		<?php echo esc_attr( $option_label ); ?>
	</label>
		<br />
	<?php endforeach; ?>
<?php
}

/**
 * Select Field
 *
 * @since Retina PHP 1.0
 */
function retina_php_settings_field_select( $args = array() ) {
	$defaults = array(
		'name'        => '',
		'value'       => '',
		'options'     => array(),
		'description' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	$id   = esc_attr( $name );
	$name = esc_attr( sprintf( 'retina_php_options[%s]', $name ) );
?>
	<label for="<?php echo $id; ?>">
		<select name="<?php echo $name; ?>">
			<?php foreach ( $options as $option_id => $option_label ) : ?>
			<option value="<?php echo esc_attr( $option_id ); ?>" <?php selected( $option_id, $value ); ?>>
				<?php echo esc_attr( $option_label ); ?>
			</option>
			<?php endforeach; ?>
		</select>
		<?php echo $description; ?>
	</label>
<?php
}

/* Helpers ***************************************************************/

function retina_php_get_categories() {
	$output = array();
	$terms  = get_terms( array( 'category' ), array( 'hide_empty' => 0 ) );
	
	foreach ( $terms as $term ) {
		$output[ $term->term_id ] = $term->name;
	}
	
	return $output;
}



