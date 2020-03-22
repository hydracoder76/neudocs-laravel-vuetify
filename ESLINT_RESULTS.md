# ESLint Report - Error

## Summary

945 problems (701 errors, 244 warnings)

### Errors

| rule | count | visual |
| --- | --- | --- |
| semi | 75 | XXXXXXXXXXXXXXXXXXXX |
| vue/script-indent | 515 | XXXXXXXXXXXXXXXXXXXX |
| vue/name-property-casing | 2 | XX |
| comma-dangle | 17 | XXXXXXXXXXXXXXXXX |
| eqeqeq | 2 | XX |
| vue/require-default-prop | 9 | XXXXXXXXX |
| guard-for-in | 5 | XXXXX |
| camelcase | 4 | XXXX |
| no-shadow | 3 | XXX |
| no-invalid-this | 4 | XXXX |
| no-param-reassign | 4 | XXXX |
| complexity | 3 | XXX |
| vue/attribute-hyphenation | 1 | X |
| one-var | 1 | X |
| space-infix-ops | 7 | XXXXXXX |
| no-mixed-spaces-and-tabs | 35 | XXXXXXXXXXXXXXXXXXXX |
| no-loop-func | 2 | XX |
| vue/no-side-effects-in-computed-properties | 4 | XXXX |
| vue/mustache-interpolation-spacing | 4 | XXXX |
| vue/require-v-for-key | 1 | X |
| vue/no-dupe-keys | 1 | X |
| no-dupe-keys | 1 | X |
| no-undef | 1 | X |

### Warnings

| rule | count | visual |
| --- | --- | --- |
| no-unused-vars | 9 | XXXXXXXXX |
| init-declarations | 28 | XXXXXXXXXXXXXXXXXXXX |
| prefer-const | 15 | XXXXXXXXXXXXXXX |
| quotes | 108 | XXXXXXXXXXXXXXXXXXXX |
| curly | 5 | XXXXX |
| no-eq-null | 4 | XXXX |
| id-length | 16 | XXXXXXXXXXXXXXXX |
| comma-spacing | 46 | XXXXXXXXXXXXXXXXXXXX |
| dot-notation | 12 | XXXXXXXXXXXX |
| consistent-return | 1 | X |



## Details


### /Users/mlawson/Work/srm/resources/js/classes/neu-menu-builder.js - 16 problems (2 errors, 14 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 18:60 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 41:60 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Warning``` | 30:12 | &#39;schemaJsonDef&#39; is defined but never used. | [no-unused-vars](http://eslint.org/docs/rules/no-unused-vars) |
| ```Warning``` | 52:7 | Variable &#39;allIndex&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 53:45 | &#39;menuIndex&#39; is defined but never used. | [no-unused-vars](http://eslint.org/docs/rules/no-unused-vars) |
| ```Warning``` | 54:51 | &#39;index&#39; is defined but never used. | [no-unused-vars](http://eslint.org/docs/rules/no-unused-vars) |
| ```Warning``` | 55:9 | &#39;parentHref&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 55:9 | Variable &#39;parentHref&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 55:57 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 55:63 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 55:68 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 66:11 | &#39;childHref&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 66:11 | Variable &#39;childHref&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 66:65 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 66:71 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 66:76 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |

