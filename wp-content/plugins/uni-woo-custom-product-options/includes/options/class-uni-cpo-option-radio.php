<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
*   Uni_Cpo_Option_text_input class
*
*/

class Uni_Cpo_Option_radio extends Uni_Cpo_Option {

    public $id = 0;

    public $post = null;

    public $option_type = 'radio';

    public $option_icon = '';

    public $option_name = '';

    public $tab_settings = array();

    public $specific_settings = array();

    protected $calc_status = true;

	/**
	 * Constructor gets the post object and sets the ID for the loaded option.
	 *
	 */
	public function __construct( $Option = false ) {

		if ( is_numeric( $Option ) ) {
			$this->id   = absint( $Option );
			$this->post = get_post( $this->id );
		} else if ( $Option instanceof Uni_Cpo_Option ) {
			$this->id   = absint( $Option->id );
			$this->post = $Option->post;
		} else if ( isset( $Option->ID ) ) {
			$this->id   = absint( $Option->ID );
			$this->post = $Option;
		}

        $this->option_icon = 'fa-dot-circle-o';
        $this->option_name = esc_html__('Radio buttons', 'uni-cpo');
        $this->tab_settings = apply_filters( "uni_cpo_".$this->option_type."_admin_settings_filter", array(
                                    array(
                                        'section_title' => esc_html__('General settings', 'uni-cpo'),
                                        'settings' => array('field_slug', 'field_required_enable', 'field_display_price_in_front',
                                                'field_display_price_in_front_text', 'field_thumb_size', 'field_option_radio_options')
                                    ),
                                    array(
                                        'section_title' => esc_html__('Display settings', 'uni-cpo'),
                                        'settings' => array('field_header_type', 'field_header_text',
                                            'field_header_tooltip_type', 'field_header_tooltip_text', 'field_header_tooltip_image',
                                            'field_extra_tooltip_selector_class', 'field_extra_class', 'field_meta_header_text')
                                    ),
                                    array(
                                        'section_title' => esc_html__('Conditional logic', 'uni-cpo'),
                                        'settings' => array('field_conditional_enable', 'field_conditional_default', 'field_conditional_scheme')
                                    )
                                )
                            );

        $this->specific_settings = apply_filters( "uni_cpo_".$this->option_type."_admin_specific_settings_filter", array(
                                    'field_option_radio_options' => array( 'radio_options' =>
                                        array(
                                            'title' => esc_html__('Sub options', 'uni-cpo'),
                                            'description' => esc_html__('Add some sub options for radio and, please, keep unique slugs for them. These slugs might be used in a formula conditional rules (e.g. when you use operators "equal", "not equal" etc).', 'uni-cpo'),
                                            'doc_link' => esc_url('http://moomoo.agency/demo/cpo/docs'),
                                            'name' => 'field_option_radio_options',
                                            'required' => false
                                        )
                                    )
                                )
                            );

	}

