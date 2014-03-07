<?php
/**
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Theme03
 * @subpackage G1_Shortcodes
 * @since G1_Shortcodes 1.0.0
 */

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>
<?php


/**
 * Add "panels" section to the global shortcode generator
 *
 * @param       G1_Shortcode_Generator $generator
 */
function g1_shortgen_section_panels( $generator ) {
    $generator->add_section( 'panels', array(
        'label' => __( 'Panels', 'g1_theme' ),
    ));
}
add_action( 'g1_shortcode_generator_register', 'g1_shortgen_section_panels', 9 );



class G1_Toggle_Shortcode extends G1_Shortcode {
    public function __construct( $id, $args = array() ) {
        parent::__construct( $id, $args );

        // content
        $this->set_content( 'content', array(
            'form_control' => 'Long_Text',
        ));

        add_action( 'g1_shortcode_generator_register', array( $this, 'add_shortcode_generator_item' ) );
    }

    /**
     * Add shortcode to the global shortcode generator
     *
     * @param       G1_Shortcode_Generator $generator
     */
    public function add_shortcode_generator_item( $generator ) {
        $generator->add_item( $this, 'panels' );
    }

    protected function load_attributes() {
        // title
        $this->add_attribute( 'title', array(
            'form_control' => 'Text',
        ));

        // on attribute
        $this->add_attribute( 'state', array(
            'form_control' => 'Choice',
            'choices'	   => array(
                'on' 		=> 'on',
                'off'	    => 'off',
            ),
            'default'       => 'off',
        ));

        // style attribute
        $this->add_attribute( 'style', array(
            'form_control' => 'Choice',
            'choices'	   => array(
                'simple' 	=> 'simple',
                'solid'	    => 'solid',
            ),
            'default'       => 'solid',
        ));

        // icon attribute
        $this->add_attribute( 'icon', array(
            'form_control' => 'Choice',
            'default'      => '',
            'choices_cb'   => 'g1_get_font_awesome',
        ));
    }

    /**
     * Shortcode callback function.
     *
     * @return string
     */
    protected function do_shortcode() {
        extract( $this->extract() );

        $content = preg_replace('#^<\/p>|<p>$#', '', $content);

        // Compose final HTML id attribute
        $final_id = strlen( $id ) ? $id : 'g1-toggle-counter-' . $this->get_counter();

        // Compose final HTML class attribute
        $final_class = array(
            'g1-toggle',
            'g1-toggle--' . $state,
            'g1-toggle--' . $style,
        );

        if ( strlen( $icon ) ) {
            $icon = '<i class="icon-' . sanitize_html_class($icon) . '"></i>';
            $final_class[] = 'g1-toggle--icon';
        } else {
            $final_class[] = 'g1-toggle--noicon';
        }

        // Compose the template
        $out = 	'<div %id%%class%>' .
            '<div class="g1-toggle__title">%icon%%title%</div>' .
            '<div class="g1-toggle__content"><div class="g1-block">%content%</div></div>' .
            '</div>';

        //Fill in the template
        $out = str_replace(
            array(
                '%id%',
                '%class%',
                '%icon%',
                '%title%',
                '%content%',
            ),
            array(
                strlen( $final_id ) ? 'id="' . esc_attr( $final_id ) . '" ' : '',
                count( $final_class ) ? 'class="' . sanitize_html_classes( $final_class ) . '" ' : '',
                $icon,
                $title,
                do_shortcode( shortcode_unautop( $content ) ),
            ),
            $out
        );

        return $out;
    }
}
function G1_Toggle_Shortcode() {
    static $instance = null;

    if ( !isset( $instance ) )
        $instance = new G1_Toggle_Shortcode( 'toggle' );

    return $instance;
}
// Fire in the hole :)
G1_Toggle_Shortcode();



class G1_Tabs_Shortcode extends G1_Shortcode {
    /**
     * @todo What about the commented description?
     */
    public function __construct( $id, $args = array() ) {
        parent::__construct( $id, $args );

        // content
        $this->set_content( 'content', array(
            'form_control' => 'Long_Text',
        ));

        add_action( 'g1_shortcode_generator_register', array( $this, 'add_shortcode_generator_item' ) );
    }

    /**
     * Add shortcode to the global shortcode generator
     *
     * @param       G1_Shortcode_Generator $generator
     */
    public function add_shortcode_generator_item( $generator ) {
        $generator->add_item( $this, 'panels' );
    }