### /Users/mlawson/Work/srm/resources/js/components/BurgerMenu.vue - 68 problems (48 errors, 20 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 8:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 11:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 12:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 12:13 | Property name &quot;push&quot; is not PascalCase. | [vue/name-property-casing](http://eslint.org/docs/rules/vue/name-property-casing) |
| ```Error``` | 13:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 14:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 15:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 18:20 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 19:5 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 25:5 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 30:47 | Expected &#39;!==&#39; and instead saw &#39;!=&#39;. | [eqeqeq](http://eslint.org/docs/rules/eqeqeq) |
| ```Error``` | 35:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 36:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 37:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 37:35 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 38:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 39:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 40:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 40:36 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 41:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 42:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 43:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 43:26 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 44:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 46:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 48:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 49:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 50:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 51:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 52:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 53:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 54:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 55:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 56:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 58:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 59:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 60:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 61:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 62:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 62:27 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 63:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 64:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 65:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 66:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 67:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 68:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 75:4 | Prop &#39;it&#39; requires default value to be set. | [vue/require-default-prop](http://eslint.org/docs/rules/vue/require-default-prop) |
| ```Error``` | 90:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Warning``` | 8:22 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 12:13 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 30:4 | Expected { after &#39;if&#39; condition. | [curly](http://eslint.org/docs/rules/curly) |
| ```Warning``` | 30:8 | Use &#39;===&#39; to compare with null. | [no-eq-null](http://eslint.org/docs/rules/no-eq-null) |
| ```Warning``` | 30:50 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 32:4 | Expected { after &#39;else&#39;. | [curly](http://eslint.org/docs/rules/curly) |
| ```Warning``` | 44:15 | &#39;width&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 44:15 | Variable &#39;width&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 44:63 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 44:70 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 46:43 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 50:15 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 54:15 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 58:34 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 59:13 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 63:34 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 64:13 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 65:34 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 65:66 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 66:41 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |

### /Users/mlawson/Work/srm/resources/js/components/Company.vue - 19 problems (4 errors, 15 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 75:5 | The body of a for-in should be wrapped in an if statement to filter unwanted properties from the prototype. | [guard-for-in](http://eslint.org/docs/rules/guard-for-in) |
| ```Error``` | 91:27 | Identifier &#39;company_name&#39; is not in camel case. | [camelcase](http://eslint.org/docs/rules/camelcase) |
| ```Error``` | 119:78 | &#39;data&#39; is already declared in the upper scope. | [no-shadow](http://eslint.org/docs/rules/no-shadow) |
| ```Error``` | 215:3 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Warning``` | 75:14 | &#39;i&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 75:14 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 75:14 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 100:14 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 100:14 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 115:14 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 115:14 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 116:15 | Identifier name &#39;j&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 116:15 | Variable &#39;j&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 119:78 | &#39;data&#39; is defined but never used. | [no-unused-vars](http://eslint.org/docs/rules/no-unused-vars) |
| ```Warning``` | 138:34 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 191:124 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 192:74 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 199:111 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 203:116 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |

