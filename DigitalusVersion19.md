# Introduction #
In version 1.9 of the Digitalus CMS there have been some small changes in the database structure.
This requires an update of the databases.

# Upgrading from version 1.8 #
If You want to upgrade Your installation from version 1.8 to 1.9, please perform the following steps:
  * make a backup copy of both - all Your files and Your databases
  * Download the zip file of Digitalus v1.9
  * unzip the zip file and copy the contents in Your installation path (Your old Digitalus CMS contents will be overwritten, but **not** the configuration file)
  * start Your webserver and log in into the admin area
  * Go to **_site_**
  * go to **_open console_** (on the left side)
  * type in the **_UpdateVersion19_** command (case-sensitive !!)
  * push the **_Info_** button to get a note about what's happening
  * push the **_run command_** button to run the command and update the databases
  * done

Other things to check:
  * remove xml declaration in custom templates xml files (e.g. _/templates/public/myTemplate/pages/default.xml_): remove `<?xml version="1.0" encoding="UTF-8"?>`
  * Digitalus 1.9 introduces a new feature regarding the publishing of pages. Check that all Your pages are published (in fact, should be done by "UpdateVersion19" command)

# Upgrading from version 1.5 #
If You upgrade from version 1.5, first run the command **_UpdateVersion18_** and then **_UpdateVersion19_**.

# Upgrading from older versions #
If You upgrade from a version prior to version 1.5, first run the command **_UpdateDatabase_** and then the commands above.