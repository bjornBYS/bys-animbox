<?php
/*
Plugin Name: AnimBox
Plugin URI: http://www.byyoursite.nl
Description: ByYourSite Background Hover Effects
Version: 1.0.0
Author: ByYourSite
Author URI: http://www.byyyoursite.nl
Text Domain: bys-animbox
Domain Path: /languages
License: GPL2

AnimBox is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
AnimBoxis distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with AnimBox. If not, see http://www.gnu.org/licenses/gpl.html.
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Developer mode.
if ( ! defined( 'BYS_ANIMBOX_DEV_MODE' ) ) {
	define( 'BYS_ANIMBOX_DEV_MODE', false );
}
// Plugin version.
if ( ! defined( 'BYS_ANIMBOX_VERSION' ) ) {
	define( 'BYS_ANIMBOX_VERSION', '1.0.3' );
}
// Plugin Folder Path.
if ( ! defined( 'BYS_ANIMBOX_PLUGIN_DIR' ) ) {
	define( 'BYS_ANIMBOX_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
// Plugin Folder URL.
if ( ! defined( 'BYS_ANIMBOX_PLUGIN_URL' ) ) {
	define( 'BYS_ANIMBOX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
// Plugin Root File.
if ( ! defined( 'BYS_ANIMBOX_PLUGIN_FILE' ) ) {
	define( 'BYS_ANIMBOX_PLUGIN_FILE', __FILE__ );
}
// CSS path.
if ( ! defined( 'BYS_ANIMBOX_CSS_PATH' ) ) {
	define( 'BYS_ANIMBOX_CSS_PATH', plugin_dir_path( __FILE__ ) . 'css/animbox.css' );
}

register_activation_hook( __FILE__, array( 'BysAnimbox', 'activation' ) );
include_once(BYS_ANIMBOX_PLUGIN_DIR.'class.animbox.php');

/**
 * Main FusionBuilder Class.
 *
 * @since 1.0
 */
class BysAnimbox {
	/**
	 * The one, true instance of this object.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 */
	public static function get_instance() {

		// @codingStandardsIgnoreStart
		global $wp_rich_edit, $is_gecko, $is_opera, $is_safari, $is_chrome, $is_IE, $is_edge;
		// @codingStandardsIgnoreEnd

		if ( ! isset( $wp_rich_edit ) ) {
			$wp_rich_edit = false;

			if ( 'true' == @get_user_option( 'rich_editing' ) || ! @is_user_logged_in() ) { // default to 'true' for logged out users.
				// @codingStandardsIgnoreStart
				if ( $is_safari ) {
					$wp_rich_edit = ! wp_is_mobile() || ( preg_match( '!AppleWebKit/(\d+)!', $_SERVER['HTTP_USER_AGENT'], $match ) && intval( $match[1] ) >= 534 );
				} elseif ( $is_gecko || $is_chrome || $is_IE || $is_edge || ( $is_opera && ! wp_is_mobile() ) ) {
					$wp_rich_edit = true;
				}
				// @codingStandardsIgnoreEnd
			}
		}

		if ( $wp_rich_edit ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
		}

		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( null === self::$instance ) {
			self::$instance = new BysAnimbox();
		}
		return self::$instance;
	}

	/**
	 * Initializes the plugin by setting localization, hooks, filters,
	 * and administrative functions.
	 *
	 * @access private
	 * @since 1.0
	 */
	private function __construct() {
		if ( class_exists( 'FusionBuilder' ) ) {
			new FusionSC_AnimBox();
			add_action( 'fusion_builder_before_init', array($this, 'fusion_element_AnimBox' ));
			$animbox_css_filename = ( true == BYS_ANIMBOX_DEV_MODE ) ? 'css/animbox.css' : 'css/animbox.min.css';
			wp_enqueue_style( 'bys-animbox-style', BYS_ANIMBOX_PLUGIN_URL . $animbox_css_filename, array(), BYS_ANIMBOX_VERSION );
		} else {
			wp_enqueue_style( 'bys-animboxstyle', BYS_ANIMBOX_CSS_PATH, array(), BYS_ANIMBOX_VERSION, 'all' );
		}
	}

