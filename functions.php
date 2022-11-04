<?php

add_action( 'wp_enqueue_scripts', 'wolmart_child_css', 1001 );

// Load CSS
function wolmart_child_css() {
	// wolmart child theme styles
	wp_deregister_style( 'styles-child' );
	wp_register_style( 'styles-child', esc_url( get_theme_file_uri() ) . '/style.css' );
	wp_enqueue_style( 'styles-child' );
}

add_action('woocommerce_archive_description', 'custom_archive_description', 2);

function custom_archive_description(){ 
if ( is_product_category() ){
   remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
  add_action('woocommerce_after_main_content', 'woocommerce_taxonomy_archive_description', 5 );
  }
}


add_filter( 'woocommerce_product_tabs', 'misha_rename_custom_tab', 99 );

function misha_rename_custom_tab( $tabs ) {

	$tabs[ 'seller_enquiry_form' ][ 'title' ] = 'Demande d\'informations';

	return $tabs;

}

add_filter( 'woocommerce_product_tabs', 'misha_customize_description_tab' );

function misha_customize_description_tab( $tabs ) {
	
	$tabs[ 'description' ][ 'callback' ] = 'misha_custom_description_callback';
	return $tabs;
	
}

function misha_custom_description_callback() {
	echo get_the_content();
	
}


add_filter( 'woocommerce_product_tabs', 'my_remove_description_tab', 99 );
 
function my_remove_description_tab( $tabs ) {
  unset( $tabs['reviews'] );
  return $tabs;
}

function add_this_script_footer(){ ?>
<script>
document.addEventListener("click", function(event){
	if(event.target.nodeName === "A"){
			
        var path = new URL(event.target).pathname;
        cat_slug = path.replace(/\/$/, "").split("/").pop();
			
		jQuery.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			type: 'post',
			data: { action: 'data_slug_fetch', cat_slug: cat_slug },
			success: function(data) {
				if(data !== ""){
					document.querySelector(".archive.tax-product_cat .page-title").innerHTML = data;
				}
				// console.log(data);
			}
		});
			
			
	}
    })
</script>
	
	<?php } 
	
	add_action('wp_footer', 'add_this_script_footer'); 

add_action('wp_ajax_data_slug_fetch' , 'data_slug_fetch');
add_action('wp_ajax_nopriv_data_slug_fetch','data_slug_fetch');
function data_slug_fetch(){
	$cat =  get_term_by( 'slug', esc_attr( $_POST['cat_slug'] ), 'product_cat'); 
	echo $cat->name;
	die();
}


// function that runs when shortcode is called
function get_Categories_list() { 

	global $product;
	$terms = get_the_terms($product->ID, 'product_cat');
	ob_start();
	?>
	<style>
		.product_cats .fa-circle{
			font-size: 10px;
			color: var( --e-global-color-e86f7d4 );
			padding: 10px 10px 10px 10px;
			margin: 0px 5px 0px 0px;
		}
		.product_cats li{
			list-style: none;
		}
		.product_cats a{
			color: #4A4A4A;
		}
		.product_cats {
			margin: 0;
			padding: 0;
		}
	</style>
	<?php
	if($terms){
		?>
		<ul class="product_cats">
			<?php
				foreach($terms as $term){
					?>
					<li><i class="fas fa-circle"></i><a href="<?php echo $term->slug; ?>" rel="tag"><?php echo $term->name; ?></a></li>
					<?php
				}
			?>
		</ul>
		<?php
	}else{
		?>
		<ul class="product_cats">
			<li><i class="fas fa-circle"></i><a href="https://woodpartners.fr/product-category/amenagements-exterieurs/" rel="tag">Aménagements Extérieurs</a></li>
			<li><i class="fas fa-circle"></i><a href="https://woodpartners.fr/product-category/amenagements-exterieurs/traverse-paysagere/" rel="tag">Traverse Paysagère</a></li>
		</ul>
		<?php
	}
	return ob_get_clean();
}
// register shortcode
add_shortcode('categories_list', 'get_Categories_list');


function get_popup_form_prod() { 

	global $product;

	$id = $product ? $product->get_id() : '';
	$product_id = $id;
	$seller = get_post_field( 'post_author', $id);
	$author = get_user_by( 'id', $seller );
	$vendor = dokan()->vendor->get( $author );
	$email = $author->user_email;
	ob_start();
	?>
	<style>
		#popup_form_products .d-none{
			display: none;
		}
		#popup_form_products{
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 999;
			background-color: #72727294;
		}
		#popup_form_products .modal-dialog{
			background-color: white;
    		padding: 20px;
			max-width: 600px;
    		width: 100%;
		}
		#popup_form_products .modal-dialog{
			text-align: center;
		}
		#popup_form_products .modal-dialog .close{
			width: 27px;
			display: block;
			margin-left: auto;
			border: none;
			font-size: 20px;
		}
		#popup_form_products .form-control{
			margin-bottom: 10px;
			background-color: #F6F6F6;
		}
		#popup_form_products .submit_btn{
			background-color: #CD1719;
			color: white;
			border: none;
			padding: 7px 14px;
		}
	</style>
	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary launch_popup_form" data-toggle="modal" data-target="#popup_form_products">
	Ce produit vous intéresse
	</button>

	<!-- Modal -->
	<div class="modal fade d-none" id="popup_form_products" tabindex="-1" role="dialog" aria-labelledby="popup_form_productsLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<h5 class="modal-title" id="popup_form_productsLabel">Ce produit vous intéresse ?</h5>
		</div>
		<div class="modal-body">
			<form id="form" action="" >
				<input class="form-control" type="text" name="your_name" placeholder="Your Name">
				<input class="form-control" type="email" name="your_email" placeholder="Your Email">
				<input placeholder="Your Phone"  class="form-control" type="tel" name="phone" pattern="{+}{1}[0-9]{11,14}" required>
				<input placeholder="Code Postal"  class="form-control" type="text" name="code_postal" required>
				<input placeholder="Pays"  class="form-control" type="text" name="author_pays" required>
				<textarea class="form-control" name="enq_message" placeholder="Votre demande" rows="5" required></textarea>
				<input type="hidden" name="product" value="<?php echo $product_id; ?>">
				<input type="hidden" name="emailto" value="<?php echo $email; ?>">
				<button class="submit_btn" type="submit">Envoyer la demande</button>
			</form>
		</div>
		</div>
	</div>
	</div>

	<?php
	return ob_get_clean();
}
// register shortcode
add_shortcode('popup_form_prod', 'get_popup_form_prod');

