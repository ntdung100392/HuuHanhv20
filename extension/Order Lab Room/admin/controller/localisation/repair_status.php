<?php

class ControllerLocalisationRepairStatus extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('localisation/repair_status');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/repair_status');

        $this->getList();
    }

    public function insert() {
        $this->load->language('localisation/repair_status');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/repair_status');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_localisation_repair_status->addRepairStatus($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('localisation/repair_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function update() {
        $this->load->language('localisation/repair_status');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/repair_status');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_localisation_repair_status->editRepairStatus($this->request->get['repair_status_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('localisation/repair_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('localisation/repair_status');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/repair_status');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $repair_status_id) {
                $this->model_localisation_repair_status->deleteRepairStatus($repair_status_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('localisation/repair_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('localisation/repair_status', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['insert'] = $this->url->link('localisation/repair_status/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('localisation/repair_status/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['repair_statuses'] = array();

        $dat = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $repair_status_total = $this->model_localisation_repair_status->getTotalRepairStatuses();

        $results = $this->model_localisation_repair_status->getRepairStatuses($dat);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('localisation/repair_status/update', 'token=' . $this->session->data['token'] . '&repair_status_id=' . $result['repair_status_id'] . $url, 'SSL')
            );

            $data['repair_statuses'][] = array(
                'repair_status_id' => $result['repair_status_id'],
                'name' => $result['name'] . (($result['repair_status_id'] == $this->config->get('config_repair_status_id')) ? $this->language->get('text_default') : null),
                'selected' => isset($this->request->post['selected']) && in_array($result['repair_status_id'], $this->request->post['selected']),
                'action' => $action,
               );
        }

   	$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
                
        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('localisation/repair_status', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $repair_status_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('localisation/repair_status', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/repair_status_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_name'] = $this->language->get('entry_name');
    $data['column_name'] = $this->language->get('column_name');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('localisation/repair_status', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['repair_status_id'])) {
            $data['action'] = $this->url->link('localisation/repair_status/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('localisation/repair_status/update', 'token=' . $this->session->data['token'] . '&repair_status_id=' . $this->request->get['repair_status_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('localisation/repair_status', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['repair_status'])) {
            $data['repair_status'] = $this->request->post['repair_status'];
        } elseif (isset($this->request->get['repair_status_id'])) {
            $data['repair_status'] = $this->model_localisation_repair_status->getRepairStatusDescriptions($this->request->get['repair_status_id']);
        } else {
            $data['repair_status'] = array();
        }


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/repair_status_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'localisation/repair_status')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['repair_status'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'localisation/repair_status')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('sale/repair');

        foreach ($this->request->post['selected'] as $repair_status_id) {
            if ($this->config->get('config_repair_status_id') == $repair_status_id) {
                $this->error['warning'] = $this->language->get('error_default');
            }

            $repair_total = $this->model_sale_repair->getTotalRepairsByRepairStatusId($repair_status_id);

            if ($repair_total) {
                $this->error['warning'] = sprintf($this->language->get('error_repair'), $repair_total);
            }

            $repair_total = $this->model_sale_repair->getTotalRepairHistoriesByRepairStatusId($repair_status_id);

            if ($repair_total) {
                $this->error['warning'] = sprintf($this->language->get('error_repair'), $repair_total);
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}

?>