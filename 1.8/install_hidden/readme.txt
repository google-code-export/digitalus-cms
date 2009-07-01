UNINSTALLING DIGITALUS CMS

If you need to uninstall Digitalus there are 2 things you must do:
1. Update site config:
	The config file stores when the cms was installed.  Delete the timestamp from this line:
	
	<installDate>1229053518</installDate>
	
2. Delete the database.  Digitalus will only install into an empty database. 