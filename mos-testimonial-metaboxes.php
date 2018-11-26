<?php
function mos_testimonial_metaboxes() {
    $prefix = '_mos_testimonial_';   

    $mos_testimonial_details = new_cmb2_box(array(
        'id' => $prefix . 'mos_testimonial_details',
        'title' => __('Testimonial Details', 'cmb2'),
        'object_types' => array('testimonial'),
    ));
    $mos_testimonial_details->add_field(array(
        'name' => 'Designation',
        'desc' => __( 'Designation of the author.', 'cmb2' ),
        'id' => $prefix . 'designation',
        'type' => 'text'
    ));
    $mos_testimonial_details->add_field(array(
        'name' => 'Website',
        'desc' => __( 'URL of the author.', 'cmb2' ),
        'id' => $prefix . 'url',
        'type' => 'text'
    ));
    $mos_testimonial_details->add_field( array(
        'name'             => 'Rating',
        'desc'             => 'Select an option',
        'id' => $prefix . 'rating',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => array(
            '1' => __( 'One Star', 'cmb2' ),
            '2' => __( 'Two Stars', 'cmb2' ),
            '3' => __( 'Three Stars', 'cmb2' ),
            '4' => __( 'Four Stars', 'cmb2' ),
            '5' => __( 'Five Stars', 'cmb2' ),
        ),
    ) );

    $mos_testimonial_details->add_field(array(
        'name' => 'Cover Image',
        'desc' => __( 'Cover image of oEmbed.', 'cmb2' ),
        'id' => $prefix . 'image',
        'type' => 'file',
        'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
        'query_args' => array( 'type' => 'image' ), // Only images attachment
    ));

    $mos_testimonial_details->add_field(array(
        'name' => 'oEmbed',
        'desc' => __( 'Enter a youtube, twitter, or instagram URL.', 'cmb2' ),
        'id'   => $prefix . 'oembed',
        'type' => 'oembed',
    ));

     

}
add_action('cmb2_admin_init', 'mos_testimonial_metaboxes');