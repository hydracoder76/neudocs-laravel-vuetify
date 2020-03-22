# Data Formats and Validation

# - JSON Requests -
## What's used?

Currently, any date utilizing JSON is to be validated via schema from the 
[JSON Schema Organization](https://json-schema.org/)

This group has setup a schema definition language (written in JSON) similar to XSD for xml (which itself is 
written in xml). These definitions can be used to validate JSON objects such that we can ensure the data we expect to be
in a given request is there and valid. Currently, a rule is in place for FormRequest objects to use to validate
json based on a given schema. So, using this, we know that a request that requires a string in a property called
"some_data" is actually string in a field actually called that when validated. In this way, we know before access
whether or not the data is correct.

## How is this validated?

There are several libraries out there that implement the standard outlined by the JSON Schema organization. The one in
use by the SRM system is built for PHP as a composer package 
available [here](https://packagist.org/packages/justinrainbow/json-schema).
Others exist, however this is just the most popular, and is extremely easy to use. An example usage is used below.

## What does a schema look like?

Here is an example schema, followed by a description of what the pieces do, and then more information can be found on
the website

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "title": "Upload",
  "description": "File to be uploaded to the SRM system. Passed as a parameter fo a formdata object",
  "type": "array",
  "properties": {
	"file_name": {
	  "description": "The name of the file without extension. Will be normalized on the server if needed",
	  "type": "string"
	},
	"file_ext": {
	  "type": "string",
	  "description": "The actual file extension to be used later for construction of the file"
	},

	"file_mime": {
	  "type": "string",
	  "description": "Proper mime type of the file, may be guessed by the server if need be"
	},
	"file_size": {
	  "type": "integer",
	  "description": "size in bytes, gives the server a heads up"
	},
	"user_id": {
	  "type": "string",
	  "description": "ID of the user uploading the file, hopefully that's obvious"
	},
	"time_of_upload": {
	  "type": "integer",
	  "description": "unix timestamp of the upload time, can be used for debugging later"
	},
	"locale_of_upload": {
	  "type": "string",
	  "description": "locale to apply to the timestamp",
	  "minLength": 5,
	  "maxLength": 5
	},
	"upload_type" : {
	  "type": "string",
	  "description": "Used to determine what upload type to put in the database",
	  "enum": ["DIRECT", "SCAN"]
	}
  },
  "required": ["file_name", "file_size", "file_ext", "user_id", "upload_type"]
}
```

This schema defines the required metadata that is used when uploading a file in the SRM system. How does it work?

**Not all fields are required in the description, please see the documentation**

- The first three properties are meta data for the schema, with the first being a link to the schema draft we will
be using to validate against, in this case draft 7

- **type** is the first meaningful property we'll be using. The values should be either **object** or **array** and will
define the initial type passed in. Is it a single object, or an array of objects?

- **properties** is the key used for the object that will contain the actual definitions of the object(s) in the data.

- The **properties** object contains on the first level the property name, which is itself an object document that contains
the rules for that to follow.

- In the case of the file metadata, we are saying that if a property named **file_name** is seen, it MUST have the type
of "string". **type** is required in this case, whereas description is optional, and is useful for a developer opening
the schema definition to know what it's for. This is useful since upon validation, we get some amount of type checking.

- Other items of note are additional rules we can give to a property. Here you can see **minLength** and **maxLength**
used on a string field. As the names imply, these numeric fields put additional restrictions around what kind
of data can be put in that field. One of the most important properties include is the **enum** rule, which says that
not only must a property contain a string, it must contain one of the strings listed in the **enum** array, which
is a great way to avoid arbitrary string data that is unpredictable and has very little semantic meaning (magic strings)

- Once the properties themselves are defined, we then find other descriptors about the data packet itself, in this case
which properties are required to be there. If a document that validates against this schema comes in via a request,
you can be assured that those fields are there. 
