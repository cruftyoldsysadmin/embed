<?php
class Specialty_Customizer_CSS_Generator {

	/**
	 * Holds a copy of itself, so it can be referenced by the class name.
	 *
	 * @var Specialty_Customizer_CSS_Generator
	 */
	public static $instance;

	protected $css = array(
		'desktop'      => array(),
		'tablet'       => array(),
		'mobile'       => array(),
		'desktop-only' => array(),
		'tablet-only'  => array(),
		'mobile-only'  => array(),
	);

	/**
	 * 'name' => 'default' list of typography controls.
	 *
	 * @var array
	 */
	protected $typography_controls = array();

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return Specialty_Customizer_CSS_Generator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_array( $breakpoint ) {
		$return = array();

		if ( array_key_exists( $breakpoint, $this->css ) ) {
			$return = $this->css[ $breakpoint ];
		}

		$return = array_map( 'trim', $return );
		$return = array_filter( $return );

		return apply_filters( 'specialty_customizer_css_generator_get_array', $return, $breakpoint );
	}

	public function get( $breakpoint ) {
		$return = implode( PHP_EOL, $this->get_array( $breakpoint ) );

		return apply_filters( 'specialty_customizer_css_generator_get', $return, $breakpoint );
	}

	/**
	 * @param string $breakpoint
	 * @param string $css
	 */
	public function add( $breakpoint, $css ) {
		if ( ! array_key_exists( $breakpoint, $this->css ) ) {
			trigger_error( sprintf( 'Invalid breakpoint: %s', $breakpoint ) );
		}

		$css = trim( $css );
		$css = apply_filters( 'specialty_customizer_css_generator_add', $css, $breakpoint );

		if ( ! empty( $css ) ) {
			$this->css[ $breakpoint ][] = $css;
		}
	}

	/**
	 * Adds CSS, if the value is not empty.
	 * If the placeholder %s is found in $css, it will be replaced with the value.
	 * When a $callback is set, the value is always passed as the first argument.
	 *
	 * Examples:
	 *  $css->add_value( 'mobile', get_theme_mod( 'header_layout_menu_type' ), 'body { background-color: %s; color: %s; }' );
	 *  $css->add_value( 'mobile', get_theme_mod( 'header_layout_menu_type' ), 'body { background-color: %s; color: %s; }', 'absint' );
	 *  $css->add_value( 'mobile', get_theme_mod( 'header_layout_menu_type' ), 'body { background-color: %s; color: %s; }', 'strtok', array( '_' ) );
	 *
	 * @param string     $breakpoint       The breakpoint to apply the css rule.
	 * @param mixed      $value            The value to be added.
	 * @param string     $css              CSS rule. May contain the placeholder %s which will be replaced with the value.
	 * @param bool       $breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
	 * @param null|mixed $callback         Optional. If provided, the value will be passed through the callback as the first argument.
	 * @param array      $callback_args    Optional. Additional arguments to pass to the callback.
	 */
	public function add_value( $breakpoint, $value, $css, $breakpoint_limit = false, $callback = null, $callback_args = array() ) {
		if ( $this->value_is_empty( $value ) ) {
			return;
		}

		if ( ! empty( $callback ) && is_callable( $callback ) ) {
			$args  = array_merge( array( $value ), $callback_args );
			$value = call_user_func_array( $callback, $args );
		}

		$css = str_replace( '%s', $value, $css );

		if ( $breakpoint_limit ) {
			$this->add( "{$breakpoint}-only", $css );
		} else {
			$this->add( $breakpoint, $css );
		}
	}

