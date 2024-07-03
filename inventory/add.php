<?php


$fb = new FormBuilder();
function add_inventory()
{
        global $fb;
        @$prod_id = $_POST['the_id'];
        include_once(DEX_PLUGIN_DIR . 'inc/attributes.php');

?>
        <form action="" method="post" id="dex_add_product_form" enctype="multipart/form-data">

                <div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12">
                                        <h1 class="text-center my-4"> Add inventory</h1>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-md-12">
                                        <div id="response" class="dex_response my-4"></div>
                                </div>
                        </div>
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
                                                                'options' =>   $product_categories
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
                                                <?php

                                                $fb->field([
                                                        'type'  => 'text',
                                                        'name'  => 'post_title',
                                                        'id'    => 'post_title',
                                                        'label' => 'Title',
                                                        'container_class' => 'form-group my-2 col-8',
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
                                                        <?php

                                                        $new_label = remove_prefix($key, 'meta_');
                                                        $fb->field([
                                                                'type'  => 'select',
                                                                'name'  => $key,
                                                                'id'    => $key,
                                                                'label' => $new_label,
                                                                'container_class' => 'form-group my-2 col-4',
                                                                'options' =>   $attribute['value']
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
                                        'MSRP'                            => '_regular_price',
                                        'Savings/Discount'                => '_discount',
                                        'Retail Price'                    => '_sale_price',
                                        'Weekly Payment'                  => 'rental_weekly',
                                        'Weekly Payment Interest Rate %'  => 'rental_percentage',
                                        'Weekly Payment Term'             => 'rental_for',
                                ];

                                foreach ($price_fields as $k => $v) :
                                        if ($v == 'rental_for') {
                                                $notes = ['description' => '(Example: Enter 48 months or 25 years)'];
                                        }      ?>

                                        <?php $fb->field([
                                                'type'  => 'text',
                                                'name'  => $v,
                                                'id'    => $v,
                                                'label' =>  $k,
                                                'container_class' => 'form-group my-2 col-4 ',
                                                !empty($notes) ? $notes : ''
                                        ]); ?>

                                <?php endforeach;
                                ?>
                        </div>
                        <div class="row">
                                <div class="col-md-12">
                                        <h5 class="section_title">feature highlights</h5>
                                </div>
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
                        $fb->field([
                                'type'                  => 'wp_upload_multiple',
                                'name'                  => 'gallery_images',
                                'label'                 => 'Main Gallery Images',
                                'id'                    => 'gallery_images',
                                'input_class'           => 'btn btn-secondary'
                        ]); ?>
                        <!-- /GALLERY UPLOAD -->
                        <div class="row my-4">
                                <?php

                                $fb->field([
                                        'type'                  => 'wp_upload',
                                        'name'                  => 'featured_image',
                                        'id'                    => 'featured_image',
                                        'label'                 => 'Featured Image',
                                        'container_class'       => 'form-group my-2 col-6 mt-4',
                                        'is_featured'           =>  1
                                ]);

                                // $fb->field([
                                //         'type'                  => 'wp_upload',
                                //         'name'                  => 'company_logo',
                                //         'id'                    => 'company_logo',
                                //         'label'                 => 'Logo Image',
                                //         'container_class'       => 'form-group my-2 col-6'
                                // ]);

                                ?>
                        </div>

                        <section class="section container-fluid" id="image_video_section">
                                <div class="row">
                                        <a href="JavaScript:void(0)" id="add_img_vid_sect" class="clone_above" data-position="1" data-section="image_video_links" data-target="#image_video_section">Add Image/Video Section</a>
                                </div>
                        </section>

                        <div class="row">
                                <div class="col-md-12">
                                        <h5 class="section_title">features & options</h5>
                                </div>

                                <section class="section container-fluid" id="features_options">
                                        <div class="row"><a href="JavaScript:void(0)" id="add_features_options_sect" class="clone_above" data-position="1" data-section="features_options" data-target="#features_options">Add features & options Section</a></div>
                                </section>
                        </div>

                        <div class="row my-4">
                                <div class="col-4">
                                        <button type="button" id="dex_add_product_btn" class="btn btn-primary float-right">Publish/Save</button>
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






add_action("wp_ajax_dex_add_product", "dex_add_product");

function dex_add_product()
{

        if ($_POST['post_title'] == "") {
                return;
        }

        $user_ID                          = get_current_user_id();
        $new_product                      = [];
        $new_product['post_title']        = $_REQUEST['post_title'];
        $new_product['post_status']       = 'publish';
        $new_product['post_author']       = $user_ID;
        $new_product['post_type']         = 'product';
        $product_id                       = wp_insert_post($new_product);
        $prod_cat                         = array($_REQUEST['product-category']);

        wp_set_object_terms($product_id, $prod_cat, 'product_cat');

        $meta_arr = [];
        $cf_arr = [];
        $product_attributes = [];
        $i = 0;


        foreach ($_POST as $key => $val) {

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
        add_post_meta($product_id, '_thumbnail_id', $_POST['featured_image']);

        $gall_img = implode(', ', $_POST['gallery_images']);
        update_post_meta($product_id, '_product_image_gallery', $gall_img);
        // update_post_meta($product_id, 'company_logo', $_POST['company_logo']);
        // update_post_meta($product_id, '_company_logo', 'field_' . uniqid());

        $response = "<strong>Product Added <a target='_blank' href='" . site_url() . "/?post_type=product&p=" . $product_id . "'>View Product</a></strong>";

        // echo json_encode(array('Status' => true, 'product_id' => $product_id, 'gallery_images' => $gall_img, 'msg' => $response, 'product_attributes' => $product_attributes, 'cf_arr' => $cf_arr));
        echo json_encode(  array('Status' => true, 'product_id' => $product_id, 'msg' => $response)  );
        exit;
}
