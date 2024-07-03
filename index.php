<?php
/*
Plugin Name: Custom WooCommerce Product Addition
Plugin URI: https://aammir.github.io/
Description:   Add woocommerce products and custom attributes & fields on one screen quickly
Author:  Aamir Hussain
Version: 1
Author URI: https://aammir.github.io/
Text Domain: 
 */


$path = plugin_dir_url(__FILE__);
define('DEX_PATH', $path);
define('DEX_PLUGIN_DIR', dirname(__FILE__) . '/');

function load_media_files()
{
        wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'load_media_files');



function remove_prefix($text, $prefix)
{
        if (0 === strpos($text, $prefix))
                $text = substr($text, strlen($prefix));
        return $text;
}

include(DEX_PLUGIN_DIR . 'inventory.php');





add_action('admin_head', 'admin_head_style');
add_action('admin_footer', 'dex_custom_script');


function admin_head_style()
{ ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <!--<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>-->
        <!-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> -->
        <style>
                .dex_wrap * {
                        font-family: 'Open Sans';
                }

                .section_title {
                        background: #000;
                        color: #FFF;
                        padding: 3px 6px;
                        text-transform: uppercase;
                        margin: 15px 0;
                }

                .hide {
                        display: none !important;
                }

                .feat_img {
                        max-width: 64px;
                }

                ._prod_title {
                        font-weight: bold;
                        color: #111;
                        text-decoration: none;
                        line-height: 40px;
                        text-indent: 10px;
                }

                .remove {
                        background: #000 !important;
                        color: #FFF !important;
                        border: none !important;
                        border-radius: 50% !important;
                }

                li#toplevel_page_inventory ul.wp-submenu.wp-submenu-wrap li:last-child {
                        display: none !important;
                }

                .btn-secondary {
                        background: #C5C8CC !important;
                        border: 1px solid #000 !important;
                        border-radius: 5px !important;
                        color: #000 !important;
                        text-decoration: none;
                        padding: 7px 10px !important;
                        font-weight: bold;
                }
        </style>
<?php

}


function dex_custom_script()
{ ?>
        <script>
                (function($) {
                        $(document).ready(function() {

                                let adminAjaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';

                                $(document).on('click', '#dex_add_product_btn', function(e) {
                                        e.preventDefault();

                                        jQuery.ajax({
                                                url: adminAjaxUrl + '?action=dex_add_product',
                                                type: "post",
                                                dataType: "json",
                                                data: $('#dex_add_product_form').serialize()
                                        }).done(function(response) {
                                                $('.dex_response').html(response.msg);
                                                $('#dex_add_product_form')[0].reset();
                                                $('#dex_add_product_form').find('img, .delete_btn, .btncls, .remove, .delete, .repeatable').remove();
                                        });
                                });

                                $(document).on('click', '#dex_update_product_btn', function(e) {
                                        e.preventDefault();

                                        jQuery.ajax({
                                                url: adminAjaxUrl + '?action=dex_update_product',
                                                type: "post",
                                                dataType: "json",
                                                data: $('#dex_add_product_form').serialize()
                                        }).done(function(response) {
                                                $('.dex_response').html(response.msg);
                                                $('#dex_add_product_form')[0].reset();
                                                $('#dex_add_product_form').find('img, .delete_btn, .btncls, .remove, .delete, .repeatable').remove();
                                        });
                                });


                                $(document).on('click', '.clone_above', function() {
                                        let _this = $(this);
                                        the_parent = _this.closest('.section');
                                        const pos = _this.attr('data-position');
                                        let new_pos = parseInt(pos) + 1;
                                        the_data = {
                                                position: pos,
                                                section: _this.attr('data-section')
                                        };
                                        jQuery.ajax({
                                                url: adminAjaxUrl + '?action=add_repeating_sections',
                                                type: "post",
                                                data: the_data
                                        }).done(function(response) {
                                                // console.log(response);
                                                the_parent.append(response);
                                                _this.attr('data-position', new_pos);
                                        });
                                });



                                $(document).on('click', '.remove', function() {
                                        $(this).closest('.repeatable').remove();
                                });


                                $(document).on('click', '#add_new_attr', function() {
                                        let _this = $(this);
                                        the_target_div = $('.product_attributes');
                                        the_data = $('#new_prod_attr_container').find('select, input').serialize();
                                        // console.log(the_data);
                                        jQuery.ajax({
                                                url: adminAjaxUrl + '?action=add_new_prod_attribute',
                                                type: "post",
                                                data: the_data
                                        }).done(function(response) {
                                                // console.log(response);
                                                the_target_div.append(response);
                                                $('#new_prod_attr_container').find('select, input').val('');
                                        });
                                });

                                $('.prod_attr_value_container').hide();
                                $(document).on('change', '#prod_attr_type', function() {
                                        if ($(this).val() == 'multiple') {
                                                $('.prod_attr_value_container').show();
                                        } else {
                                                $('.prod_attr_value_container').hide();
                                        }
                                });


                                $(document).on('click', '.del_prod_btn', function(e) {
                                        e.preventDefault();
                                        let _this = $(this);
                                        the_target_div = _this.closest('tr');
                                        the_data = {
                                                'delete_id': _this.closest('form').find('#delete_id').val()
                                        };
                                        // console.log(the_data);
                                        jQuery.ajax({
                                                url: adminAjaxUrl + '?action=delete_inventory',
                                                type: "post",
                                                data: the_data
                                        }).done(function(response) {
                                                // console.log(response);
                                                the_target_div.remove();
                                                $('#response').html(response);
                                        });
                                });



                        });
                })(jQuery);
        </script>
<?php

}