	/**
	 * Adds CSS for simple breakpoint structured values (i.e. breakpoint => value array).
	 * Can accept a string for $breakpoints_css to apply to all breakpoint, or an array for each individual one.
	 * Examples:
	 *
	 *  $value = array(
	 *    'desktop' => '#f00',
	 *    'tablet'  => '#0f0',
	 *    'mobile'  => '#00f',
	 *  );
	 *
	 *  // Different selector per breakpoint.
	 *  $css->add_responsive( $value, array(
	 *    'desktop' => 'body { background-color: %s; }',
	 *    'tablet'  => 'body { background-color: %s; }',
	 *    'mobile'  => 'body { background-color: %s; }',
	 *  ) );
	 *
	 *  // Same selector for all breakpoints.
	 *  $css->add_responsive( $value, '.site-logo a { background-color: %s; }' );
	 *
	 * @param array        $values           breakpoint => value array.
	 * @param string|array $breakpoints_css  breakpoint => css array to apply if value exists.
	 * @param bool         $breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
	 * @param array        $edge_cases       Array where the key is an edge case value, and the value is the CSS rule to apply.
	 */
	public function add_responsive( $values, $breakpoints_css, $breakpoint_limit = false, $edge_cases = array() ) {
		if ( ! is_array( $values ) || empty( $breakpoints_css ) ) {
			return;
		}

		foreach ( $values as $breakpoint => $value ) {
			if ( ! $this->value_is_empty( $value ) ) {
				if ( array_key_exists( $value, $edge_cases ) ) {
					$this->add_value( $breakpoint, $value, $edge_cases[ $value ], $breakpoint_limit );
				} else {
					if ( is_string( $breakpoints_css ) ) {
						$this->add_value( $breakpoint, $value, $breakpoints_css, $breakpoint_limit );
					} elseif ( is_array( $breakpoints_css ) && ! empty( $breakpoints_css[ $breakpoint ] ) ) {
						$this->add_value( $breakpoint, $value, $breakpoints_css[ $breakpoint ], $breakpoint_limit );
					}
				}
			}
		}
	}

	/**
	 * Adds CSS for the spacing control.
	 * Can accept a string for $breakpoints_css to apply to all breakpoint, or an array for each individual one.
	 *
	 * Examples:
	 *
	 *  $values = array(
	 *    'desktop' => array(
	 *      'top'    => 10,
	 *      'right'  => 20,
	 *      'bottom' => 15,
	 *      'left'   => 40,
	 *      'linked' => false,
	 *    ),
	 *    'tablet'  => array(
	 *      'top'    => 10,
	 *      'right'  => 10,
	 *      'bottom' => 10,
	 *      'left'   => 10,
	 *      'linked' => true,
	 *    ),
	 *    'mobile'  => array(
	 *      'top'    => '',
	 *      'right'  => '',
	 *      'bottom' => '',
	 *      'left'   => '',
	 *      'linked' => false,
	 *    )
	 *  );
	 *
	 *  // Same selector for all breakpoints.
	 *  $css->add_spacing( $values, '.header { %s }', 'padding', 'px' );
	 *
	 *  // Different selector per breakpoint.
	 *  $css->add_spacing( $value, array(
	 *    'desktop' => '.header { %s }',
	 *    'tablet'  => '.header { %s }',
	 *    'mobile'  => '.header { %s }',
	 *  ), 'padding', 'px' );
	 *
	 * @param array        $values           The array of values, as returned by the spacing control. It should include all breakpoints.
	 * @param string|array $breakpoints_css  breakpoint => css array to apply if value exists.
	 * @param string       $mode             The property to generate the rules for (something that gets -top, -right, etc). E.g. 'margin', 'padding', etc.
	 *                                       Special case 'position' generates top: right: etc ruls without a prefix-. Default 'margin'.
	 * @param string       $unit             The unit to suffix the values with. E.g. 'px', 'em', etc. Default 'px'.
	 * @param bool         $breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
	 */
	public function add_spacing( $values, $breakpoints_css, $mode = 'margin', $unit = 'px', $breakpoint_limit = false ) {
		if ( ! is_string( $mode ) || empty( $mode ) ) {
			return;
		}

		if ( ! is_array( $values ) || empty( $breakpoints_css ) ) {
			return;
		}

		foreach ( $values as $breakpoint => $value ) {
			if ( ! empty( $value ) ) {
				$rules = $this->generate_spacing_rules( $value, $mode, $unit );

				if ( is_string( $breakpoints_css ) ) {
					$this->add_value( $breakpoint, $rules, $breakpoints_css, $breakpoint_limit );
				} elseif ( is_array( $breakpoints_css ) && ! empty( $breakpoints_css[ $breakpoint ] ) ) {
					$this->add_value( $breakpoint, $rules, $breakpoints_css[ $breakpoint ], $breakpoint_limit );
				}
			}
		}
	}

