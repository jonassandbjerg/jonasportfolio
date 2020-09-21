<?php

namespace WTS_EAE\Modules\Charts\Skins;

use WTS_EAE\Classes\Post_Helper;
use Elementor\Controls_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Utils;

abstract class Skin_Base extends Elementor_Skin_Base{

    protected function _register_controls_actions() {

        add_action( 'elementor/element/eae-charts/tl_skins/after_section_end', [ $this, 'register_style_controls'] );
    }

    public function register_controls( Widget_Base $widget ) {
        $this->parent = $widget;
    }

    function common_render(){
        $settings = $this->parent->get_settings_for_display();

//       echo '<pre>'; print_r($settings); echo '</pre>';
        $type                 = $settings['_skin'];
        $labels               = $settings['data_label'];
        $show_xaxis_label     = $settings['x_axis_show_title'];
        $xaxis_label          = $settings['x_axis_label'];
        $xaxis_grid_line      = $settings['x_axis_grid_line'];
        $xaxis_gridLine_color = $settings['xaxis_grid_line_color'];
        $xaxis_gridLine_width = $settings['xaxis_grid_line_width']['size'];
        $label_rotation       = $settings['label_rotation'];
        $show_yaxis_label     = $settings['y_axis_show_title'];
        $yaxis_label          = $settings['y_axis_label'];
        $yaxis_grid_line      = $settings['y_axis_grid_line'];
        $yaxis_gridLine_color = $settings['yaxis_grid_line_color'];
        $yaxis_gridLine_width = $settings['yaxis_grid_line_width']['size'];
        $datasetText          = $settings['dataset_text'];
        $show_main_title      = $settings['chart_Heading'];
        $title                = $settings['chart_main_title'];
        $title_position       = $settings['title_position'];
        $step_size            = $settings['step_size'];
        $min_val              = $settings['y_axis_min_val'];
        $max_val              = $settings['y_axis_max_val'];
        $display_legend       = $settings['legend_display'];
        $legend_position      = $settings['legend_position'];
//        $legend_align         = $settings['legend_align'];
        $tooltip              = $settings['show_tooltips'];
        $tooltip_mode         = $settings['tooltips_mode'];
        $animation            = $settings['chart_animation'];
        $animation_duration   = $settings['duration_animation'];
        $height               = $settings['eae_chart_height'];
        $title_color          = $settings['heading_color'];
        $title_font_family    = $settings['heading_font_family'];
        $title_font_size      = $settings['heading_font_size']['size'];
        $title_font_style     = $settings['heading_font_style'];
        $title_line_height    = $settings['heading_font_style'];
        $title_padding        = $settings['heading_line_height'];
        $xaxis_label_color    = $settings['xaxis_label_color'];
        $xaxis_font_family    = $settings['xaxis_font_family'];
        $xaxis_font_size      = $settings['xaxis_font_size']['size'];
        $xaxis_font_style     = $settings['xaxis_font_style'];
        $xaxis_line_height    = $settings['xaxis_line_height'];
        $data_label_color     = $settings['data_label_color'];
        $data_font_family     = $settings['data_font_family'];
        $data_font_size       = $settings['data_font_size']['size'];
        $data_font_style      = $settings['data_font_style'];
        $data_line_height     = $settings['data_line_height'];
        $yaxis_label_color    = $settings['yaxis_label_color'];
        $yaxis_font_family    = $settings['yaxis_font_family'];
        $yaxis_font_size      = $settings['yaxis_font_size']['size'];
        $yaxis_font_style     = $settings['yaxis_font_style'];
        $yaxis_line_height    = $settings['yaxis_line_height'];
        $yaxis_data_label_color = $settings['yaxis_data_color'];
        $yaxis_data_font_size = $settings['yaxis_data_font_size']['size'];
        $legend_color         = $settings['legend_color'];
        $legend_font_family   = $settings['legend_font_family'];
        $legend_font_size     = $settings['legend_font_size']['size'];
        $legend_font_style    = $settings['legend_font_style'];

//        echo $xaxis_font_size;

 //        echo'<pre>'; print_r($datasetText); echo'</pre>';

        $chart_data = [];
        foreach ($datasetText as $dataValue){
           $col_data = [
                    'label'             =>  $dataValue['dataset_label'],
                    'data'              =>  $dataValue['dataset_data'],
                    'backgroundColor'   =>  $dataValue['bar_chart_background'],
                    'borderColor'       =>  $dataValue['bar_chart_border_color'],
                    'borderWidth'       =>  $dataValue['bar_border_width'],
                    //'barPercentage'     =>  $dataValue['bar_column_width'],
           ];
           array_push($chart_data , $col_data);

        }
 //           echo'<pre>'; print_r($chart_data); echo'</pre>';

        $this->parent->add_render_attribute('chart-wrapper', 'class', 'eae-chart-outer-wrapper');
        $this->parent->add_render_attribute('wrapper' , 'class' , 'eae-chart-wrapper');
        $this->parent->add_render_attribute('chart-wrapper', 'data-type', $type);
        $this->parent->add_render_attribute('chart-wrapper', 'data-labels', $labels);
        $this->parent->add_render_attribute('chart-wrapper', 'data-show-xaxis-label',  $show_xaxis_label);
        $this->parent->add_render_attribute('chart-wrapper', 'data-xaxis-label',  $xaxis_label);
        $this->parent->add_render_attribute('chart-wrapper', 'data-show-gridLine',  $xaxis_grid_line);
        $this->parent->add_render_attribute('chart-wrapper', 'data-gridLine-color',  $xaxis_gridLine_color);
        $this->parent->add_render_attribute('chart-wrapper', 'data-gridLine-width',  $xaxis_gridLine_width);
        $this->parent->add_render_attribute('chart-wrapper', 'data-label-rotation',  $label_rotation);
        $this->parent->add_render_attribute('chart-wrapper', 'data-show-yaxis-label',  $show_yaxis_label);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis-label', $yaxis_label);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis-show-gridLine',  $yaxis_grid_line);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis-gridLine-color',  $yaxis_gridLine_color);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis-gridLine-width',  $yaxis_gridLine_width);
        $final_data = str_replace('"[','[',json_encode($chart_data));
        $final_data = str_replace(']"',']',$final_data);
        $this->parent->add_render_attribute('chart-wrapper', 'data-chart', $final_data);
        $this->parent->add_render_attribute('chart-wrapper', 'data-show-chart-heading', $show_main_title);
        $this->parent->add_render_attribute('chart-wrapper', 'data-chart-heading', $title );
        $this->parent->add_render_attribute('chart-wrapper', 'data-chart-heading-position', $title_position);
        $this->parent->add_render_attribute('chart-wrapper', 'data-step-size', $step_size);
        $this->parent->add_render_attribute('chart-wrapper', 'data-min-val', $min_val);
        $this->parent->add_render_attribute('chart-wrapper', 'data-max-val', $max_val);
        $this->parent->add_render_attribute('chart-wrapper', 'data-display-legend', $display_legend);
        $this->parent->add_render_attribute('chart-wrapper', 'data-legend-position', $legend_position);
