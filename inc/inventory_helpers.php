<?php


add_action("wp_ajax_add_repeating_sections", "add_repeating_sections");
function add_repeating_sections()
{
    global $fb;
    $position = $_POST['position'];
    $section = $_POST['section'];
?>
    <div class="row tliv repeatable <?php echo $section; ?>" data-id="<?php echo $position ?>">
        <div class="col-md-12 image-video">
            <a class="text-black area-title">Area# <span class="position"><?php echo $position; ?></span></a>
        </div>

        <div class="col-1 form-group my-2"><input type="radio" id="" class="mt-4 position">
            <input type="hidden" class="the_position" name="<?php echo $section . '_' . $position ?>_position" value="<?php echo $position ?>">
        </div>
        <?php
        if ($section == 'image_video_links') :
            $fb->field([
                'type'  =>      'text',
                'name'  =>      $section . '[' . $position . '][title]',
                'id'    =>      $section . '_title_' . $position,
                'label' =>      'Edit title',
                'container_class' => 'form-group my-2 col-2 ',
            ]);

            $fb->field([
                'type'  => 'wp_upload',
                'name'  => $section . '[' . $position . '][link]',
                'id'    => $section . '_link_' . $position,
                'label' => 'Edit link ',
                'container_class' => 'form-group my-2 col-4 d-block',
                'is_free_link' => 1
            ]);
        elseif ($section == 'feature_highlights') :
            $fb->field([
                'type'  => 'text',
                'name'  => $section . '[' . $position . '][name]',
                'id'    => $section . '_name' . $position,
                'label' =>  'Feature highlight ' . $position,
                'container_class' => 'form-group my-2 col-3',
            ]);

            $fb->field([
                'type'  => 'wp_upload',
                'name'  => $section . '[' . $position . '][the_icon]',
                'id'    => $section . '_icon' . $position,
                'label' => 'Feature highlight icon ' . $position,
                'container_class' => 'form-group my-2 col-6'
            ]);
        elseif ($section == 'features_options') :
            $fb->field([
                'type'  => 'text',
                'name'  => $section . '[' . $position . '][title]',
                'id'    => 'featuresoptions_title_' . $position,
                'label' =>  'Edit button title ',
                'container_class' => 'form-group my-2 col-3 ',
            ]);

            $fb->field([
                'type'  => 'textarea',
                'name'  => $section . '[' . $position . '][body]',
                'id'    => 'featuresoptions_text_' . $position,
                'label' => 'Edit drop down area',
                'container_class' => 'form-group my-2 col-6'
            ]);
            // wp_editor( '', 'featuresoptions_text_'. $position, WA );
        /* ?>
            <script>
                (function($) {
                    $(document).ready(function() {
                        CKEDITOR.replace('features_options[<?php echo $position ?>][body]');
                    });
                })(jQuery);
            </script>
        <?php */

        endif;
        ?>

        <div class="col-1">
            <div class="delete">Delete</div>
            <button class="remove" title="Remove Section">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
<?php
    // 

    exit;
}





add_action("wp_ajax_add_new_prod_attribute", "add_new_prod_attribute");
function add_new_prod_attribute()
{
    global $fb;
    $new_id =  str_replace(' ', '_', $_POST['prod_attr_title']);
    $options = explode(',', $_POST['prod_attr_value']);

    if ($_POST['prod_attr_type'] == 'single') :
        $fb->field([
            'type'  =>      'text',
            'name'  =>      'meta_' . $new_id,
            'id'    =>      'meta_' . $new_id,
            'label' =>      $_POST['prod_attr_title'],
            'container_class' => 'form-group my-2 col-4',
        ]);
    else :
        $fb->field([
            'type'  =>      'select',
            'name'  =>      'meta_' . $new_id,
            'id'    =>      'meta_' . $new_id,
            'label' =>      $_POST['prod_attr_title'],
            'options' =>    $options,
            'container_class' => 'form-group my-2 col-4',
        ]);
    endif;

    exit;
}


add_action("wp_ajax_delete_inventory", "delete_inventory");
function delete_inventory()
{
    wp_delete_post($_POST['delete_id']);
    echo 'Inventory removed';
    exit;
}
