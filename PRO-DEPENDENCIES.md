# What Ultimate Dashboard PRO relies on in free

PRO is an add-on: it only runs on top of the free plugin and builds directly on free's code. Everything below is an API, even if it looks internal. Changing it breaks PRO, often silently (no error, a feature just stops working).

Customers update free and PRO at different times, so the new free must keep working with the PRO version that's out there (3.11.4 at the start of the redo).

## Check

```
php bin/check-pro-deps.php
```

Checks the free code against the full list in `bin/pro-deps.json` (about 200 items). Run it after every change in free. It must say "All … OK". It reads the source, so it doesn't replace testing with PRO active.

## The rules

1. **Classes**: never rename or move a class, or change its namespace. PRO extends `Base_Module`, `Base_Output`, `Content_Helper` and `Widget_Helper`, and calls about 20 others by name.
2. **Methods**: never rename a public method PRO calls, or change its arguments. Adding optional arguments at the end is fine.
3. **Singletons**: PRO calls `get_instance()` on free classes. Every free class keeps `get_instance()` and registers its hooks on that same instance (not `new self()`), because otherwise PRO can't unhook them.
4. **Unhooked callbacks**: PRO removes 14 of free's callbacks by hook name, method name and priority (for example `login_head` → `print_login_styles` at 20). If any of the three changes, PRO stops removing the callback and both plugins' code runs. Keep all three exactly as they are.
5. **Hooks**: never rename or remove a `udb_` action/filter that PRO uses (82 of them), and never change what it passes or what it expects back. Some pass free objects (e.g. `udb_admin_page_setup_menu` passes free's admin page output, `udb_ajax_before_get_admin_menu` passes free's menu handler), so those objects' methods count too. Adding new hooks is fine.
6. **`*_field_path` filters**: PRO swaps whole field templates through them. The template file can change, but the field names inside (and so the saved option keys) must stay the same.
7. **Constants**: `ULTIMATE_DASHBOARD_PLUGIN_DIR`, `_URL`, `_VERSION` stay. PRO uses free's version number for some of its assets.
8. **Files**: PRO loads 10 free files by path (heatbox.css, admin menu/bar list templates, admin page edit template, login redirect URL field). Never move or rename them.
9. **Scripts & styles**: PRO's scripts load after free's by handle (`udb-dashboard`, `udb-edit-widget`, `udb-login-redirect`, `udb-login-customizer-preview`, `udb-branding-instant-preview` …). PRO swaps `udb-admin-menu` and `udb-admin-bar` for its own versions. Keep the handles. Changing the markup, CSS classes or JS in those screens can break PRO's add-ons on top, so test with PRO.
10. **Data**: options (`udb_settings`, `udb_branding`, `udb_login`, `udb_admin_menu`, `udb_admin_bar`, `udb_login_redirect`, `udb_modules`) and their inner keys, post types (`udb_widgets`, `udb_admin_page`) and their meta keys, admin page slugs (`udb_settings`, `udb_branding`, `udb_tools` …), AJAX actions and nonce names. Never rename them. PRO's license page sits under `udb_widgets`.

## Allowed in free

- Fixing bugs, escaping, sanitizing, capability checks, as long as everything above stays the same.
- New code, new files, new hooks.
- Restyling admin screens (heatbox), but test every screen with PRO active, since PRO adds fields and buttons there.
- Code that's only used by free can change freely. Check `pro-deps.json` first.

## Notes for the PRO pass

- Multisite branding removes PRO's own dark mode styles from `admin_head`, but they're registered on `admin_enqueue_scripts` (priority 100), so the removal does nothing.
- PRO hooks `udb_branding_login_styles`, which neither plugin fires.
- Multisite login redirect points at `modules/login-url` in free, which doesn't exist.
- Widget output checks `property_exists( $array_helper, 'clean_unserialize' )`, which is always false for a method (should be `method_exists`), so it never cleans double-serialized roles/users.
- Widget and admin page role/user fields call plain `unserialize()` (5 spots); use free's `clean_unserialize()`, which no longer unserializes objects.
- Admin pages are publicly queryable (page builders edit them on the frontend, block/Divi pages are shown in an iframe of the frontend URL). Free only keeps logged-out visitors out, so any logged-in user can open an inactive or role-restricted admin page on the frontend (`?post_type=udb_admin_page&p=ID`). A role check there has to work for multisite blueprint pages too.
