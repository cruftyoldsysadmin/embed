<?php
	$wpc->add_setting( 'global_typo_is_google_active', array(
		'default'           => specialty_customizer_defaults( 'global_typo_is_google_active' ),
		'sanitize_callback' => 'absint',
	) );
	$wpc->add_control( 'global_typo_is_google_active', array(
		'type'    => 'checkbox',
		'section' => 'typography',
		'label'   => esc_html__( 'Enable Google Fonts', 'specialty' ),
	) );

	$wpc->add_setting( 'global_typo_body', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_body', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'Body font', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_body' ),
	) ) );

	$wpc->add_setting( 'global_typo_h1', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_h1', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'H1 font', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_h1' ),
	) ) );

	$wpc->add_setting( 'global_typo_h2', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_h2', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'H2 font', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_h2' ),
	) ) );

	$wpc->add_setting( 'global_typo_h3', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_h3', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'H3 font', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_h3' ),
	) ) );

	$wpc->add_setting( 'global_typo_h4', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_h4', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'H4 font', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_h4' ),
	) ) );

	$wpc->add_setting( 'global_typo_h5', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_h5', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'H5 font', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_h5' ),
	) ) );

	$wpc->add_setting( 'global_typo_h6', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_h6', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'H6 font', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_h6' ),
	) ) );

	$wpc->add_setting( 'global_typo_form_labels', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_form_labels', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'Form labels', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_form_labels' ),
	) ) );

	$wpc->add_setting( 'global_typo_form_text', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_form_text', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'Form text', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_form_text' ),
	) ) );

	$wpc->add_setting( 'global_typo_buttons', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_buttons', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'Button text', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_buttons' ),
	) ) );

	$wpc->add_setting( 'global_typo_widget_titles', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_widget_titles', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'Widget titles', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_widget_titles' ),
	) ) );

	$wpc->add_setting( 'global_typo_widget_text', array(
		'transport'         => 'postMessage',
		'default'           => specialty_typography_control_defaults_empty_breakpoints(),
		'sanitize_callback' => 'specialty_sanitize_typography_control_breakpoints',
	) );
	$wpc->add_control( new Specialty_Customize_Typography_Control( $wpc, 'global_typo_widget_text', array(
		'section'     => 'typography',
		'label'       => esc_html__( 'Widget text', 'specialty' ),
		'placeholder' => specialty_customizer_defaults( 'global_typo_widget_text' ),
	) ) );
