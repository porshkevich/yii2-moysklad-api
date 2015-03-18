<?php

/*
 *  @link https://github.com/porshkevich/yii2-moysklad-api
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\moysklad\models;

/**
 * Description of Product
 *
 * @author NeoSonic <neosonic@inbox.ru>
 */
class Product extends MSModel {

	public $salePrice;
	public $parentUuid;
	public $code;
	public $productCode;
	public $vat;

	/**
	 *
	 * @param ProductPrice[]
	 */
	public $prices = [];

	public function loadFromXml($data) {
		parent::loadFromXml($data);

		$this->salePrice = $data['salePrice'];
		$this->parentUuid = $data['parentUuid'];
		$this->productCode = $data['productCode'];
		$this->vat = $data['vat'];

		$this->code = $data->code;

		foreach ($this->salePrices->price as $p) {
			$price = new ProductPrice;
			$price->loadFromXml($p);
			$this->prices[] = $price;
		}
	}

	public function toXMLElement() {
		$xml = parent::toXMLElement();

		if ($this->parentUuid)
			$xml->addAttribute ('parentUuid', $this->parentUuid);

		$xml->addAttribute ('salePrice', $this->salePrice);
		$xml->addAttribute ('productCode', $this->productCode);
		$xml->addAttribute ('vat', $this->vat);

		$xml->addChild('code', $this->code);

		return $xml;
	}
}
