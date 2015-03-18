<?php

/*
 *  @link https://github.com/porshkevich/yii2-moysklad-api
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\moysklad\components;

/**
 * Description of XMLElement
 *
 * @author NeoSonic <neosonic@inbox.ru>
 */
class XMLElement {

	/**
	 *
	 * @var mixed[]
	 */
	public $attributes = [];

	/**
	 *
	 * @var XMLElement[]
	 */
	public $children = [];

	/**
	 *
	 * @var mixed
	 */
	public $value;

	/**
	 *
	 * @var string
	 */
	public $name;

	public function __construct($name, $value = null) {
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 *
	 * @param string $name
	 * @param mixed|this $value
	 */
	public function addChild($name, $value) {
		if (!$name)
			return;
		if (!isset($this->children[$name]))
			$this->children[$name] = [];
		$this->children[$name][] = $value instanceof XMLElement ? $value : new XMLElement($name, $value);
	}

	/**
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function addAttribute($name, $value) {
		if (!$name)
			return;
		$this->attributes[$name] = $value;
	}

	/**
	 *
	 * @return string|array
	 */
	public function toData() {

		$r = [];
		if ($this->attributes)
			$r['@attributes'] = $this->attributes;
		if ($this->value)
			if ($this->attributes)
				$r['@value'] = $this->value;
			else
				return $this->value;
		elseif ($this->children) {
			foreach ($this->children as $key => $children) {
				if ($children) {
					$r[$key] = [];
					foreach ($children as $child) {
						$r[$key][] = $child->toData();
					}
				}
			}
		}


		return $r;
	}

	public function toXml() {
		$data = $this->toData();

		return \LSS\Array2XML::createXML($this->name, $data)->saveXML();
	}

}
