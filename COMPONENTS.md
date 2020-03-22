# NeuSRM VueJS Components

This documents VueJS components created for the NeuSRM project. Ideally these components can be reused
in such a way that we can adopt them across our project ecosystem to speed up development across the board.

While some of these components are specific to the NeuSRM project, others are generic enough to be
able to stand on their own.

Document Meta:

Version: 1.0

Author and Primary Contact: [Mike Lawson](mailto:mlawson@neuone.com)

Current VueJS Version: 2.5.7

## So what's going on?

We've got a growing number of components being built for the NeuSRM system in order to enhance and encourage reuse, and to create a consistent experience across the application.

That said, there are several items and rules to follow to get proper usage out of these components, and writing new components moving forward. In the interest of consitency with our system and components, there will be some guidelines in place regarding naming, structure, etc.

Proper usage of these components doesn't make for consistent UIs, but consistent development practices as well. If these components are only loosely coupled to NeuSRM, they can be reused across our all of our newer offerings, and older offerings that are having their UIs updated such as NDE.

Additionally, consistent setup of these components makes testing easier. To get to where we need to be, not only do Dusk tests need to be created, but actual JavaScript unit tests as well. Dusk is a powerful tool, but only truelly useful in a Laravel environment. It's possible to take pieces of Laravel and apply them elsewhere, and while possible (I believe) with Dusk, it isn't as portable as something like karma and phantomjs with mocha which performs front end testing on the javascript itself, just not on the UI directly like Dusk does., as they are two different ideas (one functional, one behavioral). 

## Naming and naming conventions

### Non Page Component and prop naming

All components that we create need to have the prefix Neu, as this signifies that the component was created by Neuone as part of our internal libraries. In doing so, we distinguish our code from the code of other vendors, such as how Bootstrap Vue prefixes its components with a "b"

For example, a component representing a given item on a page, such as a text field wrapped as a VueJS component would be "NeuTextField" which would in turn create the element 
```html
<neu-text-field></neu-text-field>
```
in markup. 

In addition to the Neu prefix for compontents, both the names of components and props need to be properly camel cased as specified in the other style documentation found in the contribution document. Vue will automatically convert properly camel cased names to kebab case, which is current best practice for Vue elements like props and components.

For example

```html
<!-- filename: NeuTextField.vue makes -->

<neu-text-field></neu-text-field>

<!-- filename: NeuTextfield makes -->

<neu-textfield></neu-textfield>

<!-- filename: Neutextfield makes -->

<neutextfield></neutextfield>
```
The first is easier to read, and ide's will have an easier time trying to auto complete the field. However, the second does contain the correct prefix, but the second word is not as easy to field and can be easily missed when inconsistent with other formats. The third is even harder to read as it all looks like one nonexistent word

Properties follow the same rule, but do not necessarily need the neu prefix as props are always a given specific to a component, so they are defacto prefixed with our vendor prefix.

When a property is defined it should use the same camel case naming convention as above, both for readability in JavaScript and in markup, as it will be appropirately converted to an easy to read and type kebab case version.

```ecmascript 6
// assuming this property
export default {
	props: {
		newProp : {
        	type: String,
        	required: true
        }	
	}
}


//<!-- when reference by a parent component we'd get -->'

// <some-comp new-prop=""></some-comp>'

// however, if we don't camel case properly
export default {
	props: {
		newprop: {
        	type: String,
        	required: true
        }
	}
}




//'<!-- we dont get a nice name -->'

//'<some-comp newprop=""></some-comp>'
```
so on and so forth. Use your best judgement to determine where a word should separate if it's unclear

### Page Component Naming

Page level components imply those components which serve to act as a container for the rest of a given view to be
built. For example, the company management page is defined in the Company.vue component. If a 
Vue component is used for a given view, the component name should match the name of the
blade view file that it is used in. So, Company.vue corresponds to company.blade.php and the
other way around applies too.

If a component is planned to be reused elsewhere, it should not follow this convention. Any non
prefixed component is considered a base component used to define a container for other components that
would be reused. Those are the components that have the "Neu" vendor prefix.

### Event Naming

Events bubble up from a child component to the root Vue component, and in order to distinguish
an event from one of our components to that of another vendor or library component, all event
names for custom events should be prefixed with our same vendor prefix, "neu-"

For example

```ecmascript 6
export default {
	methods: {
		throwAnEvent() {
			this.$emit("neu-thrown-event");
		},
		anotherEvent() {
			this.$emit("neu-other-event");
		}
	}
}
```

If an event is not prefixed in this way, it is to be assumed that the event was not emitted from a Neuone
component. PRs that come in emitting custom events that do not use this convention should be rejected.

## Component Creation

### Criteria

