<?php

/**
 * Media Uploader Using the WordPress Media Library.
 *
 * Parameters:
 * - string $_id - A token to identify this field (the name).
 * - string $_value - The value of the field, if present.
 * - string $_desc - An optional description of the field.
 *
 */

if ( ! function_exists( 'optionsframework_uploader' ) ) :

function optionsframework_uploader( $_id, $_value, $_desc = '', $_name = '' ) {

	$optionsframework_settings = get_option( 'optionsframework' );

	// Gets the unique option id
	if ( isset( $optionsframework_settings['id'] ) ) {
		$option_name = $optionsframework_settings['id'];
	}
	else {
		$option_name = 'options_framework_theme';
	};

	$output = '';
	$id = '';
	$class = '';
	$int = '';
	$value = '';
	$name = '';

	$id = strip_tags( strtolower( $_id ) );

	// If a value is passed and we don't have a stored value, use the value that's passed through.
	if ( $_value != '' && $value == '' ) {
		$value = $_value;
	}

	if ( $_name != '' ) {
		$name = $_name;
	}
	else {
		$name = $option_name.'['.$id.']';
	}

	if ( $value ) {
		$class = ' has-file';
	}
	$output .= '<input id="' . $id . '" class="upload' . $class . '" type="text" name="'.$name.'" value="' . $value . '" placeholder="' . __('未选择文件', 'options_framework_theme') .'" />' . "\n";
	if ( function_exists( 'wp_enqueue_media' ) ) {
		if ( ( $value == '' ) ) {
			$output .= '<input id="upload-' . $id . '" class="upload-button button" type="button" value="' . __( '上传', 'options_framework_theme' ) . '" />' . "\n";
		} else {
			$output .= '<input id="remove-' . $id . '" class="remove-file button" type="button" value="' . __( '移除', 'options_framework_theme' ) . '" />' . "\n";
		}
	} else {
		$output .= '<p><i>' . __( 'Upgrade your version of WordPress for full media support.', 'options_framework_theme' ) . '</i></p>';
	}

	if ( $_desc != '' ) {
		$output .= '<span class="of-metabox-desc">' . $_desc . '</span>' . "\n";
	}

	$output .= '<div class="screenshot" id="' . $id . '-image">' . "\n";

	if ( $value != '' ) {
		$remove = '<a class="remove-image">Remove</a>';
		$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
		if ( $image ) {
			$output .= '<img src="' . $value . '" alt="" />'.$remove.'';
		} else {
			$parts = explode( "/", $value );
			for( $i = 0; $i < sizeof( $parts ); ++$i ) {
				$title = $parts[$i];
			}

			// No output preview if it's not an image.
			$output .= '';

			// Standard generic output if it's not an image.
			$title = __( 'View File', 'options_framework_theme' );
			$output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">'.$title.'</a></span></div>';
		}
	}
	$output .= '</div>' . "\n";
	return $output;
}

endif;

/**
 * Enqueue scripts for file uploader
 */

if ( ! function_exists( 'optionsframework_media_scripts' ) ) :

add_action( 'admin_enqueue_scripts', 'optionsframework_media_scripts' );

function optionsframework_media_scripts( $hook ) {

	$menu = optionsframework_menu_settings();

	if ( 'appearance_page_' . $menu['menu_slug'] != $hook )
		return;

	if ( function_exists( 'wp_enqueue_media' ) )
		wp_enqueue_media();
	wp_register_script( 'of-media-uploader', OPTIONS_FRAMEWORK_DIRECTORY .'js/media-uploader.js', array( 'jquery' ) );
	wp_enqueue_script( 'of-media-uploader' );
	wp_localize_script( 'of-media-uploader', 'optionsframework_l10n', array(
		'upload' => __( '上传', 'options_framework_theme' ),
		'remove' => __( '移除', 'options_framework_theme' )
	) );
}

endif;
