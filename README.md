<p align="center">
<a href="https://ultimatedashboard.io/" target="_blank" rel="noopener noreferrer">

![alt text](https://ultimatedashboard.io/wp-content/uploads/wordpress-ultimate-dashboard-logo.png "Ultimate Dashboard")

</a>
</p>

## Summary

- [Summary](#summary)
- [Description](#description)
- [Features](#features)
- [Installation](#installation)
		- [Through The WordPress Administrative Area](#through-the-wordpress-administrative-area)
		- [Download Manually](#download-manually)
- [Available Hooks](#available-hooks)
		- [Filter hooks](#filter-hooks)
		- [Action hooks](#action-hooks)
- [Ultimate Dashboard Pro](#ultimate-dashboard-pro)

## Description

[**Ultimate Dashboard**](https://ultimatedashboard.io/?utm_source=wordpress&utm_medium=description&utm_campaign=udb) is a lightweight WordPress plugin that lets you replace the default WordPress dashboard widgets with your own set of useful icon & text widgets.

Create custom WordPress dashboard widgets and send your clients and users to the key areas of their website – or anywhere else.

## Features

- Give the WordPress dashboard a more meaningful use for your clients & users
- Remove all/individual default WordPress dashboard widgets
- Create your own set of icon widgets
- Create text widgets
- Choose between FontAwesome & WordPress Dashicons for your icon widgets
- Import/Export widgets & settings
- Add custom CSS to your WordPress dashboard

Get even more Features with [Ultimate Dashboard PRO](#ultimate-dashboard-pro).

## Installation

#### Through The WordPress Administrative Area

- From WordPress administrative area, go to _Plugins_ -> _Add New_
- Search for "_Ultimate Dashboard_" (By David Vongries)
- Install and then activate it

#### Download Manually

- Download the ultimate-dashboard.zip file to your computer.
- Unzip the file.
- Upload the ultimate-dashboard folder to your `/wp-content/plugins/` directory.
- Activate the plugin through the Plugins menu in WordPress.

## Available Hooks

#### Filter hooks
- `udb_font_awesome` : Whether or not to enqueue Font Awesome css.
- `udb_modules` : Filter to manage what modules should be loaded.
- `udb_{$module}` : Filter to determine whether or not to enable a module. E.g: `udb_login_customizer`

#### Action hooks
- `udb_edit_widget_styles` : Action to run on styles enqueue on both new widget screen & edit widget screen.
- `udb_edit_widget_scripts` : Action to run on scripts enqueue on both new widget screen & edit widget screen.
- `udb_widget_list_styles` : Action to run on styles enqueue on widget list screen.
- `udb_widget_list_scripts` : Action to run on scripts enqueue on widget list screen.
- `udb_save_widget` : Action to run on widget saving. This hook can be used to save custom widgets.
- `udb_dashboard_styles` : Action to run on styles enqueue on dashboard screen.
- `udb_dashboard_scripts` : Action to run on scripts enqueue on dashboard screen.
- `udb_widgets_metabox` : Action to define widgets.
- `udb_edit_admin_page_styles` : Action to run on styles enqueue on both new admin page screen & edit admin page screen.
- `udb_edit_admin_page_scripts` : Action to run on scripts enqueue on both new admin page screen & edit admin page screen.
- `udb_admin_page_list_styles` : Action to run on styles enqueue on admin page list screen.
- `udb_admin_page_list_scripts` : Action to run on scripts enqueue on admin page list screen.
- `udb_save_admin_page` : Action to run on admin page saving.

## Ultimate Dashboard Pro

- Remove 3rd party widgets
- Add video widgets
- Add HTML widgets
- WordPress multisite support
- Rebrand the WordPress login & admin screen

[![Ultimate Dashboard Pro](https://img.youtube.com/vi/SFnXOYQ7vWk/0.jpg)](https://www.youtube.com/watch?v=SFnXOYQ7vWk)

Learn more about [Ultimate Dashboard Pro](https://ultimatedashboard.io/?utm_source=wordpress&utm_medium=description&utm_campaign=udb)