Any non page level component should follow the single purpose or single use principle. A
component should represent one piece and one piece only. For example, a component represent a list
of data should not be concerned with regards to where the data is coming from, all it knows
is that it will be fed the data. 

Components should only use slots to allow components to be "embedded" inside that component.
So, if a feature to be implemented requires a modal dialog, and that modal dialog is triggered by something 
in a table, the modal dialog is its own component, as that is to be reused as an existing component.
The data table should not contain any code to build the modal outside events that could be thrown
based on the state of the table, such a row hovers, clicks, etc. Only state should be communicated,
not behavior. 

If a piece of functionality is needed that cannot be created using existing components without
having to get clever or hack those components in ways not intended, then a new
computer should be created.

### Methods

Any methods created should not be duplicates of methods created in other components. If a method
is a duplicate, or can be redefined in such a way that it is a duplicate of a method in another component,
then that method needs to be extracted in to a mixin.

Method names must follow the following rules

- Camel Cased
- No numbers in the method name
- No use of the function keyword (changes the context of "this")
- No property labels as method names that reference a short form method (this is only to be used
in factories and method reference passing)
- Parameters for the method must be camel cased
- Method names and parameter names must convey some sort of reasonable meaning in regards
to the purpose of that method or parameter.
- If possible, all methods should have a docblock giving the expected data types
of parameters. This doesn't always apply but will be checked by eslint
 
```ecmascript 6
export default {
	methods: {
		badname(someparam) {
			// name is not camel cased
		},
		goodName(someParam) {
			// name is camel cased
		},
		badName2() {
			// name should not have numbers
		},
		foo: function() {
			// no property methods
		},
		bar: () => {
			// no property methods even in short form
		},
		push(a) {
			// conveys no meaning
		},
		/**
		* nice to have
		* @param {string} sideBarId
		*/
		pushSideBar(sideBarId) {
			// conveys meaning and purpose
		}
		
		
		
	}
}
```

Methods should also follow single use, and as such should be made easily unit testable
by javascript unit tests.

### Properties

Properties should always be typed checked appropriately, and should be general enough to account for reuse
and unless page level, not contain anything specific to usage. For example, a sidebar component
should not contain properties that are only valid if the component is used inside of a modal window.

You will be warned anyway while developing, but properties are considered immutable and while
possible, should not be changed at any point by a child component. Either use a computed property
setter, or use the prop as a default or initial value for a component data property.

Use a watcher if the value of a property should be reactive

### Computed Properties

While they should be avoided, setters can be used if a computer property absolutely must be mutated for
some reason

Computer properties should be single purpose, and, since they are defined by methods,
follow the same rules that methods follow.


#NOTE: Not all of these features are currently available and are pending implementation

###Additionally, some components are page level components and not considered reusable


# Available Components

## BurgerMenu

#### TODO: This component is too specialized and not following standards

BurgerMenu components represent an accordion widget for navigation purposes. It extends and wraps the existing
Menu component which extends the SidebarMenu component from NPM. 

### Props

| Name | Type | Required | Default | Purpose
|:------|:------:|:------:|:-----:|:-----:|
| projects | String | false | "" | A stringified JSON object represent a project list from SRM |
| it | Boolean | true | | Whether or not the logged in user has IT level permissions |
| child-options | Array | false | null | Array of JSON objects to define child items under any given section |
| options | Object | false | {} | For backwards compat, mostly unused |

### Events

| Name | Emitted When | Payload
|:------|:------:|:------:|
| openMenu | When the menu is to be opened | N/A
| closeMenu | When the menu is to be closed | N/A

## NeuForm

NeuForm is a powerful component in that it allows for two form types to be created based on the most common needs
for form layouts. First, there is the traditional page level form, then there is a form embedded inside a modal.
These can be toggled via the is-modal prop. Everything else happens automatically. Slots can be used to customize
any aspect of the form, and all form events are passed through via the neu-submit event, which sends along
the current data state of the form for whatever purpose the parent needs. There is also an event fired to clear
any fields necessary, should that event be captured.

# Unavailable Components

## NeuDateInput

A NeuDateInput component is used to render a text input representing a date. These fields
are validated as such. Using v-model, it's easy to get access to the data stored as text,
or as a moment.js object, configurable by prop.

### Props

| Name | Type | Required | Default | Purpose
|:------|:------:|:------:|:-----:|:-----:|
| position | Number | false | 0 | Position in a list of components |
| neu-date-field-label | String | false | | The label to display next to the field |
| neu-date-field-id | String | true | | Id for the input field to use


### Events

| Name | Emitted When | Payload
|:------|:------:|:------:|
| neu-input-entered | Anytime the input itself changes | The value of the input