	/**
	 * Processes that must run when the plugin is activated.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 */
	public static function activation() {

		$installed_plugins = get_plugins();
		$keys = array_keys( get_plugins() );
		$fusion_builder_key = '';
		$fusion_builder_slug = 'fusion-builder';
		$fusion_builder_version = '';

		foreach ( $keys as $key ) {
			if ( preg_match( '|^' . $fusion_builder_slug . '/|', $key ) ) {
				$fusion_builder_key = $key;
			}
		}

		if ( $fusion_builder_key ) {
			$fusion_builder = $installed_plugins[ $fusion_builder_key ];
			$fusion_builder_version = $fusion_builder['Version'];

			if ( version_compare( $fusion_builder_version, '1.0.3', '<' ) ) {
				$message = '<style>#error-page > p{display:-webkit-flex;display:flex;}#error-page img {height: 120px;margin-right:25px;}.fb-heading{font-size: 1.17em; font-weight: bold; display: block; margin-bottom: 15px;}.fb-link{display: inline-block;margin-top:15px;}.fb-link:focus{outline:none;box-shadow:none;}</style>';
				$message .= '<span><span class="fb-heading">BysAnimbox could not be activated</span>';
				$message .= '<span>BysAnimbox can only be activated on installs that use Fusion Builder 1.0 or higher. Click the link below to install/activate Fusion Builder 1.0.3, then you can activate BysAnim.</span>';
				$message .= '<a class="fb-link" href="' . admin_url( 'admin.php?page=avada-plugins' ) . '">' . esc_attr__( 'Go to the Avada plugin installation page', 'Avada' ) . '</a></span>';
			    wp_die( $message );
		    }
		}
	}

