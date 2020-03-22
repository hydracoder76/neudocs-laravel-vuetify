# Neubus SRM in a nutshell

## The Whats

### What is it?

The NeubusSRM system allows users to send in their own requests for data fulfillment by Neubus in the form
of submitting requests against a given record. Users are split up by company and then further by project.
Admins for a company can add and remove users, as well as create and remove projects, add indexing fields for
a project, as well as manage existing requests.

On the other end of the request system is a fulfillment warehouse that will receive the request and then upload
any necessary documents, or perform whatever action is necessary to satisfy the user's request.

### What is it made of?

The SRM system is built on top of:

- [Laravel 5.7](https://laravel.com)

- [PHP 7.1](https://www.php.net)

- [PostgreSQL](https://www.postgresql.org/)

- [VueJS 2.5](https://vuejs.org/)

- [SCSS](http://sass-lang.com/documentation/file.SCSS_FOR_SASS_USERS.html)

All technologies used are meant to be bleeding edge, but stable, and provide a good learning opportunity
for anyone looking to further their skills in that department. 

### What if I have technical or business questions?

You'll want to contact [Elten](mailto:elogis@neubus.com) or [Mike](mailto:mlawson@neuone.com)

## Developing for the SRM application

The SRM application represents a new model for development moving forward. It introduces some new concepts
that we haven't had previously, as well as to help ensure best practices are followed. We won't always
be working on this application in the future, but it's best to make sure that future developers are taken
care of when working on our code.

Code reviews are essential, and tools such as PHPUnit, jasmine, PHP Code Sniffer, ESLint, and Jenkins will 
help ensure quality. In fact, unless a commit and push passes all of the above checks, the github PR for that
change cannot be approved and will be sent back for changes.

All features must be tested to the point where PHPUnit coverage is over 70%. That number is based on
some averages and recommended best practices for unit testing, however it's on the low end. 

## The Cookbook

You can find a Chef Cookbook in the cookbooks repository under the neuone organization, the repo is:
[here](https://github.com/Neuone/Cookbook)

Clone that repo to get access to the cookbook inside the neubus_srm cookbook

There are other helpful cookbooks in there, as well as some under development for experimental
purposes.

Items you'll need:

[Vagrant](https://www.vagrantup.com/downloads.html)

[ChefDK](https://downloads.chef.io/chefdk/3.4.38)

Vagrant plugins

```bash

 vagrant plugin install vagrant-omnibus
 
 vagrant plugin install vagrant-share
 
 vagrant plugin install vagrant-vbguess
 
 ```

Then you'll need to modify the Vagrantfile inside the cookbook. At the top are some constants

If you look at them they're pretty self explanatory, just set to the appropriate local values.
You'll still need a copy of the code locally, this just sets up the development environment.

Also, don't forget to set /etc/hosts to the appropriate domain and ip address, which are both
listed in the Vagrantfile

Once you're ready to rock and roll, go in to the cookbook and run

```bash

berks update && berks install && vagrant up

```

If it's the first time you've used the cookbook, use that command, otherwise, just run

```bash

vagrant up

```
and the machine will be good to go once it starts, enjoy!

(FYI: anything involved the database will need to be done from inside the vm, so use 
vagrant ssh to login from the cookbook root)

# Programming Guidelines

## Style

### PHP

- All PHP source must be valid against the **phpcs.xml** and **Modified PSR-2.xml** files in the project root

- If using PHPStorm (which I highly recommend for this) you can just import these files as
"inspections" to use when writing code in real time. Warnings will just highlight keywords in yellow
while those things considered errors will be underlined in red. Anything regarded as an error will
cause the build to fail in Jenkins when the PR is opened, so these are required to be fixed.

- This uses a modified PSR-2 standard, which mainly sets the opening method brace on the same line
as its signature instead of directly below. 
Otherwise you can follow the information [here](https://www.php-fig.org/psr/psr-2/) on the standard

- Unless a parameter can be of a mixed type, object or scalar typehints must be used

- Unless completely unable to, all methods should specify a return type.

- Use abstractions wherever possible, as well as custom exceptions if needed

- All API endpoints should have a unit test written for them testing
multiple types of cases via PHPUnit

- Make use of the framework, learn it and don't be afraid to use what's available. DI is a must
and will be checked for. Good abstractions, separation of concerns, and other methodologies should
also be taken advantage of.

- Interfaces are your friend. Use the service container and providers to be totally rockstar
with them. The underlying implementations for some aspects of the application could change
at any time.

- All method and variable names should be camelCased, with the exception being "magic properties" created
by the ORM. Those will match the column names in the database, which are snake cased by convention.

- phpcs will enforce naming limitations in terms of characters

- No class names should use PEAR namespace conventions, meaning This_Class_Path_Abc should not be used.
In that case, the class file would be at This\Class\Path; and the class name would be Abc.

- Do not use double quotes where you can use single quotes. If you do not plan to use
string interpolation, use single quotes.

- No raw SQL should be written. The framework handles all of this for you, make use of it.

- No manual changes to the database, use a migration

- Outside blade directives, no PHP in Views

- Minimize the warnings that phpcs will give if you don't follow the style guide

- Variables should always be initialized outside conditional or loop scopes, even though PHP
scoping rules allows for variables to be defined inside. This is a readability concern, and helps
prevent cases in which a null or uninitialized variable is used.

- Methods may not start with an upper case letter

- Methods should do one thing and one thing only. If the number of lines in a method is going over
15 or so, it's time to see if you're doing too much and need to split up the work.

- Do not use the main database for testing. Only use a test database, you can configure one in the testing
.env file, .env.testing

### Laravel

- If it loads a view, use the Controller super class, if it serves JSON to some JS, use the ApiController
class.

- Make good use of namespace declaration and prefixes in the route files web.php and api.php

- Always give routes a suitable name

- API routes should use the proper restful method

- Anything that involves a user submitting information should be validated with a Request object

- For validation with Request objects, use Rule objects for any custom validation

- Follow the controller -> service -> repository -> model convention.

- Services should only focus on one aspect, but can use multiple repository objects

- Services shouldn't need any more than 3 and at max 4 repositories. If more are needed, it's time
to downsize the service and see if it's doing too much.

- A repository should only act on one model class, meaning there would be a repository
for each model in the applocation.

- The constructors for anything of this nature should be left empty, and only type hinted with
injectable classes. The word "new" shouldn't be used anywhere other than data persistence with
model objects, or if some internal class needs to be created. Try to use factories where
possible. This makes code more testable and easier to read, and keeps things more loosely
coupled. 

- Utilize everything the framework has to offer, there's lots of components to make for
a very robust application

- Do not uses the SQL builder unless absolutely necessary. The ORM abstraction is just fine
the vast majority of the time

- Relationships are already setup in the models, but can be improved if necessary, or new ones
created. Should be no need for sub selects or joins, the ORM will handle this automatically

- Model directories are setup based on the purpose of that given model. If you need to make a new
model (a new table has been added for example) please make sure it's categorized properly

### SCSS

- All created styles should be prefixed with neu-

- Use SCSS variables wherever possible

- Instead of just utilizing raw bootstrap styles, extend them and modify to fit our theme.
How to do this is in the SCSS documentation

- Make use of what SCSS really offers.

### JavaScript/ES6

- Vanilla JavaScript should not be needed. In the event it is, follow PHP standards as far as
what is doable in JavaScript, granted this will go for all.

- ES6 code will be run through eslint, which should pick up most style problems

- If code is being written to its own .js file, that file should be an ES6 class

- Unless positive a variable will need to change, use constants. Otherwise, let is sufficient. 
Do not use var

- Always use typechecked conditionals, === instead of ==

- Objects should use camel case property names, the eslint rules should already enforce this

- Make sure that any packages grabbed from npm are properly saved to package.json

- all JS/ES6 code should be unit tested with Jasmine syntax and the Karma test runner

- No jQuery

### VueJS

- Utilize single file components, there shouldn't be any need to do otherwise.

- If you need to modify a style for a single component, use the scoped keyword in the style definition
whenever possible.

- Keep components to serve a single purpose, even if a comopnent is a composite of other components.

- Use slots wherever possible

- No jQuery, not even for AJAX. Use [axios](https://www.npmjs.com/package/axios).

- When utilizing bootstrap-vue, import only those components that you will be using in a given
component. 

- Unit tests can be written for Vue just as any other, the karma config in the project root
should already account for it. See [here](https://vuejs.org/v2/guide/unit-testing.html)

- Use prop validation for components

- Never try to modify a prop directly, use events to communicate with the parent component

### General testing and pull requests

- All facets of the application need to have unit tests written, this includes javascript
(via karma and jasmine), front end actions (via Dusk), and back end testing (via phpunit)

- The tests should be performed locally before you commit anything to github, however they will
be tested anyway when a PR is opened against the root project, an not approved
until jenkins agrees that all tests are passed. Let [Mike](mailto:mlawson@neuone.com) if
you have any problems with false positives regarding failures.

- All code should be written to be testable. Stay away from the "new" keyword in PHP, unless
passing that object to another method, and also use dependency injection and bindings to provide
class instances to code. Ensure that methods do one thing and one thing only. Utilize DRY principles.
Take a look at some testable code best practices. 

- If possible, commit each subtask to a branch that represents an entire story. When all subtasks
are complete for that story, commit the story branch. This will allow any code dependencies and tests
to be included.

- All stories should have tests written unless otherwise noted

- All general tasks and bugs should have tests either written or modified unless otherwise noted.

- You can find the jenkins instance itself at https://linux7.sima.io:/jenkins however you will
likely not have access to do anything but run and view the output of any jobs you create via PR

## Misc

### General

Don't be afraid of places like npmjs.com or packagist.org, hey are great repositories full of great tools. 
If you think you need something to make this application better, go for it, or even talk to the team
about your intentions and what you'd like to do.

There is a little bit of a ramp up period if you've never used this set of practices before, so please
study up and let Mike know if you have questions or are having trouble setting something up. I know
PHP Code Sniffer can be a little finicky. Always try on your own first, and if you find yourself struggling
after one or two hours, then it's time to raise your hand. Struggling is part of learning!

If you have any ideas at all on how to make this application even better, don't be afraid to voice those ideas

### Recommended tools

- PHPStorm

- phpbrew (for managing different php versions)

- phpunit

- global install of the karma runner, jasmine, and the phantomjs browser and possibly eslint

- xdebug

- A *nix environment, so either Linux or macOS

- An xdebug helper for your browser

- Laravel ide-helper files if not in repository

- vue-cli (just in case you need some template examples)

note: these will be used if you decide to uses the vm version

- Vagrant

- Ruby (will be used for vagrant)

- ChefDK

- VirtualBox

### Possible setup and configuration issues

####Laravel

-To run dusk, set up an .env.testing file with APP_ENV set to 'testing',
and in tests/DuskTestCase.php set the APP_ENV to testing to match.
You may need to chmod the file vendor/laravel/dusk/lib/chromedriver-linux
(or the file for the corresponding OS) to allow it to be executable.
If tests run slowly, download chromedriver 2.36 instead of the installed
2.35 and replace the chromedriver file with it.

####Permissions

Steps to follow:

Ensure that both the user you are executing artisan commands with, and the user that apache is running under, are both in the same group. This group is assign in the httpd.conf file for the webserver, and you can see what group your user is in by running

```bash

sudo chown -R <apache owner> ./

```

Where the ./ is the app root

```bash

sudo chgrp -R <apache shared group> ./

```

again on the app root

```bash

sudo chown -R 775 storage \

sudo chown -R 775 bootstrap/cache

```

from app root

If there are still problems, please consult the accepted answer here:

https://stackoverflow.com/questions/30639174/how-to-set-up-file-permissions-for-laravel-5-and-others