<?php

namespace App\Controller;

use Cake\ORM;
use Cake\Core\Configure;
use Cake\Routing\Route;
use App\Utility\Utils;
use App\Controller\CoreController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use App\Model\Entity\Config;
use App\Model\Entity\LanguageContent;

abstract class BackendController extends CoreController {

    protected $slug;
    protected $modelName;
    protected $model;
    protected $multiLanguageFields;
    protected $singlePhotos;
    protected $multiPhotos;

    public function initialize() {
        parent::initialize();
        Utils::useComponents($this, ['Upload']);
        Utils::useTables($this, ['LanguageContent']);
    }

    public function index() {

        $this->render('/Element/Backend/list_view');
    }

    public function edit() {
        $this->_createTemplateFieldUpdate();
        $this->render('/Element/Backend/create_update_view');

        if ($this->request->is('post')) {
            $requestData = $this->_prepareDataUpdate($this->request->getData());
            $this->_createUpdate($requestData);
        }
    }

    protected function _prepareDataUpdate($requestData) {

        return $requestData;
    }

    protected function _createUpdate($requestData, $id = false) {
   
    }

    protected function _createTemplateFieldUpdate() {

        $mutiLanguage = Configure::read('LanguageList');
        $inputField = [];
        $multiLangFields = [];
        $inputField = array_merge($inputField, $this->_prepareObject());
        $inputField = array_merge($inputField, $this->_prepareObject());
        if (!empty($this->multiLanguageFields)) {
            foreach ($mutiLanguage as $languageCode => $languageName) {
                $multiLangFields[$languageCode] = [];
                foreach ($this->multiLanguageFields as $fieldName => $fieldInfo) {
                    $fieldName = $fieldName . '_' . strtolower($languageName);
                    $multiLangFields[$languageCode] = array_merge($multiLangFields[$languageCode], [$fieldName => $fieldInfo]);
                }
            }
        }

        if (!empty($this->singlePhotos)) {
            $inputField = array_merge($inputField, $this->singlePhotos);
        }


        $this->set('inputField', $inputField);
        $this->set('multiLangFields', $multiLangFields);
        $this->set('mutiLanguage', $mutiLanguage);
    }

    protected abstract function _prepareObject();

 
 

}
