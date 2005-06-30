Installation
------------
1.- Copy 'lang' and 'mod' to the moodle directory.
2.- If you haven't done this, create a 'webdav' directory inside the moodle dir. If you want to give the users the ability to synchronize their calendar software with your moodle site, give that folder the required rights to WebDAV access.


Module Configuration
--------------------
You can only change the folder where the iCal files will be placed, this can be done using the module configuration panel.


Apache 2 Configuration
----------------------


How to synchronize with Sunbird
-------------------------------
Remember that this module only _exports_ Calendar events, it does not import your own events into Moodle's Calendar.

You can synchronize with your site or course using the "File > Subscribe to remote calendar" option in Sunbird. 
· Enter the name you want to give to the calendar in Sunbird.
· Choose a color.
· Paste the URL of the course (or site) iCal file (ask the site admin or teacher). It should be something like http://yoursite.com/moodle/webdav/shortcoursename.ics
· Then click OK and that's all!

Remember that the calendar will be updated each hour, so new events won't be available in your local calendar until 60 minutes, at most. We will work to allow admins to change this interval.


Authors
-------
Andreas Calvo Gomez and Guillermo Miranda Alamo developed this module as an assignment of Taller de Software II, a workshop in Universitat Pompeu Fabra [http://www.upf.edu]

We hope to continue involved in this module's development and maybe many others!