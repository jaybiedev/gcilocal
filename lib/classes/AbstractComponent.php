<?php

abstract class AbstractComponent {

    public $attributes = array();

    abstract function getContent();


    public function setAttributes($attributes) {
        $this->attributes = $attributes;
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function getAttribute($attribute, $default=null) {

        if (!empty($this->attributes[$attribute])) {
            return $this->attributes[$attribute];
        }

        return $default;
    }

    public function render() {
        echo $this->getContent();
    }


    public function query($taxonomy='category', $term=7, $field='term_id', $post_type='attachment') {


        $args = [
            'posts_per_page' => -1,
            // 'fields'         => 'ids',
            'post_type'      => $post_type,
            'post_status'   => 'inherit',
            'tax_query' => [
                [
                    'taxonomy'          => $taxonomy,
                    'field'             => $field,
                    'terms'             => $term,
                    'include_children'  => false
                ],
            ],
        ];
        $posts  = new WP_Query($args);

        /*
                    if( $post_parents->posts ) {

                        $args = [
                            'post_type'      => 'attachment',
                            'post_mime_type' => 'image',
                            'post_status'    => 'inherit',
                            'fields'         => 'ids',
                            'posts__in'      => $post_parents,
                        ];
                        $attachments = new WP_Query($args);

                    }
                    var_dump($attachments->posts) */

        return $posts;

    }

}
