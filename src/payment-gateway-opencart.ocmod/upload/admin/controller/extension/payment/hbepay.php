<?php
class ControllerExtensionPaymenthbepay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/hbepay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_hbepay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/hbepay', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/hbepay', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);


		if (isset($this->request->post['payment_hbepay_test'])) {
			$data['payment_hbepay_test'] = $this->request->post['payment_hbepay_test'];
		} else {
			$data['payment_hbepay_test'] = $this->config->get('payment_hbepay_test');
		}

		if (isset($this->request->post['payment_hbepay_order_status_success_id'])) {
			$data['payment_hbepay_order_status_success_id'] = $this->request->post['payment_hbepay_order_status_success_id'];
		} else {
			$data['payment_hbepay_order_status_success_id'] = $this->config->get('payment_hbepay_order_status_success_id');
		}

		if (isset($this->request->post['payment_hbepay_order_status_fail_id'])) {
			$data['payment_hbepay_order_status_fail_id'] = $this->request->post['payment_hbepay_order_status_fail_id'];
		} else {
			$data['payment_hbepay_order_status_fail_id'] = $this->config->get('payment_hbepay_order_status_fail_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_hbepay_status'])) {
			$data['payment_hbepay_status'] = $this->request->post['payment_hbepay_status'];
		} else {
			$data['payment_hbepay_status'] = $this->config->get('payment_hbepay_status');
		}

		if (isset($this->request->post['payment_hbepay_terminal'])) {
			$data['payment_hbepay_terminal'] = $this->request->post['payment_hbepay_terminal'];
		} else {
			$data['payment_hbepay_terminal'] = $this->config->get('payment_hbepay_terminal');
		}

		if (isset($this->request->post['payment_hbepay_client_id'])) {
			$data['payment_hbepay_client_id'] = $this->request->post['payment_hbepay_client_id'];
		} else {
			$data['payment_hbepay_client_id'] = $this->config->get('payment_hbepay_client_id');
		}

		if (isset($this->request->post['payment_hbepay_client_secret'])) {
			$data['payment_hbepay_client_secret'] = $this->request->post['payment_hbepay_client_secret'];
		} else {
			$data['payment_hbepay_client_secret'] = $this->config->get('payment_hbepay_client_secret');
		}


		if (isset($this->request->post['payment_hbepay_description'])) {
			$data['payment_hbepay_description'] = $this->request->post['payment_hbepay_description'];
		} else {
			$data['payment_hbepay_description'] = $this->config->get('payment_hbepay_description');
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/hbepay', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/hbepay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_hbepay_client_id']) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}

		if (!$this->request->post['payment_hbepay_client_secret']) {
			$this->error['client_secret'] = $this->language->get('error_client_secret');
		}

		return !$this->error;
	}
}
