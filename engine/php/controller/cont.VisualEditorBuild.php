<?php

class VisualEditorBuild {

    private $objid;
    private $stringcode;

    public function __construct(array &$data, int $objid = 0, string $stringcode = '') {

        $this->objid = $objid;
        $this->stringcode = $stringcode;
    }

    public function setObjid(array &$data, int $objid = 0) {

        $this->objid = $objid;
    }

    public function setStringcode(array &$data, string $stringcode = '') {

        $this->stringcode = $stringcode;
    }

    public function loadScripts(array &$data) {

        View::showView('views/php/visualEditorBuild/admin/tpl.scripts', $data);
    }

    public function show(array &$data, string $type = 'admin', bool $loadScripts = true) {

        $data['objid'] = $this->objid;
        $data['stringcode'] = $this->stringcode;

        switch ($type) {
            case 'admin':
                $this->showAdmin($data, $loadScripts);
                break;
            case 'front':
                $this->showFront($data, $loadScripts);
                break;
        }
    }

    public function showAdmin(array &$data, bool $loadScripts = true) {

        if ($loadScripts)
            $this->loadScripts($data);

        View::showView('views/php/visualEditorBuild/admin/default/tpl.index', $data);
    }

    public function showFront(array &$data, bool $loadScripts = true) {

        $data['visualEditorBuild']['htmlcontent'] = '';

        $this->getContentByReference($data);

        View::showView('views/php/visualEditorBuild/front/default/tpl.index', $data);
    }

    private function getContentByReference(array &$data) {

        $data['htmlcontent'] = [];

        $data['mysql']['executearray'] = [$this->objid, $this->stringcode];
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['table_name'] = 'visualeditorbuild_data';
        $data['mysql']['WHERE'] = 'WHERE objid = ? AND stringcode = ?';
        $data['mysql']['clear'] = TRUE;

        \Database::getData($data, 'htmlcontent');

        $data['visualEditorBuild']['htmlcontent'] = !empty($data['htmlcontent']) ? $data['htmlcontent']['content'] : '';
        $data['visualEditorBuild']['css'] = !empty($data['htmlcontent']) ? $data['htmlcontent']['css'] : '';
        $data['visualEditorBuild']['javascript'] = !empty($data['htmlcontent']) ? $data['htmlcontent']['javascript'] : '';
    }

}
