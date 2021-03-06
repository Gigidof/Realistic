<?php
	/**
	* Realistic Theme Customizer
	*
	* @package Realistic
	*/
	
/*-----------------------------------------------------------------------------------*/
/*  Registering the Customizer Settings
/*-----------------------------------------------------------------------------------*/
	
function realistic_options_theme_customizer_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// General Settings
	$wp_customize->add_section( 
		'general_settings', array(
		'title' => __( 'General Settings', 'realistic' ),
		'priority' => 10,
	) );

		//favicon upload	
		$wp_customize->add_setting( 
			'favicon_image' , array(
				'default'     => get_template_directory_uri() .'/images/favicon.gif',
				'sanitize_callback' => 'esc_url_raw',
				));
		 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'favicon_image',
				array(
					'label' =>  __( 'Custom Favicon', 'realistic' ),
					'section' => 'general_settings',
					'settings' => 'favicon_image',
				)
			)
		);			
		
		//Add "Switcher" support to the theme customizer
		class Customizer_Switcher_Control extends WP_Customize_Control {
			public $type = 'switcher';
		 
			public function render_content() {
				?>
					<label>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<input class="ios-switch green bigswitch" type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> /><div class="ios-switch-div" ><div></div></div>
					</label>				
				<?php
			}
		}		

		//Add textarea support to the theme customizer
		class Customizer_Textarea_Control extends WP_Customize_Control {
			public $type = 'textarea';
		 
			public function render_content() {
				?>
					<label>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
					</label>
				<?php
			}
		}

		// Copyright text
		$wp_customize->add_setting( 
			'footer_left', array(
				'default' => 'Proudly powered by <a href="http://wordpress.org/" rel="generator">WordPress</a>',
				'sanitize_callback' => 'realistic_sanitize_text',
			)
		);
		
		$wp_customize->add_control(
			new Customizer_Textarea_Control(
				$wp_customize,			
				'footer_left',
				array(
					'label' => __( 'Copyright Text', 'realistic' ),
					'section' => 'general_settings',
					'settings' => 'footer_left',
				)
			)
		);		

		
	// Site Title & Tagline
		
		//logo upload	
		$wp_customize->add_setting( 
			'logo_image' , array(
				'default'     => '',
				'sanitize_callback' => 'esc_url_raw',
				));
		 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'logo_image',
				array(
					'label' => __( 'Logo Image', 'realistic' ),
					'section' => 'title_tagline',
					'settings' => 'logo_image',
				)
			)
		);

	// Archives Settings
	$wp_customize->add_section( 
		'archives_settings', array(
			'title' => __( 'Archives Settings', 'realistic' ),
			'priority' => 20
	) );		

		// Add Radio-Image control support to the theme customizer
		class Customizer_Radio_Image_Control extends WP_Customize_Control {
			public $type = 'radio-image';
			
			public function enqueue() {
				wp_enqueue_script( 'jquery-ui-button' );
			}
			
			// Markup for the field's title
			public function title() {
				echo '<span class="customize-control-title">';
					$this->label();
					$this->description();
				echo '</span>';
			}

			// The markup for the label.
			public function label() {
				// The label has already been sanitized in the Fields class, no need to re-sanitize it.
				echo $this->label;
			}

			// Markup for the field's description
			public function description() {
				if ( ! empty( $this->description ) ) {
					// The description has already been sanitized in the Fields class, no need to re-sanitize it.
					echo '<span class="description customize-control-description">' . $this->description . '</span>';
				}
			}
			
			public function render_content() {
				if ( empty( $this->choices ) ) {
					return;
				}
				$name = '_customize-radio-' . $this->id;
				?>
				<?php $this->title(); ?>
				<div id="input_<?php echo $this->id; ?>" class="image">
					<?php foreach ( $this->choices as $value => $label ) : ?>
						<input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo $this->id . $value; ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
							<label for="<?php echo $this->id . $value; ?>">
								<img src="<?php echo esc_html( $label ); ?>">
							</label>
						</input>
					<?php endforeach; ?>
				</div>
				<script>jQuery(document).ready(function($) { $( '[id="input_<?php echo $this->id; ?>"]' ).buttonset(); });</script>
				<?php
			}
		}	

		//Archives Meta
		$wp_customize->add_setting( 
			'archives_post_meta' , array(
				'default'     => '1',
				'sanitize_callback' => 'realistic_sanitize_checkbox',
				)
		);
		
		$wp_customize->add_control(
			new Customizer_Switcher_Control(
				$wp_customize,			
				'archives_post_meta', array(
					'label' =>  __( 'Archives Meta', 'realistic' ),
					'section' => 'archives_settings',
				)
			)
		);

		//Add input[type=number] support to the theme customizer
		class Customizer_Number_Control extends WP_Customize_Control {
			public $type = 'number';
			
			public function render_content() {
			?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<input class="number-control small" min="0" max="500" step="1" type="number" <?php $this->link(); ?> value="<?php echo intval( $this->value() ); ?>" />
				</label>
			<?php
			}
		}		
				
		//Excerpt Length
		$wp_customize->add_setting( 
			'excerpt_length', array( 
				'default' => '40',
				'sanitize_callback' => 'realistic_sanitize_integer',
			) 
		);
				
		$wp_customize->add_control(
			new Customizer_Number_Control(
				$wp_customize,
				'excerpt_length', array(
					'label'    => __( 'Excerpt Length', 'realistic' ),
					'section'  => 'archives_settings',
					'settings' => 'excerpt_length',
				) 
			)
		);		
		
		
	// Article Settings
	$wp_customize->add_section( 
		'article_settings', array(
			'title' => __( 'Article Settings', 'realistic' ),
			'priority' => 30
	) );	

		//Post Meta
		$wp_customize->add_setting( 
			'post_meta' , array(
				'default'     => '1',
				'sanitize_callback' => 'realistic_sanitize_checkbox',
				)
		);
		
		$wp_customize->add_control(
			new Customizer_Switcher_Control(
				$wp_customize,	
				'post_meta', array(
					'label' =>  __( 'Post Meta', 'realistic' ),
					'section' => 'article_settings',
				)
			)
		);
		
		//Related Posts
		$wp_customize->add_setting( 
			'related_posts' , array(
				'default'     => '1',
				'sanitize_callback' => 'realistic_sanitize_checkbox',
				)
		);
		
		$wp_customize->add_control(
			new Customizer_Switcher_Control(
				$wp_customize,	
				'related_posts', array(
					'label' =>  __( 'Related Posts', 'realistic' ),
					'section' => 'article_settings',
				)
			)
		);
		
		//Related Posts Number
		$wp_customize->add_setting( 
			'related_posts_number', array( 
				'default' => '4',
				'sanitize_callback' => 'realistic_sanitize_integer',
		) );
				
		$wp_customize->add_control( 
			'related_posts_number', array(
				'label'    => __( 'Related Posts Number', 'realistic' ),
				'section'  => 'article_settings',
				'settings' => 'related_posts_number',
				'type'     => 'number'
		) );		
		
		//Related Posts Query type
		$wp_customize->add_setting(
			'related_posts_query',
			array(
				'default' => 'tags',
				'sanitize_callback' => 'realistic_sanitize_related_posts_query',
			)
		);
		
		$wp_customize->add_control(
			'related_posts_query', array(
				'type' => 'radio',
				'label' => __( 'Related Posts Query', 'realistic' ),
				'section' => 'article_settings',
				'choices' => array(
					'tags' => __( 'Tags', 'realistic' ),
					'categories' => __( 'Categories', 'realistic' ),
				),
			)
		);		
		
		//Next/Prev Article
		$wp_customize->add_setting( 
			'next_prev_post' , array(
				'default'     => '1',
				'sanitize_callback' => 'realistic_sanitize_checkbox',
				)
		);
		
		$wp_customize->add_control(
			new Customizer_Switcher_Control(
				$wp_customize,			
				'next_prev_post', array(
					'label' =>  __( 'Next/Prev Article', 'realistic' ),
					'section' => 'article_settings',
				)
			)
		);		
		
		//Post Author Box
		$wp_customize->add_setting( 
			'author_box' , array(
				'default'     => '1',
				'sanitize_callback' => 'realistic_sanitize_checkbox',
				)
		);
		
		$wp_customize->add_control(
			new Customizer_Switcher_Control(
				$wp_customize,	
				'author_box', array(
					'label' =>  __( 'Post Author Box', 'realistic' ),
					'section' => 'article_settings',
				)
			)
		);			
	
	
	// Design & Layout
	$wp_customize->add_section( 
		'design_layout', array(
			'title' => __( 'Design & Layout', 'realistic' ),
			'priority' => 40
	) );	
		
		// Sidebar Settings
		$wp_customize->add_setting(
			'sidebar_settings',
			array(
				'default' => 'right_sidebar',
				'sanitize_callback' => 'realistic_sanitize_sidebar_settings',
			)
		);
		
		$wp_customize->add_control(
			new Customizer_Radio_Image_Control(
				$wp_customize,	
				'sidebar_settings', array(
					'label' => __( 'Sidebar Settings', 'realistic' ),
					'section' => 'design_layout',
					'choices' => array(
						'right_sidebar' => get_template_directory_uri() .'/images/customizer/right.jpg',
						'left_sidebar' => get_template_directory_uri() .'/images/customizer/left.jpg',
					),
				)
			)
		);			

		// color scheme
		$wp_customize->add_setting(
			'theme_color',
			array(
				'default' => 'default',
				'sanitize_callback' => 'realistic_sanitize_color_scheme',
			)
		);
		
		$wp_customize->add_control(
			new Customizer_Radio_Image_Control(
				$wp_customize,	
				'theme_color', array(
					'label' => __( 'Color Scheme', 'realistic' ),
					'section' => 'design_layout',
					'choices' => array(
						'default' => get_template_directory_uri() .'/images/customizer/default.png',
						'style1' => get_template_directory_uri() .'/images/customizer/style1.png',
						'style2' => get_template_directory_uri() .'/images/customizer/style2.png',
						'style3' => get_template_directory_uri() .'/images/customizer/style3.png',
						'style4' => get_template_directory_uri() .'/images/customizer/style4.png',
						'style5' => get_template_directory_uri() .'/images/customizer/style5.png',
					),
				)
			)
		);	

		// Background Settings
		$wp_customize->add_setting(
			'background_settings',
			array(
				'default' => 'color',
				'sanitize_callback' => 'realistic_sanitize_background_settings',
			)
		);
		
		$wp_customize->add_control(
			'background_settings', array(
				'type' => 'radio',
				'label' => __( 'Background settings', 'realistic' ),
				'section' => 'design_layout',
				'choices' => array(
					'color' => __( 'Color', 'realistic' ),
					'pattern' => __( 'Pattern', 'realistic' ),					
					'custom_image' => __( 'Custom image', 'realistic' ),
				),
			)
		);		
		
		// Background color
		$wp_customize->add_setting(
			'bg_color',
			array(
				'default' => '#f7f7f7',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'bg_color',
				array(
					'label' => __( 'Background color', 'realistic' ),
					'section' => 'design_layout',
					'settings' => 'bg_color',
				)
			)
		);		

		// Background pattern
		$wp_customize->add_setting(
			'background_pattern',
			array(
				'default' => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		
		$wp_customize->add_control(
			new Customizer_Radio_Image_Control(
				$wp_customize,	
				'background_pattern', array(
					'label' => __( 'Background pattern', 'realistic' ),
					'section' => 'design_layout',
					'choices' => array(
							get_template_directory_uri() .'/images/patterns/21.gif' => get_template_directory_uri() .'/images/patterns/21.gif',
							get_template_directory_uri() .'/images/patterns/22.gif' => get_template_directory_uri() .'/images/patterns/22.gif',
							get_template_directory_uri() .'/images/patterns/23.gif' => get_template_directory_uri() .'/images/patterns/23.gif',
							get_template_directory_uri() .'/images/patterns/24.gif' => get_template_directory_uri() .'/images/patterns/24.gif',
							get_template_directory_uri() .'/images/patterns/25.gif' => get_template_directory_uri() .'/images/patterns/25.gif',
							get_template_directory_uri() .'/images/patterns/26.gif' => get_template_directory_uri() .'/images/patterns/26.gif',
							get_template_directory_uri() .'/images/patterns/27.gif' => get_template_directory_uri() .'/images/patterns/27.gif',
							get_template_directory_uri() .'/images/patterns/28.gif' => get_template_directory_uri() .'/images/patterns/28.gif',
							get_template_directory_uri() .'/images/patterns/29.gif' => get_template_directory_uri() .'/images/patterns/29.gif',
							get_template_directory_uri() .'/images/patterns/30.gif' => get_template_directory_uri() .'/images/patterns/30.gif',
							get_template_directory_uri() .'/images/patterns/31.gif' => get_template_directory_uri() .'/images/patterns/31.gif',
							get_template_directory_uri() .'/images/patterns/32.gif' => get_template_directory_uri() .'/images/patterns/32.gif',
							get_template_directory_uri() .'/images/patterns/33.gif' => get_template_directory_uri() .'/images/patterns/33.gif',
							get_template_directory_uri() .'/images/patterns/34.gif' => get_template_directory_uri() .'/images/patterns/34.gif',
							get_template_directory_uri() .'/images/patterns/35.gif' => get_template_directory_uri() .'/images/patterns/35.gif',
							get_template_directory_uri() .'/images/patterns/36.gif' => get_template_directory_uri() .'/images/patterns/36.gif',
							get_template_directory_uri() .'/images/patterns/37.gif' => get_template_directory_uri() .'/images/patterns/37.gif',
							get_template_directory_uri() .'/images/patterns/38.gif' => get_template_directory_uri() .'/images/patterns/38.gif',
							get_template_directory_uri() .'/images/patterns/39.gif' => get_template_directory_uri() .'/images/patterns/39.gif',
							get_template_directory_uri() .'/images/patterns/40.gif' => get_template_directory_uri() .'/images/patterns/40.gif',
							
							get_template_directory_uri() .'/images/patterns/1.jpg' => get_template_directory_uri() .'/images/patterns/1.jpg',
							get_template_directory_uri() .'/images/patterns/2.jpg' => get_template_directory_uri() .'/images/patterns/2.jpg',
							get_template_directory_uri() .'/images/patterns/3.jpg' => get_template_directory_uri() .'/images/patterns/3.jpg',
							get_template_directory_uri() .'/images/patterns/4.jpg' => get_template_directory_uri() .'/images/patterns/4.jpg',
							get_template_directory_uri() .'/images/patterns/5.jpg' => get_template_directory_uri() .'/images/patterns/5.jpg',			
							get_template_directory_uri() .'/images/patterns/6.jpg' => get_template_directory_uri() .'/images/patterns/6.jpg',
							get_template_directory_uri() .'/images/patterns/7.jpg' => get_template_directory_uri() .'/images/patterns/7.jpg',
							get_template_directory_uri() .'/images/patterns/8.jpg' => get_template_directory_uri() .'/images/patterns/8.jpg',
							get_template_directory_uri() .'/images/patterns/9.jpg' => get_template_directory_uri() .'/images/patterns/9.jpg',
							get_template_directory_uri() .'/images/patterns/10.jpg' => get_template_directory_uri() .'/images/patterns/10.jpg',
							get_template_directory_uri() .'/images/patterns/11.jpg' => get_template_directory_uri() .'/images/patterns/11.jpg',
							get_template_directory_uri() .'/images/patterns/12.jpg' => get_template_directory_uri() .'/images/patterns/12.jpg',
							get_template_directory_uri() .'/images/patterns/13.jpg' => get_template_directory_uri() .'/images/patterns/13.jpg',
							get_template_directory_uri() .'/images/patterns/14.jpg' => get_template_directory_uri() .'/images/patterns/14.jpg',
							get_template_directory_uri() .'/images/patterns/15.jpg' => get_template_directory_uri() .'/images/patterns/15.jpg',			
							get_template_directory_uri() .'/images/patterns/16.jpg' => get_template_directory_uri() .'/images/patterns/16.jpg',
							get_template_directory_uri() .'/images/patterns/17.jpg' => get_template_directory_uri() .'/images/patterns/17.jpg',
							get_template_directory_uri() .'/images/patterns/18.jpg' => get_template_directory_uri() .'/images/patterns/18.jpg',
							get_template_directory_uri() .'/images/patterns/19.jpg' => get_template_directory_uri() .'/images/patterns/19.jpg',
							get_template_directory_uri() .'/images/patterns/20.jpg' => get_template_directory_uri() .'/images/patterns/20.jpg',				
					),
				)
			)
		);
		
		//Background image uploader	
		$wp_customize->add_setting( 
			'background_image' , array(
				'default'     => '',
				'sanitize_callback' => 'esc_url_raw',
				));
		 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'background_image',
				array(
					'label' => __( 'Custom background image', 'realistic' ),
					'section' => 'design_layout',
					'settings' => 'background_image',
				)
			)
		);		

		//Background image repeat	
		$wp_customize->add_setting( 
			'background_image_repeat' , array(
				'default'     => 'repeat',
				'sanitize_callback' => 'realistic_sanitize_background_image_repeat',
				));

		$wp_customize->add_control(
			'background_image_repeat', array(
				'type' => 'select',
				'label' => __( 'Repeat', 'realistic' ),
				'section' => 'design_layout',
				'choices' => array(
					'repeat' => __( 'Repeat', 'realistic' ),
					'repeat-x' => __( 'Repeat-x', 'realistic' ),
					'repeat-y' => __( 'Repeat-y', 'realistic' ),
					'no-repeat' => __( 'No-repeat', 'realistic' ),					
				),
			)
		);

		//Background image attachment	
		$wp_customize->add_setting( 
			'background_image_attachment' , array(
				'default'     => 'scroll',
				'sanitize_callback' => 'realistic_sanitize_background_image_attachment',
				));

		$wp_customize->add_control(
			'background_image_attachment', array(
				'type' => 'select',
				'label' => __( 'Attachment', 'realistic' ),
				'section' => 'design_layout',
				'choices' => array(
					'scroll' => __( 'Scroll', 'realistic' ),
					'fixed' => __( 'Fixed', 'realistic' ),				
				),
			)
		);

		//Background image position	
		$wp_customize->add_setting( 
			'background_image_position' , array(
				'default'     => 'left top',
				'sanitize_callback' => 'realistic_sanitize_background_image_position',
				));

		$wp_customize->add_control(
			'background_image_position', array(
				'type' => 'select',
				'label' => __( 'Position', 'realistic' ),
				'section' => 'design_layout',
				'choices' => array(
					'left top' => __( 'Left top', 'realistic' ),
					'left center' => __( 'Left center', 'realistic' ),
					'left bottom' => __( 'Left bottom', 'realistic' ),
					'right top' => __( 'Right top', 'realistic' ),
					'right center' => __( 'Right center', 'realistic' ),
					'right bottom' => __( 'Right bottom', 'realistic' ),
					'center top' => __( 'Center top', 'realistic' ),
					'center center' => __( 'Center center', 'realistic' ),
					'center bottom' => __( 'Center bottom', 'realistic' ),				
				),
			)
		);

		// Custom CSS
		$wp_customize->add_setting( 
			'custom_css', array(
				'default' => '',
				'sanitize_callback' => 'esc_attr',
			)
		);
		
		$wp_customize->add_control(
			new Customizer_Textarea_Control(
				$wp_customize,
				'custom_css',
				array(
					'label' => __( 'Custom CSS', 'realistic' ),
					'section' => 'design_layout',
					'settings' => 'custom_css',
				)
			)
		);
		
}
add_action( 'customize_register', 'realistic_options_theme_customizer_register' );

