<?php
/**
 * Style tags for instant preview.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Color_Helper;

$colors = array(
	'menu_item_color'        => udb_is_pro_active() ? '#ffffff' : '#f0f0f1',
	'accent_color'           => udb_is_pro_active() ? '#0073AA' : '#2271b1',
	'admin_bar_bg_color'     => udb_is_pro_active() ? '#232931' : '#1d2327',
	'admin_menu_bg_color'    => udb_is_pro_active() ? '#2E3640' : '#1d2327',
	'admin_submenu_bg_color' => udb_is_pro_active() ? '#38404B' : '#2c3338',
);

$colors = apply_filters( 'udb_branding_colors', $colors );

$menu_item_color        = $colors['menu_item_color'];
$accent_color           = $colors['accent_color'];
$admin_bar_bg_color     = $colors['admin_bar_bg_color'];
$admin_menu_bg_color    = $colors['admin_menu_bg_color'];
$admin_submenu_bg_color = $colors['admin_submenu_bg_color'];

$color_helper = new Color_Helper();
?>

<?php // This part is based on a WordPress color scheme. ?>

<?php // Links. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	a {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	a:hover, a:active, a:focus {
		color: <?php $this->print_rgba_from_hex( $accent_color, 0.7 ); ?>;
	}
</style>

<?php // Forms. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background">
	input[type=radio]:checked::before {
		background: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.wp-core-ui input[type="reset"]:hover,
	.wp-core-ui input[type="reset"]:active {
		color: <?php $this->print_rgba_from_hex( $accent_color, 0.7 ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="border-color, box-shadow" data-udb-box-shadow-value="0 0 0 1px {box_shadow_value}">
	input[type="text"]:focus,
	input[type="password"]:focus,
	input[type="color"]:focus,
	input[type="date"]:focus,
	input[type="datetime"]:focus,
	input[type="datetime-local"]:focus,
	input[type="email"]:focus,
	input[type="month"]:focus,
	input[type="number"]:focus,
	input[type="search"]:focus,
	input[type="tel"]:focus,
	input[type="text"]:focus,
	input[type="time"]:focus,
	input[type="url"]:focus,
	input[type="week"]:focus,
	input[type="checkbox"]:focus,
	input[type="radio"]:focus,
	select:focus,
	textarea:focus {
		border-color: <?php echo esc_attr( $accent_color ); ?>;
		box-shadow: 0 0 0 1px <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php // Core UI. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="border-color, box-shadow" data-udb-box-shadow-value="inset 0 2px 5px -3px {box_shadow_value}">
	.wp-core-ui .button.active,
	.wp-core-ui .button.active:focus,
	.wp-core-ui .button.active:hover {
		border-color: <?php echo esc_attr( $accent_color ); ?>;
		box-shadow: inset 0 2px 5px -3px <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background, border-color">
	.wp-core-ui .button-primary {
		background: <?php echo esc_attr( $accent_color ); ?>;
		border-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background, border-color">
	.wp-core-ui .button-primary:hover, .wp-core-ui .button-primary:focus {
		background: <?php $this->print_rgba_from_hex( $accent_color, 0.85 ); ?>;
		border-color: <?php $this->print_rgba_from_hex( $accent_color, 0.85 ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="box-shadow" data-udb-box-shadow-value="0 0 0 1px #fff, 0 0 0 3px {box_shadow_value}">
	.wp-core-ui .button-primary:focus {
		box-shadow: 0 0 0 1px #fff, 0 0 0 3px <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background, border-color">
.wp-core-ui .button-primary:active {
	background: <?php echo esc_attr( $accent_color ); ?>;
	border-color: <?php echo esc_attr( $accent_color ); ?>;
}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background, border-color">
	.wp-core-ui .button-primary.active, .wp-core-ui .button-primary.active:focus, .wp-core-ui .button-primary.active:hover {
		background: <?php $this->print_rgba_from_hex( $accent_color, 0.7 ); ?>;
		border-color: <?php $this->print_rgba_from_hex( $accent_color, 0.7 ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background, border-color">
.wp-core-ui .button-primary[disabled], .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary.button-primary-disabled, .wp-core-ui .button-primary.disabled {
	background: <?php echo esc_attr( $accent_color ); ?> !important;
	border-color: <?php echo esc_attr( $accent_color ); ?> !important;
}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="border-color">
	.wp-core-ui .button-group > .button.active {
		border-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color">
	.wp-core-ui .wp-ui-primary {
		background-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.wp-core-ui .wp-ui-text-primary {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color">
.wp-core-ui .wp-ui-highlight {
	background-color: <?php echo esc_attr( $accent_color ); ?>;
}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.wp-core-ui .wp-ui-text-highlight {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color">
	.wp-core-ui .wp-ui-notification {
		background-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.wp-core-ui .wp-ui-text-notification {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.wp-core-ui .wp-ui-text-icon {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php // List tables. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color">
	.wrap .add-new-h2:hover,
	.wrap .page-title-action:hover {
		background-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.view-switch a.current:before {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.view-switch a:hover:before {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php // Admin Menu. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-admin-menu-bg-color="background">
	#adminmenuback,
	#adminmenuwrap,
	#adminmenu {
		background: <?php echo esc_attr( $admin_menu_bg_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu a {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu div.wp-menu-image:before {
		color: <?php $this->print_rgba_from_hex( $menu_item_color, 0.7 ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-accent-color="background-color">
	#adminmenu a:hover,
	#adminmenu li.menu-top:hover,
	#adminmenu li.opensub > a.menu-top,
	#adminmenu li > a.menu-top:focus {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu li.menu-top:hover div.wp-menu-image:before,
	#adminmenu li.opensub > a.menu-top div.wp-menu-image:before {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<?php // Admin Menu: submenu. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-admin-submenu-bg-color="background">
	#adminmenu .wp-submenu,
	#adminmenu .wp-has-current-submenu .wp-submenu,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu,
	.folded #adminmenu .wp-has-current-submenu .wp-submenu,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu {
		background: <?php echo esc_attr( $admin_submenu_bg_color ); ?>;
	}
</style>

<?php if ( is_rtl() ) { ?>

	<style type="text/udb" class="udb-instant-preview" data-udb-prop-admin-submenu-bg-color="border-left-color">
		#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
			border-left-color: <?php echo esc_attr( $admin_submenu_bg_color ); ?>;
		}
	</style>

<?php } else { ?>

	<style type="text/udb" class="udb-instant-preview" data-udb-prop-admin-submenu-bg-color="border-right-color">
		#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
			border-right-color: <?php echo esc_attr( $admin_submenu_bg_color ); ?>;
		}
	</style>

<?php } ?>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-accent-color="background">
	#adminmenu li.current a.menu-top,
	#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
	#adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head,
	.folded #adminmenu li.current.menu-top {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu .wp-submenu .wp-submenu-head {
		color: <?php $this->print_rgba_from_hex( $menu_item_color, 0.7 ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu .wp-submenu a,
	#adminmenu .wp-has-current-submenu .wp-submenu a,
	.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
		color: <?php $this->print_rgba_from_hex( $menu_item_color, 0.7 ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover,
	#adminmenu .wp-has-current-submenu .wp-submenu a:focus,
	#adminmenu .wp-has-current-submenu .wp-submenu a:hover,
	.folded #adminmenu .wp-has-current-submenu .wp-submenu a:focus,
	.folded #adminmenu .wp-has-current-submenu .wp-submenu a:hover,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:focus,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:hover,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:focus,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:hover {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<?php // Admin Menu: current. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu .wp-submenu li.current a,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu .wp-submenu li.current a:hover, #adminmenu .wp-submenu li.current a:focus,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:hover,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:focus,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
	#adminmenu a.current:hover div.wp-menu-image:before,
	#adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before,
	#adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before,
	#adminmenu li:hover div.wp-menu-image:before,
	#adminmenu li a:focus div.wp-menu-image:before,
	#adminmenu li.opensub div.wp-menu-image:before,
	.ie8 #adminmenu li.opensub div.wp-menu-image:before {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<?php // Admin Menu: bubble. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-accent-color="background">
	#adminmenu .awaiting-mod,
	#adminmenu .update-plugins {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-admin-submenu-bg-color="background">
#adminmenu li.current a .awaiting-mod,
#adminmenu li a.wp-has-current-submenu .update-plugins,
#adminmenu li:hover a .awaiting-mod,
#adminmenu li.menu-top:hover > a .update-plugins {
	color: <?php echo esc_attr( $menu_item_color ); ?>;
	background: <?php echo esc_attr( $admin_submenu_bg_color ); ?>;
}
</style>

<?php // Admin Bar. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-admin-bar-bg-color="background">
	#wpadminbar {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background: <?php echo esc_attr( $admin_bar_bg_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar .ab-item,
	#wpadminbar a.ab-item,
	#wpadminbar > #wp-toolbar span.ab-label,
	#wpadminbar > #wp-toolbar span.noticon {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar .ab-icon,
	#wpadminbar .ab-icon:before,
	#wpadminbar .ab-item:before,
	#wpadminbar .ab-item:after {
		color: <?php $this->print_rgba_from_hex( $menu_item_color, 0.7 ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-admin-submenu-bg-color="background">
	#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,
	#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus,
	#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
	#wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
	#wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background: <?php echo esc_attr( $admin_submenu_bg_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label,
	#wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label,
	#wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar:not(.mobile) li:hover .ab-icon:before,
	#wpadminbar:not(.mobile) li:hover .ab-item:before,
	#wpadminbar:not(.mobile) li:hover .ab-item:after,
	#wpadminbar:not(.mobile) li:hover #adminbarsearch:before {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<?php // Admin Bar: submenu. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-admin-submenu-bg-color="background">
	#wpadminbar .menupop .ab-sub-wrapper {
		background: <?php echo esc_attr( $admin_submenu_bg_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-admin-submenu-bg-color="background">
	#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
	#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
		background: <?php echo esc_attr( $admin_submenu_bg_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar .ab-submenu .ab-item,
	#wpadminbar .quicklinks .menupop ul li a,
	#wpadminbar .quicklinks .menupop.hover ul li a,
	#wpadminbar.nojs .quicklinks .menupop:hover ul li a {
		color: <?php $this->print_rgba_from_hex( $menu_item_color, 0.7 ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar .quicklinks li .blavatar,
	#wpadminbar .menupop .menupop > .ab-item:before {
		color: <?php $this->print_rgba_from_hex( $menu_item_color, 0.7 ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar .quicklinks .menupop ul li a:hover,
	#wpadminbar .quicklinks .menupop ul li a:focus,
	#wpadminbar .quicklinks .menupop ul li a:hover strong,
	#wpadminbar .quicklinks .menupop ul li a:focus strong,
	#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a,
	#wpadminbar .quicklinks .menupop.hover ul li a:hover,
	#wpadminbar .quicklinks .menupop.hover ul li a:focus,
	#wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover,
	#wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus,
	#wpadminbar li:hover .ab-icon:before,
	#wpadminbar li:hover .ab-item:before,
	#wpadminbar li a:focus .ab-icon:before,
	#wpadminbar li .ab-item:focus:before,
	#wpadminbar li .ab-item:focus .ab-icon:before,
	#wpadminbar li.hover .ab-icon:before,
	#wpadminbar li.hover .ab-item:before,
	#wpadminbar li:hover #adminbarsearch:before,
	#wpadminbar li #adminbarsearch.adminbar-focused:before {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar .quicklinks li a:hover .blavatar,
	#wpadminbar .quicklinks li a:focus .blavatar,
	#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a .blavatar,
	#wpadminbar .menupop .menupop > .ab-item:hover:before,
	#wpadminbar.mobile .quicklinks .ab-icon:before,
	#wpadminbar.mobile .quicklinks .ab-item:before {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	#wpadminbar.mobile .quicklinks .hover .ab-icon:before,
	#wpadminbar.mobile .quicklinks .hover .ab-item:before {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php // Admin Bar: search. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	#wpadminbar #adminbarsearch:before {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-accent-color="background">
	#wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php // Admin Bar: recovery mode. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-accent-color="background-color">
	#wpadminbar #wp-admin-bar-recovery-mode {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar #wp-admin-bar-recovery-mode .ab-item,
	#wpadminbar #wp-admin-bar-recovery-mode a.ab-item {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar .ab-top-menu > #wp-admin-bar-recovery-mode.hover > .ab-item,
	#wpadminbar.nojq .quicklinks .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus,
	#wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode:hover > .ab-item,
	#wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<?php // Admin Bar: my account. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar #wp-admin-bar-user-info .display-name {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar #wp-admin-bar-user-info a:hover .display-name {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar #wp-admin-bar-user-info .username {
		color: <?php $this->print_rgba_from_hex( $menu_item_color, 0.7 ); ?>;
	}
</style>

<?php // Pointers. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color, border-color">
.wp-pointer .wp-pointer-content h3 {
	background-color: <?php echo esc_attr( $accent_color ); ?>;
	border-color: <?php echo esc_attr( $accent_color ); ?>;
}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.wp-pointer .wp-pointer-content h3:before {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="border-bottom-color">
	.wp-pointer.wp-pointer-top .wp-pointer-arrow,
	.wp-pointer.wp-pointer-top .wp-pointer-arrow-inner,
	.wp-pointer.wp-pointer-undefined .wp-pointer-arrow,
	.wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner {
		border-bottom-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php // Media. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color">
	.media-item .bar,
	.media-progress-bar div {
		background-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="box-shadow" data-udb-box-shadow-value="inset 0 0 0 3px #fff, inset 0 0 0 7px {box_shadow_value}">
	.details.attachment {
		box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color, box-shadow" data-udb-box-shadow-value="0 0 0 1px #fff, 0 0 0 2px {box_shadow_value}">
.attachment.details .check {
	background-color: <?php echo esc_attr( $accent_color ); ?>;
	box-shadow: 0 0 0 1px #fff, 0 0 0 2px <?php echo esc_attr( $accent_color ); ?>;
}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="box-shadow" data-udb-box-shadow-value="0 0 0 1px #fff, 0 0 0 3px {box_shadow_value}">
	.media-selection .attachment.selection.details .thumbnail {
		box-shadow: 0 0 0 1px #fff, 0 0 0 3px <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php // Themes. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background">
	.theme-browser .theme.active .theme-name,
	.theme-browser .theme.add-new-theme a:hover:after,
	.theme-browser .theme.add-new-theme a:focus:after {
		background: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.theme-browser .theme.add-new-theme a:hover span:after,
	.theme-browser .theme.add-new-theme a:focus span:after {
		color: <?php echo esc_attr( $accent_color ); ?>;
}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="border-bottom-color">
	.theme-section.current,
	.theme-filter.current {
		border-bottom-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color">
body.more-filters-opened .more-filters {
	background-color: <?php echo esc_attr( $accent_color ); ?>;
}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color">
	body.more-filters-opened .more-filters:hover,
	body.more-filters-opened .more-filters:focus {
		background-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php // Widgets. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background-color">
.widgets-chooser li.widgets-chooser-selected {
	background-color: <?php echo esc_attr( $accent_color ); ?>;
}
</style>

<?php // Responsive Component. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background">
	.wp-responsive-open div#wp-responsive-toggle a {
		background: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background">
	.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
		background: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="color">
	.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php // TinyMCE. ?>
<style type="text/udb" class="udb-instant-preview" data-udb-prop-accent-color="background">
	.mce-container.mce-menu .mce-menu-item:hover,
	.mce-container.mce-menu .mce-menu-item.mce-selected,
	.mce-container.mce-menu .mce-menu-item:focus,
	.mce-container.mce-menu .mce-menu-item-normal.mce-active,
	.mce-container.mce-menu .mce-menu-item-preview.mce-active {
		background: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<?php do_action( 'udb_instant_preview', $colors ); ?>