function shortcode_store_logo(){
	// echo "<pre>";
	ob_start();
	global $product;
	$id = $product ? $product->get_id() : '';
	$seller = get_post_field( 'post_author', $id);
	$author = get_user_by( 'id', $seller );
	$vendor = dokan()->vendor->get( $author );
	$email = $author->user_email;

	// print_r($vendor);
	// exit;
	?>
	<div class="store_image" >
	<img  src="<?php echo $vendor->get_avatar(); ?>" alt="">
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('store_logo_shortCode', 'shortcode_store_logo');

add_action('wp_ajax_data_send_toEmail', 'data_send_toEmail_callback_mail');
add_action('wp_ajax_nopriv_data_send_toEmail', 'data_send_toEmail_callback_mail');

function data_send_toEmail_callback_mail(){
	$your_name  			=	$_POST['your_name'];
    $your_email     		=	$_POST['your_email'];
    $phone   				=	$_POST['phone'];
    $code_postal        	=	$_POST['code_postal'];
    $author_pays       		=	$_POST['author_pays'];
    $enq_message       		=	$_POST['enq_message'];
    $product 				=	$_POST['product'];
    $emailto				=	$_POST['emailto'];
    $security	        	=	$_POST['security'];
	$productName 			= 	wc_get_product( $product );



	if ( is_email( $your_email ) ){
		check_ajax_referer('form_submitProduct', 'security');

		$headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: '.get_option( 'blogname' ).' <'.$your_email.'>',
            'Reply-To: '.$your_name.' <'.$your_email.'>',
        );
    
        $subject = 'Product Enquiry Form';

        $msg = '<!DOCTYPE html>
        <html>
            <head></head>
            <body>
                <table>
                    <tr><td>Name: </td><td>' . $your_name . '</td></tr>
                    <tr><td>Email: </td><td>' . $your_email . '</td></tr>
                    <tr><td>Phone: </td><td>' . $phone . '</td></tr>
                    <tr><td>Postal Code: </td><td>' . $code_postal . '</td></tr>
                    <tr><td>Author Pays: </td><td>' . $author_pays . '</td></tr>
                    <tr><td>Enquiry Message: </td><td>' . $enq_message . '</td></tr>
                    <tr><td>Product ID: </td><td>' . $product . '</td></tr>
                    <tr><td>Product Title: </td><td>' . $productName->get_title() . '</td></tr>
                </table>
            </body>
        </html>';

		//To Save The Message In Custom Post Type
		$new_post = array(
			'post_title'    => $product->name,
			'post_status'   => 'publish',           // Choose: publish, preview, future, draft, etc.
			'post_type' => 'product_enquiry_data'  //'post',page' or use a custom post type if you want to
		);

		$pid = wp_insert_post($new_post);
		update_post_meta($pid , 'slide_one', $your_name);
		update_post_meta($pid , 'slide_two', $your_email);
		update_post_meta($pid , 'slide_three', $phone );
		update_post_meta($pid , 'slide_five', $code_postal );
		update_post_meta($pid , 'slide_six', $author_pays );
		update_post_meta($pid , 'slide_four', "(".$productName->get_title().") - " . $enq_message);

		if(wp_mail($emailto, $subject, $msg, $headers)){
            echo "email sent";
        }
	}else{
		echo "incorrect";
	}
	wp_die();
}





function insert_jquery(){
	?>
		<script>
		const btn_launch = document.querySelector(".launch_popup_form");
		const btn_close = document.querySelector("#popup_form_products .modal-dialog .close");

		function popup_form_function(event){
			document.querySelector("#popup_form_products").classList.toggle("d-none");
		}
		btn_launch.addEventListener("click", popup_form_function);
		btn_close.addEventListener("click", popup_form_function);

		// ON Form Submit
		jQuery("#popup_form_products .modal-body #form").submit(function (event) {
                event.preventDefault();
                // Collecting the whole form data

              form_data = new FormData(this);
			  form_data.append('action', 'data_send_toEmail');
			  form_data.append('security', '<?php echo wp_create_nonce( 'form_submitProduct' ); ?>');
    
          
              // Transfering data through AJAX
              jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response == "email sent"){
                        location.reload();
                    }
                    console.log(response);
                }
              });

			  return false;
             
            });
    
	</script>
	<?php
	}
	add_filter('wp_footer','insert_jquery');