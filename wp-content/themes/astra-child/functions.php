<?php
if (!function_exists('astra_child_enqueue_styles')) {
    /*
    *   Function to load parent and child theme CSS
    *
    */
    function astra_child_enqueue_styles() {
        wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
        wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/assets/css/main.css' );
    }
}

if (!function_exists('astra_child_theme_slug_setup')) {
    /*
    *   Function to load child theme textdomain
    *
    */
    function astra_child_theme_slug_setup() {
        load_child_theme_textdomain( 'astra-child', get_stylesheet_directory() . '/languages' );
    }
}
/*
* Load Child Theme and parent theme CSS
*
*/
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

/*
* Load Child theme text-domain
*
*/
add_action( 'after_setup_theme', 'astra_child_theme_slug_setup' );

/*
* Post type Публікація
*
*/

add_action( 'init', 'custom_post_type' );

function custom_post_type() {
    
	register_post_type('publication',
		array(
			'public' => true,
			'has_archive' => false,
			'rewrite' => array('publication'),
			'label' => 'Публікації',
			'supports' => array('title', 'editor'),
			'menu_position' => 56
	));

    $labels = array(            
		'name'              => 'Тип публікації',
		'singular_name'     => 'Тип публікації',
		'search_items'      => 'Шукати тип публікації',
		'all_items'         => 'Всі типи публікації',
		'view_item'         => 'Дивитися',
		'parent_item'       => 'Батьківський тип публікації',
		'parent_item_colon' => 'Батьківський тип публікації:',
		'edit_item'         => 'Редагувати тип публікації',
		'update_item'       => 'Оновити тип публікації',
		'add_new_item'      => 'Додати тип публікації',
		'new_item_name'     => 'Новий тип публікації',
		'not_found'         => 'Жодного типу публікації не знайдено',
		'back_to_items'     => 'Повернутися до типів публікації',
		'menu_name'         => 'Тип публікації',
	);

	$args = array(
		'show_ui' => true,
		'show_admin_column' => true,
		'query-var' => true,
		'rewrite' => array('publication-type'),
		'labels' => $labels,
	);

	register_taxonomy('publication-type', 'publication', $args);
}

add_action('add_meta_boxes', 'add_meta_box_img'); 

function add_meta_box_img() {
	add_meta_box(
		'publication_img',
		'Publication Image',
		'metabox_img_html',
		'publication',
		'normal',
		'default',
	);
}

function metabox_img_html($post) {
	global $post;
    $meta = get_post_meta( $post->ID, 'publication_img', true );
     ?>

<p>
	<label for="publication_img">Image Upload</label><br>
	<input type="text" name="publication_img" id="publication_img" class="meta-image regular-text" value="<?php echo $meta; ?>">
	<input type="button" class="button image-upload" value="Browse">
</p>
<div class="image-preview"><img src="<?php echo $meta; ?>" style="max-width: 250px;"></div>

<script>
  jQuery(document).ready(function ($) {.
    var meta_image_frame
    $('.image-upload').click(function (e) {
      var meta_image_preview = $(this)
        .parent()
        .parent()
        .children('.image-preview')
      e.preventDefault()
      var meta_image = $(this).parent().children('.meta-image')
      if (meta_image_frame) {
        meta_image_frame.open()
        return
      }
      meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
        title: meta_image.title,
        button: {
          text: meta_image.button,
        },
      })
      meta_image_frame.on('select', function () {
        var media_attachment = meta_image_frame
          .state()
          .get('selection')
          .first()
          .toJSON()
        meta_image.val(media_attachment.url)
        meta_image_preview.children('img').attr('src', media_attachment.url)
      })
      meta_image_frame.open()
    })
  })
</script>
  
<?php }

function save_publication_img_meta( $post_id ) {    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }    

    $old = get_post_meta( $post_id, 'publication_img', true );
    if(isset($_POST['publication_img'])) {
        $new = $_POST['publication_img'];
    } else {
        $new = $old;
    }   
    

    if ( $new && $new !== $old ) {
        update_post_meta( $post_id, 'publication_img', $new );
    } elseif ( '' === $new && $old ) {
        delete_post_meta( $post_id, 'publication_img', $old );
    }
}
add_action( 'save_post', 'save_publication_img_meta' );



/*
* Send notification emails
*
*/

function send_email_create_post( $post_id, $post, $update=false ) {

	// If this is a revision, don't send the email.
	if ( wp_is_post_revision( $post_id ) )
		return;

	$post_url = get_permalink( $post_id );
	$subject = 'A post has been created';

	$message = "A post has been created on your website:\n\n";
	$message .= $post->post_title . ": " . $post_url;

	// Send email to admin.
	wp_mail( get_bloginfo('admin_email'), $subject, $message );
  
   // Send email to author.
    
    $delay = 1200; // 20 minutes 
    $author_id = get_post_field ('post_author', $post_id);
    $author_email = get_the_author_meta( 'user_email' , $author_id );     
    
    wp_schedule_single_event( time() + $delay, 'send_author_email', array( $author_email, $subject, $message ) );
    
}
add_action( 'wp_insert_post', 'send_email_create_post', 10, 3 );

add_action( 'send_email_create_post', 'send_author_email', 10, 3 );

function send_author_email($author_email, $subject, $message) {
  wp_mail( $author_email, $subject, $message );
} 