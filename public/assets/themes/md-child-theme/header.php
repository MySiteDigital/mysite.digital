<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package suitbuilder
 */

/**
 * suitbuilder_action_before_head hook
 * @since suitbuilder 1.0.0
 *
 * @hooked suitbuilder_set_global -  0
 * @hooked suitbuilder_doctype -  10
 */
do_action( 'suitbuilder_action_before_head' );?>

<head>
	
	<?php
	/**
	 * suitbuilder_action_before_wp_head hook
	 * @since suitbuilder 1.0.0
	 *
	 * @hooked suitbuilder_before_wp_head -  10
	 */
	do_action( 'suitbuilder_action_before_wp_head' );

	wp_head();

	/**
	 * suitbuilder_action_after_wp_head hook
	 * @since suitbuilder 1.0.0
	 *
	 * @hooked null
	 */
	do_action( 'suitbuilder_action_after_wp_head' );
	?>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;900&family=Roboto:wght@900&display=swap" rel="stylesheet"> 
	<link rel='stylesheet' id='simple-dining-css'  href='http://mysite.ms/assets/themes/md-child-theme/assets/css/theme.css?ver=1586489179' type='text/css' media='all' title="mysite" />
</head>

<body <?php body_class(); ?>>

<?php
/**
 * suitbuilder_action_before hook
 * @since suitbuilder 1.0.0
 *
 * @hooked suitbuilder_page_start - 15
 */
do_action( 'suitbuilder_action_before' );

/**
 * suitbuilder_action_before_header hook
 * @since suitbuilder 1.0.0
 *
 * @hooked suitbuilder_skip_to_content - 10
 */
do_action( 'suitbuilder_action_before_header' );

/**
 * suitbuilder_action_header hook
 * @since suitbuilder 1.0.0
 *
 * @hooked suitbuilder_after_header - 10
 */
do_action( 'suitbuilder_action_header' );

/**
 * suitbuilder_action_nav_page_start hook
 * @since suitbuilder 1.0.0
 *
 * @hooked page start and navigations - 10
 */
do_action( 'suitbuilder_action_nav_page_start' );

/**
 * suitbuilder_action_on_header hook
 * @since suitbuilder 1.0.0
 *
 * @hooked page start and navigations - 10
 */
global $suitbuilder_customizer_all_values;
$Latest_blog_header =  $suitbuilder_customizer_all_values['suitbuilder-blog-header-title-text'];
if( is_home( ) && empty( $Latest_blog_header )  ) {
	return;
} else{ ?>
<div class="wrapper page-inner-title">
	<div class="container position-relative z-index ">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<header class="entry-header">
					<?php if (is_singular()){ ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<?php } 
					elseif (is_404()) { ?>
						<h1 class="entry-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'suitbuilder' ); ?></h1>
					<?php }
					elseif (is_archive()) {
						the_archive_title( '<h1 class="entry-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					}
					elseif (is_search()) { ?>
					<?php /* translators: %s: search page result */ ?>
						<h1 class="entry-title"><?php printf( esc_html__( 'Search Results for: %s', 'suitbuilder' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
					<?php }
					else{ ?>
							
						<h1 class="entry-title"><?php echo esc_html( $Latest_blog_header ); ?></h1>
				<?php }
					?>
				</header><!-- .entry-header -->
			</div>
		</div>
	</div>
</div>
<?php
}

do_action('suitbuilder_action_after_title');

/**
 * suitbuilder_action_before_content hook
 * @since suitbuilder 1.0.0
 *
 * @hooked null
 */
do_action( 'suitbuilder_action_before_content' );

?>