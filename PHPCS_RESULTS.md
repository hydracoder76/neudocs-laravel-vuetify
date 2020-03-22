# pcsg-generated-ruleset Coding Standard
## Empty Statements
Control Structures must have at least one statement inside of the body.
  <table>
   <tr>
    <th>Valid: There is a statement inside the control structure.</th>
    <th>Invalid: The control structure has no statements.</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if ($test) {
        // do nothing
    }

</td>
   </tr>
  </table>
## For Loops With Function Calls in the Test
For loops should not call functions inside the test for the loop when they can be computed beforehand.
  <table>
   <tr>
    <th>Valid: A for loop that determines its end condition before the loop starts.</th>
    <th>Invalid: A for loop that unnecessarily computes the same value on every iteration.</th>
   </tr>
   <tr>
<td>

    $end = count($foo);
    for ($i = 0; $i < $end; $i++) {
        echo $foo[$i]."\n";
    }

</td>
<td>

    for ($i = 0; $i < count($foo); $i++) {
        echo $foo[$i]."\n";
    }

</td>
   </tr>
  </table>
## Jumbled Incrementers
Incrementers in nested loops should use different variable names.
  <table>
   <tr>
    <th>Valid: Two different variables being used to increment.</th>
    <th>Invalid: Inner incrementer is the same variable name as the outer one.</th>
   </tr>
   <tr>
<td>

    for ($i = 0; $i < 10; $i++) {
        for ($j = 0; $j < 10; $j++) {
        }
    }

</td>
<td>

    for ($i = 0; $i < 10; $i++) {
        for ($j = 0; $j < 10; $i++) {
        }
    }

</td>
   </tr>
  </table>
## Unconditional If Statements
If statements that are always evaluated should not be used.
  <table>
   <tr>
    <th>Valid: An if statement that only executes conditionally.</th>
    <th>Invalid: An if statement that is always performed.</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if (true) {
        $var = 1;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: An if statement that only executes conditionally.</th>
    <th>Invalid: An if statement that is never performed.</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if (false) {
        $var = 1;
    }

</td>
   </tr>
  </table>
## Unnecessary Final Modifiers
Methods should not be declared final inside of classes that are declared final.
  <table>
   <tr>
    <th>Valid: A method in a final class is not marked final.</th>
    <th>Invalid: A method in a final class is also marked final.</th>
   </tr>
   <tr>
<td>

    final class Foo
    {
        public function bar()
        {
        }
    }

</td>
<td>

    final class Foo
    {
        public final function bar()
        {
        }
    }

</td>
   </tr>
  </table>
## Useless Overriding Methods
Methods should not be defined that only call the parent method.
  <table>
   <tr>
    <th>Valid: A method that extends functionality on a parent method.</th>
    <th>Invalid: An overriding method that only calls the parent.</th>
   </tr>
   <tr>
<td>

    final class Foo
    {
        public function bar()
        {
            parent::bar();
            $this->doSomethingElse();
        }
    }

</td>
<td>

    final class Foo
    {
        public function bar()
        {
            parent::bar();
        }
    }

</td>
   </tr>
  </table>
## Inline Control Structures
Control Structures should use braces.
  <table>
   <tr>
    <th>Valid: Braces are used around the control structure.</th>
    <th>Invalid: No braces are used for the control structure..</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if ($test)
        $var = 1;

</td>
   </tr>
  </table>
## Line Length
It is recommended to keep lines at approximately 80 characters long for better code readability.
## Multiple Statements On a Single Line
Multiple statements are not allowed on a single line.
  <table>
   <tr>
    <th>Valid: Two statements are spread out on two separate lines.</th>
    <th>Invalid: Two statements are combined onto one line.</th>
   </tr>
   <tr>
<td>

    $foo = 1;
    $bar = 2;

</td>
<td>

    $foo = 1; $bar = 2;

</td>
   </tr>
  </table>
## Space After Casts
Exactly one space is allowed after a cast.
  <table>
   <tr>
    <th>Valid: A cast operator is followed by one space.</th>
    <th>Invalid: A cast operator is not followed by whitespace.</th>
   </tr>
   <tr>
<td>

    $foo = (string) 1;

</td>
<td>

    $foo = (string)1;

</td>
   </tr>
  </table>
## Call-Time Pass-By-Reference
Call-time pass-by-reference is not allowed. It should be declared in the function definition.
  <table>
   <tr>
    <th>Valid: Pass-by-reference is specified in the function definition.</th>
    <th>Invalid: Pass-by-reference is done in the call to a function.</th>
   </tr>
   <tr>
<td>

    function foo(&$bar)
    {
        $bar++;
    }
    
    $baz = 1;
    foo($baz);

</td>
<td>

    function foo($bar)
    {
        $bar++;
    }
    
    $baz = 1;
    foo(&$baz);

</td>
   </tr>
  </table>
## Opening Brace in Function Declarations
Function declarations follow the &quot;Kernighan/Ritchie style&quot;. The function brace is on the same line as the function declaration. One space is required between the closing parenthesis and the brace.
  <table>
   <tr>
    <th>Valid: brace on same line</th>
    <th>Invalid: brace on next line</th>
   </tr>
   <tr>
<td>

    function fooFunction($arg1, $arg2 = '') {
        ...
    }

</td>
<td>

    function fooFunction($arg1, $arg2 = '')
    {
        ...
    }

</td>
   </tr>
  </table>
## Cyclomatic Complexity
Functions should not have a cyclomatic complexity greater than 20, and should try to stay below a complexity of 10.
## Nesting Level
Functions should not have a nesting level greater than 10, and should try to stay below 5.
## Constructor name
Constructors should be named __construct, not after the class.
  <table>
   <tr>
    <th>Valid: The constructor is named __construct.</th>
    <th>Invalid: The old style class name constructor is used.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        function __construct()
        {
        }
    }

