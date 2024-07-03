<?php

$attributes = [];

$categories = get_terms('product_cat', 'orderby=name&hide_empty=0&parent=0');

if ($categories) {
    $attributes['categories']['title'] = 'Categories';
    foreach ($categories as $category) {
        $attributes['categories']['value'][$category->term_id] = $category->name;
    }
}

$posts = get_posts([
    'post_type' => 'product',
    'posts_per_page' => -1,
]);

foreach ($posts as $post) {
    $post_attributes = get_post_meta($post->ID, '_product_attributes', true);

    if (is_array($post_attributes)) {
        foreach ($post_attributes as $key => $attribute) {
            if ($key == 'category') {
                continue;
            }

            if (in_array($key, ['engine', 'horsepower', 'seats', 'motor', 'max-spead', 'range', 'horsepower-hp', 'length', 'weight', 'weight-lbs'])) {
                continue;
            }

            if ($key == 'colour' || $key == 'colours') {
                $key = 'color';
            }

            $title = $attribute['name'];
            if ($attribute['name'] == 'Colour' || $attribute['name'] == 'Colours') {
                $title = 'Color';
            }

            // Initialize array if not already set
            if (!isset($attributes[$key])) {
                $attributes[$key] = ['title' => $title, 'value' => []];
            }

            $value = !empty($attribute['value']) ? $attribute['value'] : '';
            if (!empty($value) && $value != '' && $value != null) {
                if (!in_array($value, $attributes[$key]['value'])) {
                    if (strpos($value, ', ') !== false) {
                        $value = explode(', ', $value);
                    } elseif (strpos($value, ' | ') !== false) {
                        $value = explode(' | ', $value);
                    }

                    if (is_array($value)) {
                        $attributes[$key]['value'] = array_merge($attributes[$key]['value'], $value);
                    } else {
                        $attributes[$key]['value'][] = $value;
                    }
                }
            } else {
                $attributes[$key]['value'][] = $value;
            }
        }
    }
}

 
?>
