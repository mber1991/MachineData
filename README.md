MachineData
===========

Fetches machine data over network and presents it nicely.

The webserver (Apache) is being run on a UNT machine joined to Active Directory, it is installed as a service and running under our administrative account. The index.php page uses php's .net functionality to connect to a machine and fetches useful information and presents it to us in tables. This process is much faster than having to do so manually.
