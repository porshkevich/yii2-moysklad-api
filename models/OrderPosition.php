<?php

/*
 *  @link https://github.com/porshkevich/yii2-moysklad-api
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\moysklad\models;

use porshkevich\moysklad\components\XMLElement;

/**
 * Description of OrderPosition
 *
 * @author NeoSonic <neosonic@inbox.ru>
 */
class OrderPosition extends MSModel {

	protected $msType = self::TYPE_ORDER_POSITION;

	public $vat = 0;
	public $goodUuid;
	public $quantity;
	public $discount;
	public $consignmentUuid;

	/**
	 *
	 * @var float Price before all discount
	 */
	public $basePrice;

	/**
	 *
	 * @var float Price after all discount
	 */
	public $price;

	public function loadFromXml($data) {
		parent::loadFromXml($data);

		$this->vat = $data['vat'];
		$this->goodUuid = $data['goodUuid'];
		$this->consignmentUuid = $data['consignmentUuid'];
		$this->discount = $data['discount'];
		$this->quantity = $data['quantity'];

		$this->basePrice = $data->basePrice['sum'];
		$this->price = $data->price['sum'];

	}

	public function toXMLElement() {
		$xml = parent::toXMLElement();

		$xml->addAttribute('vat', $this->prepareValue($this->vat));
		$xml->addAttribute('discount', $this->prepareValue($this->discount));
		$xml->addAttribute('quantity', $this->prepareValue($this->quantity));
		$xml->addAttribute('goodUuid', $this->goodUuid);
		$xml->addAttribute('consignmentUuid', $this->consignmentUuid);

		$basePrice = new XMLElement('basePrice');
		$basePrice->addAttribute('sum', $this->basePrice);
		$basePrice->addAttribute('sumInCurrency', $this->basePrice);
		$xml->addChild('basePrice', $basePrice);

		$price = new XMLElement('price');
		$price->addAttribute('sum', $this->price);
		$price->addAttribute('sumInCurrency', $this->price);
		$xml->addChild('price', price);

		return $xml;
	}
}