</td>
<td>

    class Foo
    {
        function Foo()
        {
        }
    }

</td>
   </tr>
  </table>
## Constant Names
Constants should always be all-uppercase, with underscores to separate words.
  <table>
   <tr>
    <th>Valid: all uppercase</th>
    <th>Invalid: mixed case</th>
   </tr>
   <tr>
<td>

    define('FOO_CONSTANT', 'foo');
    
    class FooClass
    {
        const FOO_CONSTANT = 'foo';
    }

</td>
<td>

    define('Foo_Constant', 'foo');
    
    class FooClass
    {
        const foo_constant = 'foo';
    }

</td>
   </tr>
  </table>
## camelCaps Function Names
Functions should use camelCaps format for their names. Only PHP's magic methods should use a double underscore prefix.
  <table>
   <tr>
    <th>Valid: A function in camelCaps format.</th>
    <th>Invalid: A function in snake_case format.</th>
   </tr>
   <tr>
<td>

    function doSomething()
    {
    }

</td>
<td>

    function do_something()
    {
    }

</td>
   </tr>
  </table>
## Deprecated Functions
Deprecated functions should not be used.
  <table>
   <tr>
    <th>Valid: A non-deprecated function is used.</th>
    <th>Invalid: A deprecated function is used.</th>
   </tr>
   <tr>
<td>

    $foo = explode('a', $bar);

</td>
<td>

    $foo = split('a', $bar);

</td>
   </tr>
  </table>
## PHP Code Tags
Always use &lt;?php ?&gt; to delimit PHP code, not the &lt;? ?&gt; shorthand. This is the most portable way to include PHP code on differing operating systems and setups.
## Forbidden Functions
The forbidden functions sizeof() and delete() should not be used.
  <table>
   <tr>
    <th>Valid: count() is used in place of sizeof().</th>
    <th>Invalid: sizeof() is used.</th>
   </tr>
   <tr>
<td>

    $foo = count($bar);

</td>
<td>

    $foo = sizeof($bar);

</td>
   </tr>
  </table>
## Lowercase PHP Constants
The *true*, *false* and *null* constants must always be lowercase.
  <table>
   <tr>
    <th>Valid: lowercase constants</th>
    <th>Invalid: uppercase constants</th>
   </tr>
   <tr>
<td>

    if ($var === false || $var === null) {
        $var = true;
    }

</td>
<td>

    if ($var === FALSE || $var === NULL) {
        $var = TRUE;
    }

</td>
   </tr>
  </table>
## Silenced Errors
Suppressing Errors is not allowed.
  <table>
   <tr>
    <th>Valid: isset() is used to verify that a variable exists before trying to use it.</th>
    <th>Invalid: Errors are suppressed.</th>
   </tr>
   <tr>