	/**
	 * Map shortcode to Fusion Builder.
	 *
	 * @since 1.0
	 */
	public function fusion_element_AnimBox()
	{
		fusion_builder_map(
			array(
				'name'       => esc_attr__( 'BYS Animatie', 'bys-animbox' ),
				'shortcode'  => 'fusion_AnimBox',
				'icon'       => 'fusiona-image',
				'preview'    => FUSION_BUILDER_PLUGIN_DIR . 'js/previews/fusion-image-frame-preview.php',
				'preview_id' => 'fusion-builder-block-module-image-frame-preview-template',
				'params'     => array(
					array(
						'type'        => 'select',
						'heading'     => esc_attr__( 'Animation Type', 'bys-animbox' ),
						'description' => esc_attr__( 'Select the type of animation.', 'bys-animbox' ),
						'param_name'  => 'the_animation_type',
						'default'     => 'error',
						'value'       => array(
							esc_attr__( 'Background ZoomIn - Subtitle Fly From Left (Julia)', 'bys-animbox' ) => 'optie1',
							esc_attr__( 'Background Up - Subtitle Push From Bottom (Goliath)', 'bys-animbox' )   => 'optie2',
							esc_attr__( 'Hide Title - Show icons in Diamond (Hera)', 'bys-animbox' ) => 'optie3',
							/*esc_attr__( 'Title slide up - Show icons in bottom right Triangle (Winston)', 'bys-animbox' )  => 'optie4', */
							esc_attr__( 'Background ZoomOut - Title slide up, Subtitle Flip down(Selena)', 'bys-animbox' )  => 'optie5',
							/*esc_attr__( 'Background Border Left up - Title slide to left, Icons slide in from right(Terry)', 'bys-animbox' ) => 'optie6',*/
							esc_attr__( 'Background Border Diamond - Title slide up, Icons fly center(Phoebe)', 'bys-animbox' )   => 'optie7',
							esc_attr__( 'Background Blue wash from right top - Subtitle Fadein(Apollo)', 'bys-animbox' ) => 'optie8',
							esc_attr__( 'Background Whitewash - Icons Slidedown(Kira)', 'bys-animbox' )  => 'optie9',
							/*esc_attr__( 'Background Popup with dropshadow - Subtitle Fadein(Steve)', 'bys-animbox' )  => 'optie10',*/
							esc_attr__( 'Background Lensflare - Subtitle fly in from Title with Fadein(Moses)', 'bys-animbox' ) => 'optie11',
							esc_attr__( 'Background Lensflare with diagonal lines - Title ZoomIn, Subtitle Fadein+ZoomIn(Jazz)', 'bys-animbox' )   => 'optie12',
							esc_attr__( 'Subtitle in shrinking Box ZoomIn(Ming)', 'bys-animbox' ) => 'optie13',
							esc_attr__( 'Background white overlay - Subtitle in growing Bubble(Lexi)', 'bys-animbox' )  => 'optie14',
							esc_attr__( 'Background XL Lensflare ZoomIn - Title ZoomIn, Subtitle Bordered ZoomIn (Duke)', 'bys-animbox' )  => 'optie15',
	                        esc_attr__( 'Background shift right - Subtitle slide up(Lily)', 'bys-animbox' )       => 'optie16',
	                        esc_attr__( 'Background black/transparant overlay slide up - Subtitle Fadein(Sadie)', 'bys-animbox' )      => 'optie17',
	                        esc_attr__( 'Background Darkwash & white border bottom - Title Slidedown Subtitle Fadein(honey)', 'bys-animbox' )      => 'optie18',
	                        esc_attr__( 'Background shift down - Title Slidedown Subtitle Fadein Box Fadein(layla)', 'bys-animbox' )      => 'optie19',
	                        esc_attr__( 'Title in bottom white block SlideUp - Subtitle center Slideup(zoe)', 'bys-animbox' )        => 'optie20',
	                        esc_attr__( 'Background warm gradient Fadein - Title slide up, Subtitle Zoomin, White Bordered Box Zoomin(oscar)', 'bys-animbox' )      => 'optie21',
	                        esc_attr__( 'Title SlideUp with underline Slideup - Subtitle SlideUp from bottom(marley)', 'bys-animbox' )     => 'optie22',
	                        esc_attr__( 'Background ZoomOut - Title SlideUp, Bordered Box Subtitle Fadein(ruby)', 'bys-animbox' )       => 'optie23',
	                        esc_attr__( 'Background shift right - Bordered Box Subtitle Fadein & shift from left(roxy)', 'bys-animbox' )       => 'optie24',
	                        esc_attr__( 'Background orange overlay - Title shift down - Subtitle shift up & Fadein - bordered box grow from center(bubba)', 'bys-animbox' )      => 'optie25',
	                        esc_attr__( 'Background ZoomOut with Blue Wash - Title shift down underline transform to X - Subtitle shift up(romeo)', 'bys-animbox' )      => 'optie26',
	                        esc_attr__( 'Bordered box Shift down - Subtitle Fadein(dexter)', 'bys-animbox' )     => 'optie27',
	                        esc_attr__( 'Title underline grow from left - Subtitle Fly From Right(sarah)', 'bys-animbox' )      => 'optie28',
	                        esc_attr__( 'Background ZoomOut & Blue Wash - Bordered Box Fadein - Subtitle Fadein(chico)', 'bys-animbox' )      => 'optie29',
	                        esc_attr__( 'Background Zoomout & shift right & Blue wash - Subtitle Fly From Left(milo)', 'bys-animbox' )       => 'optie30',
						),
					),
					array(
						'type'        => 'upload',
						'heading'     => esc_attr__( 'Image', 'bys-animbox' ),
						'description' => esc_attr__( 'Upload an image to display in the frame.', 'bys-animbox' ),
						'param_name'  => 'element_content',
						'value'       => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Image ID', 'bys-animbox' ),
						'description' => esc_attr__( 'Image ID from Media Library.', 'bys-animbox' ),
						'param_name'  => 'image_id',
						'value'       => '',
						'hidden'      => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Image Alt Text', 'bys-animbox' ),
						'description' => esc_attr__( 'The alt attribute provides alternative information if an image cannot be viewed.', 'bys-animbox' ),
						'param_name'  => 'alt',
						'value'       => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Title', 'bys-animbox' ),
						'description' => esc_attr__( 'Title title title title.', 'bys-animbox' ),
						'param_name'  => 'blocktitle',
						'value'       => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Description', 'bys-animbox' ),
						'description' => esc_attr__( 'put a short description here ', 'bys-animbox' ),
						'param_name'  => 'blockdescription',
						'value'       => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Picture Link URL', 'bys-animbox' ),
						'description' => esc_attr__( 'Add the URL the picture will link to, ex: http://example.com.', 'bys-animbox' ),
						'param_name'  => 'link',
						'value'       => '',
						'dependency'  => array(
							array(
								'element'  => 'lightbox',
								'value'    => 'yes',
								'operator' => '!=',
							),
						),
					),
					array(
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'Link Target', 'bys-animbox' ),
						'description' => __( '_self = open in same window<br />_blank = open in new window.', 'bys-animbox' ),
						'param_name'  => 'linktarget',
						'value'       => array(
							esc_attr__( '_self', 'bys-animbox' )  => '_self',
							esc_attr__( '_blank', 'bys-animbox' ) => '_blank',
						),
						'default'     => '_self',
						'dependency'  => array(
							array(
								'element'  => 'lightbox',
								'value'    => 'yes',
								'operator' => '!=',
							),
							array(
								'element'  => 'link',
								'value'    => '',
								'operator' => '!=',
							),
						),
					),
					array(
						'type'        => 'select',
						'heading'     => esc_attr__( 'Animation Type', 'bys-animbox' ),
						'description' => esc_attr__( 'Select the type of animation to use on the element.', 'bys-animbox' ),
						'param_name'  => 'animation_type',
						'value'       => fusion_builder_available_animations(),
						'default'     => '',
						'group'       => esc_attr__( 'Animation', 'bys-animbox' ),
					),
					array(
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'Direction of Animation', 'bys-animbox' ),
						'description' => esc_attr__( 'Select the incoming direction for the animation.', 'bys-animbox' ),
						'param_name'  => 'animation_direction',
						'value'       => array(
							esc_attr__( 'Top', 'bys-animbox' )    => 'down',
							esc_attr__( 'Right', 'bys-animbox' )  => 'right',
							esc_attr__( 'Bottom', 'bys-animbox' ) => 'up',
							esc_attr__( 'Left', 'bys-animbox' )   => 'left',
							esc_attr__( 'Static', 'bys-animbox' ) => 'static',
						),
						'default'     => 'left',
						'group'       => esc_attr__( 'Animation', 'bys-animbox' ),
						'dependency'  => array(
							array(
								'element'  => 'animation_type',
								'value'    => '',
								'operator' => '!=',
							),
						),
					),
					array(
						'type'        => 'range',
						'heading'     => esc_attr__( 'Speed of Animation', 'bys-animbox' ),
						'description' => esc_attr__( 'Type in speed of animation in seconds (0.1 - 1).', 'bys-animbox' ),
						'param_name'  => 'animation_speed',
						'min'         => '0.1',
						'max'         => '1',
						'step'        => '0.1',
						'value'       => '0.3',
						'group'       => esc_attr__( 'Animation', 'bys-animbox' ),
						'dependency'  => array(
							array(
								'element'  => 'animation_type',
								'value'    => '',
								'operator' => '!=',
							),
						),
					),
					array(
						'type'        => 'select',
						'heading'     => esc_attr__( 'Offset of Animation', 'bys-animbox' ),
						'description' => esc_attr__( 'Controls when the animation should start.', 'bys-animbox' ),
						'param_name'  => 'animation_offset',
						'value'       => array(
							esc_attr__( 'Default', 'bys-animbox' )                                => '',
							esc_attr__( 'Top of element hits bottom of viewport', 'bys-animbox' ) => 'top-into-view',
							esc_attr__( 'Top of element hits middle of viewport', 'bys-animbox' ) => 'top-mid-of-view',
							esc_attr__( 'Bottom of element enters viewport', 'bys-animbox' )      => 'bottom-in-view',
						),
						'default'     => '',
						'group'       => esc_attr__( 'Animation', 'bys-animbox' ),
						'dependency'  => array(
							array(
								'element'  => 'animation_type',
								'value'    => '',
								'operator' => '!=',
							),
						),
					),
					array(
						'type'        => 'checkbox_button_set',
						'heading'     => esc_attr__( 'Element Visibility', 'bys-animbox' ),
						'param_name'  => 'hide_on_mobile',
						'value'       => fusion_builder_visibility_options( 'full' ),
						'default'     => fusion_builder_default_visibility( 'array' ),
						'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'bys-animbox' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'CSS Class', 'bys-animbox' ),
						'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'bys-animbox' ),
						'param_name'  => 'class',
						'value'       => '',
						'group'       => esc_attr__( 'General', 'bys-animbox' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'CSS ID', 'bys-animbox' ),
						'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'bys-animbox' ),
						'param_name'  => 'id',
						'value'       => '',
						'group'       => esc_attr__( 'General', 'bys-animbox' ),
					),
				),
			)
		);
	}
}

/**
 * Instantiate AnimBox class.
 */
function bys_animbox_activate() {
	BysAnimbox::get_instance();
}
add_action( 'wp_loaded', 'bys_animbox_activate' );
