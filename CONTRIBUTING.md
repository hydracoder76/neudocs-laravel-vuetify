# Code Submission Guidelines for NeuDocs SRM

## Code

### Raw PHP

There should be no plain PHP scripts created outside the framework, as by and large they are not necessary.

If there is a situation in which it's thought one may be needed, please let [Mike](mailto:mlawson@neuone.com) know.


### Artisan Commands

All artisan commands will fall under the neusrm namespace.

For example, a command to manage users might look like

`php artisan neusrm:user`

Commands should only do what they are meant for. For example, the above command to work with users
should only perform CRUD operations on the User model. If Permissions need to be tweaked, another command
should be used.

However, a command doesn't need to exist per model, nor is a command restricted to one model. As long as the command
does its one thing, then it's ok. There shouldn't be a command to manage both users and projects, for example.

When creating a command, ensure that command has no dependencies such that running it will ensure
that all dependencies are taken care of at the same time. For example, the neusrm:user command
checks to see if there are any companies to assign a user to, and then allows you to create one
if not.

### Unit Testing

Unit testing will be performed for every opened PR. If a PR causes the tests to fail, the PR will
be rejected automatically (once all is setup and ready to go)

All API unit testing will be handled via PHPUnit. A sample config file is already available in the project root.
PHPUnit 7 should suffice for our testing. 
[Laravel itself has some testing faculties built in](https://laravel.com/docs/5.7/http-tests). You can use
either the Laravel helper methods, or native PHPUnit methods.

Artisan commands should be tested as well. However, the tests for these do not need to be as
comprehensive. More information [here](https://laravel.com/docs/5.7/console-tests)

Laravel Dusk will be used for "front end" testing from the server via headless browser.
This can be used to test application workflow, as well as Vue component state. Frontend
tests will still need to be created to test JavaScript/ES6 itself.
More information dusk [here](https://laravel.com/docs/5.7/dusk)

**NOTE:** make sure a file called .env.test is created in the project root to contain your test
environment variables.

Testing the database itself is also possible. Not required but highly recommended
see [here](https://laravel.com/docs/5.7/database-testing)

### Database

There should be no manual changes to the database schema or potential seed data. One major reason
for this is to keep everything in sync at all times, another to be able to version control the state
of the database.

Migrations provide a clean API for defining what the database should look like. Some things to keep
in mind when writing a migration for a NEW table

All tables should be in their plural form unless otherwise specified. For example, a table holding "user" information
should use the "users" table. Artisan provides a handy argument to do this automatically in the scaffold

Testing should never be performed against the main application database. Create a database with _test in the name

For example, neu_srm becomes neu_srm_test and all unit tests will be run against that database instead. This
keeps test data out of the main datastore which can corrupt data already present.

## Style

### PHP Code

All PHP will adhere to the modified PSR-2 format configuration that will be made a part of this
project. The following gist should give the setup should you not be able to import the settings

If you need more information on PSR-2 please see [here](https://www.php-fig.org/psr/psr-2/)
and for the initial PSR-1 style guide, please see [here](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)

All methods in PHP code must have a valid return type, and according to standards, docblock

All methods that could cause an exception should handle exceptions in some way

API methods should only ever use the api controller's response methods to construct responses.

Models should never be accessed directly from controllers or services. The idea here is to separate
request handling, logic, and data management, in to separate and independent (thus loosely coupled)
layers. This is better for testing and debugging, as well as style and organization.

Lazy load or Lazy eager load related models whenever possible

If you see the keyword "new" in a method, see if you can handle this another way, such
as with the resolve() helper to keep everything fully independent. resolve uses the IoC container to
create objects instead of directly creating concrete instances, meaning DI is respected. This will also
allow you to provide an interface to the container to retrieve the concrete implemented bound to it
in the AppServiceProvider.

NOTE: There will likely be some extensions to hte AppServiceProvider created to help distribute bindings
across the application so we don't have a huge provider on our hands.

Try to keep methods to around 15 lines or less. This rule of thumb is to ensure that a method
does one thing and one thing only. If a method is doing too much logic at once, this hurts the ability to
test, and makes code hard to maintain and read. If you find that a method is doing too much or too long
consider splitting it up.

Some methods are not required to have a return type, namely controller responses. In the event there is
the possibility of a mixed type, there are two options. First, creating a wrapper object that allows
for the return of one type with a value that represents the value that would have been returned of
different types. Or, just leave off the return type and ensure the calling code assumes there could be
a secondary type. Try to limit this possibility as much as possible. Since we're on PHP 7.1, there is a way
to specify a special return type, which is setting a return type to nullable, like so

```php
public function foo() : ?string
```

This will allow the method to return a string, or null without a type exception.

The concept of Formatters and Typed Collections is to be used whenever applicable, and their purposes are 
as such

- Formatter is an interface that, when implemented, formats incoming data to some intermediary format
suitable for return to the client or other calling code. Formatters are resolved via DI and often bound
using contextual bindings from the AppServiceProvider or a child class of that provider. These are very
useful classes that can provide a wealther of flexibility in data transformations.

- A Typed Collection is merely a class that extends Eloquent Collections with a single method to return
the model class name, and that's about it now. What this allows us to do is return collections of whatever
type of model specified so that we can return one of these extensions as the collection result of a 
repository method. For example

	- Say you've returned an eloquent collection of Project entities. You can create a ProjectCollection to
	tell the calling code that you have indeed a collection of projects, so, anything that comes out of
	the collection is in fact a project. We can extend as needed to do appropriate type checking.


### JavaScript/ES6 Code

All JavaScript/ES6 code will be run through an eslint parser and validated against a rule
document, and this document will also use the VueJS extension. Once placed in the project root,
IDEs like PHPStorm will automatically pick them up and point out errors and warnings as you go.
This will go ahead and enforce all standards set. If you have a suggestion for a particular change to our
styles, please let me know [Mike](mailto:mlawson@neuone.com)

eslint is used to enforce certain style standards. There are overrides available in the event specific
rules must be turned off. While a lot of rules return warnings in the event something may be needed,
most rules throw and error in PHPStorm and will underline in red. When run through eslint, these
will cause a test failure and must be fixed.

The eslint rules also include style rules for VueJs templates as well. If you take a look as the .eslint
config file you'll find the extension there, along with some overrides.

### SCSS Styles

Proper use of our styles is important for making sure we have a maintainable system. Please view
the documentation for SASS and from there SCSS (more closely related to CSS in syntax) to get an
idea of how it fits together [here](https://sass-lang.com/guide)

All styles written by us as original styles must be prefixed with the neu- vendor prefix. Any styles
that are pure overrides to existing styles, such as what is used for the left nav, those will
go in to an SCSS file under the overrides directory with a filename based on the actual plugin or
extension they override. For example

```php
resources/sass/overrides/_vue-sidebar.scss
```

this represents the left nav. Other overrides for other components would follow a similar format.

Whenever possible, do not use built in bootstrap styles for the visual aspects of the application, 
such as colors, buttons, etc. It is more recommended that bootstrap be used for placement using
the grid system, and other such widgets like modals. Wrap in our own component if possible
so that we can extend as needed to suit our purposes.

## Source Control

### Format

All task commits should start with the ticket ID in the message, so something like
NS-1 would have "NS-1" as the start of the message, followed by a brief summary of what was done.
If the changes made were just compilations, whitespace, or other changes that don't actually effect
the source code, this is not necessary.

### Jenkins

Once committed, a PR should be opened against the master branch (or possible release branch) on the
main repository. After opening, the unit tests on Jenkins should be run, and once finished a report
will be sent back to github to approve or deny the PR. If denied, the PR is rejected and a new one
should be opened with the fixes made in order to let the unit tests pass. This will happen for
every PR opened, so if you need to, group commits together to avoid extremely long processing
times.

Jenkins will run tests on the following, and reject based on such

- dusk tests: Jenkins will run laravel dusk and perform the same teseting we perform locally

- phpcs check: Ensure that all php code follows the provided styles, failing if errors are found

- eslint check: Ensure that all JavaScript code follows the provided styles via eslint, failing
if errors are found.

- phpunit tests: phpunit will be run for all tests with the following goals

	- the tests themselves pass

	- code coverage meets a given threshold. At the beginning we will start low, but this will increase
as time goes on.

After this process is finished, Jenkins will allow manual review to begin.

Jenkins will likely periodically scan the repository for changes and run all tests on a separate
executor thread, and send out reports should the build fail on a spot check. For the future.

### Manual PR reviews

In addition to the testing and linting done by Jenkins, a manual review should be done
to detect any errors that might be impossible to detect, such as logic errors, typos, etc.
The checks can be done during or after the Jenkins testing, preferably after to ensure no current
code changes.

### PR approval

Unless otherwise agreed upon, no manual overrides of a PR failure should be performed. All tests
and reviews must pass in order for the PR to be approved, with the exception of non behavior related
changes such as compilation, whitespace, etc.


## Addendum

### Contact and Meta for this document

Current version: 1.0.1

Current Authors: 

- [Mike Lawson](mailto:mlawson@neuone.com)

Last modified on: 12/10/2018

### Notes

When testing with dusk, use the dusk= attribute on any component or element you'd
like to be able to access via dusk selector. For example, if we say dusk="foo" then in
a test we can grab that element with "@foo"

### Misc

Extension and abstraction is the name of the game. While it may seem like we aren't introducing new
functionality in all cases, this idea is important because in the event we do need to add our own
functionality, we aren't reinventing the wheel, and we are making it easier on ourselves to make
a component or class or such do what we need it to do.


**This document may change at any time**