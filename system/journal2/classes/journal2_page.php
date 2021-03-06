<?php

class Journal2Page {

    private $layout_id = null;
    private $id = null;
    private $type = null;
    private $modules = array(
        'content_top'   => array(),
        'column_left'   => array(),
        'column_right'  => array(),
        'content_bottom'=> array(),
        'top'           => array(),
        'bottom'        => array()
    );

    public function __construct($registry, $html_classes) {
        $registry->get('load')->model('design/layout');
        $registry->get('load')->model('catalog/category');
        $registry->get('load')->model('catalog/product');
        $registry->get('load')->model('catalog/information');
        $registry->get('load')->model('journal2/module');
        $registry->get('load')->model('journal2/blog');

        $get = $registry->get('request')->get;

        if (isset($get['route'])) {
            $route = (string)$get['route'];
        } else {
            $route = 'common/home';
        }

        if ($route == 'product/category' && isset($get['path'])) {
            $this->type = 'category';
            $this->id = $get['path'];
            $path = explode('_', (string)$get['path']);
            $this->layout_id = $registry->get('model_catalog_category')->getCategoryLayoutId(end($path));
        }

        if ($route == 'journal2/blog') {
            $this->type = 'journal-blog';
            if (isset($get['journal_blog_category_id'])) {
                $this->id = $get['journal_blog_category_id'];
                $this->layout_id = $registry->get('model_journal2_blog')->getBlogCategoryLayoutId($this->id);
            }
        }

        if ($route == 'journal2/blog/post' && isset($get['journal_blog_post_id'])) {
            $this->type = 'journal-blog-post';
            $this->id = $get['journal_blog_post_id'];
            $this->layout_id = $registry->get('model_journal2_blog')->getBlogPostLayoutId($this->id);
        }

        if ($route == 'product/product' && isset($get['product_id'])) {
            $this->type = 'product';
            $this->id = $get['product_id'];
            $this->layout_id = $registry->get('model_catalog_product')->getProductLayoutId($this->id);
        }

        if ($route == 'product/search') {
            $this->type = 'search';
        }

        if ($route == 'product/special') {
            $this->type = 'special';
        }

        if ($route == 'journal2/quickview' && isset($get['pid'])) {
            $this->type = 'quickview';
            $this->id = $get['pid'];
            $this->layout_id = null;
        }

        if ($route == 'information/information' && isset($get['information_id'])) {
            $this->type = 'information';
            $this->id = $get['information_id'];
            $this->layout_id = $registry->get('model_catalog_information')->getInformationLayoutId($this->id);
        }

        if ($route == 'journal2/quickview' && isset($get['product_id'])) {
            $this->type = 'quickview';
            $this->id = $get['product_id'];
        }

        if (strpos($route, 'affiliate') === 0) {
            $this->type = 'affiliate';
        }

        if (strpos($route, 'account') === 0) {
            $this->type = 'account';
        }

        if (strpos($route, 'checkout') === 0) {
            $this->type = 'checkout';
        }

        if ($this->type) {
            $html_classes->addClass($this->type . '-page');
            if ($this->id) {
                $html_classes->addClass($this->type . '-page-' . $this->id);
            }
        }

        if (!isset($get['route']) || $get['route'] === 'common/home') {
            $html_classes->addClass('home-page');
        }

        if (!$this->layout_id) {
            $this->layout_id = $registry->get('model_design_layout')->getLayout($route);
        }

        if (!$this->layout_id) {
            $this->layout_id = $registry->get('config')->get('config_layout_id');
        }

        $html_classes->addClass('layout-' . $this->layout_id);

        if ($route) {
            $html_classes->addClass('route-' . str_replace('/', '-', $route));
        }

        if (Front::$IS_OC2) {
            $html_classes->addClass('oc2');
            $registry->get('load')->model('design/layout');
            $registry->get('load')->model('extension/module');
            $modules = $registry->get('model_design_layout')->getLayoutModules($this->layout_id, 'column_left');
            foreach ($modules as $module) {
                $part = explode('.', $module['code']);

                if (strpos($module['code'], 'journal2_') === 0) {
                    if ($registry->get('config')->get($part[0] . '_' . $module['layout_module_id'] . '_status')) {
                        $action = new Action('module/' . $part[0], array(
                            'position'  => $module['position'],
                            'layout_id' => $this->layout_id,
                            'module_id' => $part[1]
                        ));
                        if ($action->execute($registry)) {
                            $this->modules['column_left'][] = 1;
                        }
                    }
                    continue;
                }

                if (isset($part[0]) && $registry->get('config')->get($part[0] . '_status')) {
                    $action = new Action('module/' . $part[0]);
                    if ($action->execute($registry)) {
                        $this->modules['column_left'][] = 1;
                    }
                }

                if (isset($part[1])) {
                    $setting_info = $registry->get('model_extension_module')->getModule($part[1]);

                    if ($setting_info && $setting_info['status']) {
                        $action = new Action('module/' . $part[0], $setting_info);
                        if ($action->execute($registry)) {
                            $this->modules['column_left'][] = 1;
                        }
                    }
                }
            }
            $modules = $registry->get('model_design_layout')->getLayoutModules($this->layout_id, 'column_right');
            foreach ($modules as $module) {
                $part = explode('.', $module['code']);

                if (strpos($module['code'], 'journal2_') === 0) {
                    if ($registry->get('config')->get($part[0] . '_' . $module['layout_module_id'] . '_status')) {
                        $action = new Action('module/' . $part[0], array(
                            'position'  => $module['position'],
                            'layout_id' => $this->layout_id,
                            'module_id' => $part[1]
                        ));
                        if ($action->execute($registry)) {
                            $this->modules['column_right'][] = 1;
                        }
                    }
                    continue;
                }

                if (isset($part[0]) && $registry->get('config')->get($part[0] . '_status')) {
                    $action = new Action('module/' . $part[0]);
                    if ($action->execute($registry)) {
                        $this->modules['column_right'][] = 1;
                    }
                }

                if (isset($part[1])) {
                    $setting_info = $registry->get('model_extension_module')->getModule($part[1]);

                    if ($setting_info && $setting_info['status']) {
                        $action = new Action('module/' . $part[0], $setting_info);
                        if ($action->execute($registry)) {
                            $this->modules['column_right'][] = 1;
                        }
                    }
                }
            }
        } else {
            $registry->get('load')->model('setting/extension');
            $extensions = $registry->get('model_setting_extension')->getExtensions('module');

            foreach ($extensions as $extension) {
                $modules = $registry->get('config')->get($extension['code'] . '_module');

                if ($modules) {
                    foreach ($modules as $module) {
                        if ($module['layout_id'] == $this->layout_id && $module['status']) {
                            if (isset($module['module_id']) && strpos($extension['code'], 'journal2_') === 0 && !$registry->get('model_journal2_module')->getModule($module['module_id'])) {
                                continue;
                            }
                            $this->modules[$module['position']][] = array(
                                'code'       => $extension['code'],
                                'setting'    => $module,
                                'sort_order' => $module['sort_order']
                            );
                        }
                    }
                }
            }
        }
    }

    public function getType() {
        return $this->type;
    }

    public function getId() {
        return $this->id;
    }

    public function hasModules($position) {
        return count($this->modules[$position]) > 0;
    }

}