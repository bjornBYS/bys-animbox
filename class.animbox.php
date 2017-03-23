<?php
/**
 * Shortcode class.
 *
 * @package fusion-builder
 * @since 1.0
 */
class FusionSC_AnimBox {

	/**
	 * The alert class.
	 *
	 * @access private
	 * @since 1.0
	 * @var string
	 */
	private $animation_class;

	/**
	 * The image-frame counter.
	 *
	 * @access private
	 * @since 1.0
	 * @var int
	 */
	private $AnimBox_counter = 1;

	/**
	 * The image data.
	 *
	 * @access private
	 * @since 1.0
	 * @var false|array
	 */
	private $image_data = false;

	/**
	 * An array of the shortcode arguments.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var array
	 */
	public static $args;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {

		add_filter( 'fusion_attr_AnimBox-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_AnimBox-shortcode-link', array( $this, 'link_attr' ) );

		add_shortcode( 'fusion_AnimBox', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 *
	 * @access public
	 * @since 1.0
	 * @param  array  $args    Shortcode paramters.
	 * @param  string $content Content between shortcode.
	 * @return string          HTML output.
	 */
	public function render( $args, $content = '' ) {
		$defaults = FusionBuilder::set_shortcode_defaults(
			array(
				'align'               => '',
				'alt'                 => '',
				'blocktitle'    	  => '',
				'blockdescription'    	  => '',
				'animation_direction' => 'left',
				'animation_offset'    => ( class_exists( 'Avada' ) ) ? Avada()->settings->get( 'animation_offset' ) : '',
				'animation_speed'     => '',
				'animation_type'      => '',
				'bordercolor'         => '',
				'borderradius'        => intval( FusionBuilder::get_theme_option( 'AnimBox_border_radius' ) ) . 'px',
				'bordersize'          => ( class_exists( 'Avada' ) ) ? Avada()->settings->get( 'AnimBox_border_size' ) : '',
				'class'               => '',
				'gallery_id'          => '',
				'hide_on_mobile'      => fusion_builder_default_visibility( 'string' ),
				'hover_type'          => 'none',
				'id'                  => '',
				'lightbox'            => 'no',
				'lightbox_image'      => '',
				'link'                => '',
				'linktarget'          => '_self',
				'style'               => '',
				'stylecolor'          => '',
				'image_id'            => '',
				'style_type'          => 'none',  // Deprecated.
			), $args
		);


		$defaults['borderradius'] = FusionBuilder::validate_shortcode_attr_value( $defaults['borderradius'], 'px' );
		$defaults['bordersize']   = FusionBuilder::validate_shortcode_attr_value( $defaults['bordersize'], 'px' );

		if ( ! $defaults['style'] ) {
			$defaults['style'] = $defaults['style_type'];
		}

		if ( $defaults['borderradius'] && 'bottomshadow' == $defaults['style'] ) {
			$defaults['borderradius'] = '0';
		}

		if ( 'round' == $defaults['borderradius'] ) {
			$defaults['borderradius'] = '50%';
		}

		extract( $defaults );

		self::$args = $defaults;
		
		switch ( $args['the_animation_type'] ) {

			case 'optie1':
				$this->animation_class = 'effect-julia';
				/*
				if ( ! $icon || 'none' !== $icon ) {
					self::$args['icon'] = $icon = 'effect-lily';
				}*/
				break;
			case 'optie2':
				$this->animation_class = 'effect-goliath';
				break;
			case 'optie3':
				$this->animation_class = 'effect-hera';
				break;
			case 'optie4':
				$this->animation_class = 'effect-winston';
				break;
			case 'optie5':
				$this->animation_class = 'effect-selena';
				break;
			case 'optie6':
				$this->animation_class = 'effect-terry';
				break;
			case 'optie7':
				$this->animation_class = 'effect-phoebe';
				break;
			case 'optie8':
				$this->animation_class = 'effect-apollo';
				break;
			case 'optie9':
				$this->animation_class = 'effect-kira';
				break;
			case 'optie10':
				$this->animation_class = 'effect-steve';
				break;
			case 'optie11':
				$this->animation_class = 'effect-moses';
				break;
			case 'optie12':
				$this->animation_class = 'effect-jazz';
				break;
			case 'optie13':
				$this->animation_class = 'effect-ming';
				break;
			case 'optie14':
				$this->animation_class = 'effect-lexi';
				break;
			case 'optie15':
				$this->animation_class = 'effect-duke';
				break;
			case 'optie16':
				$this->animation_class = 'effect-lily';
				break;
            case 'optie17':
                $this->animation_class = 'effect-sadie';
                break;
            case 'optie18':
                $this->animation_class = 'effect-honey';
                break;
            case 'optie19':
                $this->animation_class = 'effect-layla';
                break;
            case 'optie20':
                $this->animation_class = 'effect-zoe';
                break;
            case 'optie21':
                $this->animation_class = 'effect-oscar';
                break;
            case 'optie22':
                $this->animation_class = 'effect-marley';
                break;
            case 'optie23':
                $this->animation_class = 'effect-ruby';
                break;
            case 'optie24':
                $this->animation_class = 'effect-roxy';
                break;
            case 'optie25':
                $this->animation_class = 'effect-bubba';
                break;
            case 'optie26':
                $this->animation_class = 'effect-romeo';
                break;
            case 'optie27':
                $this->animation_class = 'effect-dexter';
                break;
            case 'optie28':
                $this->animation_class = 'effect-sarah';
                break;
            case 'optie29':
                $this->animation_class = 'effect-chico';
                break;
            case 'optie30':
                $this->animation_class = 'effect-milo';
                break;
		}

		// Add the needed styles to the img tag.
		if ( ! $bordercolor ) {
			$bordercolor = FusionBuilder::get_theme_option( 'imgframe_border_color' );
		}

		if ( ! $stylecolor ) {
			$stylecolor = FusionBuilder::get_theme_option( 'imgframe_style_color' );
		}

		$rgb = FusionBuilder::hex2rgb( $stylecolor );
		$border_radius = $img_styles = '';

		if ( '0' != $borderradius && '0px' != $borderradius ) {
			$border_radius .= "-webkit-border-radius:{$borderradius};-moz-border-radius:{$borderradius};border-radius:{$borderradius};";
		}

		if ( $border_radius ) {
			$img_styles = ' style="' . $border_radius . '"';
		}

		// Alt tag.
		$title = $alt_tag = $image_url = $image_id = $image_width = $image_height = '';

		preg_match( '/(src=["\'](.*?)["\'])/', $content, $src );

		if ( array_key_exists( '2', $src ) ) {
			$src = $src[2];
		} elseif ( false === strpos( $content, '<img' ) && $content ) {
			$src = $content;
		}

		if ( $src ) {

			$src = str_replace( '&#215;', 'x', $src );

			$image_url = self::$args['pic_link'] = $src;

			$lightbox_image = self::$args['pic_link'];
			if ( self::$args['lightbox_image'] ) {
				$lightbox_image = self::$args['lightbox_image'];
			}

			$this->image_data = FusionBuilder::get_attachment_data_from_url( self::$args['pic_link'] );

			if ( $this->image_data ) {
				$image_width  = ( $this->image_data['width'] ) ? $this->image_data['width'] : '';
				$image_height = ( $this->image_data['height'] ) ? $this->image_data['height'] : '';
				$image_id     = $this->image_data['id'];
				$alt_tag      = ( $this->image_data['alt'] ) ? $this->image_data['alt'] : '';
				$title        = ( $this->image_data['title'] ) ? $this->image_data['title'] : '';
			}

			// For pre 5.0 shortcodes extract the alt tag.
			preg_match( '/(alt=["\'](.*?)["\'])/', $content, $legacy_alt );
			if ( array_key_exists( '2', $legacy_alt ) ) {
				$alt_tag = $legacy_alt[2];
			} elseif ( $alt ) {
				$alt_tag = $alt;
			}

			if ( false !== strpos( $content, 'alt=""' ) && $alt_tag ) {
				$content = str_replace( 'alt=""', $alt_tag, $content );
			} elseif ( false === strpos( $content, 'alt' ) && $alt_tag ) {
				$content = str_replace( '/> ', $alt_tag . ' />', $content );
			}

			if ( 'no' == $lightbox && ! $link ) {
				$title = ' title="' . $title . '"';
			} else {
				$title = '';
			}

			$content = '<img src="' . $image_url . '" width="' . $image_width . '" height="' . $image_height . '" alt="' . $alt_tag . '"' . $title . ' />';
		}

		$img_classes = 'img-responsive';

		if ( ! empty( $image_id ) ) {
			$img_classes .= ' wp-image-' . $image_id;
		}

		// Get custom classes from the img tag.
		preg_match( '/(class=["\'](.*?)["\'])/', $content, $classes );

		if ( ! empty( $classes ) ) {
			$img_classes .= ' ' . $classes[2];
		}

		$img_classes = 'class="' . $img_classes . '"';

		// Add custom and responsive class and the needed styles to the img tag.
		if ( ! empty( $classes ) ) {
			$content = str_replace( $classes[0], $img_classes . $img_styles , $content );
		} else {
			$content = str_replace( '/>', $img_classes . $img_styles . '/>', $content );
		}

		if ( class_exists( 'Avada' ) && property_exists( Avada(), 'images' ) ) {
			Avada()->images->set_grid_image_meta( array( 'layout' => 'large', 'columns' => '1' ) );
		}
		$content = wp_make_content_images_responsive( $content );
		if ( class_exists( 'Avada' ) && property_exists( Avada(), 'images' ) ) {
			Avada()->images->set_grid_image_meta( array() );
		}

		// Set the lightbox image to the dedicated link if it is set.
		if ( $lightbox_image ) {
			self::$args['pic_link'] = $lightbox_image;
		}

		/* if ( $icon && 'none' !== $icon ) {
			
		}*/ 
		$output = '<figure class='. $this->animation_class .'>' . do_shortcode( $content ) . 
			'<figcaption>
				<h2>' . $blocktitle . '</h2>
				<p>'. $blockdescription .'</p>
				<a href="#">View more</a>
			</figcaption>' . 
		'</figure>';

		/*
		if ( 'yes' == $lightbox || $link ) {
			//$output = '<a ' . FusionBuilder::attributes( 'AnimBox-shortcode-link' ) . '>' . do_shortcode( $content ) . '</a>';
			$output = '<figure>' . do_shortcode( $content )  . '</figure>';
		} */ 

		//$html = '<span ' . FusionBuilder::attributes( 'AnimBox-shortcode' ) . '>' . $output . '</span>';
		$html = '<div class="grid">' . $output . '</div>';
		if ( 'liftup' == $hover_type ) {
			$liftup_classes = 'AnimBox-liftup';
			$liftup_styles  = '';

			if ( 'left' == $align ) {
				$liftup_classes .= ' fusion-AnimBox-liftup-left';
			} elseif ( 'right' == $align  ) {
				$liftup_classes .= ' fusion-AnimBox-liftup-right';
			}

			if ( $border_radius ) {
				$liftup_styles = '<style scoped="scoped">.AnimBox-liftup.AnimBox-' . $this->AnimBox_counter . ':before{' . $border_radius . '}</style>';
				$liftup_classes .= ' AnimBox-' . $this->AnimBox_counter;
			}
			$html = '<div ' . FusionBuilder::attributes( $liftup_classes ) . '>' . $liftup_styles . $html . '</div>';
		}

		if ( 'center' == $align ) {
			$html = '<div ' . FusionBuilder::attributes( 'AnimBox-align-center' ) . '>' . $html . '</div>';
		}


		$this->AnimBox_counter++;

		return $html;

	}

	/**
	 * Builds the attributes array.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function attr() {

		$attr = array(
			'style' => '',
		);

		$bordercolor  = self::$args['bordercolor'];
		$stylecolor   = self::$args['stylecolor'];
		$bordersize   = self::$args['bordersize'];
		$borderradius = self::$args['borderradius'];
		$style        = self::$args['style'];

		// Add the needed styles to the img tag.
		if ( ! $bordercolor ) {
			$bordercolor = FusionBuilder::get_theme_option( 'imgframe_border_color' );
		}

		if ( ! $stylecolor ) {
			$stylecolor = FusionBuilder::get_theme_option( 'imgframe_style_color' );
		}

		$rgb = FusionBuilder::hex2rgb( $stylecolor );
		$img_styles = '';

		if ( '0' != $bordersize && '0px' != $bordersize ) {
			$img_styles .= "border:{$bordersize} solid {$bordercolor};";
		}

		if ( '0' != $borderradius && '0px' != $borderradius ) {
			$img_styles .= "-webkit-border-radius:{$borderradius};-moz-border-radius:{$borderradius};border-radius:{$borderradius};";
		}

		if ( 'glow' == $style ) {
			$img_styles .= "-moz-box-shadow: 0 0 3px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);-webkit-box-shadow: 0 0 3px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);box-shadow: 0 0 3px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);";
		} elseif ( 'dropshadow' == $style ) {
			$img_styles .= "-moz-box-shadow: 2px 3px 7px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);-webkit-box-shadow: 2px 3px 7px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);box-shadow: 2px 3px 7px rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},.3);";
		}

		if ( $img_styles ) {
			$attr['style'] .= $img_styles;
		}

		$attr['class'] = 'fusion-AnimBox AnimBox-' . self::$args['style'] . ' AnimBox-' . $this->AnimBox_counter;

		if ( 'bottomshadow' == self::$args['style'] ) {
			$attr['class'] .= ' element-bottomshadow';
		}

		if ( 'liftup' !== self::$args['hover_type'] ) {
			if ( 'left' === self::$args['align'] ) {
				$attr['style'] .= 'margin-right:25px;float:left;';
			} elseif ( 'right' === self::$args['align'] ) {
				$attr['style'] .= 'margin-left:25px;float:right;';
			}

			$attr['class'] .= ' hover-type-' . self::$args['hover_type'];
		}

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		if ( self::$args['animation_type'] ) {
			$animations = FusionBuilder::animations( array(
				'type'      => self::$args['animation_type'],
				'direction' => self::$args['animation_direction'],
				'speed'     => self::$args['animation_speed'],
				'offset'    => self::$args['animation_offset'],
			) );

			$attr = array_merge( $attr, $animations );

			//$attr['class'] .= ' ' . $attr['animation_class'];
			//unset( $attr['animation_class'] );
		}

		return fusion_builder_visibility_atts( self::$args['hide_on_mobile'], $attr );

	}

	/**
	 * Builds the link attributes array.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function link_attr() {

		$attr = array();

		if ( 'yes' == self::$args['lightbox'] ) {
			$attr['href']  = self::$args['pic_link'];
			$attr['class'] = 'fusion-lightbox';

			if ( self::$args['gallery_id'] || '0' === self::$args['gallery_id'] ) {
				$attr['data-rel'] = 'iLightbox[' . self::$args['gallery_id'] . ']';
			} else {
				$attr['data-rel'] = 'iLightbox[' . substr( md5( self::$args['pic_link'] ), 13 ) . ']';
			}

			if ( $this->image_data ) {
				$attr['data-caption'] = $this->image_data['caption'];
				$attr['data-title']   = $this->image_data['title'];
				$attr['title']   = $this->image_data['title'];
			}
		} elseif ( self::$args['link'] ) {
			$attr['class']  = 'fusion-no-lightbox';
			$attr['href']   = self::$args['link'];
			$attr['target'] = self::$args['linktarget'];
			if ( '_blank' == self::$args['linktarget'] ) {
				$attr['rel'] = 'noopener noreferrer';
			}
		}

		return $attr;

	}
}
