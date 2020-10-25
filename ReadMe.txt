This is the Release Candidate for Content 1.o  There is a huge number of changes.  

For directions on how to use content checkout the wiki at xoops.thehandcoders.com.  Individuals who contribute useful material to the wiki will get prefrence for any feature request they submit.  You can also buy your way on to the feature request by making a paypal donation.  This is a bug testing release, please submit bugs the the dev site at: http://dev.xoops.org/modules/xfmod/project/?content

These are some of the new features, I'm sure I've missed some.  If you find one that's not mentioned, let me know an I'll add it to the list.  content@idiotsabound.com

Permissions - You can now define what groups can access what page.  Permissions can be set on each individual page or can be set on all pages at once via the "Manage Permissions" page.

Popup Editor - If you use the interstitial editor, you no longer have to go into the adminsection.  The edition tools are available in a popwindow.  Once you changes are saved, the window is closed and the original page is reloaded.

Web 2.0 - The admin section has begun it change into a web 2.0 style editor.  The pages are now sorted in folder that can be expanded and collapsed.  The Add/Edit pages change based on the type of content selected.

Module Highlight - When you create a link, you can also specify a module associated with that link.  this is usful for the site navigation.  If a link has a modle associated with it, and you are currently on that page, the link will be highlighted in the navigation.

Error Page - If your server allows mod_rewrite, you can use the included .htaccess page to change the error habits of the web server.  If a page is not found, the server will redirect to the page defined in the admin interface.

New Icons -  All of the icons for the content module have been updated to have a consistent look and feeld.

Header images - You can specify a specific image to use as a header on each individual page.  This header is displayed outside of the content block and can be positioned seperatly in the template.

Modified FCKeditor Link Tool - When you add a link to a content page, the fckeditor dialog now contains a pulldown list of all the available content pages.  This pulldown is show in addition to all the standard elements.

Breadcrumbs - Content will generate breadcrumb navigation based on the current pages parent.

Pagebreak - By placing the [pagebreak] tag in your page content, the module will automatically break your one page in to multiple virtual pages.  All the content is managed from as one page in the system, but will appear as multiple pages on the site.

Page Title - You can specify a page title that is seperate from the link title.  This lets you have a longer page title while still keeping the navigation short.

Upgrade - There is an upgrade script included to upgrade your current database to the latest schema.

