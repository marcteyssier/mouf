<?php
/*
 * This file is part of the Mouf core package.
 *
 * (c) 2012 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
namespace Mouf\Annotations;

use Mouf\MoufException;

/**
 * The @param annotation.
 * This annotation contains the name of the parameter and the type. Finally, some explanation about the parameter.
 * For instance: @param array<string> $strArray This is an array of strings.
 * The type can be any class or interface, or a primitive type (string, boolean, ...)
 * It can also be an array. In this case, you can specify the array type using: array<Type>, or even array<Type, Type>
 */
class paramAnnotation extends varAnnotation
{
	private $parameterName;
	private $comments;
	
    public function __construct($value)
    {
    	$this->analyzeString($value);
    }

    /**
     * Analyzes the string.
     * The string must be of the kind: @param array<string> $myParam
     *
     * @param string $value
     */
	private function analyzeString($value) {
		// In the "type", there must be a $ sign.
		// So let's find this:
		$varPos = strpos($value, '$');
		
		// Some framework (Symfony, I'm looking at you) are not always passing the variable name
		/*if ($varPos === false) {
			throw new MoufException("Error in the @param annotation. The @param annotation does not refer to a variable. The structure must be: \"@param type \$variable comment\". Passed value: @param $value");
		}*/
		
		if ($varPos !== false) {	
			$type = trim(substr($value, 0, $varPos));
			// Get the parameter name
			$this->parameterName = strtok(substr($value, $varPos), " \n\t");
			$this->comments = strtok("");
		} else {
			$type = $value;
			$tmp = strtok($value, " \n\t");
			$this->comments = strtok("");
		}

		if (empty($type)) {
			throw new MoufException("The @param annotation does not have a type. The structure must be: \"@param type \$variable comment\". Passed value: @param $value");
		}
		$this->analyzeType($type);
		
		
	}
	
	/**
	 * Returns the name of the parameter.
	 *
	 * @return string
	 */
	public function getParameterName() {
		return $this->parameterName;
	}
	
	/**
	 * Returns the comments (the part after the type and the variable name).
	 * @return string
	 */
	public function getComments() {
		return $this->comments;
	}
}

?>
