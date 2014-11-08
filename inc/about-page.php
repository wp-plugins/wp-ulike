<?php 
/**
 * WP ULike about page
 */
 
add_filter('get_avatar', 'remove_photo_class');
function remove_photo_class($avatar) {
    return str_replace(' photo', ' gravatar', $avatar);
}
 
?>

<style>
.ulike-badge{
    background: url('<?php echo plugins_url('/assets', dirname(__FILE__)); ?>/img/wp-ulike-badge.png') no-repeat scroll center 24px / 150px 150px;
    margin: 5px 0px 0px;
    padding-top: 120px;
    display: inline-block;
	height:60px;
    width: 150px;
}
<?php if(is_rtl()): ?>
.about-wrap .ulike-badge {
    position: absolute;
    top: 0px;
    left: 0px;
}
<?php else: ?>
.about-wrap .ulike-badge {
    position: absolute;
    top: 0px;
    right: 0px;
}
<?php endif; ?>
</style>

<div class="wrap about-wrap">

    <h1><?php echo _e('Welcome to WP ULike','alimir') . ' ' . wp_ulike_get_version(); ?></h1>

    <div class="about-text"><?php echo _e('Thanks for updating! This version is our leanest and most powerful version yet.', 'alimir'); ?></div>
	<div class="ulike-badge"></div>

    <h2 class="nav-tab-wrapper">
		<a class="nav-tab nav-tab-active" href="#"><?php echo _e('What’s New','alimir'); ?></a> 
	
    </h2>

	<div class="changelog">
		<h2 class="about-headline-callout"><?php echo _e('About WP ULike', 'alimir'); ?></h2>
		<img class="about-overview-img" src="<?php echo plugins_url('/assets', dirname(__FILE__)); ?>/img/wp-ulike-banner.png" alt="WP ULike" />
		<p class="about-description"><?php _e('WP ULike enables you to add Ajax Like button into your WordPress site and allowing your visitors to like and dislike posts, pages, comments AND buddypress activities. It features: Clean Design, Ajax feature to update the data without reloading, Custom Like-Dislike Texts, Simple custom style with color picker settings, BuddyPress activity support, Widget to show Most Liked Posts And Most Liked Users avatars, Added automatically, RTL language support, Simple configuration panel and many more...', 'alimir'); ?></p>
	</div>
	
	<hr />
	
	<div class="changelog">
		<h2 class="about-headline-callout"><?php echo _e('New Administrative Tools','alimir'); ?></h2>

		<div class="feature-section col two-col">
			<div>
				<h4>Buddypress likes support</h4>
				<img style="width:90%" src="<?php echo plugins_url('/assets', dirname(__FILE__)); ?>/img/wp-ulike-17/activity-likes.png" alt="WP ULike" />
			</div>

			<div class="last-feature">
				<h4>Likes Logs Support</h4>
				<img style="width:90%" src="<?php echo plugins_url('/assets', dirname(__FILE__)); ?>/img/wp-ulike-17/likes-logs.png" alt="WP ULike" />
			</div>
		</div>

	</div>

	<hr />
	
	<h2 class="about-headline-callout"><?php echo _e('Credits'); ?></h2><br />
	<ul class="wp-people-group">
		<li class="wp-person" id="wp-person-alimirzaei">
			<a href="http://about.alimir.ir"><?php echo get_avatar( 'info@alimir.ir', 64 ); ?></a>
			<a class="web" href="https://profiles.wordpress.org/alimir/">Ali Mirzaei</a>
			<span class="title">Project Lead</span>
		</li>					
	</ul>
		
	<h4 class="wp-people-group"><?php _e('Translations','alimir'); ?></h4>
	English, Persian, France, Chinese (Thanks Changmeng Hu), Chinese Tradition (Thanks Arefly)

	<h4 class="wp-people-group"><?php _e('Like this plugin?','alimir'); ?></h4>
	<p><?php _e('Show your support by Rating 5 Star in <a href="http://wordpress.org/plugins/wp-ulike"> Plugin Directory reviews</a>','alimir'); ?></p>
						
</div>