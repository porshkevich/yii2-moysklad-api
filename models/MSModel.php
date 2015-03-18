<?php

/*
 *  @link https://github.com/porshkevich/yii2-moysklad-api
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\moysklad\models;

use yii\base\Model;
use porshkevich\moysklad\components\XMLElement;

/**
 * Description of MSModel
 *
 * @author NeoSonic <neosonic@inbox.ru>
 */
class MSModel extends Model {

	const TYPE_CATEGORY = 'GoodFolder';
	const TYPE_PRODUCT = 'Good';
	const TYPE_ORDER = 'CustomerOrder';
	const TYPE_ORDER_POSITION = 'CustomerOrderPosition';
	const TYPE_STORE = 'Warehouse';

	protected $msType;
	public $uuid;
	public $externalCode;
	public $name;
	public $description;

	/**
	 *
	 * @param \SimpleXMLElement $data
	 */
	public function loadFromXml($data) {
		$this->uuid = $data->uuid;
		$this->externalCode = $data->externalCode;
		$this->name = $data['name'];
		$this->description = $data->description;
	}

	/**
	 *
	 * @return XMLElement
	 */
	public function toXMLElement() {
		$xml = new XMLElement($this->msType);

		if($this->uuid)
			$xml->addChild('uuid', $this->uuid);
		if($this->externalCode)
			$xml->addChild('externalCode', $this->externalCode);
		if ($this->description)
			$xml->addChild('description', $this->description);

		$xml->addAttribute('name', $this->name);

		return $xml;
	}

	/**
	 *
	 * @param mixed $value
	 */
	protected function prepareValue($value) {
		if(is_bool($value))
			$value = $value?'true':'false';
		elseif(!is_string($value))
			$value = sprintf("%01.1f", $value);
		return $value;
	}

}