### /Users/mlawson/Work/srm/resources/js/components/Menu.vue - 220 problems (167 errors, 53 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 18:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 19:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 19:13 | Property name &quot;menubar&quot; is not PascalCase. | [vue/name-property-casing](http://eslint.org/docs/rules/vue/name-property-casing) |
| ```Error``` | 20:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 21:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 22:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 23:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 24:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 25:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 26:3 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 27:4 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 28:4 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 29:3 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 30:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 30:9 | Prop &#39;isOpen&#39; requires default value to be set. | [vue/require-default-prop](http://eslint.org/docs/rules/vue/require-default-prop) |
| ```Error``` | 31:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 32:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 33:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 34:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 34:9 | Prop &#39;right&#39; requires default value to be set. | [vue/require-default-prop](http://eslint.org/docs/rules/vue/require-default-prop) |
| ```Error``` | 35:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 36:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 37:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 38:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 39:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 40:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 41:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 42:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 43:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 43:9 | Prop &#39;disableEsc&#39; requires default value to be set. | [vue/require-default-prop](http://eslint.org/docs/rules/vue/require-default-prop) |
| ```Error``` | 44:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 45:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 46:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 47:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 47:9 | Prop &#39;noOverlay&#39; requires default value to be set. | [vue/require-default-prop](http://eslint.org/docs/rules/vue/require-default-prop) |
| ```Error``` | 48:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 49:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 50:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 51:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 51:9 | Prop &#39;onStateChange&#39; requires default value to be set. | [vue/require-default-prop](http://eslint.org/docs/rules/vue/require-default-prop) |
| ```Error``` | 52:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 53:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 54:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 55:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 56:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 57:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 58:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 59:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 60:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 61:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 62:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 63:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 64:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 64:10 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 65:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 66:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 67:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 68:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 69:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 70:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 71:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 72:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 73:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 74:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 75:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 76:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 78:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 79:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 80:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 81:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 82:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 83:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 84:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 85:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 86:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 86:62 | Unexpected &#39;this&#39;. | [no-invalid-this](http://eslint.org/docs/rules/no-invalid-this) |
| ```Error``` | 87:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 87:17 | Unexpected &#39;this&#39;. | [no-invalid-this](http://eslint.org/docs/rules/no-invalid-this) |
| ```Error``` | 88:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 89:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 89:71 | Unexpected &#39;this&#39;. | [no-invalid-this](http://eslint.org/docs/rules/no-invalid-this) |
| ```Error``` | 90:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 90:17 | Unexpected &#39;this&#39;. | [no-invalid-this](http://eslint.org/docs/rules/no-invalid-this) |
| ```Error``` | 91:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 92:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 93:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 94:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 96:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 97:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 98:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 99:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 100:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 101:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 102:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 103:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 104:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 105:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 106:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 108:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 109:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 109:11 | Assignment to function parameter &#39;e&#39;. | [no-param-reassign](http://eslint.org/docs/rules/no-param-reassign) |
| ```Error``` | 110:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 111:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 112:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 113:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 114:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 115:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 116:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 117:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 117:10 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 119:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 121:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 122:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 123:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 124:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 125:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 126:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 127:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 128:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 129:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 130:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 131:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 132:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 133:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 134:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 135:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 136:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 137:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 137:18 | Method &#39;handler&#39; has a complexity of 5. | [complexity](http://eslint.org/docs/rules/complexity) |
| ```Error``` | 138:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 139:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 139:30 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 140:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 141:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 142:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 142:31 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 143:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 144:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 145:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 146:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 147:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 148:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 149:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 150:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 151:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 152:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 153:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 154:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 155:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 156:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 157:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 158:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 159:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 160:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 161:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 162:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 163:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 164:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 165:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 166:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 167:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 168:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 169:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 170:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 171:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 173:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 174:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Warning``` | 17:28 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 19:13 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 41:20 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 75:22 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 79:40 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 82:36 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 82:61 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 83:36 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 83:62 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 86:37 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 87:30 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 88:17 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 89:39 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 90:30 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 91:17 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 92:39 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 92:70 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 97:22 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 100:13 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 101:13 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 103:35 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 103:60 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 104:35 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 104:67 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 105:35 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 105:66 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 108:24 | Identifier name &#39;e&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 110:25 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 111:37 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 111:62 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 112:37 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 112:69 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 113:37 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 113:68 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 114:51 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 123:37 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 127:35 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 130:38 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 131:38 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 152:40 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 152:74 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 153:40 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 153:75 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 154:40 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 154:65 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 155:40 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 155:66 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 160:40 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 160:74 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 163:34 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 164:36 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 165:41 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 165:66 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |

### /Users/mlawson/Work/srm/resources/js/components/NavBar.vue - 12 problems (7 errors, 5 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 2:29 | Attribute &#39;isOpen&#39; must be hyphenated. | [vue/attribute-hyphenation](http://eslint.org/docs/rules/vue/attribute-hyphenation) |
| ```Error``` | 24:20 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 25:5 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 31:5 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 36:47 | Expected &#39;!==&#39; and instead saw &#39;!=&#39;. | [eqeqeq](http://eslint.org/docs/rules/eqeqeq) |
| ```Error``` | 48:4 | Prop &#39;it&#39; requires default value to be set. | [vue/require-default-prop](http://eslint.org/docs/rules/vue/require-default-prop) |
| ```Error``` | 66:3 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Warning``` | 15:28 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 36:4 | Expected { after &#39;if&#39; condition. | [curly](http://eslint.org/docs/rules/curly) |
| ```Warning``` | 36:8 | Use &#39;===&#39; to compare with null. | [no-eq-null](http://eslint.org/docs/rules/no-eq-null) |
| ```Warning``` | 36:50 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 38:4 | Expected { after &#39;else&#39;. | [curly](http://eslint.org/docs/rules/curly) |

### /Users/mlawson/Work/srm/resources/js/components/ProjectAdmin.vue - 60 problems (31 errors, 29 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 15:40 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 16:31 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 25:33 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 30:7 | The body of a for-in should be wrapped in an if statement to filter unwanted properties from the prototype. | [guard-for-in](http://eslint.org/docs/rules/guard-for-in) |
| ```Error``` | 31:8 | Assignment to property of function parameter &#39;elem&#39;. | [no-param-reassign](http://eslint.org/docs/rules/no-param-reassign) |
| ```Error``` | 31:25 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 34:7 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 37:38 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 38:4 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 38:8 | Combine this with the previous &#39;let&#39; statement. | [one-var](http://eslint.org/docs/rules/one-var) |
| ```Error``` | 38:13 | Operator &#39;=&#39; must be spaced. | [space-infix-ops](http://eslint.org/docs/rules/space-infix-ops) |
| ```Error``` | 38:15 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 39:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 40:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 41:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 42:32 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 43:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 44:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 44:23 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 45:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 45:24 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 46:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 46:19 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 47:19 | Operator &#39;=&#39; must be spaced. | [space-infix-ops](http://eslint.org/docs/rules/space-infix-ops) |
| ```Error``` | 47:32 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 55:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 64:65 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 67:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 68:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 70:5 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 72:3 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Warning``` | 19:9 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 30:16 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 30:16 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 30:16 | &#39;i&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 37:9 | Variable &#39;tmpDataTable&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 37:9 | &#39;tmpDataTable&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 38:12 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 38:12 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 42:29 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 54:11 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 55:38 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 56:21 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 57:13 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 58:14 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 59:12 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 63:12 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 63:34 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 63:81 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 64:12 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 64:35 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 64:65 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 65:12 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 65:27 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 66:12 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 66:28 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 67:27 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 67:41 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 68:27 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 68:45 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |

### /Users/mlawson/Work/srm/resources/js/components/ProjectManagement.vue - 198 problems (163 errors, 35 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 30:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 30:43 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 31:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 31:34 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 32:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 32:30 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 33:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 33:42 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 36:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 38:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 39:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 40:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 41:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 42:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 43:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 44:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 45:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 46:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 47:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 48:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 49:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 50:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 51:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 52:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 53:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 54:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 55:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 56:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 56:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 57:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 58:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 59:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 61:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 62:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 63:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 64:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 65:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 65:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 72:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 73:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 74:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 75:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 76:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 76:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 76:23 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 78:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 78:17 | The body of a for-in should be wrapped in an if statement to filter unwanted properties from the prototype. | [guard-for-in](http://eslint.org/docs/rules/guard-for-in) |
| ```Error``` | 79:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 79:46 | Don&#39;t make functions within a loop. | [no-loop-func](http://eslint.org/docs/rules/no-loop-func) |
| ```Error``` | 81:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 83:8 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 83:12 | Assignment to property of function parameter &#39;el2&#39;. | [no-param-reassign](http://eslint.org/docs/rules/no-param-reassign) |
| ```Error``` | 83:28 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 84:12 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 85:7 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 86:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 87:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 88:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 89:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 89:31 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 90:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 91:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 91:23 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 92:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 93:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 95:43 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 98:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 98:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 109:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 109:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 118:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 118:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 119:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 119:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 120:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 122:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 129:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 130:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 131:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 131:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 132:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 132:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 134:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 134:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 135:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 136:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 137:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 137:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 138:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 138:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 139:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 139:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 140:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 140:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 141:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 141:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 142:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 143:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 144:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 151:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 152:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 153:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 154:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 154:68 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 155:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 155:82 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 156:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 157:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 158:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 159:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 160:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 161:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 162:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 163:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 164:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 164:37 | The body of a for-in should be wrapped in an if statement to filter unwanted properties from the prototype. | [guard-for-in](http://eslint.org/docs/rules/guard-for-in) |
| ```Error``` | 165:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 165:41 | Assignment to property of function parameter &#39;elem&#39;. | [no-param-reassign](http://eslint.org/docs/rules/no-param-reassign) |
| ```Error``` | 165:58 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 166:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 167:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 167:31 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 168:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 168:90 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 169:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 170:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 171:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 172:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 173:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 177:84 | &#39;data&#39; is already declared in the upper scope. | [no-shadow](http://eslint.org/docs/rules/no-shadow) |
| ```Error``` | 178:36 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 179:63 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 187:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 188:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 189:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 189:34 | Operator &#39;+&#39; must be spaced. | [space-infix-ops](http://eslint.org/docs/rules/space-infix-ops) |
| ```Error``` | 189:38 | Operator &#39;+&#39; must be spaced. | [space-infix-ops](http://eslint.org/docs/rules/space-infix-ops) |
| ```Error``` | 189:41 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 190:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 191:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 192:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 193:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 194:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 195:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 196:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 197:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 198:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 199:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 200:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 201:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 202:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 203:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 204:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 205:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 206:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 207:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 208:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 209:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 210:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 210:14 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 211:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 212:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 212:6 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Warning``` | 66:64 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 66:77 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 69:21 | [&quot;total&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 69:21 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 70:21 | [&quot;currentPage&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 70:21 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 75:43 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 75:47 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 76:18 | Identifier name &#39;j&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 76:18 | Variable &#39;j&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 78:26 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 78:26 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 78:26 | &#39;i&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 88:40 | Use &#39;===&#39; to compare with null. | [no-eq-null](http://eslint.org/docs/rules/no-eq-null) |
| ```Warning``` | 112:22 | [&quot;total&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 113:22 | [&quot;currentPage&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 146:21 | [&quot;total&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 147:21 | [&quot;currentPage&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 153:43 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 161:77 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 161:86 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 164:37 | Expected { after &#39;for-in&#39;. | [curly](http://eslint.org/docs/rules/curly) |
| ```Warning``` | 164:45 | &#39;i&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 164:45 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 164:45 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 174:14 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 174:14 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 175:15 | Identifier name &#39;j&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 175:15 | Variable &#39;j&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 177:63 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 188:46 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 196:103 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 197:86 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 198:74 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 202:75 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |

### /Users/mlawson/Work/srm/resources/js/components/ResetPassword.vue - 149 problems (138 errors, 11 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 80:27 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 91:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 92:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 93:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 94:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 95:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 96:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 97:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 98:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 99:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 100:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 101:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 102:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 103:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 104:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 104:84 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 105:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 106:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 107:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 108:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 109:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 110:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 111:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 112:5 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 115:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 116:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 117:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 118:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 118:21 | Identifier &#39;password_confirmation&#39; is not in camel case. | [camelcase](http://eslint.org/docs/rules/camelcase) |
| ```Error``` | 119:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 120:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 121:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 122:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 122:71 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 123:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 124:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 125:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 126:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 127:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 128:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 129:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 129:90 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 130:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 131:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 132:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 134:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 135:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 136:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 136:57 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 137:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 138:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 139:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 140:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 141:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 142:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 143:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 144:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 145:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 146:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 147:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 148:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 149:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 150:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 150:68 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 151:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 152:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 153:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 154:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 154:36 | Method &#39;checkOnValidationErrors&#39; has a complexity of 6. | [complexity](http://eslint.org/docs/rules/complexity) |
| ```Error``` | 155:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 156:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 157:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 158:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 159:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 160:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 161:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 162:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 163:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 164:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 165:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 166:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 167:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 168:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 169:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 170:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 171:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 172:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 173:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 174:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 175:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 176:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 176:25 | Unexpected side effect in &quot;notSamePasswords&quot; computed property. | [vue/no-side-effects-in-computed-properties](http://eslint.org/docs/rules/vue/no-side-effects-in-computed-properties) |
| ```Error``` | 177:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 177:25 | Unexpected side effect in &quot;notSamePasswords&quot; computed property. | [vue/no-side-effects-in-computed-properties](http://eslint.org/docs/rules/vue/no-side-effects-in-computed-properties) |
| ```Error``` | 178:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 179:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 181:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 181:21 | Unexpected side effect in &quot;notSamePasswords&quot; computed property. | [vue/no-side-effects-in-computed-properties](http://eslint.org/docs/rules/vue/no-side-effects-in-computed-properties) |
| ```Error``` | 182:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 182:21 | Unexpected side effect in &quot;notSamePasswords&quot; computed property. | [vue/no-side-effects-in-computed-properties](http://eslint.org/docs/rules/vue/no-side-effects-in-computed-properties) |
| ```Error``` | 183:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 184:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 185:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 186:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 186:31 | Method &#39;passwordValidation&#39; has a complexity of 6. | [complexity](http://eslint.org/docs/rules/complexity) |
| ```Error``` | 187:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 188:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 189:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 190:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 190:55 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 191:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 192:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 193:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 194:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 195:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 195:59 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 196:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 197:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 198:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 200:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 201:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 201:51 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 202:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 203:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 203:48 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 204:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 204:14 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 205:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 207:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 207:13 | Prop &#39;resetPassUrl&#39; requires default value to be set. | [vue/require-default-prop](http://eslint.org/docs/rules/vue/require-default-prop) |
| ```Error``` | 208:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 208:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 209:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 209:30 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 210:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 213:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 214:4 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 215:3 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Warning``` | 129:40 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 138:30 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 144:21 | Variable &#39;errorList&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 145:23 | &#39;errors&#39; is assigned a value but never used. | [no-unused-vars](http://eslint.org/docs/rules/no-unused-vars) |
| ```Warning``` | 173:13 | Expected to return a value at the end of method &#39;notSamePasswords&#39;. | [consistent-return](http://eslint.org/docs/rules/consistent-return) |
| ```Warning``` | 187:21 | Variable &#39;errors&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 187:21 | &#39;errors&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 189:30 | &#39;condition&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 189:30 | Variable &#39;condition&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 193:30 | &#39;condition&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 193:30 | Variable &#39;condition&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |

### /Users/mlawson/Work/srm/resources/js/components/Settings.vue - 54 problems (41 errors, 13 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 7:156 | Expected 1 space after &#39;{{&#39;, but not found. | [vue/mustache-interpolation-spacing](http://eslint.org/docs/rules/vue/mustache-interpolation-spacing) |
| ```Error``` | 7:171 | Expected 1 space before &#39;}}&#39;, but not found. | [vue/mustache-interpolation-spacing](http://eslint.org/docs/rules/vue/mustache-interpolation-spacing) |
| ```Error``` | 8:13 | Elements in iteration expect to have &#39;v-bind:key&#39; directives. | [vue/require-v-for-key](http://eslint.org/docs/rules/vue/require-v-for-key) |
| ```Error``` | 9:71 | Expected 1 space after &#39;{{&#39;, but not found. | [vue/mustache-interpolation-spacing](http://eslint.org/docs/rules/vue/mustache-interpolation-spacing) |
| ```Error``` | 9:86 | Expected 1 space before &#39;}}&#39;, but not found. | [vue/mustache-interpolation-spacing](http://eslint.org/docs/rules/vue/mustache-interpolation-spacing) |
| ```Error``` | 40:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 41:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 44:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 48:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 48:35 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 49:3 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 49:8 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 51:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 55:50 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 58:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 69:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 70:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 76:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 78:45 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 81:26 | Operator &#39;=&#39; must be spaced. | [space-infix-ops](http://eslint.org/docs/rules/space-infix-ops) |
| ```Error``` | 81:43 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 82:33 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 83:29 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 85:4 | Duplicated key &#39;countDownChanged&#39;. | [vue/no-dupe-keys](http://eslint.org/docs/rules/vue/no-dupe-keys) |
| ```Error``` | 85:4 | Duplicate key &#39;countDownChanged&#39;. | [no-dupe-keys](http://eslint.org/docs/rules/no-dupe-keys) |
| ```Error``` | 86:45 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 87:5 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 91:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 92:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 92:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 93:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 93:30 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 94:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 95:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 96:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 96:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 97:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 98:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 101:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 102:4 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 104:3 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Warning``` | 42:15 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 43:19 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 55:23 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 60:21 | &#39;page&#39; is defined but never used. | [no-unused-vars](http://eslint.org/docs/rules/no-unused-vars) |
| ```Warning``` | 71:34 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 71:61 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 71:109 | &#39;result&#39; is defined but never used. | [no-unused-vars](http://eslint.org/docs/rules/no-unused-vars) |
| ```Warning``` | 72:21 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 72:52 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 74:21 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 74:62 | Strings must use doublequote. | [quotes](http://eslint.org/docs/rules/quotes) |
| ```Warning``` | 80:21 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 89:25 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |

### /Users/mlawson/Work/srm/resources/js/components/UserAdmin.vue - 149 problems (100 errors, 49 warnings)

| Type | Line | Description | Rule |
| --- | --- | --- | --- |
| ```Error``` | 43:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 44:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 45:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 46:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 47:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 48:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 49:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 50:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 51:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 52:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 52:13 | Prop &#39;submitTo&#39; requires default value to be set. | [vue/require-default-prop](http://eslint.org/docs/rules/vue/require-default-prop) |
| ```Error``` | 53:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 54:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 55:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 65:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 65:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 69:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 69:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 70:19 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 73:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 73:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 76:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 82:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 82:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 85:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 85:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 86:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 86:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 98:1 | Expected indentation of 5 tabs but found 6 tabs. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 99:1 | Expected indentation of 6 tabs but found 7 tabs. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 100:1 | Expected indentation of 6 tabs but found 7 tabs. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 101:1 | Expected indentation of 6 tabs but found 7 tabs. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 102:1 | Expected indentation of 6 tabs but found 7 tabs. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 104:1 | Expected indentation of 6 tabs but found 7 tabs. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 105:1 | Expected indentation of 5 tabs but found 6 tabs. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 106:1 | Expected indentation of 6 tabs but found 5 tabs. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 111:5 | The body of a for-in should be wrapped in an if statement to filter unwanted properties from the prototype. | [guard-for-in](http://eslint.org/docs/rules/guard-for-in) |
| ```Error``` | 117:10 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 119:8 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 123:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 123:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 136:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 136:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 143:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 143:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 145:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 145:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 146:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 147:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 147:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 148:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 148:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 148:27 | Identifier &#39;company_name&#39; is not in camel case. | [camelcase](http://eslint.org/docs/rules/camelcase) |
| ```Error``` | 151:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 152:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 153:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 155:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 158:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 158:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 159:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 160:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 161:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 161:100 | Don&#39;t make functions within a loop. | [no-loop-func](http://eslint.org/docs/rules/no-loop-func) |
| ```Error``` | 162:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 162:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 163:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 163:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 163:43 | Identifier &#39;company_name&#39; is not in camel case. | [camelcase](http://eslint.org/docs/rules/camelcase) |
| ```Error``` | 164:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 164:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 164:34 | &#39;Vue&#39; is not defined. | [no-undef](http://eslint.org/docs/rules/no-undef) |
| ```Error``` | 165:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 166:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 167:1 | Expected indentation of 7 tabs but found 9 tabs. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 168:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 169:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 170:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 176:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 176:99 | &#39;data&#39; is already declared in the upper scope. | [no-shadow](http://eslint.org/docs/rules/no-shadow) |
| ```Error``` | 177:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 177:60 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 178:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 178:101 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 179:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 180:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 180:99 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 181:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 187:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 188:4 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 188:22 | Operator &#39;+&#39; must be spaced. | [space-infix-ops](http://eslint.org/docs/rules/space-infix-ops) |
| ```Error``` | 188:26 | Operator &#39;+&#39; must be spaced. | [space-infix-ops](http://eslint.org/docs/rules/space-infix-ops) |
| ```Error``` | 188:29 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 189:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 230:1 | Expected &quot;\t&quot; character, but found &quot; &quot; character. | [vue/script-indent](http://eslint.org/docs/rules/vue/script-indent) |
| ```Error``` | 230:2 | Mixed spaces and tabs. | [no-mixed-spaces-and-tabs](http://eslint.org/docs/rules/no-mixed-spaces-and-tabs) |
| ```Error``` | 230:29 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 234:75 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 236:72 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Error``` | 271:16 | Unexpected trailing comma. | [comma-dangle](http://eslint.org/docs/rules/comma-dangle) |
| ```Error``` | 275:3 | Missing semicolon. | [semi](http://eslint.org/docs/rules/semi) |
| ```Warning``` | 88:15 | &#39;dataColumn&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 88:15 | Variable &#39;dataColumn&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 90:66 | There should be no space before &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 90:102 | There should be no space before &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 100:23 | [&quot;total&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 101:23 | [&quot;currentPage&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 104:67 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 104:80 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 110:34 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 110:38 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 111:14 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 111:14 | &#39;i&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 111:14 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 116:16 | Use &#39;===&#39; to compare with null. | [no-eq-null](http://eslint.org/docs/rules/no-eq-null) |
| ```Warning``` | 123:15 | Variable &#39;companyName&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 136:15 | &#39;dataCopy&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 136:15 | Variable &#39;dataCopy&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 145:15 | Variable &#39;dataCopy&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 145:15 | &#39;dataCopy&#39; is never reassigned. Use &#39;const&#39; instead. | [prefer-const](http://eslint.org/docs/rules/prefer-const) |
| ```Warning``` | 146:40 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 159:26 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 159:26 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 161:79 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 161:88 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 173:14 | Variable &#39;i&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 173:14 | Identifier name &#39;i&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 174:15 | Variable &#39;j&#39; should not be initialized on declaration. | [init-declarations](http://eslint.org/docs/rules/init-declarations) |
| ```Warning``` | 174:15 | Identifier name &#39;j&#39; is too short (&lt; 2). | [id-length](http://eslint.org/docs/rules/id-length) |
| ```Warning``` | 176:78 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 176:99 | &#39;data&#39; is defined but never used. | [no-unused-vars](http://eslint.org/docs/rules/no-unused-vars) |
| ```Warning``` | 187:50 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 194:22 | [&quot;total&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 195:22 | [&quot;currentPage&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 219:21 | [&quot;total&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 220:21 | [&quot;currentPage&quot;] is better written in dot notation. | [dot-notation](http://eslint.org/docs/rules/dot-notation) |
| ```Warning``` | 233:55 | &#39;result&#39; is defined but never used. | [no-unused-vars](http://eslint.org/docs/rules/no-unused-vars) |
| ```Warning``` | 248:88 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 248:103 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 249:67 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 249:82 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 250:75 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 250:90 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 251:59 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 255:61 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 256:51 | A space is required after &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 258:27 | There should be no space before &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 259:26 | There should be no space before &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 260:27 | There should be no space before &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |
| ```Warning``` | 261:23 | There should be no space before &#39;,&#39;. | [comma-spacing](http://eslint.org/docs/rules/comma-spacing) |

### /Users/mlawson/Work/srm/resources/js/classes/neu-constants.js - 0 problems


---

Generated on Mon May 06 2019 17:34:55 GMT-0500 (Central Daylight Time)