	/**
	 * Generates the appropriate CSS rules for "spacing" controls, e.g. padding and margin, for a single breakpoint.
	 *
	 * @param array  $values The array of values for a single breakpoint.
	 * @param string $mode   The property to generate the rules for (something that gets -top, -right, etc). E.g. 'margin', 'padding', etc.
	 *                       Special case 'position' generates top: right: etc ruls without a prefix-. Default 'margin'.
	 * @param string $unit   The unit to suffix the values with. E.g. 'px', 'em', etc. Default 'px'.
	 *
	 * @return string
	 */
	public function generate_spacing_rules( $values, $mode = 'margin', $unit = 'px' ) {
		if ( ! is_array( $values ) || empty( $values ) ) {
			return '';
		}

		$rules = array();

		$linked = false;
		if ( isset( $values['linked'] ) ) {
			$linked = $values['linked'];
			unset( $values['linked'] );
		}

		$prefix = "{$mode}-";
		if ( 'position' === $mode ) {
			$prefix = '';
		}

		foreach ( $values as $direction => $value ) {
			if ( ! $this->value_is_empty( $value ) ) {
				if ( 0 === intval( $value ) ) {
					$rules[] = "{$prefix}{$direction}: {$value};";
				} else {
					$rules[] = "{$prefix}{$direction}: {$value}{$unit};";
				}
			}
		}

		return implode( ' ', $rules );
	}

	/**
	 * Add CSS for typography control.
	 * Can accept a string for $breakpoints_css to apply to all breakpoint, or an array for each individual one.
	 *
	 * @param array        $values           The array of values, as returned by the typography control. It should include all breakpoints.
	 * @param string|array $fallback_stack   Array or comma-separated string of fallback font names. It's considered only when 'family' is set for a breakpoint.
	 * @param string|array $breakpoints_css  breakpoint => css array to apply if value exists.
	 * @param bool         $breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
	 */
	public function add_typography( $values, $fallback_stack, $breakpoints_css, $breakpoint_limit = false ) {
		if ( ! is_array( $values ) || empty( $breakpoints_css ) ) {
			return;
		}

		foreach ( $values as $breakpoint => $value ) {
			if ( ! empty( $value ) && is_array( $value ) ) {

				$rules = $this->generate_typography_rules( $value, $fallback_stack );

				if ( is_string( $breakpoints_css ) ) {
					$this->add_value( $breakpoint, $rules, $breakpoints_css, $breakpoint_limit );
				} elseif ( is_array( $breakpoints_css ) && ! empty( $breakpoints_css[ $breakpoint ] ) ) {
					$this->add_value( $breakpoint, $rules, $breakpoints_css[ $breakpoint ], $breakpoint_limit );
				}
			}
		}
	}

	/**
	 * Add CSS for the image background control.
	 *
	 * @param array  $values           The array of values, as returned by the image-bg control.
	 * @param string $image_size       Image size name, e.g. 'post-thumbnail'.
	 * @param string $css              CSS selector to apply if value exists.
	 * @param string $background_color Optional background color to include in the generated CSS.
	 */
	public function add_image_background_by_id( $values, $image_size, $css, $background_color = '' ) {

		if ( ! is_array( $values ) || empty( $css ) || empty( $image_size ) ) {
			return;
		}

		$url = false;
		if ( isset( $values['image_id'] ) && ! $this->value_is_empty( $values['image_id'] ) ) {
			$url = wp_get_attachment_image_url( $values['image_id'], $image_size );
		}

		$rules = $this->generate_image_background_rules( $values, $url, $background_color );

		if ( ! empty( $rules ) ) {
			$this->add_value( 'desktop', $rules, $css );
		}
	}

	/**
	 * Add CSS for the image background control. Uses the actual size selected by the user.
	 *
	 * @param array  $values           The array of values, as returned by the image-bg control.
	 * @param string $css              CSS selector to apply if value exists.
	 * @param string $background_color Optional background color to include in the generated CSS.
	 */
	public function add_image_background_by_url( $values, $css, $background_color = '' ) {
		if ( ! is_array( $values ) || empty( $css ) ) {
			return;
		}

		$url = false;
		if ( ! empty( $values['image_url'] ) ) {
			$url = $values['image_url'];
		}

		$rules = $this->generate_image_background_rules( $values, $url, $background_color );

		if ( ! empty( $rules ) ) {
			$this->add_value( 'desktop', $rules, $css );
		}
	}