    protected function load_attributes() {
        // position attribute
        $this->add_attribute( 'position', array(
            'form_control' => 'Choice',
            'choices'	   => array(
                'top-left' 		=> 'top-left',
                'top-center'	=> 'top-center',
                'top-right'		=> 'top-right',
                'bottom-left'	=> 'bottom-left',
                'bottom-center'	=> 'bottom-center',
                'bottom-right'	=> 'bottom-right',
                'left-top' 		=> 'left-top',
                'right-top' 	=> 'right-top',
            ),
        ));

        // style attribute
        $this->add_attribute( 'style', array(
            'form_control' => 'Choice',
            'default'      => 'simple',
            'choices'	   => array(
                'simple' 		=> 'simple',
                'button'		=> 'button',
                'transparent'	=> 'transparent',
            ),
        ));

        // type attribute
        $this->add_attribute( 'type', array(
            'form_control' => 'Choice',
            'default'      => 'click',
            'choices'	   => array(
                'click'     => 'change tab on click',
                'hover'		=> 'change tab on hover',
            ),
        ));
    }

    /**
     * Shortcode callback function.
     *
     * @return string
     */
    protected function do_shortcode() {
        extract( $this->extract() );

        $content = preg_replace('#^<\/p>|<p>$#', '', $content);

        // Compose final HTML id attribute
        $final_id = strlen( $id ) ? $id : 'g1-tabs-' . $this->get_counter();


        // Compose final HTML class attribute
        $final_class = array(
            'g1-tabs',
            'g1-tabs--' . sanitize_html_class( $style ),
            'g1-type--' . sanitize_html_class( $type ),
        );

        switch ( $position ) {
            case 'top-left':
            case 'top_left':
                $final_class[] = 'g1-tabs--horizontal';
                $final_class[] = 'g1-tabs--top';
                $final_class[] = 'g1-align-left';
                break;

            case 'top-center':
            case 'top_center':
                $final_class[] = 'g1-tabs--horizontal';
                $final_class[] = 'g1-tabs--top';
                $final_class[] = 'g1-align-center';
                break;

            case 'top-right':
            case 'top_right':
                $final_class[] = 'g1-tabs--horizontal';
                $final_class[] = 'g1-tabs--top';
                $final_class[] = 'g1-align-right';
                break;

            case 'bottom-left':
            case 'bottom_left':
                $final_class[] = 'g1-tabs--horizontal';
                $final_class[] = 'g1-tabs--bottom';
                $final_class[] = 'g1-align-left';
                break;

            case 'bottom-center':
            case 'bottom_center':
                $final_class[] = 'g1-tabs--horizontal';
                $final_class[] = 'g1-tabs--bottom';
                $final_class[] = 'g1-align-center';
                break;

            case 'bottom-right':
            case 'bottom_right':
                $final_class[] = 'g1-tabs--horizontal';
                $final_class[] = 'g1-tabs--bottom';
                $final_class[] = 'g1-align-right';
                break;

            case 'left-top':
            case 'left_top':
                $final_class[] = 'g1-tabs--vertical';
                $final_class[] = 'g1-tabs--left';
                $final_class[] = 'g1-align-top';
                break;

            case 'left_center':
            case 'left_center':
            case 'left_middle':
            case 'left_middle':
                $final_class[] = 'g1-tabs--vertical';
                $final_class[] = 'g1-tabs--left';
                $final_class[] = 'g1-align-middle';
                break;

            case 'left-bottom':
            case 'left_bottom':
                $final_class[] = 'g1-tabs--vertical';
                $final_class[] = 'g1-tabs--left';
                $final_class[] = 'g1-align-bottom';
                break;

            case 'right-top':
            case 'right_top':
                $final_class[] = 'g1-tabs--vertical';
                $final_class[] = 'g1-tabs--right';
                $final_class[] = 'g1-align-top';
                break;

            case 'right-center':
            case 'right_center':
            case 'right-middle':
            case 'right_middle':
                $final_class[] = 'g1-tabs--vertical';
                $final_class[] = 'g1-tabs--right';
                $final_class[] = 'g1-align-middle';
                break;

            case 'right-bottom':
            case 'right_bottom':
                $final_class[] = 'g1-tabs--vertical';
                $final_class[] = 'g1-tabs--right';
                $final_class[] = 'g1-align-bottom';
                break;
        }

        // Compose output
        $out = '';


        $out .= '<div id="' . esc_attr( $final_id ) . '" class="' . sanitize_html_classes( $final_class ) . '">';
        $out .= do_shortcode( shortcode_unautop( $content ) );
        $out .= '</div>';

        return $out;
    }
}
function G1_Tabs_Shortcode() {
    static $instance = null;

    if ( null === $instance )
        $instance = new G1_Tabs_Shortcode( 'tabs' );

    return $instance;
}
// Fire in the hole :)
G1_Tabs_Shortcode();



