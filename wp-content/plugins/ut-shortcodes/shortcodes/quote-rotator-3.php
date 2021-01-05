<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'UT_Quote_Rotator_3' ) ) {
	
    class UT_Quote_Rotator_3 {
        
        private $shortcode;
        private $inner_shortcode;
        private $add_script;
            
        function __construct() {
			
            /* shortcode base */
            $this->shortcode       = 'ut_qtrotator_3';
            $this->inner_shortcode = 'ut_qt_3';
            
            add_action( 'init', array( $this, 'ut_map_shortcode' ) );
            
            add_shortcode( $this->shortcode, array( $this, 'ut_create_shortcode' ) );
            add_shortcode( $this->inner_shortcode, array( $this, 'ut_create_inner_shortcode' ) );
            
            /* scripts */
            add_action( 'init', array( $this, 'register_scripts' ) );
            add_action( 'wp_footer', array( $this, 'enqueue_scripts' ) );            	
            
		}
                
        function ut_map_shortcode( $atts, $content = NULL ) {
            
            if( function_exists( 'vc_map' ) ) {
                                
                vc_map(
                    array(
                        'name'                  => esc_html__( 'Simple Text Quote Rotator', 'ut_shortcodes' ),
						'description'     		=> esc_html__( 'Please this element inside a fullwidth row.', 'ut_shortcodes' ),
                        'base'                  => $this->shortcode,
                        'category'              => 'Community',
                        // 'icon'                  => 'fa fa-comments ut-vc-module-icon',
                        'icon'                  => UT_SHORTCODES_URL . '/admin/img/vc_icons/quote-rotator.png',
                        'class'                 => 'ut-vc-icon-module ut-community-module', 
                        'as_parent'             => array( 'only' => $this->inner_shortcode ),
                        'is_container'          => true,
                        'params'                => array(
                            
                            array(
                                'type'          => 'dropdown',
                                'heading'       => esc_html__( 'Autoplay Slider?', 'ut_shortcodes' ),
                                'param_name'    => 'autoplay',
                                'group'         => 'Slider Settings',
                                'value'         => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'
                                ),
                            ),
                            array(
                                'type'          => 'textfield',
                                'heading'       => esc_html__( 'Autoplay Timeout', 'ut_shortcodes' ),
                                'description'   => esc_html__( 'Autoplay interval timeout in milliseconds. Default: 5000' , 'ut_shortcodes' ),
                                'param_name'    => 'autoplay_timeout',
                                'group'         => 'Slider Settings',
                                'dependency'    => array(
                                    'element' => 'autoplay',
                                    'value'   => array( 'true' ),
                                )

                            ),
                            array(
                                'type'          => 'dropdown',
                                'heading'       => esc_html__( 'Loop Slider?', 'ut_shortcodes' ),
                                'param_name'    => 'loop',
                                'group'         => 'Slider Settings',
                                'value'         => array(
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true',
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false'
                                ),
                                'dependency'    => array(
                                    'element' => 'type',
                                    'value'   => array( 'single' ),
                                ) 
                            ),
                            array(
                                'type'          => 'dropdown',
                                'heading'       => esc_html__( 'Show Next / Prev Navigation?', 'ut_shortcodes' ),
                                'param_name'    => 'nav',
                                'group'         => 'Slider Settings',
                                'value'         => array(
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true',
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false'
                                ),
                            ),
                            array(
                                'type'          => 'dropdown',
                                'heading'       => esc_html__( 'Quote Width?', 'ut_shortcodes' ),
                                'param_name'    => 'quote_width',
                                'group'         => 'Slider Settings',
                                'value'         => array(
                                    esc_html__( 'centered'  , 'ut_shortcodes' ) => 'centered',
                                    esc_html__( 'fullwidth', 'ut_shortcodes' ) => 'fullwidth'
                                ),
                                'dependency'    => array(
                                    'element' => 'nav',
                                    'value'   => array( 'false' ),
                                )
                            ),
                            array(
                                'type'          => 'dropdown',
                                'heading'       => esc_html__( 'Show Dot Navigation?', 'ut_shortcodes' ),
                                'param_name'    => 'dot_nav',
                                'group'         => 'Slider Settings',
                                'value'         => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true',                                    
                                ),
                            ),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Dot Alignment', 'ut_shortcodes' ),
								'param_name'        => 'dot_align',
								'group'             => 'Slider Settings',
                                'value'             => array(
                                    esc_html__( 'center', 'ut_shortcodes' ) => 'center',
                                    esc_html__( 'left'  , 'ut_shortcodes' ) => 'left',
                                    esc_html__( 'right' , 'ut_shortcodes' ) => 'right',
                                ),
						  	),                    
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Animation Effect', 'ut_shortcodes' ),
                                'param_name'        => 'slide_effect',
                                'group'             => 'Slider Settings',
                                'value'         => array(
                                    esc_html__( 'Slide'  , 'ut_shortcodes' ) => 'slide',
                                    esc_html__( 'Fade', 'ut_shortcodes' ) => 'fade'
                                ),                                
                            ),
							array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Quote Alignment', 'ut_shortcodes' ),
                                'description'       => esc_html__( '', 'ut_shortcodes' ),
                                'param_name'        => 'slide_content_align',
                                'group'             => 'Slider Settings',
                                'value'             => array(
                                    esc_html__( 'left', 'ut_shortcodes' ) => 'left',
									esc_html__( 'center'  , 'ut_shortcodes' ) => 'center',
                                    esc_html__( 'right'  , 'ut_shortcodes' ) => 'right'                                                                        
                                )
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'CSS Class', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'ut_shortcodes' ),
                                'param_name'        => 'class',
                                'group'             => 'Slider Settings'
                            ),                            
                            
                            /* navigation colors */
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Arrow Color', 'ut_shortcodes' ),
								'param_name'        => 'arrow_color',
								'group'             => 'Navigation Colors',
                                'dependency'    => array(
                                    'element' => 'nav',
                                    'value'   => array( 'true' ),
                                ) 
						  	), 
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Arrow Color Hover', 'ut_shortcodes' ),
								'param_name'        => 'arrow_color_hover',
								'group'             => 'Navigation Colors',
                                'dependency'    => array(
                                    'element' => 'nav',
                                    'value'   => array( 'true' ),
                                ) 
						  	),
                            
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Arrow Background Color', 'ut_shortcodes' ),
								'param_name'        => 'arrow_background_color',
								'group'             => 'Navigation Colors',
                                'dependency'    => array(
                                    'element' => 'nav',
                                    'value'   => array( 'true' ),
                                ) 
						  	), 
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Arrow Background Color Hover', 'ut_shortcodes' ),
								'param_name'        => 'arrow_background_color_hover',
								'group'             => 'Navigation Colors',
                                'dependency'    => array(
                                    'element' => 'nav',
                                    'value'   => array( 'true' ),
                                ) 
						  	),
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Dot Color', 'ut_shortcodes' ),
								'param_name'        => 'dot_color',
								'group'             => 'Navigation Colors',
                                'dependency'        => array(
                                    'element' => 'dot_nav',
                                    'value'   => array( 'true' ),
                                ) 
						  	),        
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Dot Active and Hover Color', 'ut_shortcodes' ),
								'param_name'        => 'dot_hover_color',
								'group'             => 'Navigation Colors',
                                'dependency'        => array(
                                    'element' => 'dot_nav',
                                    'value'   => array( 'true' ),
                                ) 
						  	),
                    
                    
                            /* testimonial colors */
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Author Name Color', 'ut_shortcodes' ),
								'param_name'        => 'name_color',
								'group'             => 'Testimonial Colors'
						  	),
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Quote Color', 'ut_shortcodes' ),
								'param_name'        => 'quote_color',
								'group'             => 'Testimonial Colors'
						  	),
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Origin Color', 'ut_shortcodes' ),
								'param_name'        => 'origin_color',
								'group'             => 'Testimonial Colors'
						  	),  
                            array(
								'type'              => 'colorpicker',
								'heading'           => esc_html__( 'Logo Color', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Only available for logos based on icons.', 'ut_shortcodes' ),
								'param_name'        => 'logo_color',
								'group'             => 'Testimonial Colors'
						  	),        
                    
                    
                            /* Font Settings */
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Use a custom font for quote text?', 'unitedthemes' ),
                                'param_name'        => 'quote_custom_font',
                                'group'             => 'Quote Font Settings',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'no',
                                    esc_html__( 'yes', 'ut_shortcodes' ) => 'yes'                                    
                                )                                
                            ),
                            array(
								'type'              => 'google_fonts',
								'heading'           => esc_html__( 'Google Font', 'ut_shortcodes' ),
                                'param_name'        => 'quote_font',
								'group'             => 'Quote Font Settings',
                                'dependency'    => array(
                                    'element' => 'quote_custom_font',
                                    'value'   => 'yes',
                                ),
						  	),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Font Size', 'ut_shortcodes' ),
                                'param_name'        => 'quote_font_size',
                                'group'             => 'Quote Font Settings',
                                'edit_field_class'  => 'vc_col-sm-6'
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Line Height', 'ut_shortcodes' ),
                                'param_name'        => 'quote_line_height',
                                'group'             => 'Quote Font Settings',
                                'edit_field_class'  => 'vc_col-sm-6'
                            ),
                            array(
								'type'              => 'dropdown',
								'heading'           => esc_html__( 'Origin Font Weight', 'ut_shortcodes' ),
								'param_name'        => 'origin_font_weight',
								'group'             => 'Quote Font Settings',
                                'value'             => array(
                                    esc_html__( 'Select Font Weight' , 'ut_shortcodes' ) => '',
                                    esc_html__( 'normal' , 'ut_shortcodes' )             => 'normal',
                                    esc_html__( 'bold' , 'ut_shortcodes' )               => 'bold'
                                ),
						  	),  
                            
                            /* animation */
                            array(
                                'type'              => 'animation_style',
                                'heading'           => __( 'Animation Effect', 'ut_shortcodes' ),
                                'description'       => __( 'Select image animation effect.', 'ut_shortcodes' ),
                                'group'             => 'Animation',
                                'param_name'        => 'effect',
                                'settings' => array(
                                    'type' => array(
                                        'in',
                                        'out',
                                        'other',
                                    ),
                                )
                                
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Animation Duration', 'unitedthemes' ),
                                'description'       => esc_html__( 'Animation time in seconds  e.g. 1s', 'unitedthemes' ),
                                'param_name'        => 'animation_duration',
                                'group'             => 'Animation',
                            ), 
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Animate Once?', 'unitedthemes' ),
                                'description'       => esc_html__( 'Animate only once when reaching the viewport, animate everytime when reaching the viewport or make the animation infinite? By default the animation executes everytime when the element becomes visible in viewport, means when leaving the viewport the animation will be reseted and starts again when reaching the viewport again. By setting this option to yes, the animation executes exactly once. By setting it to infinite, the animation loops all the time, no matter if the element is in viewport or not.', 'unitedthemes' ),
                                'param_name'        => 'animate_once',
                                'group'             => 'Animation',
                                'value'             => array(
                                    esc_html__( 'yes', 'unitedthemes' )      => 'yes',
                                    esc_html__( 'no' , 'unitedthemes' )      => 'no',
                                    esc_html__( 'infinite', 'unitedthemes' ) => 'infinite',
                                )
                            ),  
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Animate Image on Tablet?', 'ut_shortcodes' ),
                                'param_name'        => 'animate_tablet',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'
                                ),
                            ),
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Animate Image on Mobile?', 'ut_shortcodes' ),
                                'param_name'        => 'animate_mobile',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'
                                ),
                            ),                            
                            array(
                                'type'              => 'dropdown',
                                'heading'           => esc_html__( 'Delay Animation?', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Time in milliseconds until the image appears. e.g. 200', 'ut_shortcodes' ),
                                'param_name'        => 'delay',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'value'             => array(
                                    esc_html__( 'no', 'ut_shortcodes' ) => 'false',
                                    esc_html__( 'yes'  , 'ut_shortcodes' ) => 'true'                                                                        
                                )
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Delay Timer', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'Time in milliseconds until the next image appears. e.g. 200', 'ut_shortcodes' ),
                                'param_name'        => 'delay_timer',
                                'group'             => 'Animation',
                                'edit_field_class'  => 'vc_col-sm-6',
                                'dependency'        => array(
                                    'element' => 'delay',
                                    'value'   => 'true',
                                )
                            ),
                    
                            /* css editor */
                            array(
                                'type'              => 'css_editor',
                                'param_name'        => 'css',
                                'group'             => esc_html__( 'Design Options', 'ut_shortcodes' ),
                            )                            
                                  
                        ),
                        'js_view'         => 'VcColumnView'                        
                        
                    )
                
                ); /* end mapping */
                
                vc_map(
                    array(
                        'name'            => esc_html__( 'Quote', 'ut_shortcodes' ),
                        'base'            => $this->inner_shortcode,
                        // 'icon'            => 'fa fa-comments ut-vc-module-icon',
                        'icon'            => UT_SHORTCODES_URL . '/admin/img/vc_icons/single-quote.png',
                        'category'        => 'Community',
                        'class'           => 'ut-vc-icon-module ut-community-module',
                        'as_child'        => array( 'only' => $this->shortcode ),
                        'content_element' => true,
                        'params'          => array(
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Author', 'ut_shortcodes' ),
                                'param_name'        => 'author',
                                'admin_label'       => true,
                                'group'             => 'General'
                            ),
                            array(
                                'type'              => 'textarea',
                                'heading'           => esc_html__( 'Quote', 'ut_shortcodes' ),
								'admin_label'       => true,
                                'param_name'        => 'content',
                                'group'             => 'General'
                            ),
                            array(
								'type'              => 'iconpicker',
                                'heading'           => esc_html__( 'Choose Icon', 'ut_shortcodes' ),
                                'param_name'        => 'icon_bklyn',
                                'group'             => 'General',
                                'settings' => array(
                                    'emptyIcon'     => true,
                                    'type'          => 'bklynicons',
                                ),                              
                            ),                    
                            array(
								'type'              => 'attach_image',
                                'heading'           => esc_html__( 'or upload own Company Logo', 'ut_shortcodes' ),
                                'description'       => esc_html__( 'recommended size 64x64', 'ut_shortcodes' ),
                                'param_name'        => 'imageicon',
                                'group'             => 'General',                                
                            ),
                            array(
                                'type'              => 'textfield',
                                'heading'           => esc_html__( 'Origin', 'ut_shortcodes' ),
                                'param_name'        => 'origin',
                                'admin_label'       => true,
                                'group'             => 'General'
                            ),
                            
                        )                        
                        
                    )
                
                ); /* end mapping */
 
            } 
        
        }
        
        function ut_create_inline_css( $id, $atts ) {
            
            extract( shortcode_atts( array (
                'nav'                          => 'true',
                'dot_nav'                      => 'false',
                'dot_align'                    => 'center',
                'quote_width'                  => 'centered',
                'arrow_color'                  => '',
                'arrow_color_hover'            => '',
                'arrow_background_color'       => '',
                'arrow_background_color_hover' => '',
                'dot_color'                    => '',
                'dot_hover_color'              => '',
                'name_color'                   => '',
                'quote_color'                  => '',
                'origin_color'                 => '',
                'logo_color'                   => '',
                'origin_font_weight'           => '',
                'quote_font_size'              => '',
                'quote_line_height'            => '',
                'quote_font'                   => '',
                'quote_custom_font'            => 'no'
            ), $atts ) );
            
            $ut_font_css = false;
            
            /* initialize google font */
            if( $quote_custom_font == 'yes' && !empty( $quote_font ) ) {
                
                 $ut_google_font     = new UT_VC_Google_Fonts( $atts, 'quote_font', $this->shortcode );
                 $ut_font_css        = $ut_google_font->get_google_fonts_css_styles();
                        
            }
            
            /* font size */
            if( $quote_font_size ) {
                $ut_font_css[] = 'font-size:'  . $quote_font_size . ';';
            }
            
            /* line height */
            if( $quote_line_height ) {
                $ut_font_css[] = 'line-height:'  . $quote_line_height . ';';
            }
            
            /* font settings */
            $ut_font_css = is_array( $ut_font_css ) ? implode( '', $ut_font_css ) : $ut_font_css;
            
            ob_start();
            
            ?>   
            
            <style type="text/css" scoped>
                
                @media (min-width: 1025px) {
                    
                    <?php if( $nav == 'true' ) : ?>
                    
                        #<?php echo $id; ?> .owl-carousel {
                            padding-left: 80px;
                            padding-right: 80px;
                        }
                        
                        #<?php echo $id; ?> .bkly-testimonials-style2 {
                            padding: 0 80px;    
                        } 
                    
                    <?php endif; ?>
                    
                    <?php if( $quote_width == 'fullwidth' ) : ?>
                    
                        #<?php echo $id; ?> .owl-carousel {
                            padding: 0;                            
                        }                        
                        
                        #<?php echo $id; ?> .bkly-testimonials-style2 {
                            padding: 0;    
                        } 
                    
                    <?php endif; ?>
                
                }
                
                <?php if( $arrow_color ) : ?>     
                    
                    #<?php echo $id; ?> .ut-next-gallery-slide { color: <?php echo $arrow_color ?>;}
                    #<?php echo $id; ?> .ut-prev-gallery-slide { color: <?php echo $arrow_color ?>;}
                    
                <?php endif; ?>
                
                <?php if( $arrow_color_hover ) : ?>     
                    
                    #<?php echo $id; ?> .ut-next-gallery-slide:hover { color: <?php echo $arrow_color_hover ?>;}
                    #<?php echo $id; ?> .ut-prev-gallery-slide:hover { color: <?php echo $arrow_color_hover ?>;}                        
                    
                <?php endif; ?>
                
                <?php if( $arrow_background_color ) : ?>     
                    
                    #<?php echo $id; ?> .ut-next-gallery-slide { background: <?php echo $arrow_background_color ?>;}
                    #<?php echo $id; ?> .ut-prev-gallery-slide { background: <?php echo $arrow_background_color ?>;}
                    
                <?php endif; ?>
                
                <?php if( $arrow_background_color_hover ) : ?>     
                    
                    #<?php echo $id; ?> .ut-next-gallery-slide:hover { background: <?php echo $arrow_background_color_hover ?>;}
                    #<?php echo $id; ?> .ut-prev-gallery-slide:hover { background: <?php echo $arrow_background_color_hover ?>;}
                    
                <?php endif; ?>
                
                <?php if( $name_color ) : ?>     
                    
                    #<?php echo $id; ?> .bklyn-testimonials-author { color: <?php echo $name_color ?>;}
                    
                <?php endif; ?>
                
                <?php if( $logo_color ) : ?>     
                    
                    #<?php echo $id; ?> .bklyn-testimonials-font-icon i { color: <?php echo $logo_color ?>;}
                    
                <?php endif; ?>
                
                <?php if( $quote_color ) : ?>     
                    
                    #<?php echo $id; ?> .bklyn-testimonials-quote { color: <?php echo $quote_color ?>;}
                    
                <?php endif; ?>
                
                <?php if( $origin_color ) : ?>     
                    
                    #<?php echo $id; ?> .bklyn-testimonials-origin { color: <?php echo $origin_color ?>;}
                    
                <?php endif; ?>
                
                <?php if( $ut_font_css ) : ?>
                    
                   #<?php echo $id; ?> .bklyn-testimonials-quote p { <?php echo $ut_font_css; ?> } 
                
                <?php endif; ?>
                
                <?php if( $origin_font_weight ) : ?>
                    
                   #<?php echo $id; ?> .bklyn-testimonials-origin { font-weight: <?php echo $origin_font_weight; ?>; } 
                
                <?php endif; ?>
                
                <?php if( $dot_color ) : ?>
                    
                   #<?php echo $id; ?> .owl-dots .owl-dot span { background: <?php echo $dot_color; ?> } 
                
                <?php endif; ?>
                
                <?php if( $dot_hover_color ) : ?>
                    
                    #<?php echo $id; ?> .owl-dots .owl-dot.active span { background: <?php echo $dot_hover_color; ?>; } 
                    #<?php echo $id; ?> .owl-dots .owl-dot:hover span { background: <?php echo $dot_hover_color; ?>; } 
                
                <?php endif; ?>
                
                <?php if( $dot_align ) : ?>
                    
                    #<?php echo $id; ?> .owl-dots { text-align: <?php echo $dot_align; ?> } 
                
                <?php endif; ?>                
                
            </style>
            
            <?php
            
            return ob_get_clean();
        
        }
        
        function ut_create_inline_script( $id, $atts, $slides ) {
            
            /* no custom js for search excerpts */
            if( is_search() ) {
                return;
            }
            
            $loop = $slides > 1 ? 'true' : 'false';
            
            extract( shortcode_atts( array (
                'slide_effect'     => 'slide',
                'quote_width'      => 'centered',
                'dot_nav'          => 'false',
                'autoplay'         => 'false',
                'autoplay_timeout' => 5000,
                'loop'             => $loop,
            ), $atts ) );
            
            ob_start();
            
            ?>
            
            <script type="text/javascript">
                
                (function($){
                                            
                    $(document).ready(function(){
                        
                        var owl_<?php echo esc_attr( $id ); ?> = {
                            items:1,
                            <?php if( $quote_width == 'fullwidth' ) : ?>
                            margin: 80,
                            <?php endif; ?>
                            smartSpeed: 600,
                            lazyLoad: true,
                            <?php if( $slide_effect == 'fade' ) : ?>
                            animateIn: "fadeIn",
                            animateOut: "fadeOut",
                            <?php endif; ?>                            
                            autoplay: <?php echo $autoplay; ?>,
                            autoplayTimeout: <?php echo $autoplay_timeout; ?>,
                            loop:<?php echo $loop; ?>,
                            nav: false,
                            dots: <?php echo $dot_nav; ?>,
                        };
                        
                        var $<?php echo esc_attr( $id ); ?> =  $("#<?php echo esc_attr( $id ); ?>");
                                                
                        if ( $<?php echo esc_attr( $id ); ?>.closest( '.vc_row[data-vc-full-width]' ).length && $( window ).width() > 1600 ) {

                            /* wait for f***king vc */
                            new ResizeSensor( $( "#<?php echo esc_attr( $id ); ?>" ).closest( '.vc_row[data-vc-full-width]' ), function () {

                                $<?php echo esc_attr( $id ); ?>.owlCarousel( owl_<?php echo esc_attr( $id ); ?> );

                            } );

                        } else if ( $<?php echo esc_attr( $id ); ?>.closest( '.vc_section[data-vc-full-width]' ).length && $( window ).width() > 1600 ) {

                            /* wait for f***king vc */
                            new ResizeSensor( $( "#<?php echo esc_attr( $id ); ?>" ).closest( '.vc_section[data-vc-full-width]' ), function () {

                                $<?php echo esc_attr( $id ); ?>.owlCarousel( owl_<?php echo esc_attr( $id ); ?> );

                            } );

                        } else {

                            $<?php echo esc_attr( $id ); ?>.owlCarousel( owl_<?php echo esc_attr( $id ); ?> );

                        }
                
                    });
                        
                })(jQuery);
                    
            </script>
            
            <?php
            
            return ob_get_clean();
        
        }
        
        
        function ut_create_shortcode( $atts, $content = NULL ) {
            
            /* enqueue scripts */
            $this->add_script = true;
            
            extract( shortcode_atts( array (
                'nav'               	=> 'true',
                'dot_nav'           	=> 'false',
                'effect'            	=> '',
				'slide_content_align'	=> 'left',
                'animate_once'      	=> 'yes',
                'animate_mobile'    	=> false,
                'animate_tablet'    	=> false,
                'delay'             	=> 'false',
                'delay_timer'       	=> '100',
                'animation_duration'	=> '',                
                'css'               	=> '',                
                'class'             	=> ''
            ), $atts ) ); 
            
            /* class array */
            $classes    = array();
            $attributes = array();
            
            /* extra element class */
            $classes[] = $class;
            $classes[] = 'ut-bklyn-qt-rotator-style-2-align-' . $slide_content_align;
			
            /* animation effect */
            $dataeffect = NULL;
            
            if( !empty( $effect ) && $effect != 'none' ) {
                
                $attributes['data-effect']      = esc_attr( $effect );
                $attributes['data-animateonce'] = esc_attr( $animate_once );
                $attributes['data-delay'] = $delay == 'true' ? esc_attr( $delay_timer ) : 0;
                
                if( !empty( $animation_duration ) ) {
                    $attributes['data-animation-duration'] = esc_attr( ut_add_timer_unit( $animation_duration, 's' ) );    
                }
                
                $classes[]  = 'ut-animate-element';
                $classes[]  = 'animated';
                
                if( !$animate_tablet ) {
                    $classes[]  = 'ut-no-animation-tablet';
                }
                
                if( !$animate_mobile ) {
                    $classes[]  = 'ut-no-animation-mobile';
                }
                
                if( $animate_once == 'infinite' ) {
                    $classes[]  = 'infinite';
                }
                
            }     
            
            /* attributes string */
            $attributes = implode(' ', array_map(
                function ($v, $k) { return sprintf("%s=\"%s\"", $k, $v); },
                $attributes,
                array_keys( $attributes )
            ) ); 
            
            /* set unique ID for this rotator */
            $id = uniqid("qtSlider_");
            $outer_id = uniqid("qtSliderOuter");
            
            /* start output */
            $output = '';            
            
            /* attach script */
            $output .= $this->ut_create_inline_script( $id, $atts, substr_count( $content, '[ut_qt') );
            
            /* attach css */
            $output .= ut_minify_inline_css( $this->ut_create_inline_css( $outer_id, $atts ) );
            
            $output .= '<div ' . $attributes . ' class="ut-bkly-qt-rotator ut-bkly-text-rotator ut-bklyn-qt-rotator-style-2 ' . implode( ' ', $classes ) . '">';
            
                $output .= '<div id="' . esc_attr( $id ) . '" class="owl-carousel">';
                    
                    $output .= do_shortcode( $content );
                         
                $output .= '</div>';
                
                if( $nav == 'true' ) {
                        
                    $output .= '<a href="#" data-for="' . esc_attr( $id ) . '" class="ut-prev-gallery-slide"><i class="Bklyn-Core-Left-2"></i></a>';
                    $output .= '<a href="#" data-for="' . esc_attr( $id ) . '" class="ut-next-gallery-slide"><i class="Bklyn-Core-Right-2"></i></a>';                
                
                }
            
            $output .= '</div>';
            
            if( defined( 'WPB_VC_VERSION' ) ) { 
                
                return '<div id="'. esc_attr( $outer_id ).'" class="wpb_content_element ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->shortcode, $atts ) . '">' . $output . '</div>'; 
            
            }
                
            return $output;
        
        }
        
        function ut_create_inner_shortcode( $atts, $content = NULL ) {
            
            extract( shortcode_atts( array (
                'author'     => '',
                'icon_bklyn' => '',
                'imageicon'  => '',
                'origin'     => '',
            ), $atts ) ); 
            
            $output = '';
            
            $output .= '<div class="ut-bkly-qt-slide">';
                
                $output .= '<div class="bkly-testimonials-style2">';
                
                    $output .= '<div class="bklyn-testimonials-quote">';
                        
                        if( !empty( $content ) ) {
                    
                            $output .= do_shortcode( wpautop( $content ) );
                            
                        }
                        
                        if( !empty( $imageicon ) || !empty( $author ) || !empty( $origin ) ) {
                        
                            $output .= '<div class="bklyn-about-testimonials-author">';
                        
                                if( !empty( $imageicon ) ) {
                                    
                                    if( !empty( $imageicon ) && is_numeric( $imageicon ) ) {
                                        $imageicon = ut_resize( wp_get_attachment_url( $imageicon ) , '64' , '64', true , true , true );        
                                    }
                                    
                                    if( $imageicon ) {
                                    
                                        $output .= '<div class="bklyn-testimonials-icon-wrap">';

                                            $output .= '<div class="bklyn-testimonials-icon">';

                                                $output .= '<img alt="' . ( !empty( $author ) && $imageicon ? $author : '' ) . '" src="' . esc_url( $imageicon ) . '">';

                                            $output .= '</div>';

                                        $output .= '</div>';
                                
                                    }
                                        
                                } elseif( !empty( $icon_bklyn ) ) {
                                    
                                    $output .= '<div class="bklyn-testimonials-icon-wrap">';
                                    
                                        $output .= '<div class="bklyn-testimonials-font-icon">';
                                            
                                            $output .= '<i class="' . $icon_bklyn . '"></i>';
                                    
                                        $output .= '</div>';
                                    
                                    $output .= '</div>';
                                    
                                }
                                
                                if( !empty( $author ) || !empty( $origin ) ) {
                                
                                    $output .= '<div class="bklyn-testimonials-author-wrap">';
                                    
                                        if( !empty( $author ) ) {
                                                
                                            $output .= '<h3 class="bklyn-testimonials-author">';
                                            
                                                $output .= $author;
                                        
                                            $output .= '</h3>';
                                        
                                        }
                                            
                                        if( !empty( $origin ) ) {
                                        
                                            $output .= '<div class="bklyn-testimonials-origin">';
                                        
                                                $output .= $origin;
                                        
                                            $output .= '</div>';
                                        
                                        }
                                    
                                    $output .= '</div>';
                                
                                }
                            
                            $output .= '</div>';
                        
                        }
 
                    $output .= '</div>';
                
                $output .= '</div>';
                
            $output .= '</div>';
            
            return $output;                     
        
        }
        
        function register_scripts() {
            
            $min = NULL;
        
            if( !WP_DEBUG ){
                $min = '.min';
            } 
            
            wp_register_script( 
                'ut-owl-carousel', 
                plugins_url('../js/plugins/owlsider/js/owl.carousel' . $min . '.js', __FILE__), 
                array('jquery'), 
                '2.0.0', 
                true
            );
            
            wp_register_script( 
                'ut-resize-sensor', 
                plugins_url('../js/plugins/element-queries/ResizeSensor' . $min . '.js', __FILE__), 
                array('jquery'), 
                '2.0.0', 
                true
            );
            
            wp_register_style(
                'ut-owl-carousel', 
                plugins_url('../js/plugins/owlsider/css/owl.carousel' . $min . '.css', __FILE__), 
                array(), 
                '2.0.0' 
            );
            
            wp_register_style(
                'ut-owl-carousel-theme', 
                plugins_url('../js/plugins/owlsider/css/owl.theme.default' . $min . '.css', __FILE__), 
                array(), 
                '2.0.0' 
            );
            
            
        
        }        
        
        function enqueue_scripts() {
            
            if( !$this->add_script ) {
                return;
            }
            
            wp_print_scripts('ut-owl-carousel');
            wp_print_scripts('ut-resize-sensor');
            
            wp_print_styles('ut-owl-carousel');
            wp_print_styles('ut-owl-carousel-theme');
        
        }      
            
    }

}

