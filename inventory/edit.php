<?php


$fb = new FormBuilder();
function edit_inventory()
{
        $prod_id = $_POST['the_id'];
        if (!isset($prod_id)) {
                wp_redirect(admin_url('/admin.php?page=inventory'));
        }
        global $fb;
        @$feat_image_raw = wp_get_attachment_image_src(get_post_thumbnail_id($prod_id), 'single-post-thumbnail');
        @$feat_image     = $feat_image_raw[0];
        include_once(DEX_PLUGIN_DIR . 'inc/attributes.php');
        if (isset($prod_id)) :
                $product = get_product($prod_id);
                // echo '<pre>';
                //  print_r($product);
                //  echo $product->get_attribute( 'max-speed' );
                // echo '</pre>';
                $prod_cate = get_the_terms($prod_id, 'product_cat')[0]->name;
        endif;
        // exit;
?>
        <form action="" method="post" id="dex_add_product_form" enctype="multipart/form-data">

                <div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12">
                                        <h1 class="text-center my-4">Edit inventory</h1>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-md-12">
                                        <div id="response" class="dex_response my-4"></div>
                                </div>
                        </div>
                        <input type="hidden" name="product_id" value="<?php echo $prod_id?>">
                        
                        <div class="row">
                                <div class="col-md-12">
                                        <h5 class="section_title">Product Category</h5>
                                        <div class="row my-2">
                                                <div class="col-md-6">
                                                        <?php

                                                        $product_categories_raw  = get_terms('product_cat', array('hide_empty' => false, 'parent' => 0));
                                                        $product_categories = [];
                                                        foreach ($product_categories_raw  as $cat) {
                                                                $product_categories[] = $cat->name;
                                                        }
                                                        ?>

                                                        <?php
                                                        $fb->field([
                                                                'type'  => 'select',
                                                                'name'  => 'product-category',
                                                                'id'    => 'product-category',
                                                                'label' => 'product category',
                                                                'options' =>   $product_categories,
                                                                'dbval' => $prod_cate
                                                        ]); ?>

                                                </div>
                                                <!---- /col-6----->

                                                <div class="col-md-4"> <label>Edit Product Categories</label><br />
                                                        <a target="_blank" class="btn btn-secondary btn-sm form-control" href="<?php echo site_url() ?>/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product"><span class="dashicons dashicons-edit"></span> Edit Product Category List</a>
                                                </div>

                                                <hr class="mt-4" />
                                        </div>
                                        <!---- /row----->
                                </div>

                        </div>
                        <div class="row">

                                <div class="col-md-12">
                                        <h5 class="section_title">Product info</h5>
                                        <div class="row">
                                                <div class="col-4 float-right">
                                                        <a target="_blank" class="btn btn-secondary btn-sm form-control" href="<?php echo site_url() ?>/wp-admin/edit.php?post_type=product&page=product_attributes"><span class="dashicons dashicons-edit"></span> Edit Product Info Categories</a>
                                                </div>
                                        </div>

                                        <div class="row product_attributes">
                                                <?php //if(isset($prod_id)){ $name_dbval = ['dbval' => $product->name];}
                                                ?>
                                                <?php

                                                $fb->field([
                                                        'type'  => 'text',
                                                        'name'  => 'post_title',
                                                        'id'    => 'post_title',
                                                        'label' => 'Title',
                                                        'container_class' => 'form-group my-2 col-8',
                                                        'dbval' => $product->name,
                                                ]); ?>
                                                <!-- PRODUCT ATTRIBUTES -->
                                                <?php
                                                foreach ($attributes as $key => $attribute) :

                                                        if ($key == 'categories') {
                                                                $key = 'category';
                                                        } else {
                                                                $key = 'meta_' . $key;
                                                        }

                                                        $all_title =  $attribute['title'];

                                                        if ($key == 'category') {
                                                                continue;
                                                        }
                                                ?>
                                                        <?php //if(isset($prod_id)){ $meta_dbval = ['dbval' => $product->get_attribute( $new_label )];}
                                                        ?>
                                                        <?php

                                                        $new_label = remove_prefix($key, 'meta_');
                                                        $fb->field([
                                                                'type'  => 'select',
                                                                'name'  => $key,
                                                                'id'    => $key,
                                                                'label' => $new_label,
                                                                'container_class' => 'form-group my-2 col-4',
                                                                'options' =>   $attribute['value'],
                                                                'dbval' => $product->get_attribute($new_label),
                                                        ]); ?>


                                                <?php endforeach; ?>
                                                <!-- /PRODUCT ATTRIBUTES -->
                                        </div>
                                        <div class="row mt-4" id="new_prod_attr_container">
                                                <a class="text-black add_product_attribute col-12">Enter New Product Info Area</a>
                                                <?php
                                                $fb->field([
                                                        'type'  => 'text',
                                                        'name'  => 'prod_attr_title',
                                                        'id'    => 'prod_attr_title',
                                                        'label' => 'Enter New Title',
                                                        'container_class' => 'form-group my-2 col-4'
                                                ]);

                                                $fb->field([
                                                        'type'  => 'select',
                                                        'name'  => 'prod_attr_type',
                                                        'id'    => 'prod_attr_type',
                                                        'label' => 'Select Entery Field Type',
                                                        'container_class' => 'form-group my-2 col-4',
                                                        'options' =>   ['single', 'multiple']
                                                ]);

                                                $fb->field([
                                                        'type'  => 'text',
                                                        'name'  => 'prod_attr_value',
                                                        'id'    => 'prod_attr_value',
                                                        'label' => 'Enter Value(s)',
                                                        'container_class' => 'form-group my-1 col-4',
                                                        'description' =>   'Seperate entries with comma'
                                                ]);
                                                $icon = '<i class="fa fa-add"></i>';
                                                $fb->field([
                                                        'type'          => 'button',
                                                        'name'          => 'add_new_attr',
                                                        'id'            => 'add_new_attr',
                                                        'label'         =>  $icon . '+ Save new product info area',
                                                        'container_class'   => 'form-group my-2 col-4 mt-4'
                                                ]); ?>

                                        </div>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-md-12">
                                        <h5 class="section_title">Price info</h5>
                                </div>

                                <?php
                                $price_fields = [
                                        'Price'                            => '_regular_price',
                                        // 'Savings/Discount'                => '_discount',
                                        'Sale Price'                    => '_sale_price',
                                        // 'Weekly Payment'                  => 'rental_weekly',
                                        // 'Weekly Payment Interest Rate %'  => 'rental_percentage',
                                        // 'Weekly Payment Term'             => 'rental_for',
                                ];

                                foreach ($price_fields as $k => $v) :
                                        if ($v == 'rental_for') {
                                                $notes = ['description' => '(Example: Enter 48 months or 25 years)'];
                                        }      ?>
                                        <?php //if(isset($prod_id)){ $dbval = ['dbval' => get_post_meta( $prod_id, $v, true )];}
                                        ?>
                                        <?php $fb->field([
                                                'type'  => 'text',
                                                'name'  => $v,
                                                'id'    => $v,
                                                'label' =>  $k,
                                                'dbval' => get_post_meta($prod_id, $v, true),
                                                'container_class' => 'form-group my-2 col-4 ',
                                                !empty($notes) ? $notes : '',
                                        ]); ?>

                                <?php endforeach;
                                ?>
                        </div>
                        <div class="row">
                                <div class="col-md-12">
                                        <h5 class="section_title">feature highlights</h5>
                                </div>

                                <?php
                                if (isset($prod_id)) :
                                        // $all_meta = get_post_meta($prod_id);
                                        $feature_highlights = get_post_meta($prod_id, 'feature_highlights', true);

                                        /*echo '<pre>';
                                        print_r($feature_highlights);
                                        echo '</pre>';*/
                                        $n = 0;
                                        if(is_array($feature_highlights)){
                                        foreach ($feature_highlights as $fh) {
                                                $n++; ?>
                                                <!-- <div><img src="<?php echo $fh['the_icon']; ?>"> <span><?php echo $fh['name']; ?></span></div> -->
                                                <?php
                                                $section  = 'feature_highlights';
                                                $fb->field([
                                                        'type'  => 'text',
                                                        'name'  => $section . '[' . $n . '][name]',
                                                        'id'    => $section . '_name' . $n,
                                                        'label' =>  'Feature highlight ' . $n,
                                                        'container_class' => 'form-group my-2 col-3',
                                                        'dbval' => $fh['name'],
                                                ]);
                                                $fb->field([
                                                        'type'  => 'wp_upload',
                                                        'name'  => $section . '[' . $n . '][the_icon]',
                                                        'id'    => $section . '_icon' . $n,
                                                        'label' => 'Feature highlight icon ' . $n,
                                                        'container_class' => 'form-group my-2 col-8',
                                                        'dbval' => $fh['the_icon'],
                                                ]);

                                                ?>
                                <?php }
}
                                endif;
                                ?>
                                <section class="section container-fluid" id="feature_highlights">
                                        <div class="row"><a href="JavaScript:void(0)" id="add_feature_highlights" class="clone_above" data-position="1" data-section="feature_highlights" data-target="#feature_highlights">Add feature highlights Section</a></div>
                                </section>
                        </div>

                        <div class="row">
                                <div class="col-md-12">
                                        <h5 class="section_title">image and video links</h5>
                                </div>

                        </div>
                        <!-- GALLERY UPLOAD -->
                        <?php
                        $gallery_images = get_post_meta($prod_id, 'gallery_images', true);
                        // print_r($gallery_images);
                        $fb->field([
                                'type'                  => 'wp_upload_multiple',
                                'name'                  => 'gallery_images',
                                'label'                 => 'Main Gallery Images',
                                'id'                    => 'gallery_images',
                                'input_class'           => 'btn btn-secondary',
                                'dbval'                 =>  $gallery_images
                        ]); ?>
                        <!-- /GALLERY UPLOAD -->
                        <div class="row my-4">
                                <?php

                                $fb->field([
                                        'type'                  => 'wp_upload',
                                        'name'                  => 'featured_image',
                                        'id'                    => 'featured_image',
                                        'label'                 => 'Featured Image',
                                        'container_class'       => 'form-group my-2 col-6 mt-3',
                                        'return_id'             => 'yes',
                                        'is_featured'           =>  1,
                                        'dbval'                 => $feat_image
                                ]);
                                // $company_logo = get_post_meta($prod_id, 'company_logo', true);

                                // $fb->field([
                                //         'type'                  => 'wp_upload',
                                //         'name'                  => 'company_logo',
                                //         'id'                    => 'company_logo',
                                //         'label'                 => 'Logo Image',
                                //         'container_class'       => 'form-group my-2 col-6',
                                //         'dbval'                 => $company_logo
                                // ]);

                                ?>
                        </div>
                        <div class="row my-4 image_video_links" data-id="image_video_links">
                                <?php
                                $image_video_links = get_post_meta($prod_id, 'image_video_links', true);
$i = 0;
                                // echo '<pre>';
                                // print_r($image_video_links);
                                // echo '</pre>';
                                $section ='image_video_links';
                                if(is_array($image_video_links)){ 
                                foreach ($image_video_links as $image_video_link) : $i++;
                                        // print_r($image_video_link['link']);
                                        $fb->field([
                                                'type'  =>      'text',
                                                'name'  =>      $section . '[' . $i . '][title]',
                                                'id'    =>      $section . '_title_' . $i,
                                                'label' =>      'Edit title',
                                                'container_class' => 'form-group my-2 col-4',
                                                'dbval' => $image_video_link['title']
                                        ]);

                                        $fb->field([
                                                'type'  => 'wp_upload',
                                                'name'  => $section . '[' . $i . '][link]',
                                                'id'    => $section . '_link_' . $i,
                                                'label' => 'Edit link ',
                                                'container_class' => 'form-group my-2 col-6',
                                                'is_free_link' => 1,
                                                'dbval' => $image_video_link['link']
                                        ]);
                                endforeach;
                        }
                                ?>
                        </div>
                        <section class="section container-fluid" id="image_video_section">
                                <div class="row"><a href="JavaScript:void(0)" id="add_img_vid_sect" class="clone_above" data-position="1" data-section="image_video_links" data-target="#image_video_section">Add Image/Video Section</a></div>
                        </section>

                        <div class="row">
                                <div class="col-md-12">
                                        <h5 class="section_title">features & options</h5>
                                </div>
                                <?php
                                $section = 'features_options';
                                $features_and_options = get_post_meta($prod_id, 'features_options', true);
                      $x = 0;
                      if(is_array($features_and_options)):
                                foreach($features_and_options as $feature_option):
                           $x++;
                                $fb->field([
                                        'type'  => 'text',
                                        'name'  => $section . '[' . $x . '][title]',
                                        'id'    => 'featuresoptions_title_' . $x,
                                        'label' =>  'Edit button title ',
                                        'container_class' => 'form-group my-2 col-4 ',
                                        'dbval' => $feature_option['title'],
                                ]);

                                $fb->field([
                                        'type'  => 'textarea',
                                        'name'  => $section . '[' . $x . '][body]',
                                        'id'    => 'featuresoptions_text_' . $x,
                                        'label' => 'Edit drop down area',
                                        'container_class' => 'form-group my-2 col-7',
                                        'dbval' => $feature_option['body'],
                                ]);
                        endforeach;
                endif;
                                ?>

                                <section class="section container-fluid" id="features_options">
                                        <div class="row"><a href="JavaScript:void(0)" id="add_features_options_sect" class="clone_above" data-position="1" data-section="features_options" data-target="#features_options">Add features & options Section</a></div>
                                </section>
                        </div>
                        <div class="row my-4">
                                <div class="col-4">
                                        <button type="button" id="dex_update_product_btn" class="btn btn-primary float-right"> Update Inventory </button>
                                </div>
                                <div class="col-8">
                                        <div id="response-2" class="dex_response my-4"></div>
                                </div>
                        </div>
                </div>
        </form>

<?php

}

