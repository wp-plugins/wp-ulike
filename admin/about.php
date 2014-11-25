<?php 
/**
 * WP ULike about page
 */
 
add_filter('get_avatar', 'remove_photo_class');
function remove_photo_class($avatar) {
    return str_replace(' photo', ' gravatar', $avatar);
}

function wp_ulike_about_page() {
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
.boxstyle {
    padding: 1px 12px;
    background-color: #FFF;
    box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.1);
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

    <div class="about-text"><?php echo _e('Thank you for choosing WP ULike! This version is our leanest and most powerful version yet.', 'alimir') ; ?><br />
	<a target="_blank" href="http://preview.alimir.ir/wp-ulike-plugin/"> <?php _e('Visit our homepage','alimir'); ?></a>
	</div>
	<div class="ulike-badge"></div>

    <h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if(!isset($_GET["credit"])) echo 'nav-tab-active'; ?>" href="admin.php?page=wp-ulike-about"><?php echo _e('Getting Started','alimir'); ?></a> 
		<a class="nav-tab <?php if(isset($_GET["credit"])) echo 'nav-tab-active'; ?>" href="admin.php?page=wp-ulike-about&credit=true"><?php echo _e('Credits','alimir'); ?></a> 
		<a target="_blank" class="nav-tab" href="https://wordpress.org/support/plugin/wp-ulike"><?php echo _e('Support','alimir'); ?></a> 
		<a target="_blank" class="nav-tab" href="https://wordpress.org/plugins/wp-ulike/faq/"><?php echo _e('FAQ','alimir'); ?></a> 
		<a target="_blank" class="nav-tab" href="https://wordpress.org/support/view/plugin-reviews/wp-ulike"><?php echo _e('Reviews','alimir'); ?></a> 
		<a target="_blank" class="nav-tab" href="http://preview.alimir.ir/contact/"><?php echo _e('Contact','alimir'); ?></a> 
    </h2>
	
	<?php if(!isset($_GET["credit"])): ?>
	
	<div class="changelog">
		<img class="about-overview-img" src="<?php echo plugins_url('/assets', dirname(__FILE__)); ?>/img/wp-ulike-banner.png" alt="WP ULike" />
	</div>
	
	<hr />
	
	<div class="changelog">
		<h2 class="about-headline-callout"><?php echo _e('Novelty Of WP ULike','alimir'); ?></h2>

		<div class="feature-section col two-col">
			<div>
				<h4><?php echo _e('New setting panel','alimir'); ?> (WP ULike 1.8)</h4>
				<img style="width:100%" src="<?php echo plugins_url('/assets', dirname(__FILE__)); ?>/img/new-tools/new-setting.png" alt="WP ULike" />
			</div>

			<div class="last-feature">
				<h4><?php echo _e('Better coding on plugin files','alimir'); ?> (WP ULike 1.8)</h4>
				<img style="width:100%" src="<?php echo plugins_url('/assets', dirname(__FILE__)); ?>/img/new-tools/new-coding.png" alt="WP ULike" />
			</div>
		</div>	
		
		<div class="feature-section col two-col">
			<div>
				<h4><?php echo _e('Buddypress likes support','alimir'); ?> (WP ULike 1.7)</h4>
				<img style="width:100%" src="<?php echo plugins_url('/assets', dirname(__FILE__)); ?>/img/new-tools/activity-likes.png" alt="WP ULike" />
			</div>

			<div class="last-feature">
				<h4><?php echo _e('Likes logs support','alimir'); ?> (WP ULike 1.7)</h4>
				<img style="width:100%" src="<?php echo plugins_url('/assets', dirname(__FILE__)); ?>/img/new-tools/likes-logs.png" alt="WP ULike" />
			</div>
		</div>

	</div>
	
	<?php else: ?>
	
	<p class="about-description"><?php echo _e('WP ULike is created by many love and time. Enjoy it :)','alimir'); ?></p>	
	<h4 class="wp-people-group"><?php echo _e('Project Leaders','alimir'); ?></h4>
	<ul class="wp-people-group">
		<li class="wp-person" id="wp-person-alimirzaei">
			<a href="http://about.alimir.ir"><?php echo get_avatar( 'info@alimir.ir', 64 ); ?></a>
			<a class="web" href="https://profiles.wordpress.org/alimir/">Ali Mirzaei</a>
			<span class="title"><?php echo _e('Project Lead & Developer','alimir'); ?></span>
		</li>					
	</ul>
	
	<h4 class="wp-people-group"><?php _e('Translations','alimir'); ?></h4>
	<ul>
	<li>English</li>
	<li>Persian</li>
	<li>France</li>
	<li>Chinese (Thanks Changmeng Hu)</li>
	<li>Chinese Tradition (Thanks Arefly)</li>
	<li>Dutch (Thanks Joey)</li>
	</ul>
	
	<h4 class="wp-people-group"><?php _e('Like this plugin?','alimir'); ?></h4>
	<div class="boxstyle"><p><strong><?php _e('Show your support by Rating 5 Star in <a href="http://wordpress.org/plugins/wp-ulike"> Plugin Directory reviews</a>','alimir'); ?></strong></p></div>
	
	<?php endif; ?>
						
</div>
<?php
}