<td>

    if (isset($foo) && $foo) {
        echo "Hello\n";
    }

</td>
<td>

    if (@$foo) {
        echo "Hello\n";
    }

</td>
   </tr>
  </table>
## Unnecessary String Concatenation
Strings should not be concatenated together.
  <table>
   <tr>
    <th>Valid: A string can be concatenated with an expression.</th>
    <th>Invalid: Strings should not be concatenated together.</th>
   </tr>
   <tr>
<td>

    echo '5 + 2 = ' . (5 + 2);

</td>
<td>

    echo 'Hello' . ' ' . 'World';

</td>
   </tr>
  </table>
## Class Comments
Classes and interfaces must have a non-empty doc comment.  The short description must be on the second line of the comment.  Each description must have one blank comment line before and after.  There must be one blank line before the tags in the comments.  A @version tag must be in Release: package_version format.
  <table>
   <tr>
    <th>Valid: A doc comment for the class.</th>
    <th>Invalid: No doc comment for the class.</th>
   </tr>
   <tr>
<td>

    /**
     * The Foo class.
     */
    class Foo
    {
    }

</td>
<td>

    class Foo
    {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: A doc comment for the class.</th>
    <th>Invalid: Invalid comment type for the class.</th>
   </tr>
   <tr>
<td>

    /**
     * The Foo class.
     */
    class Foo
    {
    }

</td>
<td>

    // The Foo class.
    class Foo
    {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: A doc comment for the class.</th>
    <th>Invalid: The blank line after the comment makes it appear as a file comment, not a class comment.</th>
   </tr>
   <tr>
<td>

    /**
     * The Foo class.
     */
    class Foo
    {
    }

</td>
<td>

    /**
     * The Foo class.
     */
    
    class Foo
    {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Short description is the second line of the comment.</th>
    <th>Invalid: An extra blank line before the short description.</th>
   </tr>
   <tr>
<td>

    /**
     * The Foo class.
     */
    class Foo
    {
    }

</td>
<td>

    /**
     *
     * The Foo class.
     */
    class Foo
    {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Exactly one blank line around descriptions.</th>
    <th>Invalid: Extra blank lines around the descriptions.</th>
   </tr>
   <tr>
<td>

    /**
     * The Foo class.
     * 
     * A helper for the Bar class.
     * 
     * @see Bar
     */
    class Foo
    {
    }

</td>
<td>

    /**
     * The Foo class.
     * 
     * 
     * A helper for the Bar class.
     * 
     * 
     * @see Bar
     */
    class Foo
    {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Exactly one blank line before the tags.</th>
    <th>Invalid: Extra blank lines before the tags.</th>
   </tr>
   <tr>
<td>

    /**
     * The Foo class.
     * 
     * @see Bar
     */
    class Foo
    {
    }

</td>
<td>

    /**
     * The Foo class.
     * 
     * 
     * @see Bar
     */
    class Foo
    {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Version tag is in the correct format.</th>
    <th>Invalid: No Release: text.</th>
   </tr>
   <tr>
<td>

    /**
     * The Foo class.
     *
     * @version Release: 1.0
     */
    class Foo
    {
    }

</td>
<td>

    /**
     * The Foo class.
     *
     * @version 1.0
     */
    class Foo
    {
    }

</td>
   </tr>
  </table>
## Function Comments
Functions must have a non-empty doc comment.  The short description must be on the second line of the comment.  Each description must have one blank comment line before and after.  There must be one blank line before the tags in the comments.  There must be a tag for each of the parameters in the right order with the right variable names with a comment.  There must be a return tag.  Any throw tag must have an exception class.
  <table>
   <tr>
    <th>Valid: A function doc comment is used.</th>
    <th>Invalid: No doc comment for the function.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description here.
     *
     * @return void
     */
     function foo()
     {
     }

</td>
<td>

    function foo()
     {
     }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Short description is the second line of the comment.</th>
    <th>Invalid: An extra blank line before the short description.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description here.
     *
     * @return void
     */
     function foo()
     {
     }

</td>
<td>

    /**
     * 
     * Short description here.
     *
     * @return void
     */
     function foo()
     {
     }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Exactly one blank line around descriptions.</th>
    <th>Invalid: Extra blank lines around the descriptions.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description here.
     * 
     * Long description here.
     * 
     * @return void
     */
     function foo()
     {
     }

</td>
<td>

    /**
     * Short description here.
     * 
     * 
     * Long description here.
     * 
     * 
     * @return void
     */
     function foo()
     {
     }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Exactly one blank line before the tags.</th>
    <th>Invalid: Extra blank lines before the tags.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description here.
     *
     * Long description here.
     * 
     * @return void
     */
     function foo()
     {
     }

</td>
<td>

    /**
     * Short description here.
     *
     * Long description here.
     * 
     * 
     * @return void
     */
     function foo()
     {
     }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Throws tag has an exception class.</th>
    <th>Invalid: No exception class given for throws tag.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description here.
     *
     * @return void
     * @throws FooException
     */
     function foo()
     {
     }

</td>
<td>

    /**
     * Short description here.
     *
     * @return void
     * @throws
     */
     function foo()
     {
     }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Return tag present.</th>
    <th>Invalid: No return tag.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description here.
     *
     * @return void
     */
     function foo()
     {
     }

</td>
<td>

    /**
     * Short description here.
     */
     function foo()
     {
     }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Param names are correct.</th>
    <th>Invalid: Wrong parameter name doesn't match function signature.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description here.
     *
     * @param string $foo Foo parameter
     * @param string $bar Bar parameter
     * @return void
     */
     function foo($foo, $bar)
     {
     }

</td>
<td>

    /**
     * Short description here.
     *
     * @param string $foo Foo parameter
     * @param string $qux Bar parameter
     * @return void
     */
     function foo($foo, $bar)
     {
     }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Param names are ordered correctly.</th>
    <th>Invalid: Wrong parameter order.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description here.
     *
     * @param string $foo Foo parameter
     * @param string $bar Bar parameter
     * @return void
     */
     function foo($foo, $bar)
     {
     }

</td>
<td>

    /**
     * Short description here.
     *
     * @param string $bar Bar parameter
     * @param string $foo Foo parameter
     * @return void
     */
     function foo($foo, $bar)
     {
     }

</td>
   </tr>
  </table>
## Inline Comments
Perl-style # comments are not allowed.
  <table>
   <tr>
    <th>Valid: A // style comment.</th>
    <th>Invalid: A # style comment.</th>
   </tr>
   <tr>
<td>

    // A comment.

</td>
<td>

    # A comment.

</td>
   </tr>
  </table>
## Multi-line If Conditions
Multi-line if conditions should be indented one level and each line should begin with a boolean operator.  The end parenthesis should be on a new line.
  <table>
   <tr>
    <th>Valid: Correct indentation.</th>
    <th>Invalid: No indentation used on the condition lines.</th>
   </tr>
   <tr>
<td>

    if ($foo
        && $bar
    ) {
    }

</td>
<td>

    if ($foo
    && $bar
    ) {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Boolean operator at the start of the line.</th>
    <th>Invalid: Boolean operator at the end of the line.</th>
   </tr>
   <tr>
<td>

    if ($foo
        && $bar
    ) {
    }

</td>
<td>

    if ($foo &&
        $bar
    ) {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: End parenthesis on a new line.</th>
    <th>Invalid: End parenthesis not moved to a new line.</th>
   </tr>
   <tr>
<td>

    if ($foo
        && $bar
    ) {
    }

</td>
<td>

    if ($foo
        && $bar) {
    }

</td>
   </tr>
  </table>
## Including Code
Anywhere you are unconditionally including a class file, use *require_once*. Anywhere you are conditionally including a class file (for example, factory methods), use *include_once*. Either of these will ensure that class files are included only once. They share the same file list, so you don't need to worry about mixing them - a file included with *require_once* will not be included again by *include_once*.
Note that *include_once* and *require_once* are statements, not functions. Parentheses should not surround the subject filename.
  <table>
   <tr>
    <th>Valid: used as statement</th>
    <th>Invalid: used as function</th>
   </tr>
   <tr>
<td>

    require_once 'PHP/CodeSniffer.php';

</td>
<td>

    require_once('PHP/CodeSniffer.php');

</td>
   </tr>
  </table>
## Default Values in Function Declarations
Arguments with default values go at the end of the argument list.
  <table>
   <tr>
    <th>Valid: argument with default value at end of declaration</th>
    <th>Invalid: argument with default value at start of declaration</th>
   </tr>
   <tr>
<td>

    function connect($dsn, $persistent = false)
    {
        ...
    }

</td>
<td>

    function connect($persistent = false, $dsn)
    {
        ...
    }

</td>
   </tr>
  </table>
## Class Declaration
Each class must be in a file by itself and must be under a namespace (a top-level vendor name).
  <table>
   <tr>
    <th>Valid: One class in a file.</th>
    <th>Invalid: Multiple classes in a single file.</th>
   </tr>
   <tr>
<td>

    <?php
    namespace Foo;
    
    class Bar {
    }

</td>
<td>

    <?php
    namespace Foo;
    
    class Bar {
    }
    
    class Baz {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: A vendor-level namespace is used.</th>
    <th>Invalid: No namespace used in file.</th>
   </tr>
   <tr>
<td>

    <?php
    namespace Foo;
    
    class Bar {
    }

</td>
<td>

    <?php
    class Bar {
    }

</td>
   </tr>
  </table>
## Property Declarations
Property names should not be prefixed with an underscore to indicate visibility.  Visibility should be used to declare properties rather than the var keyword.  Only one property should be declared within a statement.  The static declaration must come after the visibility declaration.
  <table>
   <tr>
    <th>Valid: Correct property naming.</th>
    <th>Invalid: An underscore prefix used to indicate visibility.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private $bar;
    }

</td>
<td>

    class Foo
    {
        private $_bar;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Visibility of property declared.</th>
    <th>Invalid: Var keyword used to declare property.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private $bar;
    }

</td>
<td>

    class Foo
    {
        var $bar;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: One property declared per statement.</th>
    <th>Invalid: Multiple properties declared in one statement.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private $bar;
        private $baz;
    }

</td>
<td>

    class Foo
    {
        private $bar, $baz;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: If declared as static, the static declaration must come after the visibility declaration.</th>
    <th>Invalid: Static declaration before the visibility declaration.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        public static $bar;
        private $baz;
    }

</td>
<td>

    class Foo
    {
        static protected $bar;
    }

</td>
   </tr>
  </table>
## Control Structure Spacing
Control Structures should have 0 spaces after opening parentheses and 0 spaces before closing parentheses.
  <table>
   <tr>
    <th>Valid: Correct spacing.</th>
    <th>Invalid: Whitespace used inside the parentheses.</th>
   </tr>
   <tr>
<td>

    if ($foo) {
        $var = 1;
    }

</td>
<td>

    if ( $foo ) {
        $var = 1;
    }

</td>
   </tr>
  </table>
## Switch Declarations
Case statements should be indented 4 spaces from the switch keyword.  It should also be followed by a space.  Colons in switch declarations should not be preceded by whitespace.  Break statements should be indented 4 more spaces from the case statement.  There must be a comment when falling through from one case into the next.
  <table>
   <tr>
    <th>Valid: Case statement indented correctly.</th>
    <th>Invalid: Case statement not indented 4 spaces.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
    }

</td>
<td>

    switch ($foo) {
    case 'bar':
        break;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Case statement followed by 1 space.</th>
    <th>Invalid: Case statement not followed by 1 space.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
    }

</td>
<td>

    switch ($foo) {
        case'bar':
            break;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Colons not prefixed by whitespace.</th>
    <th>Invalid: Colons prefixed by whitespace.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
        default:
            break;
    }

</td>
<td>

    switch ($foo) {
        case 'bar' :
            break;
        default :
            break;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Break statement indented correctly.</th>
    <th>Invalid: Break statement not indented 4 spaces.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
    }

</td>
<td>

    switch ($foo) {
        case 'bar':
        break;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Comment marking intentional fall-through.</th>
    <th>Invalid: No comment marking intentional fall-through.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
        // no break
        default:
            break;
    }

</td>
<td>

    switch ($foo) {
        case 'bar':
        default:
            break;
    }

</td>
   </tr>
  </table>
## End File Newline
PHP Files should end with exactly one newline.
## Method Declarations
Method names should not be prefixed with an underscore to indicate visibility.  The static keyword, when present, should come after the visibility declaration, and the final and abstract keywords should come before.
  <table>
   <tr>
    <th>Valid: Correct method naming.</th>
    <th>Invalid: An underscore prefix used to indicate visibility.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private function bar()
        {
        }
    }

</td>
<td>

    class Foo
    {
        private function _bar()
        {
        }
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Correct ordering of method prefixes.</th>
    <th>Invalid: static keyword used before visibility and final used after.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        final public static function bar()
        {
        }
    }

</td>
<td>

    class Foo
    {
        static public final function bar()
        {
        }
    }

</td>
   </tr>
  </table>
## Namespace Declarations
There must be one blank line after the namespace declaration.
  <table>
   <tr>
    <th>Valid: One blank line after the namespace declaration.</th>
    <th>Invalid: No blank line after the namespace declaration.</th>
   </tr>
   <tr>
<td>

    namespace \Foo\Bar;
    
    use \Baz;

</td>
<td>

    namespace \Foo\Bar;
    use \Baz;

</td>
   </tr>
  </table>
## Namespace Declarations
Each use declaration must contain only one namespace and must come after the first namespace declaration.  There should be one blank line after the final use statement.
  <table>
   <tr>
    <th>Valid: One use declaration per namespace.</th>
    <th>Invalid: Multiple namespaces in a use declaration.</th>
   </tr>
   <tr>
<td>

    use \Foo;
    use \Bar;

</td>
<td>

    use \Foo, \Bar;

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Use statements come after first namespace.</th>
    <th>Invalid: Namespace declared after use.</th>
   </tr>
   <tr>
<td>

    namespace \Foo;
    
    use \Bar;

</td>
<td>

    use \Bar;
    
    namespace \Foo;

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: A single blank line after the final use statement.</th>
    <th>Invalid: No blank line after the final use statement.</th>
   </tr>
   <tr>
<td>

    use \Foo;
    use \Bar;
    
    class Baz
    {
    }

</td>
<td>

    use \Foo;
    use \Bar;
    class Baz
    {
    }

</td>
   </tr>
  </table>
## Static This Usage
Static methods should not use $this.
  <table>
   <tr>
    <th>Valid: Using self:: to access static variables.</th>
    <th>Invalid: Using $this-> to access static variables.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        static function bar()
        {
            return self::$staticMember;
        }
    }

</td>
<td>

    class Foo
    {
        static function bar()
        {
        return $this->$staticMember;
        }
    }

</td>
   </tr>
  </table>
## Cast Whitespace
Casts should not have whitespace inside the parentheses.
  <table>
   <tr>
    <th>Valid: No spaces.</th>
    <th>Invalid: Whitespace used inside parentheses.</th>
   </tr>
   <tr>
<td>

    $foo = (int)'42';

</td>
<td>

    $foo = ( int )'42';

</td>
   </tr>
  </table>
## Language Construct Whitespace
The php constructs echo, print, return, include, include_once, require, require_once, and new should have one space after them.
  <table>
   <tr>
    <th>Valid: echo statement with a single space after it.</th>
    <th>Invalid: echo statement with no space after it.</th>
   </tr>
   <tr>
<td>

    echo "hi";

</td>
<td>

    echo"hi";

</td>
   </tr>
  </table>
## Object Operator Spacing
The object operator (-&gt;) should not have any space around it.
  <table>
   <tr>
    <th>Valid: No spaces around the object operator.</th>
    <th>Invalid: Whitespace surrounding the object operator.</th>
   </tr>
   <tr>
<td>

    $foo->bar();

</td>
<td>

    $foo -> bar();

</td>
   </tr>
  </table>
## Scope Keyword Spacing
The php keywords static, public, private, and protected should have one space after them.
  <table>
   <tr>
    <th>Valid: A single space following the keywords.</th>
    <th>Invalid: Multiple spaces following the keywords.</th>
   </tr>
   <tr>
<td>

    public static function foo()
    {
    }

</td>
<td>

    public  static  function foo()
    {
    }

</td>
   </tr>
  </table>
## Semicolon Spacing
Semicolons should not have spaces before them.
  <table>
   <tr>
    <th>Valid: No space before the semicolon.</th>
    <th>Invalid: Space before the semicolon.</th>
   </tr>
   <tr>
<td>

    echo "hi";

</td>
<td>

    echo "hi" ;

</td>
   </tr>
  </table>
Documentation generated on Fri, 01 Mar 2019 15:54:05 +0000 by [PHP_CodeSniffer 3.4.0](https://github.com/squizlabs/PHP_CodeSniffer)
