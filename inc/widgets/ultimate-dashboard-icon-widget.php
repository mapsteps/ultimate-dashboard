<?php
/**
 * Icon Widget
 *
 * @package Ultimate Dashboard
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function udb_icon_widget() { ?>

	<?php global $post; ?>

	<div class="udb-metabox-wrapper udb-icon-wrapper active">

		<div class="udb-metabox-section">

			<h3><?php _e( 'Icon', 'ultimate-dashboard' ); ?></h3>

			<?php

			wp_nonce_field( basename( __FILE__ ), 'udb_metabox_nonce' );

			$udb_stored_meta = get_post_meta( $post->ID, 'udb_icon_key', true );

			if( !$udb_stored_meta ) {
				$udb_stored_meta = 'dashicons dashicons-menu'; 
			}

			?>

			<div id="udb-icon-header">
				<h4><?php _e( 'Search', 'ultimate-dashboard' ); ?> <i class="fa fa-search"></i></h4>
			</div>

			<div class="ca-accordion">

				<!-- WordPress Dashicons -->
				<h4 class="ca-accordion-title"><?php _e( 'WordPress Dashicons', 'ultimate-dashboard' ); ?></h4>

				<div class="udb-icon-list ca-accordion-content">

				<ul>

					<li>

						<h4><?php _e( 'Admin Menu', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-menu', 'dashicons-admin-site', 'dashicons-dashboard', 'dashicons-admin-post', 'dashicons-admin-media', 'dashicons-admin-links', 'dashicons-admin-page', 'dashicons-admin-comments', 'dashicons-admin-appearance', 'dashicons-admin-plugins', 'dashicons-admin-users', 'dashicons-admin-tools', 'dashicons-admin-settings', 'dashicons-admin-network', 'dashicons-admin-home', 'dashicons-admin-generic', 'dashicons-admin-collapse', 'dashicons-filter', 'dashicons-admin-customizer', 'dashicons-admin-multisite' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Welcome Screen', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-welcome-write-blog', 'dashicons-welcome-add-page', 'dashicons-welcome-view-site', 'dashicons-welcome-widgets-menus', 'dashicons-welcome-comments', 'dashicons-welcome-learn-more' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Post Formats', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-format-aside', 'dashicons-format-image', 'dashicons-format-gallery', 'dashicons-format-video', 'dashicons-format-status', 'dashicons-format-quote', 'dashicons-format-chat', 'dashicons-format-audio', 'dashicons-camera', 'dashicons-images-alt', 'dashicons-images-alt2', 'dashicons-video-alt', 'dashicons-video-alt2', 'dashicons-video-alt3' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Media', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-media-archive', 'dashicons-media-audio', 'dashicons-media-code', 'dashicons-media-default', 'dashicons-media-document', 'dashicons-media-interactive', 'dashicons-media-spreadsheet', 'dashicons-media-text', 'dashicons-media-video', 'dashicons-playlist-audio', 'dashicons-playlist-video', 'dashicons-controls-play', 'dashicons-controls-pause', 'dashicons-controls-forward', 'dashicons-controls-skipforward', 'dashicons-controls-back', 'dashicons-controls-skipback', 'dashicons-controls-repeat', 'dashicons-controls-volumeon', 'dashicons-controls-volumeoff' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Image Editing', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-image-crop', 'dashicons-image-rotate', 'dashicons-image-rotate-left', 'dashicons-image-rotate-right', 'dashicons-image-flip-vertical', 'dashicons-image-flip-horizontal', 'dashicons-image-filter', 'dashicons-undo', 'dashicons-redo' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Tiny MCE', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-editor-bold', 'dashicons-editor-italic', 'dashicons-editor-ul', 'dashicons-editor-ol', 'dashicons-editor-quote', 'dashicons-editor-alignleft', 'dashicons-editor-aligncenter', 'dashicons-editor-alignright', 'dashicons-editor-insertmore', 'dashicons-editor-spellcheck', 'dashicons-editor-expand', 'dashicons-editor-contract', 'dashicons-editor-kitchensink', 'dashicons-editor-underline', 'dashicons-editor-justify', 'dashicons-editor-textcolor', 'dashicons-editor-paste-word', 'dashicons-editor-paste-text', 'dashicons-editor-removeformatting', 'dashicons-editor-video', 'dashicons-editor-customchar', 'dashicons-editor-outdent', 'dashicons-editor-indent', 'dashicons-editor-help', 'dashicons-editor-strikethrough', 'dashicons-editor-unlink', 'dashicons-editor-rtl', 'dashicons-editor-break', 'dashicons-editor-code', 'dashicons-editor-paragraph', 'dashicons-editor-table' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Posts Screen', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-align-left', 'dashicons-align-right', 'dashicons-align-center', 'dashicons-align-none', 'dashicons-lock', 'dashicons-unlock', 'dashicons-calendar', 'dashicons-calendar-alt', 'dashicons-visibility', 'dashicons-hidden', 'dashicons-post-status', 'dashicons-edit', 'dashicons-trash', 'dashicons-sticky' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Sorting', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-external', 'dashicons-arrow-up', 'dashicons-arrow-down', 'dashicons-arrow-right', 'dashicons-arrow-left', 'dashicons-arrow-up-alt', 'dashicons-arrow-down-alt', 'dashicons-arrow-right-alt', 'dashicons-arrow-left-alt', 'dashicons-arrow-up-alt2', 'dashicons-arrow-down-alt2', 'dashicons-arrow-right-alt2', 'dashicons-arrow-left-alt2', 'dashicons-sort', 'dashicons-leftright', 'dashicons-randomize', 'dashicons-list-view', 'dashicons-exerpt-view', 'dashicons-grid-view', 'dashicons-move' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Social', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-share', 'dashicons-share-alt', 'dashicons-share-alt2', 'dashicons-twitter', 'dashicons-rss', 'dashicons-email', 'dashicons-email-alt', 'dashicons-facebook', 'dashicons-facebook-alt', 'dashicons-googleplus', 'dashicons-networking' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'WordPress.org specific', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-hammer', 'dashicons-art', 'dashicons-migrate', 'dashicons-performance', 'dashicons-universal-access', 'dashicons-universal-access-alt', 'dashicons-tickets', 'dashicons-nametag', 'dashicons-clipboard', 'dashicons-heart', 'dashicons-megaphone', 'dashicons-schedule' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Products', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-wordpress', 'dashicons-wordpress-alt', 'dashicons-pressthis', 'dashicons-update', 'dashicons-screenoptions', 'dashicons-info', 'dashicons-cart', 'dashicons-feedback', 'dashicons-cloud', 'dashicons-translation' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Taxonomies', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-tag', 'dashicons-category' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Widgets', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-archive', 'dashicons-tagcloud', 'dashicons-text' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Notifications', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-yes', 'dashicons-no', 'dashicons-no-alt', 'dashicons-plus', 'dashicons-plus-alt', 'dashicons-minus', 'dashicons-dismiss', 'dashicons-marker', 'dashicons-star-filled', 'dashicons-star-half', 'dashicons-star-empty', 'dashicons-flag', 'dashicons-warning' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

					<li>

						<h4><?php _e( 'Misc', 'ultimate-dashboard' ); ?></h4>

						<?php $wordpress_dashicons = array( 'dashicons-location', 'dashicons-location-alt', 'dashicons-vault', 'dashicons-shield', 'dashicons-shield-alt', 'dashicons-sos', 'dashicons-search', 'dashicons-slides', 'dashicons-analytics', 'dashicons-chart-pie', 'dashicons-chart-bar', 'dashicons-chart-line', 'dashicons-chart-area', 'dashicons-groups', 'dashicons-businessman', 'dashicons-id', 'dashicons-id-alt', 'dashicons-products', 'dashicons-awards', 'dashicons-forms', 'dashicons-testimonial', 'dashicons-portfolio', 'dashicons-book', 'dashicons-book-alt', 'dashicons-download', 'dashicons-upload', 'dashicons-backup', 'dashicons-clock', 'dashicons-lightbulb', 'dashicons-microphone', 'dashicons-desktop', 'dashicons-laptop', 'dashicons-tablet', 'dashicons-smartphone', 'dashicons-phone', 'dashicons-index-card', 'dashicons-carrot', 'dashicons-building', 'dashicons-store', 'dashicons-album', 'dashicons-palmtree', 'dashicons-tickets-alt', 'dashicons-money', 'dashicons-smiley', 'dashicons-thumbs-up', 'dashicons-thumbs-down', 'dashicons-layout', 'dashicons-paperclip' ); ?>

						<?php foreach ( $wordpress_dashicons as $wordpress_dashicon ) { ?>

							<input id="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-radio" type="radio" name="udb_icon" value="dashicons <?php echo esc_attr( $wordpress_dashicon ); ?>" <?php checked( $udb_stored_meta, 'dashicons '. $wordpress_dashicon .'' ); ?> />

							<label for="udb-icon-<?php echo esc_attr( $wordpress_dashicon ); ?>" class="udb-icon dashicons-before <?php echo esc_attr( $wordpress_dashicon ); ?>" title="<?php echo esc_attr( $wordpress_dashicon ); ?>">
								<span><?php echo $wordpress_dashicon; ?></span>
							</label>

						<?php } ?>

					</li>

				</ul>

				</div>

			</div>

			<div class="ca-accordion">

				<!-- Font Awesome -->
				<h4 class="ca-accordion-title"><?php _e( 'Font Awesome', 'ultimate-dashboard' ); ?></h4>

				<div class="udb-icon-list ca-accordion-content">

					<ul>
						<li>
							<h4><?php _e( 'Font Awesome Icons', 'ultimate-dashboard' ); ?></h4>
							<?php $font_awesome_icons = array( 'fa fa-500px' => '500px', 'fa fa-adjust' => 'Adjust', 'fa fa-adn' => 'Adn', 'fa fa-align-center' => 'Align center', 'fa fa-align-justify' => 'Align justify', 'fa fa-align-left' => 'Align left', 'fa fa-align-right' => 'Align right', 'fa fa-amazon' => 'Amazon', 'fa fa-ambulance' => 'Ambulance', 'fa fa-anchor' => 'Anchor', 'fa fa-android' => 'Android', 'fa fa-angellist' => 'Angellist', 'fa fa-angle-double-down' => 'Angle double down', 'fa fa-angle-double-left' => 'Angle double left', 'fa fa-angle-double-right' => 'Angle double right', 'fa fa-angle-double-up' => 'Angle double up', 'fa fa-angle-down' => 'Angle down', 'fa fa-angle-left' => 'Angle left', 'fa fa-angle-right' => 'Angle right', 'fa fa-angle-up' => 'Angle up', 'fa fa-apple' => 'Apple', 'fa fa-archive' => 'Archive', 'fa fa-area-chart' => 'Area chart', 'fa fa-arrow-circle-down' => 'Arrow circle down', 'fa fa-arrow-circle-left' => 'Arrow circle left', 'fa fa-arrow-circle-o-down' => 'Arrow circle o down', 'fa fa-arrow-circle-o-left' => 'Arrow circle o left', 'fa fa-arrow-circle-o-right' => 'Arrow circle o right', 'fa fa-arrow-circle-o-up' => 'Arrow circle o up', 'fa fa-arrow-circle-right' => 'Arrow circle right', 'fa fa-arrow-circle-up' => 'Arrow circle up', 'fa fa-arrow-down' => 'Arrow down', 'fa fa-arrow-left' => 'Arrow left', 'fa fa-arrow-right' => 'Arrow right', 'fa fa-arrow-up' => 'Arrow up', 'fa fa-arrows' => 'Arrows', 'fa fa-arrows-alt' => 'Arrows alt', 'fa fa-arrows-h' => 'Arrows h', 'fa fa-arrows-v' => 'Arrows v', 'fa fa-asterisk' => 'Asterisk', 'fa fa-at' => 'At', 'fa fa-backward' => 'Backward', 'fa fa-balance-scale' => 'Balance scale', 'fa fa-ban' => 'Ban', 'fa fa-bar-chart' => 'Bar chart', 'fa fa-barcode' => 'Barcode', 'fa fa-bars' => 'Bars', 'fa fa-battery-empty' => 'Battery empty', 'fa fa-battery-full' => 'Battery full', 'fa fa-battery-half' => 'Battery half', 'fa fa-battery-quarter' => 'Battery quarter', 'fa fa-battery-three-quarters' => 'Battery three quarters', 'fa fa-bed' => 'Bed', 'fa fa-beer' => 'Beer', 'fa fa-behance' => 'Behance', 'fa fa-behance-square' => 'Behance square', 'fa fa-bell' => 'Bell', 'fa fa-bell-o' => 'Bell o', 'fa fa-bell-slash' => 'Bell slash', 'fa fa-bell-slash-o' => 'Bell slash o', 'fa fa-bicycle' => 'Bicycle', 'fa fa-binoculars' => 'Binoculars', 'fa fa-birthday-cake' => 'Birthday cake', 'fa fa-bitbucket' => 'Bitbucket', 'fa fa-bitbucket-square' => 'Bitbucket square', 'fa fa-black-tie' => 'Black tie', 'fa fa-bold' => 'Bold', 'fa fa-bolt' => 'Bolt', 'fa fa-bomb' => 'Bomb', 'fa fa-book' => 'Book', 'fa fa-bookmark' => 'Bookmark', 'fa fa-bookmark-o' => 'Bookmark o', 'fa fa-briefcase' => 'Briefcase', 'fa fa-btc' => 'Btc', 'fa fa-bug' => 'Bug', 'fa fa-building' => 'Building', 'fa fa-building-o' => 'Building o', 'fa fa-bullhorn' => 'Bullhorn', 'fa fa-bullseye' => 'Bullseye', 'fa fa-bus' => 'Bus', 'fa fa-buysellads' => 'Buysellads', 'fa fa-calculator' => 'Calculator', 'fa fa-calendar' => 'Calendar', 'fa fa-calendar-check-o' => 'Calendar check o', 'fa fa-calendar-minus-o' => 'Calendar minus o', 'fa fa-calendar-o' => 'Calendar o', 'fa fa-calendar-plus-o' => 'Calendar plus o', 'fa fa-calendar-times-o' => 'Calendar times o', 'fa fa-camera' => 'Camera', 'fa fa-camera-retro' => 'Camera retro', 'fa fa-car' => 'Car', 'fa fa-caret-down' => 'Caret down', 'fa fa-caret-left' => 'Caret left', 'fa fa-caret-right' => 'Caret right', 'fa fa-caret-square-o-down' => 'Caret square o down', 'fa fa-caret-square-o-left' => 'Caret square o left', 'fa fa-caret-square-o-right' => 'Caret square o right', 'fa fa-caret-square-o-up' => 'Caret square o up', 'fa fa-caret-up' => 'Caret up', 'fa fa-cart-arrow-down' => 'Cart arrow down', 'fa fa-cart-plus' => 'Cart plus', 'fa fa-cc' => 'Cc', 'fa fa-cc-amex' => 'Cc amex', 'fa fa-cc-diners-club' => 'Cc diners club', 'fa fa-cc-discover' => 'Cc discover', 'fa fa-cc-jcb' => 'Cc jcb', 'fa fa-cc-mastercard' => 'Cc mastercard', 'fa fa-cc-paypal' => 'Cc paypal', 'fa fa-cc-stripe' => 'Cc stripe', 'fa fa-cc-visa' => 'Cc visa', 'fa fa-certificate' => 'Certificate', 'fa fa-chain-broken' => 'Chain broken', 'fa fa-check' => 'Check', 'fa fa-check-circle' => 'Check circle', 'fa fa-check-circle-o' => 'Check circle o', 'fa fa-check-square' => 'Check square', 'fa fa-check-square-o' => 'Check square o', 'fa fa-chevron-circle-down' => 'Chevron circle down', 'fa fa-chevron-circle-left' => 'Chevron circle left', 'fa fa-chevron-circle-right' => 'Chevron circle right', 'fa fa-chevron-circle-up' => 'Chevron circle up', 'fa fa-chevron-down' => 'Chevron down', 'fa fa-chevron-left' => 'Chevron left', 'fa fa-chevron-right' => 'Chevron right', 'fa fa-chevron-up' => 'Chevron up', 'fa fa-child' => 'Child', 'fa fa-chrome' => 'Chrome', 'fa fa-circle' => 'Circle', 'fa fa-circle-o' => 'Circle o', 'fa fa-circle-o-notch' => 'Circle o notch', 'fa fa-circle-thin' => 'Circle thin', 'fa fa-clipboard' => 'Clipboard', 'fa fa-clock-o' => 'Clock o', 'fa fa-clone' => 'Clone', 'fa fa-cloud' => 'Cloud', 'fa fa-cloud-download' => 'Cloud download', 'fa fa-cloud-upload' => 'Cloud upload', 'fa fa-code' => 'Code', 'fa fa-code-fork' => 'Code fork', 'fa fa-codepen' => 'Codepen', 'fa fa-coffee' => 'Coffee', 'fa fa-cog' => 'Cog', 'fa fa-cogs' => 'Cogs', 'fa fa-columns' => 'Columns', 'fa fa-comment' => 'Comment', 'fa fa-comment-o' => 'Comment o', 'fa fa-commenting' => 'Commenting', 'fa fa-commenting-o' => 'Commenting o', 'fa fa-comments' => 'Comments', 'fa fa-comments-o' => 'Comments o', 'fa fa-compass' => 'Compass', 'fa fa-compress' => 'Compress', 'fa fa-connectdevelop' => 'Connectdevelop', 'fa fa-contao' => 'Contao', 'fa fa-copyright' => 'Copyright', 'fa fa-creative-commons' => 'Creative commons', 'fa fa-credit-card' => 'Credit card', 'fa fa-crop' => 'Crop', 'fa fa-crosshairs' => 'Crosshairs', 'fa fa-css3' => 'Css3', 'fa fa-cube' => 'Cube', 'fa fa-cubes' => 'Cubes', 'fa fa-cutlery' => 'Cutlery', 'fa fa-dashcube' => 'Dashcube', 'fa fa-database' => 'Database', 'fa fa-delicious' => 'Delicious', 'fa fa-desktop' => 'Desktop', 'fa fa-deviantart' => 'Deviantart', 'fa fa-diamond' => 'Diamond', 'fa fa-digg' => 'Digg', 'fa fa-dot-circle-o' => 'Dot circle o', 'fa fa-download' => 'Download', 'fa fa-dribbble' => 'Dribbble', 'fa fa-dropbox' => 'Dropbox', 'fa fa-drupal' => 'Drupal', 'fa fa-eject' => 'Eject', 'fa fa-ellipsis-h' => 'Ellipsis h', 'fa fa-ellipsis-v' => 'Ellipsis v', 'fa fa-empire' => 'Empire', 'fa fa-envelope' => 'Envelope', 'fa fa-envelope-o' => 'Envelope o', 'fa fa-envelope-square' => 'Envelope square', 'fa fa-eraser' => 'Eraser', 'fa fa-eur' => 'Eur', 'fa fa-exchange' => 'Exchange', 'fa fa-exclamation' => 'Exclamation', 'fa fa-exclamation-circle' => 'Exclamation circle', 'fa fa-exclamation-triangle' => 'Exclamation triangle', 'fa fa-expand' => 'Expand', 'fa fa-expeditedssl' => 'Expeditedssl', 'fa fa-external-link' => 'External link', 'fa fa-external-link-square' => 'External link square', 'fa fa-eye' => 'Eye', 'fa fa-eye-slash' => 'Eye slash', 'fa fa-eyedropper' => 'Eyedropper', 'fa fa-facebook' => 'Facebook', 'fa fa-facebook-official' => 'Facebook official', 'fa fa-facebook-square' => 'Facebook square', 'fa fa-fast-backward' => 'Fast backward', 'fa fa-fast-forward' => 'Fast forward', 'fa fa-fax' => 'Fax', 'fa fa-female' => 'Female', 'fa fa-fighter-jet' => 'Fighter jet', 'fa fa-file' => 'File', 'fa fa-file-archive-o' => 'File archive o', 'fa fa-file-audio-o' => 'File audio o', 'fa fa-file-code-o' => 'File code o', 'fa fa-file-excel-o' => 'File excel o', 'fa fa-file-image-o' => 'File image o', 'fa fa-file-o' => 'File o', 'fa fa-file-pdf-o' => 'File pdf o', 'fa fa-file-powerpoint-o' => 'File powerpoint o', 'fa fa-file-text' => 'File text', 'fa fa-file-text-o' => 'File text o', 'fa fa-file-video-o' => 'File video o', 'fa fa-file-word-o' => 'File word o', 'fa fa-files-o' => 'Files o', 'fa fa-film' => 'Film', 'fa fa-filter' => 'Filter', 'fa fa-fire' => 'Fire', 'fa fa-fire-extinguisher' => 'Fire extinguisher', 'fa fa-firefox' => 'Firefox', 'fa fa-flag' => 'Flag', 'fa fa-flag-checkered' => 'Flag checkered', 'fa fa-flag-o' => 'Flag o', 'fa fa-flask' => 'Flask', 'fa fa-flickr' => 'Flickr', 'fa fa-floppy-o' => 'Floppy o', 'fa fa-folder' => 'Folder', 'fa fa-folder-o' => 'Folder o', 'fa fa-folder-open' => 'Folder open', 'fa fa-folder-open-o' => 'Folder open o', 'fa fa-font' => 'Font', 'fa fa-fonticons' => 'Fonticons', 'fa fa-forumbee' => 'Forumbee', 'fa fa-forward' => 'Forward', 'fa fa-foursquare' => 'Foursquare', 'fa fa-frown-o' => 'Frown o', 'fa fa-futbol-o' => 'Futbol o', 'fa fa-gamepad' => 'Gamepad', 'fa fa-gavel' => 'Gavel', 'fa fa-gbp' => 'Gbp', 'fa fa-genderless' => 'Genderless', 'fa fa-get-pocket' => 'Get pocket', 'fa fa-gg' => 'Gg', 'fa fa-gg-circle' => 'Gg circle', 'fa fa-gift' => 'Gift', 'fa fa-git' => 'Git', 'fa fa-git-square' => 'Git square', 'fa fa-github' => 'Github', 'fa fa-github-alt' => 'Github alt', 'fa fa-github-square' => 'Github square', 'fa fa-glass' => 'Glass', 'fa fa-globe' => 'Globe', 'fa fa-google' => 'Google', 'fa fa-google-plus' => 'Google plus', 'fa fa-google-plus-square' => 'Google plus square', 'fa fa-google-wallet' => 'Google wallet', 'fa fa-graduation-cap' => 'Graduation cap', 'fa fa-gratipay' => 'Gratipay', 'fa fa-h-square' => 'H square', 'fa fa-hacker-news' => 'Hacker news', 'fa fa-hand-lizard-o' => 'Hand lizard o', 'fa fa-hand-o-down' => 'Hand o down', 'fa fa-hand-o-left' => 'Hand o left', 'fa fa-hand-o-right' => 'Hand o right', 'fa fa-hand-o-up' => 'Hand o up', 'fa fa-hand-paper-o' => 'Hand paper o', 'fa fa-hand-peace-o' => 'Hand peace o', 'fa fa-hand-pointer-o' => 'Hand pointer o', 'fa fa-hand-rock-o' => 'Hand rock o', 'fa fa-hand-scissors-o' => 'Hand scissors o', 'fa fa-hand-spock-o' => 'Hand spock o', 'fa fa-hdd-o' => 'Hdd o', 'fa fa-header' => 'Header', 'fa fa-headphones' => 'Headphones', 'fa fa-heart' => 'Heart', 'fa fa-heart-o' => 'Heart o', 'fa fa-heartbeat' => 'Heartbeat', 'fa fa-history' => 'History', 'fa fa-home' => 'Home', 'fa fa-hospital-o' => 'Hospital o', 'fa fa-hourglass' => 'Hourglass', 'fa fa-hourglass-end' => 'Hourglass end', 'fa fa-hourglass-half' => 'Hourglass half', 'fa fa-hourglass-o' => 'Hourglass o', 'fa fa-hourglass-start' => 'Hourglass start', 'fa fa-houzz' => 'Houzz', 'fa fa-html5' => 'Html5', 'fa fa-i-cursor' => 'I cursor', 'fa fa-ils' => 'Ils', 'fa fa-inbox' => 'Inbox', 'fa fa-indent' => 'Indent', 'fa fa-industry' => 'Industry', 'fa fa-info' => 'Info', 'fa fa-info-circle' => 'Info circle', 'fa fa-inr' => 'Inr', 'fa fa-instagram' => 'Instagram', 'fa fa-internet-explorer' => 'Internet explorer', 'fa fa-ioxhost' => 'Ioxhost', 'fa fa-italic' => 'Italic', 'fa fa-joomla' => 'Joomla', 'fa fa-jpy' => 'Jpy', 'fa fa-jsfiddle' => 'Jsfiddle', 'fa fa-key' => 'Key', 'fa fa-keyboard-o' => 'Keyboard o', 'fa fa-krw' => 'Krw', 'fa fa-language' => 'Language', 'fa fa-laptop' => 'Laptop', 'fa fa-lastfm' => 'Lastfm', 'fa fa-lastfm-square' => 'Lastfm square', 'fa fa-leaf' => 'Leaf', 'fa fa-leanpub' => 'Leanpub', 'fa fa-lemon-o' => 'Lemon o', 'fa fa-level-down' => 'Level down', 'fa fa-level-up' => 'Level up', 'fa fa-life-ring' => 'Life ring', 'fa fa-lightbulb-o' => 'Lightbulb o', 'fa fa-line-chart' => 'Line chart', 'fa fa-link' => 'Link', 'fa fa-linkedin' => 'Linkedin', 'fa fa-linkedin-square' => 'Linkedin square', 'fa fa-linux' => 'Linux', 'fa fa-list' => 'List', 'fa fa-list-alt' => 'List alt', 'fa fa-list-ol' => 'List ol', 'fa fa-list-ul' => 'List ul', 'fa fa-location-arrow' => 'Location arrow', 'fa fa-lock' => 'Lock', 'fa fa-long-arrow-down' => 'Long arrow down', 'fa fa-long-arrow-left' => 'Long arrow left', 'fa fa-long-arrow-right' => 'Long arrow right', 'fa fa-long-arrow-up' => 'Long arrow up', 'fa fa-magic' => 'Magic', 'fa fa-magnet' => 'Magnet', 'fa fa-male' => 'Male', 'fa fa-map' => 'Map', 'fa fa-map-marker' => 'Map marker', 'fa fa-map-o' => 'Map o', 'fa fa-map-pin' => 'Map pin', 'fa fa-map-signs' => 'Map signs', 'fa fa-mars' => 'Mars', 'fa fa-mars-double' => 'Mars double', 'fa fa-mars-stroke' => 'Mars stroke', 'fa fa-mars-stroke-h' => 'Mars stroke h', 'fa fa-mars-stroke-v' => 'Mars stroke v', 'fa fa-maxcdn' => 'Maxcdn', 'fa fa-meanpath' => 'Meanpath', 'fa fa-medium' => 'Medium', 'fa fa-medkit' => 'Medkit', 'fa fa-meh-o' => 'Meh o', 'fa fa-mercury' => 'Mercury', 'fa fa-microphone' => 'Microphone', 'fa fa-microphone-slash' => 'Microphone slash', 'fa fa-minus' => 'Minus', 'fa fa-minus-circle' => 'Minus circle', 'fa fa-minus-square' => 'Minus square', 'fa fa-minus-square-o' => 'Minus square o', 'fa fa-mobile' => 'Mobile', 'fa fa-money' => 'Money', 'fa fa-moon-o' => 'Moon o', 'fa fa-motorcycle' => 'Motorcycle', 'fa fa-mouse-pointer' => 'Mouse pointer', 'fa fa-music' => 'Music', 'fa fa-neuter' => 'Neuter', 'fa fa-newspaper-o' => 'Newspaper o', 'fa fa-object-group' => 'Object group', 'fa fa-object-ungroup' => 'Object ungroup', 'fa fa-odnoklassniki' => 'Odnoklassniki', 'fa fa-odnoklassniki-square' => 'Odnoklassniki square', 'fa fa-opencart' => 'Opencart', 'fa fa-openid' => 'Openid', 'fa fa-opera' => 'Opera', 'fa fa-optin-monster' => 'Optin monster', 'fa fa-outdent' => 'Outdent', 'fa fa-pagelines' => 'Pagelines', 'fa fa-paint-brush' => 'Paint brush', 'fa fa-paper-plane' => 'Paper plane', 'fa fa-paper-plane-o' => 'Paper plane o', 'fa fa-paperclip' => 'Paperclip', 'fa fa-paragraph' => 'Paragraph', 'fa fa-pause' => 'Pause', 'fa fa-paw' => 'Paw', 'fa fa-paypal' => 'Paypal', 'fa fa-pencil' => 'Pencil', 'fa fa-pencil-square' => 'Pencil square', 'fa fa-pencil-square-o' => 'Pencil square o', 'fa fa-phone' => 'Phone', 'fa fa-phone-square' => 'Phone square', 'fa fa-picture-o' => 'Picture o', 'fa fa-pie-chart' => 'Pie chart', 'fa fa-pied-piper' => 'Pied piper', 'fa fa-pied-piper-alt' => 'Pied piper alt', 'fa fa-pinterest' => 'Pinterest', 'fa fa-pinterest-p' => 'Pinterest p', 'fa fa-pinterest-square' => 'Pinterest square', 'fa fa-plane' => 'Plane', 'fa fa-play' => 'Play', 'fa fa-play-circle' => 'Play circle', 'fa fa-play-circle-o' => 'Play circle o', 'fa fa-plug' => 'Plug', 'fa fa-plus' => 'Plus', 'fa fa-plus-circle' => 'Plus circle', 'fa fa-plus-square' => 'Plus square', 'fa fa-plus-square-o' => 'Plus square o', 'fa fa-power-off' => 'Power off', 'fa fa-print' => 'Print', 'fa fa-puzzle-piece' => 'Puzzle piece', 'fa fa-qq' => 'Qq', 'fa fa-qrcode' => 'Qrcode', 'fa fa-question' => 'Question', 'fa fa-question-circle' => 'Question circle', 'fa fa-quote-left' => 'Quote left', 'fa fa-quote-right' => 'Quote right', 'fa fa-random' => 'Random', 'fa fa-rebel' => 'Rebel', 'fa fa-recycle' => 'Recycle', 'fa fa-reddit' => 'Reddit', 'fa fa-reddit-square' => 'Reddit square', 'fa fa-refresh' => 'Refresh', 'fa fa-registered' => 'Registered', 'fa fa-renren' => 'Renren', 'fa fa-repeat' => 'Repeat', 'fa fa-reply' => 'Reply', 'fa fa-reply-all' => 'Reply all', 'fa fa-retweet' => 'Retweet', 'fa fa-road' => 'Road', 'fa fa-rocket' => 'Rocket', 'fa fa-rss' => 'Rss', 'fa fa-rss-square' => 'Rss square', 'fa fa-rub' => 'Rub', 'fa fa-safari' => 'Safari', 'fa fa-scissors' => 'Scissors', 'fa fa-search' => 'Search', 'fa fa-search-minus' => 'Search minus', 'fa fa-search-plus' => 'Search plus', 'fa fa-sellsy' => 'Sellsy', 'fa fa-server' => 'Server', 'fa fa-share' => 'Share', 'fa fa-share-alt' => 'Share alt', 'fa fa-share-alt-square' => 'Share alt square', 'fa fa-share-square' => 'Share square', 'fa fa-share-square-o' => 'Share square o', 'fa fa-shield' => 'Shield', 'fa fa-ship' => 'Ship', 'fa fa-shirtsinbulk' => 'Shirtsinbulk', 'fa fa-shopping-cart' => 'Shopping cart', 'fa fa-sign-in' => 'Sign in', 'fa fa-sign-out' => 'Sign out', 'fa fa-signal' => 'Signal', 'fa fa-simplybuilt' => 'Simplybuilt', 'fa fa-sitemap' => 'Sitemap', 'fa fa-skyatlas' => 'Skyatlas', 'fa fa-skype' => 'Skype', 'fa fa-slack' => 'Slack', 'fa fa-sliders' => 'Sliders', 'fa fa-slideshare' => 'Slideshare', 'fa fa-smile-o' => 'Smile o', 'fa fa-sort' => 'Sort', 'fa fa-sort-alpha-asc' => 'Sort alpha asc', 'fa fa-sort-alpha-desc' => 'Sort alpha desc', 'fa fa-sort-amount-asc' => 'Sort amount asc', 'fa fa-sort-amount-desc' => 'Sort amount desc', 'fa fa-sort-asc' => 'Sort asc', 'fa fa-sort-desc' => 'Sort desc', 'fa fa-sort-numeric-asc' => 'Sort numeric asc', 'fa fa-sort-numeric-desc' => 'Sort numeric desc', 'fa fa-soundcloud' => 'Soundcloud', 'fa fa-space-shuttle' => 'Space shuttle', 'fa fa-spinner' => 'Spinner', 'fa fa-spoon' => 'Spoon', 'fa fa-spotify' => 'Spotify', 'fa fa-square' => 'Square', 'fa fa-square-o' => 'Square o', 'fa fa-stack-exchange' => 'Stack exchange', 'fa fa-stack-overflow' => 'Stack overflow', 'fa fa-star' => 'Star', 'fa fa-star-half' => 'Star half', 'fa fa-star-half-o' => 'Star half o', 'fa fa-star-o' => 'Star o', 'fa fa-steam' => 'Steam', 'fa fa-steam-square' => 'Steam square', 'fa fa-step-backward' => 'Step backward', 'fa fa-step-forward' => 'Step forward', 'fa fa-stethoscope' => 'Stethoscope', 'fa fa-sticky-note' => 'Sticky note', 'fa fa-sticky-note-o' => 'Sticky note o', 'fa fa-stop' => 'Stop', 'fa fa-street-view' => 'Street view', 'fa fa-strikethrough' => 'Strikethrough', 'fa fa-stumbleupon' => 'Stumbleupon', 'fa fa-stumbleupon-circle' => 'Stumbleupon circle', 'fa fa-subscript' => 'Subscript', 'fa fa-subway' => 'Subway', 'fa fa-suitcase' => 'Suitcase', 'fa fa-sun-o' => 'Sun o', 'fa fa-superscript' => 'Superscript', 'fa fa-table' => 'Table', 'fa fa-tablet' => 'Tablet', 'fa fa-tachometer' => 'Tachometer', 'fa fa-tag' => 'Tag', 'fa fa-tags' => 'Tags', 'fa fa-tasks' => 'Tasks', 'fa fa-taxi' => 'Taxi', 'fa fa-television' => 'Television', 'fa fa-tencent-weibo' => 'Tencent weibo', 'fa fa-terminal' => 'Terminal', 'fa fa-text-height' => 'Text height', 'fa fa-text-width' => 'Text width', 'fa fa-th' => 'Th', 'fa fa-th-large' => 'Th large', 'fa fa-th-list' => 'Th list', 'fa fa-thumb-tack' => 'Thumb tack', 'fa fa-thumbs-down' => 'Thumbs down', 'fa fa-thumbs-o-down' => 'Thumbs o down', 'fa fa-thumbs-o-up' => 'Thumbs o up', 'fa fa-thumbs-up' => 'Thumbs up', 'fa fa-ticket' => 'Ticket', 'fa fa-times' => 'Times', 'fa fa-times-circle' => 'Times circle', 'fa fa-times-circle-o' => 'Times circle o', 'fa fa-tint' => 'Tint', 'fa fa-toggle-off' => 'Toggle off', 'fa fa-toggle-on' => 'Toggle on', 'fa fa-trademark' => 'Trademark', 'fa fa-train' => 'Train', 'fa fa-transgender' => 'Transgender', 'fa fa-transgender-alt' => 'Transgender alt', 'fa fa-trash' => 'Trash', 'fa fa-trash-o' => 'Trash o', 'fa fa-tree' => 'Tree', 'fa fa-trello' => 'Trello', 'fa fa-tripadvisor' => 'Tripadvisor', 'fa fa-trophy' => 'Trophy', 'fa fa-truck' => 'Truck', 'fa fa-try' => 'Try', 'fa fa-tty' => 'Tty', 'fa fa-tumblr' => 'Tumblr', 'fa fa-tumblr-square' => 'Tumblr square', 'fa fa-twitch' => 'Twitch', 'fa fa-twitter' => 'Twitter', 'fa fa-twitter-square' => 'Twitter square', 'fa fa-umbrella' => 'Umbrella', 'fa fa-underline' => 'Underline', 'fa fa-undo' => 'Undo', 'fa fa-university' => 'University', 'fa fa-unlock' => 'Unlock', 'fa fa-unlock-alt' => 'Unlock alt', 'fa fa-upload' => 'Upload', 'fa fa-usd' => 'Usd', 'fa fa-user' => 'User', 'fa fa-user-md' => 'User md', 'fa fa-user-plus' => 'User plus', 'fa fa-user-secret' => 'User secret', 'fa fa-user-times' => 'User times', 'fa fa-users' => 'Users', 'fa fa-venus' => 'Venus', 'fa fa-venus-double' => 'Venus double', 'fa fa-venus-mars' => 'Venus mars', 'fa fa-viacoin' => 'Viacoin', 'fa fa-video-camera' => 'Video camera', 'fa fa-vimeo' => 'Vimeo', 'fa fa-vimeo-square' => 'Vimeo square', 'fa fa-vine' => 'Vine', 'fa fa-vk' => 'Vk', 'fa fa-volume-down' => 'Volume down', 'fa fa-volume-off' => 'Volume off', 'fa fa-volume-up' => 'Volume up', 'fa fa-weibo' => 'Weibo', 'fa fa-weixin' => 'Weixin', 'fa fa-whatsapp' => 'Whatsapp', 'fa fa-wheelchair' => 'Wheelchair', 'fa fa-wifi' => 'Wifi', 'fa fa-wikipedia-w' => 'Wikipedia w', 'fa fa-windows' => 'Windows', 'fa fa-wordpress' => 'Wordpress', 'fa fa-wrench' => 'Wrench', 'fa fa-xing' => 'Xing', 'fa fa-xing-square' => 'Xing square', 'fa fa-y-combinator' => 'Y combinator', 'fa fa-yahoo' => 'Yahoo', 'fa fa-yelp' => 'Yelp', 'fa fa-youtube' => 'Youtube', 'fa fa-youtube-play' => 'Youtube play', 'fa fa-youtube-square' => 'Youtube square' );

							foreach ( $font_awesome_icons as $key => $val ) { ?>

								<input id="udb-icon-<?php echo esc_attr( $key ); ?>" class="udb-radio" type="radio" name="udb_icon" value="<?php echo esc_attr( $key ); ?>" <?php checked( $udb_stored_meta, $key ); ?> />
								<label for="udb-icon-<?php echo esc_attr( $key ); ?>" class="udb-icon <?php echo esc_attr( $key ); ?>" title="<?php echo esc_attr( $val ); ?>">
									<span><?php echo $key; ?></span>
								</label>

							<?php } ?>					
						</li>
					</ul>

				</div>

			</div>

		</div>

		<div class="udb-metabox-section">

			<h3><?php _e( 'Link', 'ultimate-dashboard' ); ?></h3>

			<?php

			$udb_stored_meta = get_post_meta( $post->ID );

			if ( !isset( $udb_stored_meta['udb_link_target'][0] ) ) { 
				$udb_stored_meta['udb_link_target'][0] = '_self'; 
			}

			?>

			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="udb-link"><?php _e( 'URL', 'ultimate-dashboard' ); ?></label><br>
							<span class="description"><?php _e( "Absolute URL's (incl. http:// or https://) or relative URL's (./post-new.php) are allowed.", "ultimatedashboard" ); ?></span>
						</th>
						<td>
							<input id="udb-link" type="text" name="udb_link" value="<?php if( ! empty ( $udb_stored_meta['udb_link'][0] ) ) echo esc_attr( $udb_stored_meta['udb_link'][0] ); ?>" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="udb-link-target"><?php _e( 'Target', 'ultimate-dashboard' ); ?><br>
							<span class="description"><?php _e( 'Open link in a new tab.', 'ultimate-dashboard' ); ?></span>
							</label>
						</th>
						<td>
							<input id="udb-link-target" type="checkbox" name="udb_link_target" <?php checked( $udb_stored_meta['udb_link_target'][0], '_blank' ); ?> />
						</td>
					</tr>
				</tbody>
			</table>

		</div>

		<?php do_action( 'udb_tooltip' ); ?>

	</div>


<?php }

add_action( 'udb_metabox_widgets', 'udb_icon_widget' );