	/**
	 * Displays option in the front end
	 *
	 */
	public function render_option( $aChildrenOptionsIds = array() ) {

        $aOptionCustomMeta  = $this->get_post_meta();
        $sElementFieldName  = $this->get_slug();
        $sRadioValue        = '';
        $aAllowedHtml       = uni_cpo_allowed_html_for_option_titles();
        $aAllowedTootltipHtml = uni_cpo_allowed_html_for_tooltips();
        $tooltip_icon_html  = uni_cpo_option_tooltip_icon_output( $this );

        if ( isset($_POST[$sElementFieldName]) && !empty($_POST[$sElementFieldName]) ) {
            $sRadioValue = $_POST[$sElementFieldName];
        }

        if ( !isset($aOptionCustomMeta['_uni_cpo_field_header_tooltip_type'][0]) || empty($aOptionCustomMeta['_uni_cpo_field_header_tooltip_type'][0]) ) {
            $sTooltipType = 'classic';
        } else {
            $sTooltipType = $aOptionCustomMeta['_uni_cpo_field_header_tooltip_type'][0];
        }

        $sThumbSize         = ( !empty($aOptionCustomMeta['_uni_cpo_field_thumb_size'][0]) ) ? $aOptionCustomMeta['_uni_cpo_field_thumb_size'][0] : 'thumbnail';

        do_action( 'uni_cpo_before_option', $this );

        echo '<div id="'.esc_attr( $sElementFieldName ).'" data-type="' . esc_attr( $this->get_type() ) . '" class="uni_cpo_fields_container uni_cpo_field_type_'.esc_attr( $this->get_type() ) . ( (!empty($aOptionCustomMeta['_uni_cpo_field_extra_class'][0])) ? ' '.sanitize_html_class( $aOptionCustomMeta['_uni_cpo_field_extra_class'][0] ) : '' ) .'">';

            if ( !empty( $aOptionCustomMeta['_uni_cpo_field_header_text'][0] ) && !empty( $aOptionCustomMeta['_uni_cpo_field_header_type'][0] ) ) {
                echo '<'.esc_attr($aOptionCustomMeta['_uni_cpo_field_header_type'][0]).' class="uni_cpo_fields_header">';
                echo wp_kses( $aOptionCustomMeta['_uni_cpo_field_header_text'][0], $aAllowedHtml ) . ( ( !empty($aOptionCustomMeta['_uni_cpo_field_required_enable'][0]) && $aOptionCustomMeta['_uni_cpo_field_required_enable'][0] == 'yes' ) ? ' <span class="uni-cpo-required-label">*</span>' : '' );

                // tooltips
                if ( $sTooltipType == 'classic' ) {
                    if ( !empty($aOptionCustomMeta['_uni_cpo_field_header_tooltip_text'][0]) && empty($aOptionCustomMeta['_uni_cpo_field_extra_tooltip_selector_class'][0]) ) {
                        echo ' <span class="uni-cpo-tooltip" data-tooltip-content="#uni-cpo-tooltip-'.esc_attr( $sElementFieldName ).'">'.$tooltip_icon_html.'</span>';
                        echo '<div class="tooltip_templates"><div id="uni-cpo-tooltip-'.esc_attr( $sElementFieldName ).'">'.wp_kses( $aOptionCustomMeta['_uni_cpo_field_header_tooltip_text'][0], $aAllowedTootltipHtml ).'</div></div>';
                    } else if ( !empty($aOptionCustomMeta['_uni_cpo_field_header_tooltip_text'][0]) && !empty($aOptionCustomMeta['_uni_cpo_field_extra_tooltip_selector_class'][0]) && $aOptionCustomMeta['_uni_cpo_field_extra_tooltip_selector_class'][0] !== 'uni-cpo-custom-tooltip' ) {
                        echo ' <span class="'.esc_attr($aOptionCustomMeta['_uni_cpo_field_extra_tooltip_selector_class'][0]).' uni-cpo-tooltip-element" title="'.esc_attr(wp_kses( $aOptionCustomMeta['_uni_cpo_field_header_tooltip_text'][0], $aAllowedTootltipHtml )).'">'.$tooltip_icon_html.'</span>';
                    } else if ( !empty($aOptionCustomMeta['_uni_cpo_field_header_tooltip_text'][0]) && !empty($aOptionCustomMeta['_uni_cpo_field_extra_tooltip_selector_class'][0]) && $aOptionCustomMeta['_uni_cpo_field_extra_tooltip_selector_class'][0] === 'uni-cpo-custom-tooltip' ) {
                        echo ' <div class="uni-cpo-custom-tooltip-element">';
                        echo $tooltip_icon_html;
                            echo '<div class="uni-cpo-custom-tooltip-content">';
                            echo wp_kses( $aOptionCustomMeta['_uni_cpo_field_header_tooltip_text'][0], $aAllowedTootltipHtml );
                            echo '</div>';
                        echo '</div>';
                    }
                } else if ( $sTooltipType == 'lightbox' ) {
                    $iThumbId = intval($aOptionCustomMeta['_uni_cpo_field_header_tooltip_image'][0]);
                    $aImage = wp_get_attachment_image_src( $iThumbId, 'full' );
                    $sImageUrl = $aImage[0];
                    echo '<a href="' . esc_url($sImageUrl) . '" data-lity data-lity-desc="" class="uni-cpo-tooltip-element">';
                    echo $tooltip_icon_html;
                    echo '</a>';
                }

                echo'</'.esc_attr($aOptionCustomMeta['_uni_cpo_field_header_type'][0]).'>';
            }

            $sSerializedValues = ( !empty($aOptionCustomMeta['_uni_cpo_field_option_radio_options'][0]) && $aOptionCustomMeta['_uni_cpo_field_option_radio_options'][0] ) ? $aOptionCustomMeta['_uni_cpo_field_option_radio_options'][0] : '';
            $aArrayOfValues = maybe_unserialize( $sSerializedValues );

            if ( !empty($aOptionCustomMeta['_uni_cpo_field_required_enable'][0]) && $aOptionCustomMeta['_uni_cpo_field_required_enable'][0] == 'yes' ) {

                if ( !empty( $aArrayOfValues ) && is_array( $aArrayOfValues ) ) {
                    $i = 0;
                    foreach ( $aArrayOfValues as $sKey => $aValue ) {

                        if( $aValue === NULL ) {
                            continue;
                        }

                        $sThisOptionLabel = ( ( !empty( $aValue['label'] ) ) ? $aValue['label'] : '' );
                        echo '<label class="uni-cpo-option-label uni-cpo-radio-option-label'.( ( !empty( $aValue['image_id'] ) ) ? ' uni-cpo-radio-option-label-with-thumb' : '' ).'">';
                            if ( !empty($sRadioValue) ) {
                                echo '<input type="radio" name="'.esc_attr( $sElementFieldName ).'" class="'.esc_attr( $sElementFieldName ).'-field js-uni-cpo-field-'.esc_attr( $this->get_type() ).' uni-cpo-required" value="'.esc_attr($aValue['slug']).'" data-parsley-required="true" data-parsley-trigger="change focusout submit"'.checked($aValue['slug'], $sRadioValue, false).' />';
                            } else {
                                echo '<input type="radio" name="'.esc_attr( $sElementFieldName ).'" class="'.esc_attr( $sElementFieldName ).'-field js-uni-cpo-field-'.esc_attr( $this->get_type() ).' uni-cpo-required" value="'.esc_attr($aValue['slug']).'" data-parsley-required="true" data-parsley-trigger="change focusout submit"'.checked($i, ( isset($aValue['default']) ) ? $aValue['default'] : '', false).' />';
                            }
                            echo '<span>'.wp_kses( $sThisOptionLabel, $aAllowedHtml );
                            if ( !empty($aOptionCustomMeta['_uni_cpo_field_display_price_in_front'][0])
                                && $aOptionCustomMeta['_uni_cpo_field_display_price_in_front'][0] == 'yes' ) {

                                if ( !empty($aOptionCustomMeta['_uni_cpo_field_display_price_in_front_text'][0]) ) {
                                    $sCustomDisplayPriceText = $aOptionCustomMeta['_uni_cpo_field_display_price_in_front_text'][0];
                                    $sCustomDisplayPriceText = preg_replace('/({sPriceValue})/', uni_cpo_price( $aValue['price'] ), $sCustomDisplayPriceText);
                                    echo '<span class="uni-cpo-option-price">'.esc_attr($sCustomDisplayPriceText).'</span>';
                                } else {
                                    echo '<span class="uni-cpo-option-price">+'.uni_cpo_price( $aValue['price'] ).'</span>';
                                }

                            }
                            echo '</span>';
                            if ( isset( $aValue['tooltip'] ) && ! empty( $aValue['tooltip'] ) ) {
                                echo '<span class="uni-cpo-tooltip" data-tooltip-content="#uni-cpo-tooltip-'.esc_attr( $sElementFieldName ).'-suboption-'.esc_attr($aValue['slug']).'">'.$tooltip_icon_html.'</span>';
                                echo '<div class="tooltip_templates"><div id="uni-cpo-tooltip-'.esc_attr( $sElementFieldName ).'-suboption-'.esc_attr($aValue['slug']).'">'.wp_kses( $aValue['tooltip'], $aAllowedTootltipHtml ).'</div></div>';
                            }
                            if ( !empty( $aValue['image_id'] ) ) {
                                $iThumbId = '';
                                $iThumbId = intval($aValue['image_id']);
                                $aImage = wp_get_attachment_image_src( $iThumbId, $sThumbSize );
                                $sImageUrl = $aImage[0];
                                echo '<img src="'.$sImageUrl.'" alt="'.esc_attr($sThisOptionLabel).'" />';
                                echo '<div class="uni-cpo-clear"></div>';
                            }
                        echo '</label>';
                        $i++;
                    }
                }

            } else {

                if ( !empty( $aArrayOfValues ) && is_array( $aArrayOfValues ) ) {
                    $i = 0;
                    foreach ( $aArrayOfValues as $sKey => $aValue ) {

                        if( $aValue === NULL ) {
                            continue;
                        }

                        $sThisOptionLabel = ( ( !empty( $aValue['label'] ) ) ? $aValue['label'] : '' );
                        echo '<label class="uni-cpo-option-label uni-cpo-radio-option-label'.( ( !empty( $aValue['image_id'] ) ) ? ' uni-cpo-radio-option-label-with-thumb' : '' ).'">';
                            if ( !empty($sRadioValue) ) {
                                echo '<input type="radio" name="'.esc_attr( $sElementFieldName ).'" class="'.esc_attr( $sElementFieldName ).'-field js-uni-cpo-field-'.esc_attr( $this->get_type() ).'" value="'.esc_attr($aValue['slug']).'"'.checked( $aValue['slug'], $sRadioValue, false ).' />';
                            } else {
                                echo '<input type="radio" name="'.esc_attr( $sElementFieldName ).'" class="'.esc_attr( $sElementFieldName ).'-field js-uni-cpo-field-'.esc_attr( $this->get_type() ).'" value="'.esc_attr($aValue['slug']).'"'.checked( $i, ( isset($aValue['default']) ) ? $aValue['default'] : '', false ).' />';
                            }
                            echo '<span>'.wp_kses( $sThisOptionLabel, $aAllowedHtml );
                            if ( !empty($aOptionCustomMeta['_uni_cpo_field_display_price_in_front'][0])
                                && $aOptionCustomMeta['_uni_cpo_field_display_price_in_front'][0] == 'yes' ) {

                                if ( !empty($aOptionCustomMeta['_uni_cpo_field_display_price_in_front_text'][0]) ) {
                                    $sCustomDisplayPriceText = $aOptionCustomMeta['_uni_cpo_field_display_price_in_front_text'][0];
                                    $sCustomDisplayPriceText = preg_replace('/({sPriceValue})/', uni_cpo_price( $aValue['price'] ), $sCustomDisplayPriceText);
                                    echo '<span class="uni-cpo-option-price">'.esc_attr($sCustomDisplayPriceText).'</span>';
                                } else {
                                    echo '<span class="uni-cpo-option-price">+'.uni_cpo_price( $aValue['price'] ).'</span>';
                                }

                            }
                            echo '</span>';
                            if ( !empty( $aValue['image_id'] ) ) {
                                $iThumbId = '';
                                $iThumbId = intval($aValue['image_id']);
                                $aImage = wp_get_attachment_image_src( $iThumbId, $sThumbSize );
                                $sImageUrl = $aImage[0];
                                echo '<img src="'.$sImageUrl.'" alt="'.esc_attr($sThisOptionLabel).'" />';
                                echo '<div class="uni-cpo-clear"></div>';
                            }
                        echo '</label>';
                        $i++;
                    }
                }
            }

                // the field conditional logic
                $this->render_fc_rules();

            echo '<div class="uni-cpo-clear"></div>';
        echo '</div>';

        do_action( 'uni_cpo_after_option', $this );

	}