/*-----------------------------------------------------------------------------------*/
/*  CUSTOM DATA SANITIZATION
/*-----------------------------------------------------------------------------------*/
	
// Sanitize text
function realistic_sanitize_text( $input ) {
	return strip_tags( $input,'<a>' );
}	
	
// Sanitize checkbox
function realistic_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}

// Sanitize integer
function realistic_sanitize_integer( $input ) {
	return absint( $input );
}

// Sanitize sidebar settings
function realistic_sanitize_sidebar_settings( $input ) {
    $valid = array(
		'right_sidebar' => get_template_directory_uri() .'/images/customizer/right.jpg',
		'left_sidebar' => get_template_directory_uri() .'/images/customizer/left.jpg',
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

// Sanitize color scheme
function realistic_sanitize_color_scheme( $input ) {
    $valid = array(
		'default' => get_template_directory_uri() .'/images/customizer/right.jpg',
		'style1' => get_template_directory_uri() .'/images/customizer/right.jpg',
		'style2' => get_template_directory_uri() .'/images/customizer/right.jpg',
		'style3' => get_template_directory_uri() .'/images/customizer/right.jpg',
		'style4' => get_template_directory_uri() .'/images/customizer/right.jpg',
		'style5' => get_template_directory_uri() .'/images/customizer/right.jpg',
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

// Sanitize background settings
function realistic_sanitize_background_settings( $input ) {
    $valid = array(
		'color' => 'Color',
		'pattern' => 'Pattern',			
		'custom_image' => 'Custom image',
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

// Sanitize background image repeat
function realistic_sanitize_background_image_repeat( $input ) {
    $valid = array(
		'repeat' => __( 'Repeat', 'realistic' ),
		'repeat-x' => __( 'Repeat-x', 'realistic' ),
		'repeat-y' => __( 'Repeat-y', 'realistic' ),
		'no-repeat' => __( 'No-repeat', 'realistic' ),
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

// Sanitize background image attachment
function realistic_sanitize_background_image_attachment( $input ) {
    $valid = array(
		'scroll' => __( 'Scroll', 'realistic' ),
		'fixed' => __( 'Fixed', 'realistic' ),
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

// Sanitize background image position
function realistic_sanitize_background_image_position( $input ) {
    $valid = array(
		'left top' => __( 'Left top', 'realistic' ),
		'left center' => __( 'Left center', 'realistic' ),
		'left bottom' => __( 'Left bottom', 'realistic' ),
		'right top' => __( 'Right top', 'realistic' ),
		'right center' => __( 'Right center', 'realistic' ),
		'right bottom' => __( 'Right bottom', 'realistic' ),
		'center top' => __( 'Center top', 'realistic' ),
		'center center' => __( 'Center center', 'realistic' ),
		'center bottom' => __( 'Center bottom', 'realistic' ),
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

// Sanitize Related Posts Query
function realistic_sanitize_related_posts_query( $input ) {
    $valid = array(
		'tags' => 'Tags',
		'categories' => 'Categories',
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Customizer Styles & Scripts
/*-----------------------------------------------------------------------------------*/

// Style settings output.
function realistic_add_style_settings() {
	$sidebar_settings = get_theme_mod( 'sidebar_settings' );
	if ( $sidebar_settings && $sidebar_settings == 'left_sidebar' ) {
		$layout_style = ' .content-area { float: right !important;} ';
	} else { 
		$layout_style = '';
	}
	$custom_css = get_theme_mod( 'custom_css' );
	$background_settings = get_theme_mod( 'background_settings', 'color' );
	if ( $background_settings && $background_settings == 'color') {
		$bg_color = get_theme_mod( 'bg_color', '#f7f7f7' );
		$background = ' body { background: ' . $bg_color . ';}';
	} 
	elseif ( $background_settings && $background_settings == 'pattern') {
		$background_pattern = get_theme_mod( 'background_pattern' );
		$background = ' body { background: url(' . $background_pattern . ') repeat left bottom;}';
	} else { 
		$background_image = get_theme_mod( 'background_image' );
		$background_image_repeat = get_theme_mod( 'background_image_repeat' );
		$background_image_attachment = get_theme_mod( 'background_image_attachment' );
		$background_image_position = get_theme_mod( 'background_image_position' );
		if ( $background_image ) {
			$background = ' body { background: url(' . $background_image . ') '. $background_image_repeat .' '. $background_image_attachment .' '. $background_image_position .';}';
		}
	}
	?>
	<style type="text/css">
		<?php echo $layout_style ?>
		<?php echo $background ?>
		<?php if ( $custom_css ) { 
			echo $custom_css; 
		} ?>
	</style>
	<?php
}
add_action( 'wp_head', 'realistic_add_style_settings' );

//Loading Customizer Styles
function realistic_customizer_inline_css() {
?>
	<style type="text/css">
	#customize-control-favicon_image .current {
		width: 50px;
	}	
	.ui-state-active img {
		border: 2px solid #444;
	}
	#customize-control-theme_color .ui-state-active img {
		width: 71px;
		height: 71px;
	}
	#customize-control-sidebar_settings .ui-state-active img {
		width: 71px;
		height: 46px;
	}
	#input_background_pattern {
		height: 220px;
		overflow: auto;
	}
	#input_background_pattern img {
		width: 70px;
		height: 70px;
	}	
	#input_background_pattern .ui-state-active img {
		width: 66px;
		height: 66px;
	}	
	/* Switch Styles */	
	input[type="checkbox"].ios-switch {
		display: none !important;
	}
	input[type="checkbox"].ios-switch + div {
		vertical-align: middle;
		width: 40px;	height: 20px;
		border: 1px solid rgba(0,0,0,.4);
		border-radius: 999px;
		background-color: rgba(0, 0, 0, 0.1);
		-webkit-transition-duration: .4s;
		-webkit-transition-property: background-color, box-shadow;
		box-shadow: inset 0 0 0 0px rgba(0,0,0,0.4);
		margin: 15px 1.2em 15px 2.5em;
	}
	input[type="checkbox"].ios-switch:checked + div {
		width: 40px;
		background-position: 0 0;
		background-color: #3b89ec;
		border: 1px solid #0e62cd;
		box-shadow: inset 0 0 0 10px rgba(59,137,259,1);
	}
	input[type="checkbox"].tinyswitch.ios-switch + div {
		width: 34px;	height: 18px;
	}
	input[type="checkbox"].bigswitch.ios-switch + div {
		width: 50px;	height: 25px;
	}
	input[type="checkbox"].green.ios-switch:checked + div {
		background-color: #00e359;
		border: 1px solid rgba(0, 162, 63,1);
		box-shadow: inset 0 0 0 10px rgba(0,227,89,1);
	}
	input[type="checkbox"].ios-switch + div > div {
		float: left;
		width: 18px; height: 18px;
		border-radius: inherit;
		background: #ffffff;
		-webkit-transition-timing-function: cubic-bezier(.54,1.85,.5,1);
		-webkit-transition-duration: 0.4s;
		-webkit-transition-property: transform, background-color, box-shadow;
		-moz-transition-timing-function: cubic-bezier(.54,1.85,.5,1);
		-moz-transition-duration: 0.4s;
		-moz-transition-property: transform, background-color;
		box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3), 0px 0px 0 1px rgba(0, 0, 0, 0.4);
		pointer-events: none;
		margin-top: 1px;
		margin-left: 1px;
	}
	input[type="checkbox"].ios-switch:checked + div > div {
		-webkit-transform: translate3d(20px, 0, 0);
		-moz-transform: translate3d(20px, 0, 0);
		background-color: #ffffff;
		box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3), 0px 0px 0 1px rgba(8, 80, 172,1);
	}
	input[type="checkbox"].tinyswitch.ios-switch + div > div {
		width: 16px; height: 16px;
		margin-top: 1px;
	}
	input[type="checkbox"].tinyswitch.ios-switch:checked + div > div {
		-webkit-transform: translate3d(16px, 0, 0);
		-moz-transform: translate3d(16px, 0, 0);
		box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3), 0px 0px 0 1px rgba(8, 80, 172,1);
	}
	input[type="checkbox"].bigswitch.ios-switch + div > div {
		width: 23px; height: 23px;
		margin-top: 1px;
	}
	input[type="checkbox"].bigswitch.ios-switch:checked + div > div {
		-webkit-transform: translate3d(25px, 0, 0);
		-moz-transform: translate3d(16px, 0, 0);
		box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3), 0px 0px 0 1px rgba(8, 80, 172,1);
	}
	input[type="checkbox"].green.ios-switch:checked + div > div {
		box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(0, 162, 63,1);
	}
	.ios-switch-div {
		margin: 1px !important;
		margin-bottom: 10px !important;
	}
	.prefix-upsell-link {
		display: inline-block;
		background-color: #C60000;
		color : #fff;
		text-transform: uppercase;
		margin-top: 6px;
		padding: 3px 6px;
		font-size: 9px;
		letter-spacing: 1px;
		line-height: 1.5;
		clear: both;
	}
	.prefix-upsell-link:hover {
		background-color: #444;
		color : #fff;
	}
	</style>
	<?php
}
add_action( 'admin_enqueue_scripts', 'realistic_customizer_inline_css' );

// Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
function realistic_options_theme_customizer_preview_js() {
	wp_enqueue_script( 'options_theme_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'realistic_options_theme_customizer_preview_js' );