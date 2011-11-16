About
=====

**eZDevTools** is a REST based toolbox for eZ Publish developers.

Its aim is to expose services providing useful information that can be displayed 
inside an IDE like NetBeans, Eclipse, PHPStorm, or even VIM.

This extension is NOT a plugin for ANY IDE ! It's just a common base to help IDE plugin developers
to develop a nice plugin for eZ Publish :-).


Requirements
============

**eZDevTools** requires at least eZ Publish 4.5 Matterhorn or superior (community edition starting from *2010.4*),
as it uses and extends REST API introduced in this version.

For now PHP 5.2 is supported but PHP 5.3 exclusive support can come in a near future.


How to use
==========

**eZDevTools** is REST based and exposes several services.
You will need to be familiar to [eZ Publish REST API](http://doc.ez.no/eZ-Publish/Technical-manual/4.5/Features/Rest-API).

Supported REST services
-----------------------
eZ Publish host need to be collated to every URI listed below.

Example:
```
http://ezpublish.dev/api/ezptools/v1/classes/list
```
### Content classes list

```
/api/ezptools/v1/classes/list
```

  **Description**: Displays all content classes for current eZ Publish installation, with all their attributes.
  
  **Parameters**: None.
  
  **ResponseGroups**: None.
