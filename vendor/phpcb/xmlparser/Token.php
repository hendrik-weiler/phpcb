<?php

namespace xmlparser;

/**
 * The token class
 *
 * @author Hendrik Weiler
 * @version 1.0
 * @class Token
 * @namespace xmlparser
 */
class Token
{
	/**
	 * Returns the type of the token
	 *
	 * @var $type
	 * @type Type
	 * @memberOf Token
	 */
	public $type;

	/**
	 * Returns the value of the token
	 *
	 * @var $value
	 * @type null|string
	 * @memberOf Token
	 */
	public $value;

	/**
	 * The constructor
	 *
	 * @param Type $type The type
	 * @param null|string $value The value
	 * @memberOf Token
	 * @method __construct
	 * @constructor
	 */
	public function __construct($type,$value)
	{
		$this->type = $type;
		$this->value = $value;
	}
}