	/**
	 * gets form field(s) for option for add/edit options modal on order edit page
	 *
	 */
	public function get_order_form_field( $posted_data = array() ) {
        $option_meta    = $this->get_post_meta();
        $subptions = ( ! empty( $option_meta['_uni_cpo_field_option_radio_options'][0] ) && $option_meta['_uni_cpo_field_option_radio_options'][0] ) ? $option_meta['_uni_cpo_field_option_radio_options'][0] : '';
        $subptions = maybe_unserialize( $subptions );

        $output = '<tr>';

        $output .= '<th><label>' . esc_html( $this->get_label() ) . ' <i>(' . esc_html( $this->get_slug() ) . ')</i></label></th>';
        $output .= '<td><select name="' . esc_attr( $this->get_slug() ) . '">';
        if ( !empty( $subptions ) && is_array( $subptions ) ) {
            $output .= '<option value="">'.esc_html__( 'Please select...', 'uni-cpo' ).'</option>';
            foreach ( $subptions as $key => $val ) {
                if( $val === NULL ) {
                    continue;
                }
                $output .= '<option value="' . esc_attr( $val['slug'] ) . '"' . selected( $val['slug'], ( isset( $posted_data[$this->get_slug()] ) ? $posted_data[$this->get_slug()] : '' ), false ) . '>' . esc_html( $val['label'] ) . '</option>';
            }
        }
        $output .= '</select></td>';

        $output .= '</tr>';

        if ( ! empty( $this->get_special_vars() ) ) {
            foreach ( $this->get_special_vars() as $var_suffix ) {
                $output .= '<tr class="cpo_order_item_options_special_var">';

                $output .= '<th><label><i>(' . esc_html( $this->get_slug() . '_' . $var_suffix ) . ')</i></label></th>';
                $output .= '<td><input type="text" name="' . esc_attr( $this->get_slug() . '_' . $var_suffix ) . '" value="' . ( isset( $posted_data[$this->get_slug() . '_' . $var_suffix] ) ? $posted_data[$this->get_slug() . '_' . $var_suffix] : '' ) . '" /></td>';

                $output .= '</tr>';
            }
        }

        return $output;
	}

