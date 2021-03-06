<?php

class islemag_multiple_ads extends WP_Widget {

	 /**
     * Constructor
     **/
    public function __construct() {
        $widget_ops = array( 'classname' => 'islemag_multiple_ads' );
        parent::__construct( 'islemag_multiple_ads-widget', 'Islemag - Sidebar multiple advertisements', $widget_ops );
        add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );
    }

    /**
     * Upload the Javascripts for the media uploader
     */
    public function upload_scripts() {
        wp_enqueue_media();
        wp_enqueue_script( 'upload_media_widget', get_template_directory_uri() . '/js/islemag-upload-media.js', array("jquery"),'1.0.0', true );
    }

    function widget( $args, $instance ) {
      extract( $args );
      echo $before_widget;

      $title = $instance['widget_title'];
      if( !empty( $title ) ){
        echo $before_title. esc_html( $title ) . $after_title;
      }





      for( $i = 1 ; $i < 7 ; $i++ ){
        $title_alt = 'title_ad'.$i;
        $link = 'link_ad'.$i;
        $url = 'image_uri_ad'.$i;
        $type = 'banner_type'.$i;
        $code = 'banner_code'.$i;

        if( !empty( $instance[$type] ) && $instance[$type] == 'image' ){
          if( !empty( $instance[$url] ) ){
            if( !empty( $instance[$link] ) ){
              echo '<div class="islemag-small-banner"> <a href="' . esc_url( $instance[$link] ) . '" target="_blank" ><img src="' . esc_url( $instance[$url] ) . '" alt="' . ( !empty( $instance[$title_alt] ) ? esc_attr( $instance[$title_alt] ) : '' ).'"/></a></div>';
            } else {
              echo '<div class="islemag-small-banner"> <img src="' . esc_url( $instance[$url] ) . '" alt="'.( !empty( $instance[$title_alt] ) ? esc_attr( $instance[$title_alt] ) : '' ).'"/></div>';
            }
          }
        } else {
          if( !empty( $instance[$code] ) ){
            echo '<div class="islemag-small-banner">'.$instance[$code].'</div>';
          }
        }
      }
      echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {

      $instance = $old_instance;
      $instance['widget_title'] = sanitize_text_field( $new_instance['widget_title'] );
      $allowed_html = array(
                  'a' => array(
                    'href' => array(),
                    'class' => array(),
                    'id' => array(),
                    'target' => array()
                  ),
                  'img' => array(
                    'src' => array(),
                    'alt' => array(),
                    'title' => array(),
                    'width' => array(),
                    'height' => array()
                  ),
                  'iframe' => array(
                    'src' => array(),
                    'width' => array(),
                    'height' => array(),
                    'seamless' => array(),
                    'scrolling' => array(),
                    'frameborder' => array(),
                    'allowtransparency' => array()
                  ),
                  'script' => array(
                    'type' => array(),
                    'src' => array(),
                    'charset' => array(),
                  ),
                  'div' => array(
                    'id' => array()
                  ),
                  'ins' => array(
                      'class' => array(),
                      'style' => array(),
                      'data-ad-client' => array(),
                      'data-ad-slot' => array()
                  )
                );

      for($i = 1; $i <= 6; $i++ ){
        $instance['title_ad' . $i] = sanitize_text_field( $new_instance['title_ad' . $i] );
        $instance['link_ad' . $i] = esc_url_raw( $new_instance['link_ad' . $i] );
        $instance['image_uri_ad' . $i] = esc_url_raw( $new_instance['image_uri_ad' . $i] );

        $instance['banner_type'.$i] = strip_tags( $new_instance['banner_type'.$i] );

        $string = force_balance_tags( $new_instance['banner_code'.$i] );
        $input_santized = wp_kses( $string, $allowed_html );
        $instance['banner_code'.$i] = $input_santized ;
      }

      return $instance;
    }



    function form($instance) { ?>
      <p>
        <label for="<?php echo esc_attr( $this->get_field_id('widget_title') ); ?>"><?php _e('Title','islemag'); ?></label><br/>
        <input type="text" name="<?php echo esc_attr( $this->get_field_name('widget_title') ); ?>" id="<?php echo esc_attr( $this->get_field_id('widget_title') ); ?>" value="<?php if( !empty( $instance['widget_title'] ) ): echo esc_attr( $instance['widget_title'] ); endif; ?>" class="widefat" />
      </p>
      <p class="description"><?php esc_html_e( 'Recommended size: 125 x 125 px', 'islemag' ) ?></p>


      <?php for( $i = 1 ; $i < 7; $i++){
        $title_alt = 'title_ad'.$i;
        $link = 'link_ad'.$i;
        $url = 'image_uri_ad'.$i;
        $type = 'banner_type'.$i;
        $code = 'banner_code'.$i;

        if( empty( $instance[$type] )){
          $instance[$type] = "image";
        }
      ?>
      <div class="islemag-ad-widget">
        <div class="islemag-ad-widget-top">
          <div class="islemag-ad-widget-title">
            <h3><?php esc_html_e( 'Advertisement ','islemag' ); echo $i; ?> </h3>
          </div>
        </div>
        <div class="islemag-ad-widget-inside">
        	<p>
        		<label for="<?php echo esc_attr( $this->get_field_id($title_alt) ); ?>"><?php _e('Alt Title','islemag'); ?></label><br />
        		<input type="text" name="<?php echo esc_attr( $this->get_field_name($title_alt) ); ?>" id="<?php echo esc_attr( $this->get_field_id($title_alt) ); ?>" value="<?php if( !empty( $instance[$title_alt] ) ): echo esc_attr( $instance[$title_alt] ); endif; ?>" class="widefat" />
        	</p>

          <p>
            <input type="radio" name="<?php echo esc_attr( $this->get_field_name($type) ); ?>" value="image" class="islemag-small-ad-type" <?php if( !empty( $instance[$type] ) ): checked( $instance[$type], 'image' ); endif; ?>/> <?php esc_html_e( 'Image', 'islemag' ); ?>
            <input type="radio" name="<?php echo esc_attr( $this->get_field_name($type) ); ?>" value="code" class="islemag-small-ad-type" <?php if( !empty( $instance[$type] ) ): checked( $instance[$type], 'code' ); endif; ?>/> <?php esc_html_e( 'Code', 'islemag' ); ?>
          </p>

          <p class="islemag-small-ad-image" style="<?php if( $instance[$type] == 'code' ) echo "display:none" ?>">
            <label for="<?php echo esc_attr( $this->get_field_id($link) ); ?>"><?php _e('Link','islemag'); ?></label><br />
            <input type="text" name="<?php echo esc_attr( $this->get_field_name( $link ) ); ?>" id="<?php echo esc_attr( $this->get_field_id($link) ); ?>" value="<?php if( !empty( $instance[$link] ) ): echo esc_attr( $instance[$link] ); endif; ?>" class="widefat" />

            <label for="<?php echo esc_attr( $this->get_field_name( $url ) ); ?>"><?php _e( 'Image:', 'islemag' ); ?></label>
            <input name="<?php echo esc_attr( $this->get_field_name( $url ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( $url ) ); ?>" class="widefat custom_media_url" type="text" size="36"  value="<?php if( !empty($instance[$url]) ): echo esc_url( $instance[$url] ); endif; ?>" />
            <input class="upload_image_button" type="button" value="Upload Image" id="" />
          </p>

          <p class="islemag-small-ad-code" style="<?php if( $instance[$type] == 'image' ) echo "display:none" ?>">
            <label for="<?php echo esc_attr( $this->get_field_name( $code ) ); ?>"><?php _e( 'Code:','islemag' ); ?></label><br/>
            <textarea name="<?php echo esc_attr( $this->get_field_name( $code ) ); ?>" placeholder="<?php esc_html_e( 'Text', 'islemag' ); ?>"><?php if( !empty( $instance[$code] ) ): echo $instance[$code]; endif; ?></textarea>
          </p>
        </div>
      </div>
<?php }

    }

}
