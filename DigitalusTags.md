# Introduction #
From version 1.8 on the Digitalus CMS features an improved templating concept.
A detailed description of how to get started with this templating system can be downloaded from the [Google code site](http://digitalus-cms.googlecode.com/files/Designing%20Templates%20for%20Digitalus%20CMS.pdf).
Here You can find a list with currently supported Digitalus tags and their attributes.

# Digitalus Tags #

## All Tags ##

### Attributes ###
For all types the following attributes are available:
  * _group_: It is used to group the controls in content sections. For each group a list item is created at page->edit: page options->content sections.
  * _id_: a unique id for the digitalusTag.
  * _label_: a label that appears in the admin area for this DigitalusTag; if no label is given, the id is used as label; the label is translated.
  * _required_: defines whether this form element is required.
  * _type_: the type of DigitalusTag.

## Digitalus Control ##

### Description ###
A DigitalusControl provides a form element in the admin area. The controls can be divided into three different types of inputs. The control's type attribute is **case sensitive**!!
  1. simple text input fields (_text_, _textarea_)
  1. javascript supported input fields (_fckeditor_, _markitup_, _tinymce_, _wymeditor_, _wysiwyg_)
  1. dropdown list to select a module (_moduleSelector_)

### Example ###
`<digitalusControl type='fckeditor' id='content' required='true' cols='60' rows='4' group='main' />`

### Attributes ###
|**attribute**|**supported values**|**description**|**supported attributes**|
|:------------|:-------------------|:--------------|:-----------------------|
|type|text|simple text type input field `<input type="text" />`|_group_, _`<input type="text" />` tag allowed attributes_|
|  |textarea|textarea `<textarea></textarea>`|_group_, _`<textarea>` tag allowed attributes_|
|  |fckeditor|fckeditor javascript input field|_group_, _`<textarea>` tag allowed attributes_|
|  |markitup|markitup javascript input field|_group_, _`<textarea>` tag allowed attributes_|
|  |tinymce|tinymce javascript input field|_group_, _`<textarea>` tag allowed attributes_|
|  |wymeditor|wymeditor javascript input field|_group_, _`<textarea>` tag allowed attributes_|
|  |wysiwyg|jquery wysiwyg javascript input field|_group_, _`<textarea>` tag allowed attributes_|
|  |moduleSelector|module selection dropdown list|_group_, _`<select>` tag allowed attributes_|

## Digitalus Navigation ##

### Description ###
The DigitalusNavigation tag provides the possibilty to simply add navigational structure to the template. Three different types are supported currently, i.e. _menu_, _submenu_ and _breadcrumbs_.

From Version 1.10 on, the navigation is based on Zend\_Navigation with all added features (and drawbacks).

### Example ###
`<digitalusNavigation type='menu' indent="1" maxDepth="2" ulClass="menu" id='menu' />`

`<digitalusNavigation type='submenu' id="submenu" maxDepth="2" />`

`<digitalusNavigation type='breadcrumbs' id="breadcrumbs" />`

### Attributes ###
The attributes are **case-sensitive**!
|**attribute**|**supported values**|**description**|**supported attributes**|
|:------------|:-------------------|:--------------|:-----------------------|
|type|menu|creates a menu using the view helper _Digitalus\_View\_Helper\_Navigation\_RenderMenu_|**indent**, **maxDepth**, **minDepth**, **onlyActiveBranch**, **renderParents**, **ulClass**|
|  |submenu|creates a submenu using the view helper _Digitalus\_View\_Helper\_Navigation\_RenderSubmenu_|**indent**, **ulClass**|
|  |breadcrumbs|creates a breadcrumb using the view helper _Digitalus\_View\_Helper\_Navigation\_RenderBreadcrumbs_|**linkLast**, **maxDepth**, **minDepth**, **separator**|

## Digitalus Partial ##

### Description ###

### Example ###

### Attributes ###