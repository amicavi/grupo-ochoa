Module is installed in a regular way – simply upload your archive and click install.

CHANGELOG:
===========================
v 2.5.3 (September 23, 2015)
===========================
- [+] Possibility to delete all banners in current shop context

Files modified
-----
- /custombanners.php
- /views/templates/admin/configure.tpl
- /views/css/back.css
- /views/js/back.js
- /Readme.md
- /logo.png

===========================
v 2.5.2 (June 28, 2015)
===========================
Changed
-----
- Keep hook positions and exceptions for other shops during reset
- Automatically unhook module after last banner in hook is deleted
- Minor code optimizations

Files modified
-----
- /custombanners.php
- /Readme.md

===========================
v 2.5.1 (June 26, 2015)
===========================
Fixed
-----
- Fix for empty links, defined by id (product, category etc.)
- Added 'UTF-8' to escape modifiers in tpl-s, basing on validator requirements

Files modified
-----
- /custombanners.php
- /views/templates/admin/banner-form.tpl
- /views/templates/admin/configure.tpl 
- /views/templates/admin/custom-file-form.tpl 
- /views/templates/admin/hook-carousel-form.tpl 
- /views/templates/admin/hook-exceptions-form.tpl 
- /views/templates/admin/hook-positions-form.tpl
- /views/templates/hook/banners.tpl
- /Readme.md

===========================
v 2.5.0 (April 5, 2015)
===========================
Added
-----
- Custom classes for banners
- Custom css/js, compatible with multishop
- Restrictions by products/categories/manufacturers/suppliers/cms
- Manipulating other modules in hook position settings (activate/deactivate, unhook, uninstall)
- Some new editable settings for slider
- Moving banners between hooks
- Bulk actions for banners (activate/deactivate, move, copy, delete)
- Instruction for using the importer, easily accessible on module config page

Changed
-----
- Replaced Owl carousel by BxSlider, that is included in default PS installation
- Modified BxSlider to display predefined number of slides for different resolutions
- Optimized some layout elements in backoffice for faster loading
- Updated the predefined content, imitating default banners layout and slider
- When installing/uninstalling the module, only banners for selected context shops are affected. Same for importing.

Files modified
-----
- /custombanners.php
- /views/templates/admin/configure.tpl
- /views/templates/admin/banner-form.tpl
- /views/templates/hook/banners.tpl
- /views/js/back.js
- /views/css/back.css
- /views/css/front.css
- /defaults/data.zip
- /Readme.md

Directories added
- /views/js/custom/
- /views/css/custom/

Files added
-----
- /upgrade/install-2.5.0.php
- /views/templates/admin/importer-how-to.tpl
- /views/templates/admin/hook-exceptions-form.tpl
- /views/templates/admin/hook-carousel-form.tpl
- /views/templates/admin/hook-positions-form.tpl
- /views/templates/admin/custom-file-form.tpl
- /views/css/common-classes.css
- /views/css/custom/index.php
- /views/js/custom/index.php

Files removed
-----
- /views/templates/admin/exceptions-settings-form.tpl
- /views/templates/admin/carousel-settings-form.tpl
- /views/templates/admin/positions-settings-form.tpl

Directories removed
-----
- /views/js/owl/
- /views/css/owl/
- /views/img/owl/


===========================
v 2.0.0 (March 25, 2015)
===========================
Added
-----
- Advanced hook settings: exceptions, positions, carousel
- Drag-n-drop on image upload
- Advanced link creation by id: product link, category link etc.
- Possibility to copy banner to any hook
- Editable caption
- Autoupgrade: file locations and database tables are updated automatically on uploading new version
- Magic quotes warning

Changed
-----
- Updated user interface in BO
- Optimized tinyMCE loading
- Optimized Hook registrations: only used hooks are registered
- Improved export/import/installation: included page exceptions and module positions information
- Changed and optimized database tables

Fixed
-----
- Multisop issues on import/export/install
- Minor code fixes

Directories moved
-----
- /js/  -> /views/js/
- /css/ -> /views/css/
- /img/ -> /views/img/

Files modified
-----
- /custombanners.php
- /views/templates/admin/configure.tpl
- /views/templates/admin/banner-form.tpl
- /views/templates/hook/banners.tpl
- /views/js/back.js
- /views/css/back.css
- /views/css/front.css

Files added
-----
- /Readme.md
- /upgrade/install-2.0.0.php
- /views/templates/admin/exceptions-settings-form.tpl
- /views/templates/admin/carousel-settings-form.tpl
- /views/templates/admin/positions-settings-form.tpl

Files removed
-----
- /views/templates/admin/carousel-form.tpl

Directories removed
-----
- /views/templates/front/

===========================
v 1.5.0 (February 20, 2015)
===========================
Changes not documented

===========================
v 1.0.0 (2014)
===========================
Initial relesase