	/**
	 * Generates the appropriate CSS rules for "image-background" controls.
	 *
	 * @param  array  $values           The array of values, as returned by the image-bg control.
	 * @param  string $image_url        The image url to use as background.
	 * @param string  $background_color Optional background color.
	 *
	 * @return string
	 */
	public function generate_image_background_rules( $values, $image_url, $background_color = '' ) {
		$rules = array();

		if ( ! $this->value_is_empty( $background_color ) ) {
			$rules[] = sprintf( 'background-color: %s;', $background_color );
		}

		if ( ! empty( $image_url ) ) {
			$rules[] = sprintf( 'background-image: url(%s);', esc_url_raw( $image_url ) );

			if ( ! empty( $values['repeat'] ) ) {
				$rules[] = sprintf( 'background-repeat: %s;', $values['repeat'] );
			}

			if ( ! empty( $values['position'] ) ) {
				$rules[] = sprintf( 'background-position: %s;', $values['position'] );
			}

			if ( ! empty( $values['attachment'] ) ) {
				$rules[] = sprintf( 'background-attachment: %s;', $values['attachment'] );
			}

			if ( ! empty( $values['size'] ) ) {
				$rules[] = sprintf( 'background-size: %s;', $values['size'] );
			}
		}

		return implode( ' ', $rules );
	}


	/**
	 * Generates the appropriate CSS rules for "typography" controls, for a single breakpoint.
	 *
	 * @param array        $values         The array of values for a single breakpoint.
	 * @param string|array $fallback_stack Array or comma-separated string of fallback font names. It's considered only when 'family' is set.
	 *
	 * @return string
	 */
	public function generate_typography_rules( $values, $fallback_stack = '' ) {
		$rules = array();

		if ( isset( $values['family'] ) && ! $this->value_is_empty( $values['family'] ) ) {
			$font       = $values['family'];
			$font_array = explode( ',', $font );

			$stack = array_map( function ( $font_string ) {
				$trimmed_font_string = trim( $font_string );

				if ( false !== strpos( $trimmed_font_string, ' ' ) ) {
					return '"' . $trimmed_font_string . '"';
				}

				return $trimmed_font_string;
			}, $font_array );

			if ( ! empty( $fallback_stack ) && is_string( $fallback_stack ) ) {
				$fallback_stack = explode( ',', $fallback_stack );
				$fallback_stack = array_map( 'trim', $fallback_stack );
				$fallback_stack = array_filter( $fallback_stack );
			}

			if ( ! empty( $fallback_stack ) && is_array( $fallback_stack ) ) {
				$stack = array_merge( array( $font ), $fallback_stack );
			}

			$rules[] = sprintf( 'font-family: %s;', implode( ', ', $stack ) );
		}

		if ( isset( $values['variant'] ) && ! $this->value_is_empty( $values['variant'] ) ) {
			$variant = $values['variant'];

			$weight = $this->convert_font_variant_to_weight( $variant );
			$style  = $this->convert_font_variant_to_style( $variant );

			if ( ! $this->value_is_empty( $weight ) ) {
				$rules[] = sprintf( 'font-weight: %s;', $weight );
			}

			if ( ! $this->value_is_empty( $style ) ) {
				$rules[] = sprintf( 'font-style: %s;', $style );
			}
		}

		if ( isset( $values['size'] ) && ! $this->value_is_empty( $values['size'] ) ) {
			$rules[] = sprintf( 'font-size: %spx;', $values['size'] );
		}

		if ( isset( $values['lineHeight'] ) && ! $this->value_is_empty( $values['lineHeight'] ) ) {
			$rules[] = sprintf( 'line-height: %spx;', $values['lineHeight'] );
		}

		if ( isset( $values['transform'] ) && ! $this->value_is_empty( $values['transform'] ) ) {
			$rules[] = sprintf( 'text-transform: %s;', $values['transform'] );
		}

		if ( isset( $values['spacing'] ) && ! $this->value_is_empty( $values['spacing'] ) ) {
			$rules[] = sprintf( 'letter-spacing: %spx;', $values['spacing'] );
		}

		return implode( ' ', $rules );
	}

	/**
	 * Checks whether the passed value should be considered empty for CSS reasons.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function value_is_empty( $value ) {
		$value = trim( $value );

		if ( false === $value || '' === $value ) {
			return true;
		}

		return false;
	}

	/**
	 * Registers an option name as a typography control. Used to build and enqueue Google Fonts automatically.
	 *
	 * @param string $option_name
	 */
	public function register_typography_control( $option_name, $default ) {
		if ( ! array_key_exists( $option_name, $this->typography_controls ) ) {
			$this->typography_controls[ $option_name ] = $default;
		}
	}

