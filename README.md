What's this?
============
A module for the [Kohana PHP framework](http://kohanaframework.org/) that provides declarative transactions for controller actions, similar to the `@Transactional` annotation of the Spring framework in Java.

Actions that are declared to be transactional have all their DB access (including ORM-based) contained in one transaction, which is rolled back if the action results in an exception, and committed if it ends regularly or `die`/`exit` is called.

Why should I use it?
====================
Transactions are necessary to guarantee the consistency of the database. Managing them manually is tedious and error-prone. This module can make your entire application transactionally safe by adding a single line of code! 

How do I use it?
================

After adding the module, simply add a public property named `_transactional` to your controller. It can have the following values:
* `TRUE` makes all actions in that controller transactional
* `FALSE` makes all actions non-transactional (can be used to override parent class setting)
* An array containing the names of actions (without the `action_` prefix) makes exactly those actions transactional

So if you have a base class all your controllers inherit from, then making all your actions transactional is a matter of adding this single line:

	public $_transactional = TRUE;

Caveats
=======
* The module is based on the assumption that all DB access which happens while serving one HTTP request should be in one transaction. In most cases, this works out just fine. If you need more fine-grained control, you have to use manual transaction management for those actions.
* Transactions are committed in a shutdown function when `die` or `exit` is called (necessary since Kohana does that after redirecting a request). If your application uses `die` or `exit` for error conditions, transactions will not be rolled back - so use exceptions instead.
* Obviously, it only works with DB engines that support transactions (i.e. not MyISAM).