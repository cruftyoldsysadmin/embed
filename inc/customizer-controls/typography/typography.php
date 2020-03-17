<?php
/**
 * Typography Customizer Control
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

class Specialty_Customize_Typography_Control extends WP_Customize_Control {
	/**
	 * The type of control.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'specialty-typography';

	public $responsive = true;

	public $placeholder = false;

	public $font_family = true;

	/**
	 * The inner fields (settings) for this control.
	 *
	 * @access public
	 * @var array
	 */
	public $fields = array();

	/**
	 * The list of Fonts.
	 *
	 * @access private
	 * @var array
	 */
	private $font_list = array();

	/**
	 * The rendered options markup for all fonts.
	 *
	 * @var array
	 */
	private $font_options = array();

	/**
	 * The list of text transform choices.
	 *
	 * @access private
	 * @var array
	 */
	private $transform_choices = array();

	/**
	 * The list of variant labels.
	 *
	 * @access private
	 * @var array
	 */
	private $variant_labels = array();

	public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
		$this->font_list         = $this->get_font_choices();
		$this->font_options      = $this->get_font_options();
		$this->transform_choices = $this->get_transform_choices();
		$this->variant_labels    = $this->get_variant_labels();

		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Fetches the Fonts object.
	 *
	 * @access private
	 * @return array
	 */
	private function get_font_choices() {
		$fonts_list = Specialty_Fonts_List::get_instance();

		return $fonts_list->get();
	}

	/**
	 * Fetches grouped font options.
	 *
	 * @access private
	 * @return array
	 */
	private function get_font_options() {
		$fonts_list = Specialty_Fonts_List::get_instance();

		return $fonts_list->get_font_options();
	}

	/**
	 * Fetches text transform choices.
	 *
	 * @access private
	 * @return array
	 */
	private function get_transform_choices() {
		$fonts_list = Specialty_Fonts_List::get_instance();

		return $fonts_list->get_transform_choices();
	}

	/**
	 * Returns font variant labels.
	 *
	 * @access private
	 * @return array
	 */
	private function get_variant_labels() {
		$fonts_list = Specialty_Fonts_List::get_instance();

		return $fonts_list->get_variant_labels();
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		$theme = wp_get_theme();

		wp_enqueue_script( $this->type . '-customize-control', get_theme_file_uri( '/inc/customizer-controls/typography/customizer.js' ), array(
			'jquery',
		), $theme->get( 'Version' ), true );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$this->json['id']                  = $this->id;
		$this->json['link']                = $this->get_link();
		$this->json['fontList']            = $this->font_list;
		$this->json['fontOptions']         = $this->font_options;
		$this->json['transforms']          = $this->transform_choices;
		$this->json['variantLabels']       = $this->variant_labels;
		$this->json['canSelectFontFamily'] = $this->font_family;

		$values = $this->value();
		if ( ! $this->responsive ) {
			unset( $values['tablet'], $values['mobile'] );
		}
		$this->json['values'] = $values;

		$this->json['responsive'] = $this->responsive;

		$this->json['placeholder'] = $this->placeholder;
		$this->json['empty_value'] = specialty_typography_control_defaults_empty_breakpoints();
	}

	/**
	 * Don't render anything with PHP as rendering is handled via JS templates.
	 *
	 * @access public
	 */
	public function render_content() { }

	/**
	 * The Underscore (JS) template for this control's content.
	 *
	 * @access protected
	 * @return void
	 */
	protected function content_template() {
		?>
		<div class="specialty-typography-control-wrap">
			<# if ( data.label ) { #>
				<span class="customize-control-title">
					{{ data.label }}
				</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">
					{{ data.description }}
				</span>
			<# } #>

			<# if ( data.responsive ) { #>
				<div class="button-group-devices">
					<button type="button" data-device="desktop" class="preview-desktop active">
						<span class="screen-reader-text"><?php esc_html_e( 'Desktop', 'specialty' ); ?></span>
					</button>
					<button type="button" data-device="tablet" class="preview-tablet">
						<span class="screen-reader-text"><?php esc_html_e( 'Tablet', 'specialty' ); ?></span>
					</button>
					<button type="button" data-device="mobile" class="preview-mobile">
						<span class="screen-reader-text"><?php esc_html_e( 'Mobile', 'specialty' ); ?></span>
					</button>
				</div>
			<# } #>

			<div class="specialty-typography-control-breakpoints">
				<# _.each( data.values, function( values, breakpoint ) { #>
					<#
						var placeholder;
						if ( data.placeholder && data.placeholder[breakpoint] ) {
							placeholder = data.placeholder[breakpoint];
						} else {
							placeholder = data.empty_value;
						}
					#>
					<div class="specialty-typography-control-group-wrap specialty-responsive-controls-{{ breakpoint }}" data-breakpoint="{{breakpoint}}">
						<# if ( breakpoint === 'desktop' && data.canSelectFontFamily ) { #>
							<# if ( data.fontList && data.fontList.length ) { #>
								<div class="specialty-typography-control-wrap">
									<label for="{{data.id}}-{{breakpoint}}-font-select">
										<?php esc_html_e( 'Font Family', 'specialty' ); ?>
									</label>
									<select
										name="{{data.id}}-{{breakpoint}}-font-select"
										id="{{data.id}}-{{breakpoint}}-font-select"
										class="specialty-control-typography-font-family-select"
										data-control-id="{{data.id}}"
										data-property="family"
									>
										<option value="">
											<# if ( placeholder.family ) { #>
												<?php esc_html_e( 'Theme Font', 'specialty' ); ?> ({{ placeholder.family }})
											<# } else { #>
												<?php esc_html_e( 'Theme Font', 'specialty' ); ?>
											<# } #>
										</option>

										<# _.each(data.fontOptions, function (group) { #>
											<optgroup label="{{group.label}}">
												<# _.each(group.fonts, function (font) { #>
													<option
														value="{{ font.family }}"
														{{{ (font.family === values.family) ? 'selected' : '' }}}
													>
														{{ font.family }}
													</option>
												<# }); #>
											</optgroup>
										<# }); #>
									</select>
								</div>

								<# var font = _.findWhere(data.fontList, { family: values.family }); #>
								<div
									class="specialty-typography-control-wrap"
									style="<# if ( ! font ) { #> display: none <# } #>"
								>
									<label for="{{data.id}}-{{breakpoint}}-font-variant-select">
										<?php esc_html_e( 'Font Variant', 'specialty' ); ?>
									</label>
									<select
										name="{{data.id}}-{{breakpoint}}-font-variant-select"
										id="{{data.id}}-{{breakpoint}}-font-variant-select"
										class="specialty-control-typography-font-variant-select"
										data-property="variant"
									>
										<# if (font) { #>
											<#	_.each(font.variants, function (variant) { #>
												<option
													value="{{variant}}"
													{{{ (variant === values.variant) ? 'selected' : '' }}}
												>
													{{ data.variantLabels[variant] }}
												</option>
											<# }); #>
										<# } #>
									</select>
								</div>
							<# } #>
						<# } #>

						<div class="specialty-typography-control-split">
							<div class="specialty-typography-control-wrap">
								<label
									for="{{data.id}}-{{breakpoint}}-font-size-control-input"
									id="{{data.id}}-{{breakpoint}}-font-size-control-label"
								>
									<?php esc_html_e( 'Font Size (px)', 'specialty' ); ?>
								</label>
								<input
									type="number"
									name="{{data.id}}-{{breakpoint}}-font-size-control-input"
									id="{{data.id}}-{{breakpoint}}-font-size-control-input"
									class="specialty-control-range-input"
									min="0"
									max="100"
									step="1"
									data-property="size"
									value="{{values.size}}"
									placeholder="{{placeholder.size}}"
								>
							</div>
							<div class="specialty-typography-control-wrap">
								<label
									for="{{data.id}}-{{breakpoint}}-line-height-control-input"
									id="{{data.id}}-{{breakpoint}}-line-height-control-label"
								>
									<?php esc_html_e( 'Line Height (px)', 'specialty' ); ?>
								</label>

								<input
									type="number"
									name="{{data.id}}-{{breakpoint}}-line-height-control-input"
									id="{{data.id}}-{{breakpoint}}-line-height-control-input"
									class="specialty-control-range-input"
									min="0"
									max="100"
									step="1"
									data-property="lineHeight"
									value="{{values.lineHeight}}"
									placeholder="{{placeholder.lineHeight}}"
								>
							</div>
						</div>

						<div class="specialty-typography-control-split">
							<div class="specialty-typography-control-wrap">
								<label for="{{data.id}}-{{breakpoint}}-font-transform">
									<?php esc_html_e( 'Text Transform', 'specialty' ); ?>
								</label>
								<select
									name="{{data.id}}-{{breakpoint}}-font-transform"
									id="{{data.id}}-{{breakpoint}}-font-transform"
									data-property="transform"
								>
									<# var transform = values.transform || placeholder.transform; #>
									<# _.each(data.transforms, function (label, value) { #>
										<option
											value="{{value}}"
											{{{ (value === transform) ? 'selected' : '' }}}
										>
											{{ label }}
										</option>
									<# }); #>
								</select>
							</div>

							<div class="specialty-typography-control-wrap">
								<label for="{{data.id}}-{{breakpoint}}-font-spacing-control-input">
									<?php esc_html_e( 'Letter Spacing (px)', 'specialty' ); ?>
								</label>

								<input
									type="number"
									name="{{data.id}}-{{breakpoint}}-font-spacing-control-input"
									id="{{data.id}}-{{breakpoint}}-font-spacing-control-input"
									min="-2"
									max="10"
									step="0.01"
									data-property="spacing"
									value="{{values.spacing}}"
									placeholder="{{placeholder.spacing}}"
								>
							</div>
						</div>

					</div>

				<# } ); #>

				<input class="specialty-typography-control-hidden-value" type="hidden" {{{ data.link }}}>
			</div>

		</div>
		<?php
	}
}
