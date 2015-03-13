<?php

/*
 *  @link https://github.com/porshkevich/yii2-moysklad-api
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\moysklad;

use Yii;
use yii\base\Component;

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

	public $msBaseRestUrl = ' https://online.moysklad.ru/exchange/rest';
	public $user_agent = '';

	public function init() {
		if (!$this->username || !$this->password)
			throw new InvalidConfigException('The "username" or "password" propertys must be set.');

		if (!$this->company || !$this->storeId)
			throw new InvalidConfigException('The "company" or "storeId" propertys must be set.');
	}

	public function addOrder($order) {

	}

	public function getProduct() {

	}

	public function getProducts() {

	}

	public function getCategory() {

	}

	public function getCategories() {

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
		$options[CURLOPT_INFILESIZE] = strlen($body);
		$result = $this->execute($url, 'PUT', $options, $query);
		fclose($fp);
        return $result;
    }

    private function delete($url, $options = [], $query = []){
        return $this->execute($url, 'DELETE', $options, $query);
    }

	private function execute($url, $method = 'GET', $options = [], $query = []) {
		$handle	 = curl_init();
		$curlopt = array_merge([
			CURLOPT_RETURNTRANSFER	 => TRUE,
			CURLOPT_USERAGENT		 => $this->user_agent,
			CURLOPT_SSL_VERIFYPEER	 => false,
			CURLOPT_POSTFIELDS		 => http_build_query($query),
				], $options);

		if (!$response = curl_exec($handle))
			Yii::error(curl_error($handle), __NAMESPACE__);
		return $response;
	}

}
