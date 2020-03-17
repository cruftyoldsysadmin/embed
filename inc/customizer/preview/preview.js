/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview changes asynchronously.
 *
 * https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#using-postmessage-for-improved-setting-previewing
 */

(function ( $ ) {
	var SPECIALTY_PREVIEW_SCRIPTS = {
		/**
		 * The available breakpoints.
		 *
		 * @enum breakpoints
		 */
		breakpoints: {
			'desktop': '',
			'tablet': 991,
			'mobile': 575,
		},

		/**
		 * Checks if a value is undefined, null, or empty string.
		 *
		 * @param {*} value - The value.
		 * @returns {boolean}
		 */
		isNil: function ( value ) {
			return value == null || value === '';
		},

		/**
		 * Injects a namespaced stylesheet in the <body> element of the document
		 * or replaces its contents with the provided styles if it already exists.
		 *
		 * @param {string} settingName - The setting's name.
		 * @param {string} styles - A string of generated styles.
		 */
		injectStylesheet: function ( settingName, styles ) {
			var $stylesheet = $( 'style.' + settingName );

			if ( $stylesheet.length ) {
				$stylesheet.text( styles );
			} else {
				$( '<style />', {
					class: settingName,
				} ).text( styles ).appendTo( 'body' );
			}
		},

		/**
		 * Wraps a set of style rules in a breakpoint media query (if necessary).
		 *
		 * @param {breakpoints} breakpoint - A breakpoint.
		 * @param {string} styles - The CSS rules (styles).
		 * @returns {string} - The CSS rules optionally wrapped in a media query.
		 */
		wrapMediaQuery: function ( breakpoint, styles ) {
			if (breakpoint === 'desktop') {
				return styles;
			}

			return '@media (max-width: ' + this.breakpoints[breakpoint] + 'px) { ' + styles + ' }';
		},

		/**
		 * Wraps a set of style rules in a breakpoint media query that applies
		 * only the the particular media query.
		 *
		 * @param {breakpoints} breakpoint - A breakpoint.
		 * @param {string} styles - The CSS rules (styles).
		 * @returns {string} - The CSS rules optionally wrapped in a media query.
		 */
		wrapMediaQueryOnly: function ( breakpoint, styles ) {
			var desktopMin = this.breakpoints['tablet'] + 1;
			var tabletMax = this.breakpoints['tablet'];
			var tabletMin = this.breakpoints['mobile'] + 1;
			var mobileMax = this.breakpoints['mobile'];

			if (breakpoint === 'desktop') {
				return '@media (min-width: ' + desktopMin + 'px) { ' + styles + ' }';
			}

			if (breakpoint === 'tablet') {
				return '@media (min-width: ' + tabletMin + 'px) and (max-width: ' + tabletMax + 'px) { ' + styles + ' }';
			}

			if (breakpoint === 'mobile') {
				return '@media (max-width: ' + mobileMax + 'px) { ' + styles + ' }';
			}
		},

		/**
		 * Given a font variant (as defined in fonts.json) returns the font weight.
		 *
		 * @param {string} variant - The font variant setting.
		 * @return {string} - The font weight.
		 */
		getFontWeightFromVariant: function ( variant ) {
			if ( !variant ) {
				return;
			}

			if ( variant === 'regular' || variant === 'italic' ) {
				return '400';
			}

			var matches = variant.match( /\d+/g );
			if ( matches && matches[0] ) {
				return matches[0];
			}
		},

		/**
		 * Given a font variant (as defined in fonts.json) returns the font style.
		 *
		 * @param {string} variant - The font variant setting.
		 * @return {string} - The font style.
		 */
		getFontStyleFromVariant: function ( variant ) {
			if ( !variant ) {
				return;
			}

			if ( variant.indexOf( 'italic' ) > -1 ) {
				return 'italic';
			}

			return 'normal';
		},

		/**
		 * Generates a string of CSS rules for the typography control.
		 *
		 * @param {Object} values - The typography values as returned from the typography control.
		 * @returns {string}
		 */
		generateTypographyRules: function ( values ) {
			var rules = [];

			if ( ! this.isNil( values.family ) ) {
				var fonts = values.family
					.split( ',' )
					.map( function ( s ) {
						var trimmed = s.trim();

						if ( trimmed.indexOf( ' ' ) > - 1 ) {
							return '"' + trimmed + '"';
						}

						return trimmed;
					} )
					.join( ', ' );

				rules.push( 'font-family: ' + fonts + ';' );
			}

			if ( ! this.isNil( values.variant ) ) {
				var weight = this.getFontWeightFromVariant( values.variant );
				var style = this.getFontStyleFromVariant( values.variant );

				if ( weight ) {
					rules.push( 'font-weight: ' + weight + ';' );
				}

				if ( style ) {
					rules.push( 'font-style: ' + style + ';' );
				}
			}

			if ( ! this.isNil( values.size ) ) {
				rules.push( 'font-size: ' + values.size + 'px;' );
			}

			if ( ! this.isNil( values.lineHeight ) ) {
				rules.push( 'line-height: ' + values.lineHeight + 'px;' )
			}

			if ( ! this.isNil( values.transform ) ) {
				rules.push( 'text-transform: ' + values.transform + ';' );
			}

			if ( ! this.isNil( values.spacing ) ) {
				rules.push( 'letter-spacing: ' + values.spacing + 'px;' );
			}

			return rules.join( ' ' );
		},

		/**
		 * Creates all typography styles and injects the stylesheet.
		 *
		 * @param {string} settingName - The setting's name.
		 * @param {string} selectors - The CSS selectors.
		 * @param {Object} values - The values as returned from the typography control.
		 */
		createTypographyStyles: function (settingName, selectors, values) {
			values     = typeof values === 'string' ? JSON.parse( values ) : values;
			var that   = this;
			var styles = _.reduce( values, function ( styles, value, breakpoint ) {
				return styles + ' ' + that.wrapMediaQuery(
					breakpoint,
					selectors + ' { ' + that.generateTypographyRules( value ) + ' } ',
				);
			}, '' );

			this.injectStylesheet( settingName, styles );
		},

		/**
		 * Creates namespaced inline styles in the <body> element.
		 *
		 * @param {string} settingName - The setting name.
		 * @param {Array<Object>} styles - The style rules.
		 */
		createStyleSheet: function ( settingName, styles ) {
			var $styleElement;

			style = '<style class="' + settingName + '">';
			style += styles.reduce( function ( rules, style ) {
				rules += style.selectors + '{' + style.property + ':' + style.value + ';} ';
				return rules;
			}, '' );
			style += '</style>';

			$styleElement = $( '.' + settingName );

			if ( $styleElement.length ) {
				$styleElement.replaceWith( style );
			} else {
				$( 'body' ).append( style );
			}
		},
	};

	if ( ! window.SPECIALTY_PREVIEW_SCRIPTS ) {
		window.SPECIALTY_PREVIEW_SCRIPTS = SPECIALTY_PREVIEW_SCRIPTS;
	}
})( jQuery );
