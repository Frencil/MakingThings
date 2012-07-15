Making Things
=============

[NPoole](https://github.com/NPoole) is working on a project all about making things. We've shared some data and this is the project to attempt to visualize it in various ways.

Current Development
-------------------

Working toward showing a force-directed graph in force_directed.html that includes filters for trying out different node type combinations.

*Current Issues:*

* d3's json slurping method fails in the local environment. Throws an XMLHttpRequest error interpreting the file as being loaded cross-origin. Will take some Googling to sort out.
* Right now looking to merge node types together into a single object to pipe to d3's force object but that will fail because parse.php assigns node type IDs with a different namespace for each node type. This means there's going to be a lot of ID collisions when they merge. Need to update parse.php to use a single namespace for node IDs when generating JSON. *UPDATE:* Think I have this one sorted out.