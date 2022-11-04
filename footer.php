<?php
/**
 * Footer template
 *
 * @package Wolmart WordPress Framework
 * @since 1.0
 */

defined( 'ABSPATH' ) || die;

?>

			</main>

			<?php do_action( 'wolmart_after_main' ); ?>

			<?php

			global $wolmart_layout;

			if ( 'wolmart_template' == get_post_type() && 'footer' == get_post_meta( get_the_ID(), 'wolmart_template_type', true ) ) {
				/**
				 * View Footer Template
				 */
				?>
				<footer class="footer custom-footer footer-<?php the_ID(); ?>" id="footer">
				<?php
				if ( have_posts() ) :
					the_post();
					the_content();
					wp_reset_postdata();
				endif;
				?>
				</footer>
				<?php
			} elseif ( ! empty( $wolmart_layout['footer'] ) && 'elementor_pro' == $wolmart_layout['footer'] ) {

				/**
				 * Elementor Pro Footer
				 */
				do_action( 'wolmart_elementor_pro_footer_location' );

			} elseif ( ! empty( $wolmart_layout['footer'] ) && 'hide' == $wolmart_layout['footer'] ) {

				// Hide

			} elseif ( ! empty( $wolmart_layout['footer'] ) && 'publish' == get_post_status( intval( $wolmart_layout['footer'] ) ) ) {

				/**
				 * Custom Block Footer
				 */
				?>
				<footer class="footer custom-footer footer-<?php echo intval( $wolmart_layout['footer'] ); ?>" id="footer">
					<?php wolmart_print_template( $wolmart_layout['footer'] ); ?>
				</footer>
				<?php

			} else {
				/**
				 * Default Footer
				 */
				?>
				<footer class="footer footer-copyright" id="footer">
					<?php /* translators: date format */ ?>
					<?php printf( esc_html__( 'Wolmart eCommerce &copy; %s. All Rights Reserved', 'wolmart' ), date( 'Y' ) ); ?>
				</footer>
				<?php
			}
			?>

		</div>

		<?php do_action( 'wolmart_after_page_wrapper' ); ?>

		<?php wolmart_print_mobile_bar(); ?>

		<a id="scroll-top" class="scroll-top" href="#top" title="<?php esc_attr_e( 'Top', 'wolmart' ); ?>" role="button">
			<i class="w-icon-angle-up"></i>
			<svg  version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70">
				<circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34"/>
			</svg>
		</a>

		<?php if ( ! empty( wolmart_get_option( 'mobile_menu_items' ) ) ) { // if mobile menu has menu items... ?>
			<div class="mobile-menu-wrapper">
				<div class="mobile-menu-overlay"></div>
				<div class="mobile-menu-container" style="height: 100vh;">
					<!-- Need to ajax load mobile menus -->
				</div>
				<a class="mobile-menu-close" href="#"><i class="close-icon"></i></a>
			</div>
		<?php } ?>

		<?php
		// first popup
		if ( function_exists( 'wolmart_is_elementor_preview' ) && ! wolmart_is_elementor_preview() &&
			! empty( $wolmart_layout['popup'] ) && 'hide' != $wolmart_layout['popup'] ) {
			wp_enqueue_script( 'jquery-magnific-popup' );
			wolmart_print_popup_template( $wolmart_layout['popup'], $wolmart_layout['popup_delay'] );
		}
		?>
        
        <div class="topnav">
<?php /*?>  <div class="navbarss" id="myNavbar">
<?php wp_nav_menu( array('menu' => 'Catégories WP','container_class' => '','items_wrap' => '<ul class="nav nav-tabs nav-fill" role="tablist">%3$s</ul>')); ?>
  </div>
<?php */?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
/*function myFunction() {
document.body.classList.add('mmenu-active');

var myElement = document.querySelector('.nav-wrapper li:last-child a');
var myElements = document.querySelector('.nav-wrapper li:first-child a');
var tabactivet = document.querySelector('#categories-wp');
var tabactivetnot = document.querySelector('#main-menu');

myElement.classList.add('active');
myElements.classList.remove('active');
tabactivet.classList.add('active', 'in');
tabactivetnot.classList.remove('active', 'in');

jquery.ajax({
                type: 'POST',
                 url: '/wp-admin/admin-ajax.php',
			     data : {
                    'action' : 'wolmart_load_mobile_menu', // the php name function
                },
            });
}*/

$(".mobile-item-categories").click(function(){
 $("body").addClass("mmenu-active");
  $(".nav-wrapper li:last-child a").addClass("active");
  $("#categories-wp").addClass("active");
  $("#categories-wp").addClass("in");
  
   $("#main-menu").removeClass("active");
   $(".nav-wrapper li:first-child a").removeClass("active");

$.ajax({
                type: 'POST',
                 url: '/wp-admin/admin-ajax.php',
				  dataType: 'html',
			     data : {
                    'action' : 'wolmart_load_mobile_menu', // the php name function
					
                },
                success: function (result) {
                    jQuery('.mobile-menu-container').html(result);
					 $("#categories-wp").addClass("active");
                     $("#categories-wp").addClass("in");
					 $(".nav-wrapper li:last-child a").addClass("active");
					 $("#main-menu").removeClass("active");
                   $(".nav-wrapper li:first-child a").removeClass("active");
	  $("#categories-wp ul li.menu-item-has-children a").append("<span class='toggle-btn'></span>");


                },
                error: function(err){
                    // just for test - error (you can remove it later)
                    console.log(err);
                    console.log(choices);
                }
            });
			
});

$(document).ready(function(){
	 var newUrl = "/request-quote/";
	$('.cart-toggle.mobile-item').attr("href", newUrl)
});

$(".search-toggle.mobile-item").click(function(){
$('.search-wrapper .input-wrapper').show();	
});

window.addEventListener(
    'load',
    function load()
    {
        window.removeEventListener('load', load, false);
        document.body.classList.remove('mfp-bg.mfp-fade.mfp-wolmart.mfp-wolmart-803.mfp-ready');
		 document.body.classList.remove('mfp-wrap.mfp-close-btn-in.mfp-auto-cursor.mfp-fade.mfp-wolmart.mfp-wolmart-803.mfp-ready');
    },
    false);
</script>
<style>
.navbarss {
    display: none;
}
div#myNavbar a {
    color: #fff;
}
.navbarss.responsive {
    display: block;
    position: absolute;
    top: 0;
    background: #000;
   float : left;
    width: 100%;
    z-index: 99;
}
@media screen and (max-width: 600px) {
  .navbarss a.icon {
    float: right;
    display: block;
  }
}


@media screen and (max-width: 600px) {
  .navbarss.responsive a.icon {
    position: absolute;
    right: 0;
    bottom: 0;
  }
  .navbarss.responsive a {
    float: none;
    display: block;
    text-align: left;
  }
  .navbarss.responsive {
    display: block;
    position: absolute;
    top: 0;
    background: #000;
   float : left;
    width: 100%;
    z-index: 99;
}
div#myNavbar a {
    color: #fff;
}
.mobile-icon-bar .hs-toggle .btn-search{position: absolute;
    right: 0;
    top: 50%;
    right: 10px;
    }
}

</style>

		<?php wp_footer(); ?>
	</body>
</html>