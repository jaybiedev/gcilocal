<?php

class Post {

    public $post_id;
    public $post_meta;
    public $WP_Post;

    public $data = [];

    public function __construct($id) {
        $this->post_id = $id;
        $this->WP_Post = get_post($id);
    }

    public function __get($name) {
        /**
         * 	Variable Type	Notes
        ID	int	The ID of the post
        post_author	string	The post author's user ID (numeric string)
        post_name	string	The post's slug
        post_type	string	See Post Types
        post_title	string	The title of the post
        post_date	string	Format: 0000-00-00 00:00:00
        post_date_gmt	string	Format: 0000-00-00 00:00:00
        post_content	string	The full content of the post
        post_excerpt	string	User-defined post excerpt
        post_status	string	See get_post_status for values
        comment_status	string	Returns: { open, closed }
        ping_status	string	Returns: { open, closed }
        post_password	string	Returns empty string if no password
        post_parent	int	Parent Post ID (default 0)
        post_modified	string	Format: 0000-00-00 00:00:00
        post_modified_gmt	string	Format: 0000-00-00 00:00:00
        comment_count	string	Number of comments on post (numeric string)
        menu_order	string	Order value as set through page-attribute when enabled (numeric string. Defaults to 0)
         */

        // attributes of WP_Post
        $attributes = array(
            'id',
            'post_author',
            'post_title',
            'post_name',
            'post_type',
            'post_date',
            'post_date_gmt',
            'post_content',
            'post_excerpt',
            'post_status',
            'post_parent',
            'post_modified',
            'comment_count',
            'menu_order'
        );

        if (in_array($name, $attributes))
            return $this->WP_Post->$name;

        if (in_array('post_'. $name, $attributes)) {
            $post_attr = 'post_' . $name;
            return $this->WP_Post->$post_attr;
        }

        if (!empty($this->data[$name]))
            return $this->data[$name];

        $value = null;
        switch ($name) {
            case "caption":
                $value = $this->WP_Post->post_excerpt;
                if (empty($value))
                    $value = strip_tags($this->WP_Post->post_content);
                break;
            case 'permalink':
                $value = get_post_permalink($this->post_id);
                break;
            case 'thumbnail_url':
                $value = get_the_post_thumbnail_url($this->post_id);
                break;
            case 'post_date_formatted':
                $value = get_the_time(get_option('date_format'), $this->post_id);
                break;

            default;
                $value = null;
        }

        $this->data[$name] = $value;

        return $value;

    }

    public function getMeta() {

        if (empty($this->post_meta))
            $this->post_meta = get_post_meta($this->post_id);

        return $this->post_meta;
    }

    public function getData($name) {

        $post_meta = $this->getMeta();
        if (!empty($post_meta[$name]))
            return $post_meta[$name];

        return null;
    }

    public function  getImages($attachment_id)
    {
        if (!$attachment_id)
            return [];
        $sizes = get_intermediate_image_sizes();

        $images = array();
        foreach ($sizes as $size) {
            $images[] = wp_get_attachment_image_src($attachment_id, $size);
        }

        return $images;
    }


}