	/**
	 * Generates query builder array of data
	 *
	 */
	public function generate_filter( $bFull = false ) {
        $aSelectOptions = get_post_meta( $this->get_id(), '_uni_cpo_field_option_'.$this->get_type().'_options', true );
        $aSelectOptionsForQueryBuilder = array();
        if ( !empty($aSelectOptions) ) {
            if ( isset($aSelectOptions['default']) ) {
                unset($aSelectOptions['default']);
            }
            foreach ( $aSelectOptions as $sKey => $aValue ) {

                if( $aValue === NULL ) {
                    continue;
                }
                
                $aSelectOptionsForQueryBuilder[] = ( !empty($aValue['slug']) ) ? $aValue['slug'] : '';
            }
        }
        $aPieceOfFiltersArray = array(
            'id' => $this->post->post_name,
            'label' => $this->post->post_title,
            'type' => 'string',
            'input' => 'select',
            'values' => $aSelectOptionsForQueryBuilder,
            'operators' => array( 'equal', 'not_equal', 'is_empty', 'is_not_empty' )
        );
        $aFilterArray[] = $aPieceOfFiltersArray;

        return $aFilterArray;
	}

	/**
	 * Generates an array of vars and values for formula calculation associated with this option
	 *
	 */
	public function calculation( $aFormPostData, $bCartMeta = false ) {

        $sElementFieldName  = trim($this->post->post_title, '{}');

        if ( isset($aFormPostData[$sElementFieldName]) && !empty($aFormPostData[$sElementFieldName]) ) {
            if ( get_post_meta( $this->get_id(), '_uni_cpo_field_option_radio_options', true ) ) {
                $aSelectOptions = get_post_meta( $this->get_id(), '_uni_cpo_field_option_radio_options', true );
                $sAllOptionsChosen = '';
                if ( !empty($aSelectOptions) ) {
                unset($aSelectOptions['default']);
                foreach ( $aSelectOptions as $sKey => $aValue ) {
                    if ( (( !empty($aValue['slug']) ) ? $aValue['slug'] : '') === $aFormPostData[$sElementFieldName] ) {;
                        if ( !$bCartMeta ) {
                            return ( !empty($aValue['price']) ) ? floatval( $aValue['price'] ) : '0';
                        } else if ( $bCartMeta == 'cart' ) {
                            return $aValue['slug'];
                        } else if ( $bCartMeta == 'order' ) {
                            return $aValue['label'];
                        }
                    }
                }
                }
            } else {
                if ( !$bCartMeta ) {
                    return '0';
                } else {
                    return '';
                }
            }
        } else {
            if ( !$bCartMeta ) {
                return '0';
            } else {
                return '';
            }
        }

	}

}

?>