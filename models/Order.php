<?php

/*
 *  @link https://github.com/porshkevich/yii2-moysklad-api
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\moysklad\models;

/**
 * Description of Order
 *
 * @author NeoSonic <neosonic@inbox.ru>
 */
class Order extends MSModel {

	protected $msType = self::TYPE_ORDER;

	public $vatIncluded = false;
	public $applicable = false;
	public $targetAgentUuid;
	public $payerVat = false;
	public $sourceAgentUuid;
	public $sourceStoreUuid;
	public $moment;
	public $sum;
	public $sumInCurrency;

	/**
	 *
	 * @var OrderPosition[]
	 */
	public $orderPositions = [];

	public function loadFromXml($data) {
		parent::loadFromXml($data);

		$this->vatIncluded = $data['vatIncluded'] === 'true';
		$this->applicable = $data['applicable'] === 'true';
		$this->payerVat = $data['payerVat'] === 'true';
		$this->targetAgentUuid = $data['targetAgentUuid'];
		$this->sourceAgentUuid = $data['sourceAgentUuid'];
		$this->sourceStoreUuid = $data['sourceStoreUuid'];
		$this->moment = $data['moment'];
		$this->sum = $data->sum['sum'];
		$this->sumInCurrency = $data->sum['sumInCurrency'];

		if ($data->orderPositions) {
			foreach ($data->orderPositions as $position) {
				$model = new OrderPosition();
				$model->loadFromXml($position);
				$this->orderPositions[] = $model;
			}
		}
	}

	public function toXMLElement() {
		$xml = parent::toXMLElement();

		$xml->addAttribute('vatIncluded', $this->prepareValue($this->vatIncluded));
		$xml->addAttribute('applicable', $this->prepareValue($this->applicable));
		$xml->addAttribute('payerVat', $this->prepareValue($this->payerVat));
		$xml->addAttribute('targetAgentUuid', $this->targetAgentUuid);
		$xml->addAttribute('sourceAgentUuid', $this->sourceAgentUuid);
		$xml->addAttribute('sourceStoreUuid', $this->sourceStoreUuid);

		foreach ($this->orderPositions as $pos) {
			$element = $pos->toXMLElement();
			$xml->addChild($element->name,$element);
		}

		return $xml;
	}


}
