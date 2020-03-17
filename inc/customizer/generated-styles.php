<?php
function specialty_include_generated_styles_files() {
	require_once get_theme_file_path( '/inc/customizer/generated-styles/global.php' );
}

function specialty_get_registered_typography_controls() {

	$controls = array(
		'global_typo_body'          => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_h1'            => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_h2'            => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_h3'            => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_h4'            => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_h5'            => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_h6'            => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_form_labels'   => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_form_text'     => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_buttons'       => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_widget_titles' => specialty_typography_control_defaults_empty_breakpoints(),
		'global_typo_widget_text'   => specialty_typography_control_defaults_empty_breakpoints(),
	);

	return apply_filters( 'specialty_registered_typography_controls', $controls );
}

function specialty_enqueue_google_fonts() {
	$css = Specialty_Customizer_CSS_Generator::get_instance();

	if ( is_customize_preview() ) {
		$css->register_typography_control( 'placeholder_preview_font', specialty_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => 'Open Sans',
				'variant'    => 'regular',
				'size'       => '',
				'lineHeight' => '',
				'transform'  => '',
				'spacing'    => '',
				'is_gfont'   => true,
			),
		) ) );
	}

	foreach ( specialty_get_registered_typography_controls() as $option => $default ) {
		$css->register_typography_control( $option, $default );
	}

	$url = $css->get_google_fonts_url();
	if ( ! empty( $url ) && ! has_action( 'wp_head', 'specialty_head_preconnect_google_fonts' ) ) {
		add_action( 'wp_head', 'specialty_head_preconnect_google_fonts' );
	}

	wp_enqueue_style( 'specialty-user-google-fonts', $url, array(), wp_get_theme()->get( 'Version' ) );
}

/**
 * Generates CSS based on customizer settings.
 *
 * @return string
 */
function specialty_get_customizer_css() {
	specialty_include_generated_styles_files();

	$generator = Specialty_Customizer_CSS_Generator::get_instance();

	$css = '';

	$breakpoints = array(
		'desktop' => '',
		'tablet'  => 991,
		'mobile'  => 767,
	);

	$desktop_min = $breakpoints['tablet'] + 1;
	$tablet_min  = $breakpoints['mobile'] + 1;

	$breakpoint_css = $generator->get( 'desktop' );
	if ( trim( $breakpoint_css ) ) {
		$css .= $breakpoint_css . PHP_EOL;
	}

	$breakpoint_css = $generator->get( 'tablet' );
	if ( trim( $breakpoint_css ) ) {
		$css .= "@media (max-width: {$breakpoints['tablet']}px) {
			{$breakpoint_css}
		}" . PHP_EOL;
	}

	$breakpoint_css = $generator->get( 'desktop-only' );
	if ( trim( $breakpoint_css ) ) {
		$css .= "@media (min-width: {$desktop_min}px) {
			{$breakpoint_css}
		}" . PHP_EOL;
	}

	$breakpoint_css = $generator->get( 'tablet-only' );
	if ( trim( $breakpoint_css ) ) {
		$css .= "@media (min-width: {$tablet_min}px) and (max-width: {$breakpoints['tablet']}px) {
			{$breakpoint_css}
		}" . PHP_EOL;
	}

	// 'mobile' breakpoint only applies to mobile aanyway, but we have 'mobile-only' as well, for completeness.
	// Merge the two under one media query.
	$breakpoint_css  = $generator->get( 'mobile' );
	$breakpoint_css .= $generator->get( 'mobile-only' );
	if ( trim( $breakpoint_css ) ) {
		$css .= "@media (max-width: {$breakpoints['mobile']}px) {
			{$breakpoint_css}
		}" . PHP_EOL;
	}

	return apply_filters( 'specialty_customizer_css', $css );
}

function specialty_get_all_customizer_css() {
	$styles = array(
		'customizer-part' => specialty_get_customizer_part_css(),
		'customizer'      => specialty_get_customizer_css(),
		'hero'            => specialty_get_hero_styles(),
	);

	$styles = apply_filters( 'specialty_all_customizer_css', $styles );

	if ( is_customize_preview() ) {
		$styles[] = '/* Placeholder for preview. */';
	}

	return implode( PHP_EOL, $styles );
}

add_filter( 'specialty_customizer_css', 'specialty_minimize_css' );
function specialty_minimize_css( $css ) {
	$css = preg_replace( '/\s+/', ' ', $css );
	return $css;
}
