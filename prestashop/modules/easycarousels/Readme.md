Module is installed in a regular way – simply upload your archive and click install.

CHANGELOG:
===========================
v 1.7.3 (June 14, 2015)
===========================
Fixed
-----
- Min width fix

Files modified
-----
- /easycarousels.php (version update)
- /views/js/front.js
- /Readme.md

===========================
v 1.7.2 (May 16, 2015)
===========================
Added
-----
- View all links for new products, prices drop, bestsellers, products by supplier, products by manufacturer
- Possibility to force one line for title
- Possibility to show/hide title and define its length
- Possibility to show/hide description and define its length
- Possibility to show/hide product thumbnails, displayed using productlistthumbnails v >= 1.0.1

Fixed
-----
- Retro-compatibility for getAccessories() in /override/classes/Product.php
- Minor bug fixes

Changed
-----
- Replaced "float:left" by "display:inline-block" for all carousel items

Files modified
-----
- /easycarousels.php
- /views/templates/hook/carousel.tpl
- /views/templates/admin/carousel-form.tpl
- /views/css/front.css
- /views/js/front.js
- /override/classes/Product.php
- /Readme.md

===========================
v 1.7.1 (April 23, 2015)
===========================
Added
-----
- Possibility to enable/disable compact tabs
- Possibility to add custom class for carousels container
- Dynamic class for each carousel, indicating current number of visible items (columns) "items-num-xx"

Changed
-----
- Slight changes in FO layout. All carousels are wrapped by a common container that can take user-defined class
- Tabs are transformed to compact list only if they overlap container
- Updated russian translation
- Minor code fixes

Files modified
-----
- /easycarousels.php
- /views/templates/admin/configure.tpl
- /views/templates/admin/importer-how-to.tpl
- /views/templates/hook/carousel.tpl
- /views/js/back.js
- /views/js/front.js
- /views/css/front.css
- /views/css/bx-styles.css
- /translations/ru.php
- /Readme.md

Files added
-----
- /views/templates/admin/hook-display-form.tpl
- /upgrade/install-1.7.1.php

CHANGELOG:
===========================
v 1.7.0 (April 21, 2015)
===========================
Added
-----
- Carousel for Accessories
- Possibility to set random ordering
- Possibility to display carousels only for current category
- Possibility to show/hide product category
- Possibility to show/hide product manufacturer
- Possibility to show/hide hooks in product listings used for carousels
- Some new settings for carousels (loop, speed, slides moved, slides numbers for different resolutions, min slide width)
- Bulk actions
- Manipulating other modules in hook position settings (activate/deactivate, unhook, uninstall)
- Importer how-to

Changed
-----
- Replaced Owl carousel by BxSlider, that is included in default PS installation
- Optimized carousels for faster loading and displaying predefined number of slides for different resolutions
- Some FO and BO layout changes
- Minor code fixes

Files modified
-----
- /easycarousels.php
- /views/templates/admin/configure.tpl
- /views/templates/admin/carousel-form.tpl
- /views/templates/hook/carousel.tpl
- /views/js/back.js
- /views/js/front.js
- /views/css/back.css
- /views/css/front.css
- /democontent/carousels.txt
- /Readme.md

Files added
-----
- /views/css/common-classes.css
- /views/css/bx-styles.css
- /views/templates/admin/hook-exceptions-form.tpl
- /views/templates/admin/hook-positions-form.tpl
- /upgrade/install-1.7.0.php
- /override/index.php
- /override/classes/index.php
- /override/classes/Product.php (prevents native fetching of accessories if they are displayed in carousel)

Files removed
-----
- /views/templates/admin/exceptions-settings-form.tpl

Directories removed
-----
- /views/js/owl/
- /views/css/owl/
- /views/img/owl/

===========================
v 1.1.0 (March 7, 2015)
===========================
Added
-----
- Possibility to import/export carousels
- Possibility to edit hook exceptions on module settings page
- Added counter to hooks selector in BO

Changed
-----
- Demo content is installed from editable file /democontent/carouselts.txt
- Added 'display' prefix to custom hooks (displayEasyCarousel1, displayEasyCarousel2, displayEasyCarousel3)
- Front-office Hooks are registered only after you add carousels to them
- Minor code fixes

Files modified
-----
- /easycarousels.php
- /views/templates/configure.tpl
- /views/js/back.js
- /views/css/back.css
- /translations/ru.php

Files added
-----
- /Readme.md
- /upgrade/install-1.1.0.php
- /upgrade/install-1.0.1.php
- /views/templates/admin/exceptions-settings-form.tpl
- /views/img/grab.cur
- /views/img/grabbing.cur

Files removed
-----
- /readme_en.txt
- /upgrade/upgrade-1.0.1.php

===========================
v 1.0.1 (February 14, 2015)
===========================
Fixed
-----
- Possibility to override carousel.tpl in theme directory
- Minor code fixes

Updated
-----
- Moved 'css', 'js', 'img' to 'views' basing on validator requirements

Added
-----
- Autoupgrage functionality

Directories moved to /views/:
-----
- /js
- /css
- /img

Files modified:
-----
- /easycarousels.php
- /views/templates/hook/carousel.tpl
- /views/templates/admin/configure.tpl
- /views/templates/js/back.js

Files added:
-----
- /upgrade/index.php
- /upgrade/upgrade-1.0.1.php

Files removed:
-----
- /views/templates/hook/product-details.tpl
- /views/templates/hook/manufacturer-details.tpl
- /views/templates/hook/supplier-details.tpl

===========================
v 1.0.0 (February 06, 2015)
===========================
Initial relesase