new UT_Quote_Rotator_3;


if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    
    class WPBakeryShortCode_ut_qtrotator_3 extends WPBakeryShortCodesContainer {
        
        
    }
    
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
    
    class WPBakeryShortCode_ut_qt_3 extends WPBakeryShortCode {
        
        function __construct( $settings ) {
            
            parent::__construct( $settings );
            $this->jsScripts();
            
        }
    
        public function jsScripts() {
            
            wp_register_script( 'zoom', vc_asset_url( 'lib/bower/zoom/jquery.zoom.min.js' ), array(), WPB_VC_VERSION );
            wp_register_script( 'vc_image_zoom', vc_asset_url( 'lib/vc_image_zoom/vc_image_zoom.min.js' ), array(
                'jquery',
                'zoom',
            ), WPB_VC_VERSION, true );
            
        }
    
        public function singleParamHtmlHolder( $param, $value ) {
            
            $output = '';
    
            $param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
            $type = isset( $param['type'] ) ? $param['type'] : '';
            $class = isset( $param['class'] ) ? $param['class'] : '';
    
            if ( 'attach_image' === $param['type'] && 'avatar' === $param_name ) {
                $output .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
                $element_icon = $this->settings( 'icon' );
                $img = wpb_getImageBySize( array(
                    'attach_id' => (int) preg_replace( '/[^\d]/', '', $value ),
                    'thumb_size' => 'thumbnail',
                ) );
                $this->setSettings( 'logo', ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . vc_asset_url( 'vc/blank.gif' ) . '" class="attachment-thumbnail vc_general vc_element-icon"  data-name="' . $param_name . '" alt="" title="" style="display: none;" />' ) . '<span class="no_image_image vc_element-icon' . ( ! empty( $element_icon ) ? ' ' . $element_icon : '' ) . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '" /><a href="#" class="column_edit_trigger' . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '">' . __( 'Add image', 'js_composer' ) . '</a>' );
                $output .= $this->outputTitleTrue( $this->settings['name'] );
            } elseif ( ! empty( $param['holder'] ) ) {
                if ( 'input' === $param['holder'] ) {
                    $output .= '<' . $param['holder'] . ' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '">';
                } elseif ( in_array( $param['holder'], array( 'img', 'iframe' ) ) ) {
                    $output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="' . $value . '">';
                } elseif ( 'hidden' !== $param['holder'] ) {
                    $output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
                }
            }
    
            if ( ! empty( $param['admin_label'] ) && true === $param['admin_label'] ) {
                $output .= '<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>' . $param['heading'] . '</label>: ' . $value . '</span>';
            }
    
            return $output;
        }
    
        public function getImageSquareSize( $img_id, $img_size ) {
            if ( preg_match_all( '/(\d+)x(\d+)/', $img_size, $sizes ) ) {
                $exact_size = array(
                    'width' => isset( $sizes[1][0] ) ? $sizes[1][0] : '0',
                    'height' => isset( $sizes[2][0] ) ? $sizes[2][0] : '0',
                );
            } else {
                $image_downsize = image_downsize( $img_id, $img_size );
                $exact_size = array(
                    'width' => $image_downsize[1],
                    'height' => $image_downsize[2],
                );
            }
            $exact_size_int_w = (int) $exact_size['width'];
            $exact_size_int_h = (int) $exact_size['height'];
            if ( isset( $exact_size['width'] ) && $exact_size_int_w !== $exact_size_int_h ) {
                $img_size = $exact_size_int_w > $exact_size_int_h
                    ? $exact_size['height'] . 'x' . $exact_size['height']
                    : $exact_size['width'] . 'x' . $exact_size['width'];
            }
    
            return $img_size;
        }
    
        protected function outputTitle( $title ) {
            return '';
        }
    
        protected function outputTitleTrue( $title ) {
            return '<h4 class="wpb_element_title">' . $title . ' ' . $this->settings( 'logo' ) . '</h4>';
        }                
        
    }
    
}