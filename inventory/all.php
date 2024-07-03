<?php

function inventory_page()
{
?>
    <div class="container-fluid wrap dex_wrap">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center my-4">All inventory</h1>
                <a href="<?php echo admin_url('/admin.php?page=add-inventory') ?>" class="btn btn-secondary page-title-action">
                    + Add Inventory
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h5 class="section_title">&nbsp;</h5>
            </div>
            <div class="col-md-12" id="response"></div>
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>TITLE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                        <td scope="row"></td>
                        <td></td>
                        <td></td>
                    </tr> -->
                        <?php
                        $n = 0;
                        $args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => -1
                        );

                        $loop = new WP_Query($args);

                        while ($loop->have_posts()) : $loop->the_post();
                            global $product;
                            $n++;
                        ?>

                            <tr>
                                <td scope="row"><?php echo $n; ?></td>
                                <td scope="row"><?php echo get_the_ID(); ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="<?php echo get_permalink(); ?>" class="text-decoration-none">
                                            <img src="<?php echo get_the_post_thumbnail_url(); ?>" class=" feat_img" />
                                        </a>
                                        &nbsp;
                                        <a href="<?php echo get_permalink(); ?>" class="_prod_title">
                                            <?php echo get_the_title(); ?>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <form action="<?php echo admin_url('/admin.php?page=edit-inventory') ?>" method="post">
                                    <input name="the_id" value="<?php echo get_the_ID();?>" type="hidden"> 
                                    <button type="submit" class="link">Edit</button>
                                    </form>

                                    <form action="JavaScript:void(0)" method="post">
                                    <input name="delete_id" id="delete_id" value="<?php echo get_the_ID();?>" type="hidden"> 
                                    <button type="submit" class="link del_prod_btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile;

                        wp_reset_query();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php
}
