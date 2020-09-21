<?php

namespace WTS_EAE\Modules\Charts\Skins;

use Elementor\Widget_Base;

class Skin_Bubble_Chart extends Skin_Base {

    public function get_id() {
        return 'bubble';
    }

    public function get_title() {
        return __( 'Bubble', 'wts-eae' );
    }

    public function register_items_control( Widget_Base $widget ) {
        $this->parent = $widget;
    }
    public function render() {
        $this->common_render();
    }
}