//        $this->parent->add_render_attribute('chart-wrapper', 'data-legend-align', $legend_align);
        $this->parent->add_render_attribute('chart-wrapper', 'data-show-tooltip', $tooltip);
        $this->parent->add_render_attribute('chart-wrapper', 'data-tooltip-mode', $tooltip_mode);
        $this->parent->add_render_attribute('chart-wrapper', 'data-chart-animation', $animation);
        $this->parent->add_render_attribute('chart-wrapper', 'data-animation-duration', $animation_duration);
        $this->parent->add_render_attribute('wrapper', 'width', 1101);
        $this->parent->add_render_attribute('wrapper', 'height', $height);
        $this->parent->add_render_attribute('chart-wrapper', 'data-title-color', $title_color);
        $this->parent->add_render_attribute('chart-wrapper', 'data-title-font-family', $title_font_family);
        $this->parent->add_render_attribute('chart-wrapper', 'data-title-font-size', $title_font_size);
        $this->parent->add_render_attribute('chart-wrapper', 'data-title-font-style', $title_font_style);
        $this->parent->add_render_attribute('chart-wrapper', 'data-title-line-height', $title_line_height);
        $this->parent->add_render_attribute('chart-wrapper', 'data-title-padding', $title_padding);
        $this->parent->add_render_attribute('chart-wrapper', 'data-xaxis_label_color', $xaxis_label_color);
        $this->parent->add_render_attribute('chart-wrapper', 'data-xaxis_label_font_family', $xaxis_font_family);
        $this->parent->add_render_attribute('chart-wrapper', 'data-xaxis_label_font_size', $xaxis_font_size);
        $this->parent->add_render_attribute('chart-wrapper', 'data-xaxis_label_font_style', $xaxis_font_style);
        $this->parent->add_render_attribute('chart-wrapper', 'data-xaxis_label_line_height', $xaxis_line_height);
        $this->parent->add_render_attribute('chart-wrapper', 'data-data_label_color', $data_label_color);
        $this->parent->add_render_attribute('chart-wrapper', 'data-data_label_font_family', $data_font_family);
        $this->parent->add_render_attribute('chart-wrapper', 'data-data_label_font_size', $data_font_size);
        $this->parent->add_render_attribute('chart-wrapper', 'data-data_label_font_style', $data_font_style);
        $this->parent->add_render_attribute('chart-wrapper', 'data-data_label_line_height', $data_line_height);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis_label_color', $yaxis_label_color);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis_label_font_family', $yaxis_font_family);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis_label_font_size', $yaxis_font_size);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis_label_font_style', $yaxis_font_style);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis_label_line_height', $yaxis_line_height);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis_data_color', $yaxis_data_label_color);
        $this->parent->add_render_attribute('chart-wrapper', 'data-yaxis_data_font_size', $yaxis_data_font_size);
        $this->parent->add_render_attribute('chart-wrapper', 'data-legend-color', $legend_color);
        $this->parent->add_render_attribute('chart-wrapper', 'data-legend-font-family', $legend_font_family);
        $this->parent->add_render_attribute('chart-wrapper', 'data-legend-font-size', $legend_font_size);
        $this->parent->add_render_attribute('chart-wrapper', 'data-legend-font-style', $legend_font_style);





        ?>
            <div <?php echo $this->parent->get_render_attribute_string('chart-wrapper');?>>
            <div class="eae-chart-inner-wrapper">
                <canvas <?php echo $this->parent->get_render_attribute_string('wrapper');?> ></canvas>
            </div>
            </div>

<?php
    }
 function _content_template() {?>
     <div class="eae-canvas-wrapper">
        <#

         #>
     </div>
<?php
 }

}