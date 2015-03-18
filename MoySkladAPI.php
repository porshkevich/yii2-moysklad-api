<?php

/*
 *  @link https://github.com/porshkevich/yii2-moysklad-api
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\moysklad;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Description of MoySkladAPI
 *
 * @author NeoSonic <neosonic@inbox.ru>
 */
class MoySkladAPI extends Component {

	public $username;
	public $password;

	public $company;
	public $storeId;

	public $msBaseRestUrl = 'https://online.moysklad.ru/exchange/rest/';
	public $user_agent = '';

	public function init() {
		if (!$this->username || !$this->password)
			throw new InvalidConfigException('The "username" or "password" propertys must be set.');

		if (!$this->company || !$this->storeId)
			throw new InvalidConfigException('The "company" or "storeId" propertys must be set.');
	}

	/**
	 *
	 * @param \porshkevich\moysklad\models\Order $order
	 * @return \porshkevich\moysklad\models\Order|boolean
	 */
	public function addOrder(models\Order $order) {
		$response = $this->put('ms/xml/CustomerOrder', $order->toXMLElement()->toXml());

		if(!$response) {
			Yii::error("failed to add order with name: {$order->name}");
		}

		$xml = simplexml_load_string($response);

		$order->loadFromXml($xml);

		return $order;
	}

	public function getProduct() {

	}

	public function getProducts() {

	}

	public function getCategory() {

	}

	public function getCategories($start = 0, $count = 1000) {
		return $this->get('ms/xml/GoodFolder/list',[], ['start'=>$start, 'count'=>$count]);
	}

	public function addCategory(){
		$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<goodFolder archived="false" productCode="12" name="TestGroup1">
	<code>12</code>
	<description></description>
</goodFolder>
XML;
		return $this->put('ms/xml/GoodFolder', $xml);
	}

	// Request methods:
	private function get($url, $options = [], $query = []){
        return $this->execute($url, 'GET', $options, $query);
    }

    private function put($url, $body, $options = [], $query = []){

		/** use a max of 256KB of RAM before going to disk */
		$fp = fopen('php://temp/maxmemory:256000', 'w');
		if ( !$fp )
		{
			return;
		}
		fwrite($fp, $body);
		fseek($fp, 0);

		$options[CURLOPT_HTTPHEADER] = ['Content-Type: application/xml'];
		$options[CURLOPT_INFILE] = $fp;
		$options[CURLOPT_INFILESIZE] = fstat($fp)['size'];
		$options[CURLOPT_PUT] = true;
		$result = $this->execute($url, 'PUT', $options, $query);
		fclose($fp);
        return $result;
    }

    private function delete($url, $options = [], $query = []){
        return $this->execute($url, 'DELETE', $options, $query);
    }

	/**
	 *
	 * @param string $url
	 * @param string $method
	 * @param array $options
	 * @param array $query
	 * @return mixed
	 * @throws \RuntimeException
	 */
	private function execute($url, $method = 'GET', $options = [], $query = []) {
		$handle	 = curl_init();
		$curlopt = ArrayHelper::merge([
			CURLOPT_USERPWD			 => "$this->username:$this->password",
			CURLOPT_RETURNTRANSFER	 => TRUE,
			CURLOPT_USERAGENT		 => $this->user_agent,
			CURLOPT_SSL_VERIFYPEER	 => false,
				], $options);
		if(strtoupper($method) == 'POST'){
            $curlopt[CURLOPT_POST] = TRUE;
            $curlopt[CURLOPT_POSTFIELDS] = http_build_query($query);
        }
        elseif(strtoupper($method) != 'GET'){
            $curlopt[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
            $curlopt[CURLOPT_POSTFIELDS] = http_build_query($query);
        }
        elseif(count($query)){
            $url .= strpos($url, '?')? '&' : '?';
            $url .= http_build_query($query);
        }
		$curlopt[CURLOPT_URL] = $this->msBaseRestUrl . $url;
		curl_setopt_array($handle, $curlopt);

		if (!$response = curl_exec($handle)) {
			Yii::error(curl_error($handle), __NAMESPACE__);
			if (YII_DEBUG)
				throw new \RuntimeException(curl_error($handle));
		}

		curl_close($handle);
		return $response;
	}

}
