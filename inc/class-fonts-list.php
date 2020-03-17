<?php
class Specialty_Fonts_List {
	/**
	 * Holds an array of font objects, as retrieved by the fonts.json file.
	 *
	 * @var array
	 */
	private $fonts = array();

	/**
	 * Holds a copy of itself, so it can be referenced by the class name.
	 *
	 * @var Specialty_Fonts_List
	 */
	public static $instance;

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return Specialty_Fonts_List
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		$font_path = '/inc/fonts.json';

		$body = false;

		// Try to fetch the file locally.
		$font_file = get_theme_file_path( $font_path );
		if ( is_readable( $font_file ) ) {
			$body = file_get_contents( $font_file );
		}

		// Failed. Try with a remote request.
		if ( false === $body ) {
			$font_file = get_theme_file_uri( $font_path );
			$request   = wp_safe_remote_get( $font_file );

			if ( is_wp_error( $request ) ) {
				return false;
			}

			$body = wp_remote_retrieve_body( $request );
		}

		if ( empty( $body ) ) {
			return false;
		}

		$content = json_decode( $body );

		if ( ! is_null( $content ) ) {
			$this->fonts = $content->items;
		}
	}

	/**
	 * Returns the fonts array, as retrieved from the json file.
	 *
	 * @return array
	 */
	public function get() {
		return $this->fonts;
	}

	/**
	 * Returns an array of fonts divided into groups.
	 *
	 * @return array
	 */
	public function get_font_options() {
		$fonts = array(
			'standard'     => array(
				'label' => esc_html__( 'Standard Fonts', 'specialty' ),
				'fonts' => array(),
			),
			'google_fonts' => array(
				'label' => esc_html__( 'Google Fonts', 'specialty' ),
				'fonts' => array(),
			),
		);

		foreach ( $this->fonts as $font ) {
			if ( isset( $font->kind ) && 'webfonts#webfont' === $font->kind ) {
				array_push( $fonts['google_fonts']['fonts'], $font );
			} else {
				array_push( $fonts['standard']['fonts'], $font );
			}
		}

		return $fonts;
	}

	/**
	 * Echoes the optgroup and option elements required by a <select> dropdown to display the fonts.
	 *
	 * @param string $selected
	 */
	public function echo_font_options( $selected = '' ) {
		$fonts = $this->get_font_options();

		foreach ( $fonts as $optgroup ) {
			?><optgroup label="<?php echo esc_attr( $optgroup['label'] ); ?>"><?php

			foreach ( $optgroup['fonts'] as $font ) {
				?>
				<option value="<?php echo esc_attr( $font->family ); ?>" <?php selected( $selected, $font->family ); ?>>
					<?php echo wp_kses( $font->family, 'strip' ); ?>
				</option>
				<?php
			}

			?></optgroup><?php
		}
	}

	/**
	 * Fetches text transform choices.
	 *
	 * @access public
	 * @return array
	 */
	public function get_transform_choices() {
		return array(
			''          => __( 'Inherit', 'specialty' ),
			'none'      => __( 'None', 'specialty' ),
			'uppercase' => __( 'Uppercase', 'specialty' ),
			'lowercase' => __( 'Lowercase', 'specialty' ),
		);
	}

	/**
	 * Returns font variant labels.
	 *
	 * @access public
	 * @return array
	 */
	public function get_variant_labels() {
		return array(
			'100'       => __( 'Thin 100', 'specialty' ),
			'100italic' => __( 'Thin 100 Italic', 'specialty' ),
			'200'       => __( 'Light 200', 'specialty' ),
			'200italic' => __( 'Light 200 Italic', 'specialty' ),
			'300'       => __( 'Book 300', 'specialty' ),
			'300italic' => __( 'Book 300 Italic', 'specialty' ),
			'regular'   => __( 'Normal 400', 'specialty' ),
			'400'       => __( 'Normal 400', 'specialty' ),
			'italic'    => __( 'Normal 400 Italic', 'specialty' ),
			'400italic' => __( 'Normal 400 Italic', 'specialty' ),
			'500'       => __( 'Medium 500', 'specialty' ),
			'500italic' => __( 'Medium 500 Italic', 'specialty' ),
			'600'       => __( 'Semibold 500', 'specialty' ),
			'600italic' => __( 'Semibold 500 Italic', 'specialty' ),
			'700'       => __( 'Bold 700', 'specialty' ),
			'700italic' => __( 'Bold 700 Italic', 'specialty' ),
			'800'       => __( 'Extra Bold 800', 'specialty' ),
			'800italic' => __( 'Extra Bold 800 Italic', 'specialty' ),
			'900'       => __( 'Heavy 900', 'specialty' ),
			'900italic' => __( 'Heavy 900 Italic', 'specialty' ),
		);
	}
}