	/**
	 * Build a Google Fonts query URL, based on the registered typography controls.
	 */
	public function get_google_fonts_url() {
		// font_name => array( variations )
		$fonts = array();

		$fonts_url = '';

		foreach ( $this->typography_controls as $option_name => $default ) {
			$global_option   = get_theme_mod( $option_name, $default );
			$override_option = false;

			if ( in_array( $option_name, array( 'hero_typo_primary_text', 'hero_typo_secondary_text' ), true ) ) {
				$data            = specialty_get_hero_data();
				$key             = str_replace( 'hero_typo_', '', $option_name );
				$override_option = $data['typography'][ $key ];
			} elseif ( is_singular() ) {
				$override_option = get_post_meta( get_queried_object_id(), $option_name, true );
			}

			$breakpoints = array( 'desktop', 'tablet', 'mobile' );

			foreach ( $breakpoints as $breakpoint ) {
				$bp_values = false;

				if ( ! empty( $override_option ) && ! empty( $override_option[ $breakpoint ] ) && ! empty( $override_option[ $breakpoint ]['family'] ) ) {
					$bp_values = (array) $override_option[ $breakpoint ];
				} elseif ( ! empty( $global_option ) && ! empty( $global_option[ $breakpoint ] ) && ! empty( $global_option[ $breakpoint ]['family'] ) ) {
					$bp_values = (array) $global_option[ $breakpoint ];
				}

				if ( empty( $bp_values ) || empty( $bp_values['family'] ) ) {
					continue;
				}

				// Skip fonts that aren't Google Fonts, or any that we don't know whether they are or not.
				if ( ! array_key_exists( 'is_gfont', $bp_values ) || false === (bool) $bp_values['is_gfont'] ) {
					continue;
				}

				$family  = $bp_values['family'];
				$variant = isset( $bp_values['variant'] ) ? $bp_values['variant'] : '';

				// Create an entry in the $fonts array.
				if ( ! array_key_exists( $family, $fonts ) ) {
					$fonts[ $family ] = array();
				}

				$weight = $this->convert_font_variant_to_weight( $variant );
				$style  = $this->convert_font_variant_to_style( $variant );
				$style  = 'italic' === $style ? 'i' : '';

				$fonts[ $family ][] = $weight . $style;
			}
		}

		$font_strings = array();

		$fonts_list = Specialty_Fonts_List::get_instance();
		$all_fonts  = $fonts_list->get();


		foreach ( $fonts as $font_name => $font_weights ) {
			if ( is_customize_preview() ) {
				$font_obj = wp_filter_object_list( $all_fonts, array( 'family' => $font_name ), 'and' );
				if ( ! empty( $font_obj ) ) {
					$font_obj = array_values( $font_obj );
					$font_obj = $font_obj[0];
					if ( ! empty( $font_obj->variants ) ) {
						foreach ( $font_obj->variants as $variant ) {
							$weight = $this->convert_font_variant_to_weight( $variant );
							$style  = $this->convert_font_variant_to_style( $variant );
							$style  = 'italic' === $style ? 'i' : '';

							$font_weights[] = $weight . $style;
						}
					}
				}
			}

			$font_weights = array_filter( $font_weights );
			$font_weights = array_unique( $font_weights );

			// Example format: 'Fira Sans:400,400i,500,700,900'
			$font_strings[] = sprintf( '%s:%s', $font_name, implode( ',', $font_weights ) );
		}

		if ( ! empty( $font_strings ) ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', $font_strings ) ),
			), 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}

	private function convert_font_variant_to_weight( $variant ) {
		if ( empty( $variant ) || 'regular' === $variant || 'italic' === $variant ) {
			return '400';
		} else {
			$success = preg_match( '/(\d*)(\w*)/', $variant, $matches );
			if ( $success ) {
				$weight = $matches[1];

				if ( empty( $weight ) ) {
					$weight = '400';
				}

				return $weight;
			}
		}

		return '400';
	}

	private function convert_font_variant_to_style( $variant ) {
		if ( empty( $variant ) || 'regular' === $variant ) {
			return '';
		} elseif ( 'italic' === $variant ) {
			return 'italic';
		} else {
			$success = preg_match( '/(\d*)(\w*)/', $variant, $matches );
			if ( $success ) {
				$style = $matches[2];

				return $style;
			}
		}

		return '';
	}

}
