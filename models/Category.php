<?php

/*
 *  @link https://github.com/porshkevich/yii2-moysklad-api
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\moysklad\models;

/**
 * Description of Category
 *
 * @author NeoSonic <neosonic@inbox.ru>
 */
class Category extends MSModel {

	protected $type = self::TYPE_CATEGORY;


	public $parentUuid;

	public function loadFromXml($data) {
		parent::loadFromXml($data);

		$this->parentUuid = $data['parentUuid'];
	}

	public function toXMLElement() {
		$xml = parent::toXMLElement();

		$xml->addAttribute('parentUuid', $this->parentUuid);

		return $xml;
	}
}