include_once(DEX_PLUGIN_DIR . 'inc/inventory_helpers.php');






add_action("wp_ajax_dex_update_product", "dex_update_product");
///////////////////////////EDIT THIS
function dex_update_product()
{
        // print_r($_POST);
        // exit;
        $product_id                       = $_POST['product_id'];

        $user_ID                          = get_current_user_id();
        $the_product                      = [];
        $the_product['post_title']        = $_REQUEST['post_title'];
        $the_product['post_status']       = 'publish';
        $the_product['post_author']       = $user_ID;
        $the_product['post_type']         = 'product';
        $the_product['ID']                = $product_id;
        $prod_cat                         = array($_REQUEST['product-category']);
        
        wp_set_object_terms($product_id, $prod_cat, 'product_cat');
        
        wp_update_post($the_product);

        $meta_arr = [];
        $cf_arr = [];
        $product_attributes = [];
        $i = 0;
        $all_arr = [];

        foreach ($_POST as $key => $val) {
                $all_arr[$key] = $val;
                if (strpos($key, "meta_") === 0) {
                        $new_key = remove_prefix($key, 'meta_');

                        $product_attributes[$new_key] = array(
                                'name'          =>      $new_key,
                                'value'         =>      $val,
                                'position'      =>      $i,
                                // 'is_variation'  =>      0, 
                                // 'is_visible'    =>      1,
                                // 'is_taxonomy'   =>      0
                        );
                        $i++;
                        update_post_meta($product_id, $new_key, $val);
                } else {
                        update_post_meta($product_id, $key, $val);
                        update_post_meta($product_id, '_' . $key, 'field_' . uniqid());
                        $cf_arr[$key] = $val;
                }
        }

        update_post_meta($product_id, '_price', $_POST['_regular_price']);
        update_post_meta($product_id, '_product_attributes', $product_attributes);


        // $attachment_id = wp_insert_attachment( $_POST['featured_image'] );
        set_post_thumbnail($product_id, $_POST['featured_image']);
        update_post_meta($product_id, '_thumbnail_id', $_POST['featured_image']);

        $gall_img = implode(', ', $_POST['gallery_images']);
        update_post_meta($product_id, '_product_image_gallery', $gall_img);
        // update_post_meta($product_id, 'company_logo', $_POST['company_logo']);
        // update_post_meta($product_id, '_company_logo', 'field_' . uniqid());

        $response = "<strong>Product Updated <a target='_blank' href='" . site_url() . "/?post_type=product&p=" . $product_id . "'>View Product</a></strong>";

        echo json_encode(array('Status' => true, 'product_id' => $product_id, 'all_attributes'=> $all_arr, 'gallery_images' => $gall_img, 'msg' => $response, 'product_attributes' => $product_attributes, 'cf_arr' => $cf_arr));
        exit;
}
