<?php
if (!defined('_PS_VERSION_'))
    exit;

class pwthemetester extends Module
{
    public function __construct()
    {
        $this->name = strtolower(get_class());
        $this->tab = 'other';
        $this->version = 0.1;
        $this->author = 'Prestaweb.ru';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = "Тест элементов темы";
        $this->description = "Модуль для тестирования стилей темы стандартных элементов html";
    }

    public function install()
    {
        return (parent::install()  AND $this->registerHook('displayHeader'));
    }

    //start_helper
    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Настройки'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Случайная настройка 1'),
                        'name' => 'PWTHEMETESTER_OPTION1',
                        'hint' => $this->l('Select which category is displayed in the block. The current category is the one the visitor is currently browsing.'),
                        'values' => array(
                            array(
                                'id' => 'home',
                                'value' => 0,
                                'label' => $this->l('Вариант 1')
                            ),
                            array(
                                'id' => 'current',
                                'value' => 1,
                                'label' => $this->l('Вариант 2')
                            ),
                            array(
                                'id' => 'parent',
                                'value' => 2,
                                'label' => $this->l('Вариант 3')
                            )
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Настройка 2'),
                        'name' => 'PWTHEMETESTER_OPTION2',
                        'desc' => $this->l('Подсказка'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Настройка 3'),
                        'name' => 'PWTHEMETESTER_OPTION3',
                        'desc' => $this->l('Подсказка'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Сохранить'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPWTHEMETESTER';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'PWTHEMETESTER_OPTION1' => Tools::getValue('PWTHEMETESTER_OPTION1', Configuration::get('PWTHEMETESTER_OPTION1')),
            'PWTHEMETESTER_OPTION2' => Tools::getValue('PWTHEMETESTER_OPTION2', Configuration::get('PWTHEMETESTER_OPTION2')),
            'PWTHEMETESTER_OPTION3' => Tools::getValue('PWTHEMETESTER_OPTION3', Configuration::get('PWTHEMETESTER_OPTION3')),
        );
    }
    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitPWTHEMETESTER'))
        {
            $maxDepth = (int)(Tools::getValue('PWTHEMETESTER_OPTION1'));
            if ($maxDepth < 0)
                $output .= $this->displayError($this->l('Опция не прошла проверку, убирите её из кода если не нужна'));
            else{
                Configuration::updateValue('PWTHEMETESTER_OPTION1', Tools::getValue('PWTHEMETESTER_OPTION1'));
                Configuration::updateValue('PWTHEMETESTER_OPTION2', Tools::getValue('PWTHEMETESTER_OPTION2'));
                Configuration::updateValue('PWTHEMETESTER_OPTION3', Tools::getValue('PWTHEMETESTER_OPTION3'));
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=6');
            }
        }
        return $output.$this->renderForm();
    }
    //end_helper

    


	public function hookdisplayHeader($params){
		$this->context->controller->addCSS($this->_path.$this->name.'.css', 'all');
		$this->context->controller->addJS($this->_path.$this->name.'.js');
	}


}