class G1_Tab_Title_Shortcode extends G1_Shortcode {
    /**
     * @todo What about the commented description?
     */
    public function __construct( $id, $args = array() ) {
        parent::__construct( $id, $args );

        // content
        $this->set_content( 'content', array(
            'form_control' => 'Text',
        ));

        add_action( 'g1_shortcode_generator_register', array( $this, 'add_shortcode_generator_item' ) );
    }

    /**
     * Add shortcode to the global shortcode generator
     *
     * @param       G1_Shortcode_Generator $generator
     */
    public function add_shortcode_generator_item( $generator ) {
        $generator->add_item( $this, 'panels' );
    }

    /**
     * Shortcode callback function.
     *
     * @return string
     */
    protected function do_shortcode() {
        extract( $this->extract() );

        $content = preg_replace('#^<\/p>|<p>$#', '', $content);

        // Compose final HTML id attribute
        $final_id = strlen( $id ) ? $id : 'tab-title-counter-' . $this->get_counter();

        // Compose final HTML class attribute
        $final_class = array(
            'g1-tab-title',
        );

        // Compose output
        $out = '<div id="' . esc_attr( $final_id ) . '" class="' . sanitize_html_classes( $final_class ) . '">' .
                    do_shortcode( shortcode_unautop( $content ) ) .
               '</div>';


        return $out;
    }
}
function G1_Tab_Title_Shortcode() {
    static $instance = null;

    if ( null === $instance )
        $instance = new G1_Tab_Title_Shortcode( 'tab_title' );

    return $instance;
}
// Fire in the hole :)
G1_Tab_Title_Shortcode();



class G1_Tab_Content_Shortcode extends G1_Shortcode {
    /**
     * @todo What about the commented description?
     */
    public function __construct( $id, $args = array() ) {
        parent::__construct( $id, $args );

        // content
        $this->set_content( 'content', array(
            'form_control' => 'Long_Text',
        ));

        add_action( 'g1_shortcode_generator_register', array( $this, 'add_shortcode_generator_item' ) );
    }

    /**
     * Add shortcode to the global shortcode generator
     *
     * @param  G1_Shortcode_Generator $generator
     */
    public function add_shortcode_generator_item( $generator ) {
        $generator->add_item( $this, 'panels' );
    }

    /**
     * Shortcode callback function.
     *
     * @return string
     */
    protected function do_shortcode() {
        extract( $this->extract() );

        $content = preg_replace('#^<\/p>|<p>$#', '', $content);

        // Compose final HTML id attribute
        $final_id = strlen( $id ) ? $id : 'tab-content-counter-' . $this->get_counter();

        // Compose final HTML class attribute
        $final_class = array(
            'g1-tab-content',
        );

        // Compose output
        $out = '<div id="' . esc_attr( $final_id ) . '" class="' . sanitize_html_classes( $final_class ) . '">' .
                    do_shortcode( shortcode_unautop( $content ) ) .
                '</div>';


        return $out;
    }
}
function G1_Tab_Content_Shortcode() {
    static $instance = null;

    if ( null === $instance )
        $instance = new G1_Tab_Content_Shortcode( 'tab_content' );

    return $instance;
}
// Fire in the hole :)
G1_Tab_Content_Shortcode();



/**
 * Add tabs snippets to the global shortcode generator
 *
 * @param       G1_Shortgen $shortgen
 */
function g1_shortgen_tabs_snippets( $shortgen ) {
$result = <<<G1_HEREDOC_DELIMITER
[tabs type="simple" position="top-left"]

[tab_title]Tab 1[/tab_title]

[tab_content]

here goes some tab content...

[/tab_content]

[tab_title]Tab 2[/tab_title]

[tab_content]

here goes some tab content...

[/tab_content]

[/tabs]
G1_HEREDOC_DELIMITER;

    // 2 tabs
    $shortgen->add_item(
        '*** 2 tabs',
        array(
            'label'		=> __('2 tabs', 'g1_theme'),
            'result'	=> $result,
            'section'	=> 'panels',
        )
    );

$result = <<<G1_HEREDOC_DELIMITER
[tabs type="simple" position="top-left"]

[tab_title]Tab 1[/tab_title]

[tab_content]

here goes some tab content...

[/tab_content]

[tab_title]Tab 2[/tab_title]

[tab_content]

here goes some tab content...

[/tab_content]

[tab_title]Tab 3[/tab_title]

[tab_content]

here goes some tab content...

[/tab_content]

[/tabs]
G1_HEREDOC_DELIMITER;



    // 3 tabs
    $shortgen->add_item(
        '*** 3 tabs',
        array(
            'label'		=> __('3 tabs', 'g1_theme'),
            'result'	=> $result,
            'section'	=> 'panels',
        )
    );

$result = <<<G1_HEREDOC_DELIMITER
[tabs type="simple" position="top-left"]

[tab_title]Tab 1[/tab_title]

[tab_content]

here goes some tab content...

[/tab_content]

[tab_title]Tab 2[/tab_title]

[tab_content]

here goes some tab content...

[/tab_content]

[tab_title]Tab 3[/tab_title]

[tab_content]

here goes some tab content...

[/tab_content]

[tab_title]Tab 4[/tab_title]

[tab_content]

here goes some tab content...

[/tab_content]

[/tabs]
G1_HEREDOC_DELIMITER;


    // 4 tabs
    $shortgen->add_item(
        '*** 4 tabs',
        array(
            'label'		=> __('4 tabs', 'g1_theme'),
            'result'	=> $result,
            'section'	=> 'panels',
        )
    );
}
add_action( 'g1_shortgen_register', 'g1_shortgen_tabs_snippets' );




