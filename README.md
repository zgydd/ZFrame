#ZFrame

A experimental server middleware for distributed architecture with PHP

#History

Init readMe at 2017-01-23.

V0.0.10 defined at 2017-02-10.

#Comment

##V0.0.10

Build a sample frame with four level: Router, Builder, Register and service.

Include Libra V0.0.1

###Data format
{uuid, head{routeFlg, modelFlg, servicesList[], dataFrom[], dataTo[]}, entity{body}}

#####Router:

Transfer data to Builder by routeFlg in head within Libra's map.

#####Builder:

Call register within Libra's map and can do somethings by modelFlg in head.

#####Register:

Call one or more services used curl_multi by servicesList in head within Libra's map.

#####Service:

Get data used PDO.
