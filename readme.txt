=== Ultimate Dashboard – Custom WordPress Dashboard ===
Contributors: davidvongries
Tags: custom dashboard, dashboard widgets, custom login, white label, login url
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 3.9
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Replace the default dashboard widgets with your own, customize the login screen, change the login URL & clean up the WordPress admin area.

== Description ==

**[Ultimate Dashboard](https://ultimatedashboard.io/?utm_source=repository&utm_medium=link&utm_campaign=udb)** is the #1 plugin to **customize the WordPress dashboard**. Replace the default dashboard widgets with your own, point your clients to the places that matter and give the WordPress admin area a more meaningful use.

Lightweight, modular and built for agencies & freelancers who hand over websites to clients. A short setup wizard helps you get started, and you can turn off every part you don't need.

== Why choose Ultimate Dashboard? ==

= ❌ Remove dashboard widgets =
Remove all or individual default WordPress dashboard widgets with a single click, and hide the welcome panel or replace it with your own content.

= ➕ Create your own widgets =
Create **icon**, **text** & **HTML widgets** to build a custom **client dashboard** that sends your clients to the key areas of their website – or anywhere else.

= 🎨 Login Customizer =
Customize the **WordPress login screen** with your own logo, background, colors & layout, with a live preview in the WordPress Customizer.

= ↔️ Login Redirect =
**Change the WordPress login URL**, **hide /wp-admin/** from logged-out visitors and **redirect users after login** based on their user role.

= 📋 Custom admin pages =
Create **top-level** & **submenu pages** with the WordPress editor or HTML and add them anywhere in the WordPress admin menu.

= 🖌️ Custom CSS =
Add custom CSS to the **WordPress dashboard**, the entire **admin area** or the **login screen**.

= ✨ More features =
* Set a custom dashboard headline & widget colors
* Change the "Howdy" text
* Change the admin footer & version text
* Hide the admin bar on the frontend for specific user roles
* Remove the "Help" & "Screen Options" tabs
* Export & import widgets and settings
* Turn modules on or off on the *Modules* screen
* Optionally remove all plugin data when you uninstall it

---------

=== 🏆 Ultimate Dashboard PRO ===

Take it further with **[Ultimate Dashboard PRO](https://ultimatedashboard.io/?utm_source=repository&utm_medium=link&utm_campaign=udb)**:

* Admin Menu Editor
* Admin Bar Editor
* Remove 3rd party dashboard widgets
* Restrict dashboard widgets & admin pages to specific users or user roles
* Fully white label & rebrand WordPress
* Advanced Login Customizer features
* Create admin pages & a custom dashboard with the Block Editor, Elementor, Divi, Bricks, Breakdance, Beaver Builder, Oxygen & Brizy
* Video & contact form widgets
* WordPress multisite support
* Dark mode

[youtube https://youtu.be/w0oEQF3C6v0]

Get **[Ultimate Dashboard PRO](https://ultimatedashboard.io/?utm_source=repository&utm_medium=link&utm_campaign=udb)** today!

---------

=== What's next? ===

If you like Ultimate Dashboard make sure to check out our other products:

* **[Page Builder Framework](https://wp-pagebuilderframework.com/?utm_source=udb&utm_medium=repository&utm_campaign=wpbf)** - A fast & minimalistic WordPress theme designed for the new WordPress era.
* **[Better Admin Bar](https://betteradminbar.com/?utm_source=udb&utm_medium=repository&utm_campaign=bab)** - The plugin to make your clients enjoy WordPress. It replaces the default admin bar to provide the best possible user experience when editing & navigating a website.
* **[WP Video Popup](https://wp-video-popup.com/?utm_source=udb&utm_medium=repository&utm_campaign=wp_video_popup)** - Add a YouTube, Vimeo or Rumble video popup to WordPress. Lightweight, privacy-friendly lightbox that works with any page builder.

== External services ==

The setup wizard offers an optional newsletter sign-up. Only if you enter your name and email address and click subscribe, they are sent to ultimatedashboard.io, which adds you to our newsletter at MailerLite. Nothing is sent otherwise. ultimatedashboard.io: [Privacy](https://ultimatedashboard.io/privacy-policy/). MailerLite: [Terms](https://www.mailerlite.com/legal/terms-of-service), [Privacy](https://www.mailerlite.com/legal/privacy-policy).

== Installation ==
1. In your WordPress admin, go to *Plugins → Add New Plugin* and search for "Ultimate Dashboard".
2. Click *Install Now*, then *Activate*.
3. The setup wizard opens. Follow it, or skip it and go to *Ultimate Dashboard* in the admin menu.

== Frequently Asked Questions ==

= Where do I create dashboard widgets? =
Go to *Ultimate Dashboard → Add Dashboard Widget*. All your widgets are listed under *Ultimate Dashboard → All Widgets*.

= I changed the login URL and forgot it. How do I log in? =
Rename the `ultimate-dashboard` folder in `/wp-content/plugins/` via FTP or your host's file manager. This turns the plugin off and the default `/wp-login.php` works again. Rename it back once you're logged in and check the URL under *Ultimate Dashboard → Login Redirect*.

= Which icons can I use? =
Any of the 2,000+ [Lucide](https://lucide.dev) icons, or WordPress' own Dashicons. Click the icon on an icon widget or admin page and search by name or topic.

= Can I turn off the parts I don't use? =
Yes. Go to *Ultimate Dashboard → Modules* and switch off any module, for example the Login Customizer or Admin Pages.

= Is my data deleted when I deactivate the plugin? =
No. Your widgets and settings are only removed if you turn on *Remove Data on Uninstall* under *Ultimate Dashboard → Settings* and then delete the plugin.

= Does it work on WordPress multisite? =
Multisite support is available in [Ultimate Dashboard PRO](https://ultimatedashboard.io/?utm_source=repository&utm_medium=link&utm_campaign=udb).

== Screenshots ==
1. A custom dashboard with your own welcome panel, icon, text and HTML widgets
2. Icon widgets with tooltips, next to text and HTML widgets
3. All your dashboard widgets at a glance
4. Creating a dashboard widget
5. Settings: remove default widgets, set widget colors and edit the welcome panel
6. Settings: general options
7. Settings: custom CSS for the dashboard and the whole admin area
8. Turn each module on or off
9. White Label
10. A custom admin page
11. Creating a custom admin page with HTML & CSS
12. Login Customizer: background image
13. Login Customizer: log in button
14. Change the login URL and redirect users after login
15. Admin Menu Editor
16. Admin Bar Editor

== Changelog ==

= 3.9 | November 13, 2026 =
* New: Refreshed design on every Ultimate Dashboard screen, incl. the widget & admin page lists and edit screens, also on phones
* New: Icon picker for icon widgets, admin pages & the menu editors
* New: 2,000+ Lucide line icons replace Font Awesome for icon widgets & admin page menu icons, WordPress' own icons are still there. Icons you picked before get the closest Lucide icon
* New: Icon widgets show their icon in a soft color chip, tinted with the icon color
* Tweak: Font Awesome is no longer loaded, so the "Remove Font Awesome" setting is gone
* New: One simpler setup wizard (replaces the separate setup wizard & onboarding screens)
* Tweak: "Remove Data on Uninstall" now only removes data when you delete the plugin, never on deactivation, and includes widgets & admin pages
* Tweak: Login Redirect: role redirects no longer override the page a user asked for, /wp-admin/ shows "not found" while a custom login URL is set, and login URLs that clash with existing pages are refused
* Tweak: Login Customizer preview now matches the WordPress 7.1 login screen
* Tweak: Requires PHP 7.4 & WordPress 5.0
* Fixed: Several security issues
* Fixed: Login Customizer preview not loading
* Fixed: Export & import (data saved twice, import running twice)
* Fixed: Welcome panel changes made in the Text tab were lost
* Fixed: "Hide admin bar" roles were reset when saving the settings
* Fixed: Lists and images in admin pages made with the default editor
= 3.8.18 | October 10, 2026 =
* Tested up to WordPress 7.1
* Tweak: Added webp & avif support
* Fixed: Incompatibility with Admin Menu Editor & Elementor Page Builder
= 3.8.17 | June 6, 2026 =
* Tweak: WordPress 7.0 compatibility
* Fixed: Minor security issue
* Fixed: Improved module activation/deactivation
= 3.8.16 | April 6, 2026 =
* Minor tweaks & fixes
= 3.8.15 | March 10, 2026 =
* Fixed: Minor security issue
* Fixed: Several UI inconsistencies
* Fixed: HTML rendering issues
* Fixed: Contact form fields stripped by wp_kses_post
= 3.8.14 | February 27, 2026 =
* Fixed: Possible memory exhaustion on sites with many users
* Fixed: Default welcome panel content is broken when saving settings
* Fixed: Broken output in Custom CSS fields as certain characters are converted
= 3.8.13 | February 25, 2026 =
* Minor tweaks
= 3.8.12 | November 8, 2025 =
* Update BFCM deal details
= 3.8.11 | October 30, 2025 =
* Fixed: Backwards compatibility with Ultimate Dashboard PRO
= 3.8.10 | October 27, 2025 =
* Fixed: Removed a MailerLite API key that was mistakenly included in the plugin package
* Fixed: PHP notice in WordPress 6.7 related to early translation loading — text domains are now loaded at the proper hook
* Improved: Added additional data sanitization and proper output escaping throughout the plugin
= 3.8.9 | June 5, 2025 =
* Tweak: Allow shortcode in HTML widget
* Tested up to WordPress 6.8
= 3.8.8 | March 15, 2025 =
* Fixed: Issue where relative path URLs were broken in icon widgets
* Fixed: Security vulnerability (CVE-2025-2276) that allowed users with limited capabilities to enable/disable modules via AJAX
* Fixed: Security vulnerability that allowed users with limited capabilities to access AJAX across modules
= 3.8.7 | March 12, 2025 =
* Fixed: Issue where links weren't saved in text widgets
= 3.8.6 | March 3, 2025 =
* Fixed: XSS vulnerabilities in the edit widget and edit admin page screens
* Tweak: Additional security enhancements
= 3.8.5 | November 22, 2024 =
* Fixed: BFCM notice wasn't dismissible
= 3.8.4 | November 15, 2024 =
* Tested up to WordPress 6.7
* Fixed: Issue where custom login page logo won't show after the release of WordPress 6.7
= 3.8.3 | September 30, 2024 =
* New: Onboarding Wizard
* New: Hide admin bar for specific user roles
= 3.8.2 | August 14, 2024 =
* Tested up to WordPress 6.6
= 3.8.1 | July 24, 2024 =
* Fixed: Fatal error in the WordPress admin area if "Howdy"-text was changed due to recent WordPress core update (6.1.1)
= 3.8 | June 19, 2024 =
* New: Dark Mode (PRO)
* New: Move sub-menu items across top-level menu items in the Admin Menu Editor (PRO)
* Tweak: Overall improvements to the Admin Menu Editor (PRO)
* Tweak: Overall UI/UX improvements
* Fixed: Performance issues related to the Admin Menu Editor
= 3.7.14 | May 10, 2024 =
* Tested up to WordPress 6.5
= 3.7.13 | January 9, 2024 =
* Fixed: "Add New" text in the WordPress admin menu for adding new widgets & admin pages
= 3.7.12 | December 30, 2023 =
* Fixed: Minor security issue
= 3.7.11 | December 11, 2023 =
* Fixed: Minor security issue
= 3.7.10 | November 22, 2023 =
* Fixed: Bug where BFCM notice was not dismissible
= 3.7.9 | November 22, 2023 =
* Update BFCM deal details
= 3.7.8 | November 7, 2023 =
* Minor security tweaks
= 3.7.7 | August 27, 2023 =
* Tested up to WordPress 6.3
= 3.7.6 | May 21, 2023 =
* Tweak: Minor security fix
= 3.7.5 | May 11, 2023 =
* Tweak: Improve export/import functionality
* Tweak: Allow source tag in HTML widget
* Fixed: Fatal error with Google SiteKit
= 3.7.4 | February 7, 2023 =
* Tweak: Updated logo
= 3.7.3 | January 30, 2023 =
* Tweak: Wording
= 3.7.2 | December 5, 2022 =
* Fixed: Issue with password protected pages & login redirect feature
= 3.7.1.5 | October 4, 2022 =
* Minor tweaks & improvements
= 3.7.1.4 | July 20, 2022 =
* Tweak: Wording
= 3.7.1.3 | July 19, 2022 =
* Tweak: Open link in a new tab
= 3.7.1.2 | July 16, 2022 =
* Fixed: Wording
= 3.7.1.1 | July 1, 2022 =
* Minor tweaks
= 3.7.1 | July 1, 2022 =
* New: Field to replace the block editor logo (Ultimate Dashboard PRO)
= 3.7 | June 15, 2022 =
* New: Improved Login Customizer with features such as Background Image, Background Overlay, Form Box Shadow, etc.
* Fixed: Issues when configuring Admin Menu Editor for specific users
= 3.6.3 | May 13, 2022 =
* Tweak: Further improved Admin Menu Editor functionality
= 3.6.2 | May 10, 2022 =
* Fixed: Some menu items didn't appear in the Admin Menu Editor (for instance Yoast SEO)
* Fixed: Admin Menu Editor & Admin Bar Editor are not responsive
= 3.6.1 | May 4, 2022 =
* Tweak: Changed plugin header information, updated images & footer links
* Fixed: Some sub-menu items won't loade in the Admin Menu Editor, for instance in the WooCommerce Analytics section. If you still don't see certain menu or sub-menu items appear, please try resetting the Admin Menu Editor for all or specific user roles.
= 3.6 | April 25, 2022 =
* New: Allow rendering shortcodes in the WordPress welcome panel
* New: Allow rendering shortcodes in the Ultimate Dashboard widget titles
* New: Refactored import/export functionality to allow for more granular control when exporting settings
* New: Placeholder tags to display information dynamically in widgets & welcome panel
* Tweak: Rewrite URL's when exporting & importing settings to a different WordPress install
* Fixed: PHP Warning: Undefined array key "path" (https://wordpress.org/support/topic/php-message-php-warning-undefined-array-key-path/)
* Fixed: Build Admin Menu Editor only when a specific role is clicked instead of loading them all at once (in a row). This was causing performance issues & was interpreted as a security issue by some installs.
* Fixed: Reliability & accuracy of the Admin Menu Editor has been improved
= 3.5.3 | March 2, 2022 =
* New: Added support for WordPress' new language switcher on login screen
* Updated description
= 3.5.2 | February 3, 2022 =
* Tested up to 5.9
* Tweak: Updated texts
* Fixed: Fatal error in WordPress' tools section
* Fixed: Content of Custom CSS fields on settings page appear empty until clicked
= 3.5.1 | November 11, 2021 =
* Tweak: Updated admin notice
= 3.5 | August 16, 2021 =
* New: Change WordPress' Howdy text
* New: Change WordPress login URL
* New: Redirect yoursite.com/wp-admin/ for non logged-in users
* New: Login Redirect feature
* Tweak: Show review notice only to admins
* Tweak: New tabbed layout on Settings page
* Fixed: Some 3rd party menu items did not appear in the Admin Menu Editor
* Fixed: Some 3rd party menu items did not appear in the Admin Bar Editor
* Fixed: Only load FontAwesome in the WordPress admin if an admin page exists
* Fixed: Settings link on the plugin screen links to non-existent page
* Fixed: Remove data on uninstall on multisite installations
= 3.4.1 | July 26, 2021 =
* Fixed: Welcome panel cannot be removed
= 3.4 | June 22, 2021 =
* New: Advanced White Label settings
= 3.3.4 | June 8, 2021 =
* New: WordPress admin body classes (udb-user-{nicename}, udb-role-{current-user-role})
* New: Settings to change the WordPress welcome panel
= 3.3.3 | June 1, 2021 =
* New: Remember Users/Roles tab when saving in Admin Menu Editor (PRO)
* Other minor tweaks & improvements
= 3.3.2 | May 22, 2021 =
* New: Add separators to your admin menu with the Admin Menu Editor (PRO)
* Tweak: Remove Theme Options tab from custom Admin Pages
* Fixed: Some sub menu items can't be removed with the Admin Menu Editor (PRO)
* Fixed: Conflict with 3rd party plugins where not all roles were loaded in the Admin Menu Editor (PRO)
* Fixed: On multisites, all menu items are shown no matter what role is selected (PRO)
= 3.3.1 | May 18, 2021 =
* New: TablePress support for Admin Menu Editor (PRO)
* New: Admin notice that kindly asks you to leave a review
* Tweak: Pave the way to implement Brizy support for Custom Admin Pages (PRO)
= 3.3 | April 13, 2021 =
* New: Admin Bar Editor (PRO)
* New: Add custom CSS to the WordPress login screen
* Fixed: Cannot redeclare retrieve_password (Fatal error)
* Fixed: Export/import issue with user roles (dashboard widgets & admin pages)
* Fixed: Export/import functionality for "Modules" were missing
* Fixed: All widgets were active/inactive after import, even though if they were active/inactive on the original website
= 3.2.1 | March 4, 2021 =
* Fixed: Module switcher no longer working
= 3.2 | March 4, 2021 =
* New: Improved & cleaned up admin & settings page design
* New: Admin Menu Editor - Change the admin menu for specific user roles (PRO)
* New: Admin Menu Editor - Add custom menu items to the WordPress admin menu (PRO)
* Tweak: Admin Menu Editor - Changed sub menu name from Admin Menu to Admin Menu Editor to be more precise
* Fixed: Widget & admin page user roles are not imported properly (PRO)
* Fixed: Login Customizer - Border color styling
* Fixed: Admin Menu Editor - Rare case where the admin menu was broken after using the Admin Menu Editor feature (PRO)
* Fixed: Login Customizer - Box-width setting doesn't work (PRO)
= 3.1.3 | December 20, 2020 =
* Tweak: Improved "Modules" feature
* Tweak: Improved "Login Customizer" CSS output
* Fixed: Admin email verification screen styling
= 3.1.2 | December 9, 2020 =
* Fixed: Wrong plugin dir for PRO plugin updater
= 3.1.1 | December 9, 2020 =
* Fixed: Broken plugin updater in PRO add-on
= 3.1 | December 9, 2020 =
* WordPress 5.6 compatibility
* New: Modules screen to turn on/off features
* Tweak: Updated & improved settings page UI
= 3.0 | October 27, 2020 =
* Rewritten the plugin from the ground up
* New: Enable/disable widgets on the "All Widgets" screen
* New: Updated to FontAwesome 5
* New: Improved icon picker
* Fixed: Admin Pages export/import functionality
* Other tweaks & improvements
= 2.8.3 | August 11, 2020 =
* Fixed: WordPress 5.5 compatibility
= 2.8.1 | March 4, 2020 =
* Fixed: CSS sanitization
= 2.8 | March 4, 2020 =
* New: Create admin pages & sub-pages
* Minor tweaks & improvements
= 2.7.2 | April 17, 2020 =
* New: Use {home_url} inside login customizer logo URL to dynamically fetch the site URL
* New: Filter (udb_login_customizer) to disable the Login Customizer feature entirely
* New: Filter (udb_login_logo) to change the login logo image. That will allow you to change the logo without having to upload it to your or your clients website
* Minor tweaks & improvements
* Fixed: HTML widget wouldn't save
= 2.7.1 | April 4, 2020 =
* Tweak: We found a better way to implement the login customizer feature and were able to remove the auto-generated page.
= 2.7 | April 1, 2020 =
* New: Login Customizer
* New: Widget Color Settings
* New: Change Footer & Version Text
* New: HTML Widget
* New: Added support for Health Status widget
* Fixed: contextual_help_list deprecated notice
* Fixed: Long tooltips overlap icon widgets
= 2.6 | February 18, 2020 =
* New: Change dashboard headline
* New: Add custom admin CSS
* New: Remove "Help" tab
* New: Remove "Screen Options" tab
* New: Remove admin bar from the frontend
= 2.5.1 | January 3, 2020 =
* Fixed: Text decoration issue
= 2.5 | September 27, 2019 =
* Maintenance Release
= 2.4 | April 30, 2019 =
* New: Text Widgets
* New: Tooltips for Icon Widgets
* New: Import/Export Widgets & Settings
* New: Custom Dashboard CSS field
* New: Improved UX/UI
= 2.3.1 | January 28, 2019 =
* Tweak: cleaned up codebase
* Fixed: internationalization
= 2.3 | November 30, 2018 =
* Tweak: set required PHP version to 5.6
* Tweak: tested up to 5.0
* Tweak: highlighted pro link
* Fixed: internationalization
= 2.2 | August 1, 2018 =
* Tweak: Updated Settings Page UI
* Tweak: Updated Links & Descriptions under Widgets -> PRO
= 2.1.1 | May 31, 2018 =
* Fixed: Number of Dashboard Widgets is limited by the "Reading" settings under Appearance -> Reading
= 2.1 | March 18, 2018 =
* Maintenance release
* Added: Action Link
* Tweak: save deactivated dashboard widgets in a single array
* Fixed: wrong hook used to remove dashboard widgets
= 2.0.1 | November 17, 2017 =
* Minor Tweaks & Improvements
= 2.0 | November 14, 2017 =
* Rewritten the plugin from ground up
* Ultimate Dashboard now has its own, top-level menu in the WordPress Dashboard
* Tweak: metabox styling
* Tweak: embedded font-awesome instead of loading it from an external source
* Tweak: removed roboto as default font
* Tweak: minor security tweaks
= 1.3.1 | October 28, 2016 =
* Updated: Plugin Header & options page sidebar
= 1.3 | October 6, 2016 =
* Added: filter/search functionality for dashboard icons (Dashicons & Font-Awesome)
= 1.2.1 | August 21, 2016 =
* Fixed: The Plugin Generated xxx Characters of Unexpected Output During Activation message
= 1.2 | August 21, 2016 =
* Removed: Ultimate Dashboard PRO notification
* Added: Sidebar to Ultimate Dashboard options page
* Added: Custom Post Type columns (icon, link target)
* Tweak: Default plugin language is now english
* Added: German translation
= 1.1 | July 20, 2016 =
* Removed the limit of 5 dashboard widgets
= 1.0.3 | July 7, 2016 =
* Added: all WordPress Dashicons
* Tweak: new font for Dashboard Widgets (Roboto)
* Tweak: Added an accordion to sort icon groups
= 1.0.2 | July 5, 2016 =
* Moved the Ultimate Dashboard CPT from the main menu to Dashboard > Dashboard Widgets
* Added better descriptions while creating a Dashboard Widget for absolute and relative urls
= 1.0.1 =
Removed a bug that only allowed to create 1 Dashboard Widget.
= 1.0 =
Initial Release