class G1_Before_After_Shortcode extends G1_Shortcode {
    /**
     * Constructor
     */
    public function __construct( $id, $args = array() ) {
        parent::__construct( $id, $args );

        add_action( 'g1_shortcode_generator_register', array( $this, 'add_shortcode_generator_item' ) );
    }

    /**
     * Add shortcode to the global shortcode generator
     *
     * @param       G1_Shortcode_Generator $generator
     */
    public function add_shortcode_generator_item( $generator ) {
        $generator->add_item( $this, 'panels' );
    }

    protected function load_attributes() {
        // type attribute
        $this->add_attribute( 'type', array(
            'form_control'  => 'Choice',
            'choices'	    => array(
                'smooth'    => __( 'Splitted image', 'g1_theme' ),
                'flip'	    => __( 'Flip image on click', 'g1_theme' ),
                'hover'	    => __( 'Change on hover', 'g1_theme' ),
            ),
            'default'       => 'smooth',
        ));

        // before_src attribute
        $this->add_attribute( 'before_src', array(
            'form_control' => 'Text',
            'id_aliases' => array(
                'before',
                'before_image',
                'before_path',
                'before_url',
            )
        ));

        // after_src attribute
        $this->add_attribute( 'after_src', array(
            'form_control' => 'Text',
            'id_aliases' => array(
                'after',
                'after_image',
                'after_path',
                'after_url',
            )
        ));

        // width attribute
        $this->add_attribute( 'width', array(
            'form_control' => 'Text',
            'hint' => __( 'The width in pixels', 'g1_theme' ),
        ));

        // height attribute
        $this->add_attribute( 'height', array(
            'form_control' => 'Text',
            'hint' => __( 'The height in pixels', 'g1_theme' ),
        ));
    }

    /**
     * shortcode callback function.
     *
     * @param 			array $atts
     * @param			string $content
     * @return			string
     */
    protected function do_shortcode() {
        extract( $this->extract() );

        $content = preg_replace('#^<\/p>|<p>$#', '', $content);

        $width = absint( $width );
        $height = absint( $height );

        // Compose final HTML id attribute
        $final_id = strlen( $id ) ? $id : 'g1-banda-' . $this->get_counter();

        // Compose final HTML class attribute
        $final_class = array(
            'g1-banda',
            'g1-banda--' . $type,
        );

        // Compose output
        $out =	'<div id="%id%" class="%class%">' .
                    '[fluid_wrapper %width% %height%]' .
                        '<ol class="g1-banda__items">' .
                            '<li class="g1-banda__before"><img src="%before_src%" %width% %height% alt="%before_alt%" /></li>'.
                            '<li class="g1-banda__after"><img src="%after_src%" %width% %height% alt="%after_alt%" /></li>' .
                        '</ol>' .
                        '<div class="g1-banda__handle">' .
                            '<span></span>' .
                        '</div>' .
                    '[/fluid_wrapper]' .
                '</div>';

        $out = str_replace(
            array(
                '%id%',
                '%class%',
                '%width%',
                '%height%',
                '%before_src%',
                '%before_alt%',
                '%after_src%',
                '%after_alt%',
            ),
            array(
                esc_attr( $final_id ),
                sanitize_html_classes( $final_class ),
                ( $width ? 'width="' . absint( $width ) . '" ' : '' ),
                ( $height ? 'height="' . absint( $height ) . '" ' : '' ),
                esc_url( $before_src ),
                esc_attr( __( 'Before', 'g1_theme' ) ),
                esc_url( $after_src ),
                esc_attr( __( 'After', 'g1_theme' ) ),
            ),
            $out
        );

        $out = do_shortcode( shortcode_unautop( $out ) );


        return $out;
    }
}
function G1_Before_After_Shortcode() {
    static $instance = null;

    if ( null === $instance ) {
        $instance = new G1_Before_After_Shortcode( 'before_after', array( 'label' => 'Before & After' ) );
    }

    return $instance;
}
// Fire in the hole :)
G1_Before_After_Shortcode();