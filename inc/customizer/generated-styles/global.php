<?php
	$css = Specialty_Customizer_CSS_Generator::get_instance();

	//
	// Typography
	//
	$value = get_theme_mod( 'global_typo_body', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', 'body { %s }' );

	$value = get_theme_mod( 'global_typo_h1', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', 'h1, .entry-title { %s }' );

	$value = get_theme_mod( 'global_typo_h2', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', 'h2 { %s }' );

	$value = get_theme_mod( 'global_typo_h3', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', 'h3 { %s }' );

	$value = get_theme_mod( 'global_typo_h4', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', 'h4 { %s }' );

	$value = get_theme_mod( 'global_typo_h5', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', 'h5 { %s }' );

	$value = get_theme_mod( 'global_typo_h6', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', 'h6 { %s }' );

	$value = get_theme_mod( 'global_typo_form_text', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', 'input, textarea, select { %s }' );

	$value = get_theme_mod( 'global_typo_form_labels', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', 'form label, form .label { %s }' );

	$value = get_theme_mod( 'global_typo_buttons', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', '
		.btn,
		.button,
		button[type="submit"],
		input[type="submit"],
		input[type="reset"],
		input[type="button"],
		button,
		.comment-reply-link {
			%s
		}'
	);

	$value = get_theme_mod( 'global_typo_widget_titles', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', '.widget-title { %s }' );

	$value = get_theme_mod( 'global_typo_widget_text', specialty_typography_control_defaults_empty_breakpoints() );
	$css->add_typography( $value, '', '
	.sidebar .widget,
	.footer .widget,
	.sidebar .widget_meta li,
	.sidebar .widget_pages li,
	.sidebar .widget_categories li,
	.sidebar .widget_archive li,
	.sidebar .widget_nav_menu li,
	.sidebar .widget_recent_entries li,
	.footer .widget_meta li,
	.footer .widget_pages li,
	.footer .widget_categories li,
	.footer .widget_archive li,
	.footer .widget_nav_menu li,
	.footer .widget_recent_entries li { %s }'
	);
