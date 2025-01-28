<?php
class ControllerExtensionPaymenthbepay extends Controller {
	public function index() {		
		
		$data['action'] = $this->url->link('extension/payment/hbepay/paymentGateway', '', true);

		return $this->load->view('extension/payment/hbepay', $data);
	}

	function paymentGateway() {
		// initiate api urls
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$test_url = "https://testoauth.homebank.kz/epay2/oauth2/token";
		$prod_url = "https://epay-oauth.homebank.kz/oauth2/token";
		$test_page = "https://test-epay.homebank.kz/payform/payment-api.js";
        	$prod_page = "https://epay.homebank.kz/payform/payment-api.js";

		$token_api_url = "";
		$pay_page = "";
		$err_exist = false;
		$err = "";

		// initiate default variables
		$hbp_account_id = "";
		$hbp_telephone = "";
		$hbp_email = "";
		$hbp_currency = "KZT";
		$hbp_language = "RU";
		$language = $this->language->get('code');
		$hbp_description = "Оплата в интернет магазине";


		$hbp_client_id = $this->config->get('payment_hbepay_client_id');
		$hbp_client_secret = $this->config->get('payment_hbepay_client_secret');
		$hbp_terminal = $this->config->get('payment_hbepay_terminal');
		$hbp_invoice_id = '201001' . $order_info['order_id'];
		$hbp_amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		if(isset($language)) {
				$hbp_language = $language;
		}
		if(isset($order_info['currency_code'])) {
				$hbp_currency = $order_info['currency_code'];
		}
		$hbp_back_link = $this->url->link('extension/payment/hbepay/success');
		$hbp_failure_back_link = $this->url->link('extension/payment/hbepay/failure');
		$hbp_post_link = '';
		$hbp_failure_post_link = '';
		

		if ($this->config->get('payment_hbepay_test')) {
				$token_api_url = $test_url;
				$pay_page = $test_page;
		} else {
				$token_api_url = $prod_url;
				$pay_page = $prod_page;
		}
		
		$fields = [
				'grant_type'      => 'client_credentials', 
				'scope'           => 'payment usermanagement',
				'client_id'       => $hbp_client_id,
				'client_secret'   => $hbp_client_secret,
				'invoiceID'       => $hbp_invoice_id,
				'amount'          => $hbp_amount,
				'currency'        => $hbp_currency,
				'terminal'        => $hbp_terminal,
				'postLink'        => '',
				'failurePostLink' => ''
			];
		
			$fields_string = http_build_query($fields);
		
			$ch = curl_init();
		
			curl_setopt($ch, CURLOPT_URL, $token_api_url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		
			$result = curl_exec($ch);
		
			$json_result = json_decode($result, true);
			if (!curl_errno($ch)) {
				switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
						case 200:
								$hbp_auth = (object) $json_result;
		
								$hbp_payment_object = (object) [
										"invoiceId" => $hbp_invoice_id,
										"backLink" => $hbp_back_link,
										"failureBackLink" => $hbp_failure_back_link,
										"postLink" => $hbp_post_link,
										"failurePostLink" => $hbp_failure_post_link,
										"language" => $hbp_language,
										"description" => $hbp_description,
										"accountId" => $hbp_account_id,
										"terminal" => $hbp_terminal,
										"amount" => $hbp_amount,
										"currency" => $hbp_currency,
										"auth" => $hbp_auth,
										"phone" => $hbp_telephone,
										"email" => $hbp_email
								];
						?>
						<script src="<?=$pay_page?>"></script>
						<script>
								halyk.pay(<?= json_encode($hbp_payment_object) ?>);
						</script>
				<?php
								break;
						default:
								echo 'Неожиданный код HTTP: ', $http_code, "\n";
				}
		}
	}

	public function success() {
		$this->load->language('extension/payment/hbepay');
		$this->load->model('checkout/order');

	
		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_hbepay_order_status_success_id'), 'Successful Payment');

		$this->response->redirect($this->url->link('checkout/success'));
	}

	public function failure() {
		$this->load->language('extension/payment/hbepay');
		$this->load->model('checkout/order');

	
		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_hbepay_order_status_fail_id'), 'Payment failed');

		$this->response->redirect($this->url->link('checkout/failure'));
	}

	
}