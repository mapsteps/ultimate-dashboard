<?php
$accent_color           = '#0073AA';
$admin_bar_bg_color     = '#232931';
$admin_menu_bg_color    = '#2E3640';
$admin_submenu_bg_color = '#38404B';
$menu_item_active_color = $accent_color;
$menu_item_color        = '#ffffff';

$accent_color_hex = $accent_color;
$accent_color_hex = str_replace( '#', '', $accent_color_hex );

if ( 3 === strlen( $accent_color_hex ) ) {
	$accent_color_r = hexdec( substr( $accent_color_hex, 0, 1 ) . substr( $accent_color_hex, 0, 1 ) );
	$accent_color_g = hexdec( substr( $accent_color_hex, 1, 1 ) . substr( $accent_color_hex, 1, 1 ) );
	$accent_color_b = hexdec( substr( $accent_color_hex, 2, 1 ) . substr( $accent_color_hex, 2, 1 ) );
} else {
	$accent_color_r = hexdec( substr( $accent_color_hex, 0, 2 ) );
	$accent_color_g = hexdec( substr( $accent_color_hex, 2, 2 ) );
	$accent_color_b = hexdec( substr( $accent_color_hex, 4, 2 ) );
}

$accent_color_rgb = array( $accent_color_r, $accent_color_g, $accent_color_b );
?>

<style class="udb-instant-preview" data-udb-prop-accent-color="color">
	a {
		color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-accent-color="color">
	a:hover, a:active, a:focus {
		color: rgba(<?php echo esc_attr( $accent_color_rgb[0] ); ?>,<?php echo esc_attr( $accent_color_rgb[1] ); ?>, <?php echo esc_attr( $accent_color_rgb[2] ); ?>, .7);
	}
</style>

<style class="udb-instant-preview" data-udb-prop-accent-color="border-color, box-shadow" data-udb-box-shadow-value="0 0 0 1px {box_shadow_value}">
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

<style class="udb-instant-preview" data-udb-prop-accent-color="background, border-color">
	.wp-core-ui .button-primary {
		background: <?php echo esc_attr( $accent_color ); ?>;
		border-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-accent-color="background, border-color">
	.wp-core-ui .button-primary:hover,
	.wp-core-ui .button-primary:focus {
		background: rgba(<?php echo esc_attr( $accent_color_rgb[0] ); ?>,<?php echo esc_attr( $accent_color_rgb[1] ); ?>, <?php echo esc_attr( $accent_color_rgb[2] ); ?>, .85);
		border-color: rgba(<?php echo esc_attr( $accent_color_rgb[0] ); ?>,<?php echo esc_attr( $accent_color_rgb[1] ); ?>, <?php echo esc_attr( $accent_color_rgb[2] ); ?>, .85);
	}
</style>

<style class="udb-instant-preview" data-udb-prop-accent-color="box-shadow" data-udb-box-shadow-value="0 0 0 1px #fff, 0 0 0 3px {box_shadow_value}">
	.wp-core-ui .button-primary:focus {
		box-shadow: 0 0 0 1px #fff, 0 0 0 3px <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-admin-bar-bg-color="background">
	#wpadminbar {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background: <?php echo esc_attr( $admin_bar_bg_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-admin-menu-bg-color="background">
	#adminmenuback,
	#adminmenuwrap,
	#adminmenu {
	  background: <?php echo esc_attr( $admin_menu_bg_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-admin-submenu-bg-color="color"></style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-menu-item-active-color="background-color">
	#adminmenu a:hover,
	#adminmenu li.menu-top:hover,
	#adminmenu li.opensub > a.menu-top,
	#adminmenu li > a.menu-top:focus {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background-color: <?php echo esc_attr( $accent_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-menu-item-active-color="background">
	#adminmenu li.current a.menu-top,
	#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
	#adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head,
	.folded #adminmenu li.current.menu-top {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background: <?php echo esc_attr( $menu_item_active_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-menu-item-active-color="background-color">
	#adminmenu a:hover,
	#adminmenu li.menu-top:hover,
	#adminmenu li.opensub > a.menu-top,
	#adminmenu li > a.menu-top:focus {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background-color: <?php echo esc_attr( $menu_item_active_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu a {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu .wp-submenu a,
	#adminmenu .wp-has-current-submenu .wp-submenu a,
	.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
		color: rgba(255,255,255,.7);
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
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

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	/* Admin Menu: current */
	#adminmenu .wp-submenu li.current a,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu .wp-submenu li.current a:hover, #adminmenu .wp-submenu li.current a:focus,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:hover,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:focus,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar .ab-item,
	#wpadminbar a.ab-item,
	#wpadminbar > #wp-toolbar span.ab-label,
	#wpadminbar > #wp-toolbar span.noticon {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar .ab-top-menu > #wp-admin-bar-recovery-mode.hover > .ab-item,
	#wpadminbar.nojq .quicklinks .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus,
	#wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode:hover > .ab-item,
	#wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background-color: #cb9841; /* Bagus ask: What is this? */
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color" data-udb-prop-admin-submenu-bg-color="background">
	#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,
	#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus,
	#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
	#wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
	#wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
		background: <?php echo esc_attr( $admin_submenu_bg_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label,
	#wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label,
	#wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
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

<style class="udb-instant-preview" data-udb-prop-menu-item-color="color">
	#adminmenu li.menu-top:hover div.wp-menu-image:before,
	#adminmenu li.opensub > a.menu-top div.wp-menu-image:before {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
</style>
