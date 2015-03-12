# Introduction #
## _**Version 1.10 is currently being developed and has been released as [second alpha version](http://digitalus-cms.googlecode.com/files/digitalus_1.10.0_alpha2.zip)** ##_

In version 1.10 of the Digitalus CMS have been made some significant changes in the database structure, especially regarding the database _users_ and _pages_.

This requires an update of the databases.
To achieve this, both an SQL file and a new updating functionality are provided.

Updating the system is not trivial and might fail because a lot of new validators and constraints have been added to obtain a more robust and consistent database.

# Significant changes and new features #
Update of the Zend Framework to version [1.10.3](http://framework.zend.com/releases/ZendFramework-1.10.3/ZendFramework-1.10.3-minimal.zip)

The navigation now makes use of Zend\_Navigation - so does the Digitalus tag `<digitalusNavigation>` which simply forwards to the view helpers. `<digitalusNavigation>` supports all attributes that Zend\_Navigation offers.

To clean up the directory structure, the _data_ and _configs_ directories have been moved into the _admin_ module directory.

The _models_ directory has also been moved into the _admin_ module directory.

The _language_ directory has been renamed to _languages_ with a trailing _s_. This also affects the modules. Modules' translation files have to reside in a directory called _languages_.

In version 1.10 some changes to the model _users_ have been made. It is now possible (and required) to define a username for each user. The superuser's username is _administrator_.
In the update process, the email address is copied to use as username (by database update).
Users can now be (de)activated.
This comes along with a login/registration module to register new users via the frontend.

In modules until v1.10 it was only possible to assign one single frontend action (PublicController) to a page. Now it's possible to define the action name via a url parameter. Simply add _/p/a/ACTIONNAME_ to the url and the action _ACTIONNAME_ will be performed instead of the indexAction.

**NOTE: action names must be lowercase! actions musst not require module data from database to work properly. For more details, have a look at the login module.**

Each page now has form fields for _label_ and _headline_. This makes it possible to add not only different page content for each language, but from now on also page labels. These labels appear in the menu respectively in breadcrumbs. This makes the menu fully translatable (a common user request).
As a drawback, page names now must be unique (case insensitive).


# Updating from version 1.9 #
**In both cases: make a backup copy of both - all Your files and Your databases**

## New Updater ##
  * download the zip file of Digitalus v1.10
  * unzip it in Your webserver's root directory
  * as known from a fresh installation, simply rename the _install\_hidden_ directory to _install_
  * follow the instructions
  * done

## Using SQL file ##
When upgrading using the provided SQL file, please perform the following steps:
  * download the zip file of Digitalus v1.10
  * unzip it in Your webserver's root directory
  * run the SQL file _Version19to110.sql_ (in the directory _library/Digitalus/Updater/_) on Your server.
  * copy Your config.xml file from `application/data/comfig.xml` to `application/admin/data/config.xml`
  * adjust the language path in Your config.xml as follows:
```
<language>
    <path>./application/admin/data/languages</path>
    ...
</language>
```
  * adjust the icon path in Your config.xml as follows:
```
<filepath>
    ...
    <icons>images/icons/silk</icons>
    ...
</filepath>
```
  * done

# Updating from older versions #
If You upgrade from a version prior to version 1.9, first update to the version 1.9.