<?php

class PageBuild {

    private $output;
    private $css;
    private $clearSpaces;

    public function __construct(&$data) {

        $this->output = '';
        $this->css = '';
        $this->clearSpaces = false;
    }

    public function __get($property) {

        if (property_exists($this, $property)) {

            return $this->$property;
        }
    }

    public function __set($property, $value) {

        if (property_exists($this, $property)) {

            $this->$property = $value;
        }

        return $this;
    }

    public function strReplaceFirst($from, $to, $subject) {

        $from = '/' . preg_quote($from, '/') . '/';

        return preg_replace($from, $to, $subject, 1);
    }

    public function getStringBetween($string, $start, $end) {

        $string = ' ' . $string;

        $ini = strpos($string, $start);

        if ($ini == 0)
            return '';

        $ini += strlen($start);

        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }

    public function build(array &$data, string $put, string $typeData = 'view') {

        if ($typeData == 'view') {

            if (is_file($put)) {

                ob_start();
                include $put;
                $this->output = ob_get_contents();
                ob_end_clean();
            } else {
                Header::Location('/404');
            }
        } else if ($typeData == 'content') {
            $this->output = $put;
        } else {
            $this->output = '';
        }

        $this->setSectionView($data);

        $this->setCss($data);

        $this->buildMaster($data);
    }

    public function buildMaster(array &$data) {

        if (empty($this->getStringBetween($this->output, "@masterSection(", ")end"))) {

            $this->output = str_replace('@cssPut()end', $this->css, $this->output);

            if ($this->clearSpaces)
                $this->output = preg_replace('/\r|\n/', '', $this->output);

            return;
        }

        $this->setMasterSection($data);

        $this->setSectionView($data);

        $this->setCss($data);

        $this->buildMaster($data);
    }

    private function setSectionData(array &$data, $content) {

        $section = $this->getStringBetween($content, "@set(", ")end");

        $sectionString = $section;

        if (!empty($section)) {

            $stringData = StringData::get($data, $section);

            $section = explode('.', $section);

            if ($section[0] == 'file') {

                $url = $this->getStringBetween($this->output, "file." . $section[1] . ".data=[url|", "])end");

                if (!empty($data['pageBuildLoadStaticFile'])) {

                    $output = file_get_contents($url);

                    if ($section[1] == 'css') {
                        $output = '<style>' . $output . '</style>';
                    } else if ($section[1] == 'script') {
                        $output = '<script>' . $output . '</script>';
                    }
                } else {

                    $output = $url;

                    if ($section[1] == 'css') {

                        $output = '<link rel="stylesheet" href="' . $output . '">';
                    } else if ($section[1] == 'script') {
                        $output = '<script src="' . $output . '"></script>';
                    }
                }

                $replace = '@set(file.' . $section[1] . '.data=[url|' . $url . '])end';
            } else if ($section[0] == 'view') {

                if (!empty($stringData['type']) && $stringData['type'] == 'plugin') {

                    $output = '';

                    $stringcode = !empty($stringData['stringcode']) ? $stringData['stringcode'] : '';
                    $folder = !empty($stringData['folder']) ? $stringData['folder'] : '';

                    if (is_file('plugins/' . $folder . '/php/visualEditorBuild/loadItem/index.php')) {

                        ob_start();
                        include 'plugins/' . $folder . '/php/visualEditorBuild/loadItem/index.php';
                        $output = ob_get_contents();
                        ob_end_clean();
                    }

                    $replace = '@set(' . $sectionString . ')end';
                } else if (!empty($stringData['type']) && $stringData['type'] == 'admin') {

                    $output = '';

                    if (!isset($objectStructure[$stringData['file']]) || ( isset($objectStructure[$stringData['file']]) && $objectStructure[$stringData['file']])) {

                        if (!empty($stringData['db']) && $stringData['db'] == 'true') {

                            $data[$stringData['objectName']]->get($data, $stringData['file']);
                        }

                        ob_start();
                        include 'views/php/admin/tpl.' . $stringData['file'] . '.php';
                        $output = ob_get_contents();
                        ob_end_clean();
                    }

                    $replace = '@set(' . $sectionString . ')end';
                } else if (!empty($stringData['type']) && $stringData['type'] == 'template') {

                    $output = '';

                    if (!isset($objectStructure[$stringData['file']]) || ( isset($objectStructure[$stringData['file']]) && $objectStructure[$stringData['file']])) {

                        if (!empty($stringData['db']) && $stringData['db'] == 'true') {

                            $data[$stringData['objectName']]->get($data, $stringData['file']);
                        }

                        ob_start();
                        include 'views/php/template/website/public/' . $data['template']->name . '/tpl.' . $stringData['file'] . '.php';
                        $output = ob_get_contents();
                        ob_end_clean();
                    }

                    $replace = '@set(' . $sectionString . ')end';
                }
            } else if ($section[0] == 'data') {

                if (!isset($objectStructure[$stringData['type']]) || ( isset($objectStructure[$stringData['type']]) && $objectStructure[$stringData['type']])) {

                    $data[$stringData['objectName']]->get($data, $stringData['type']);
                }

                $output = '';
                $replace = '@set(' . $sectionString . ')end';
            } elseif ($section[0] == '') {
                
            } else {

                $output = '';
                $replace = '@set(' . $section[0] . ')end';
            }

            $content = str_replace($replace, $output, $content);

            $this->setSectionData($data, $content);
        }
    }

    private function setSectionView(array &$data) {

        $section = $this->getStringBetween($this->output, "@set(", ")end");

        $sectionString = $section;

        if (!empty($section)) {

            $stringData = StringData::get($data, $section);

            $section = explode('.', $section);

            if (!empty($data['zone']->data->objectStructure))
                $objectStructure = (array) $data['zone']->data->objectStructure;
            else
                $objectStructure = [];

            if ($section[0] == 'file') {

                $url = $this->getStringBetween($this->output, "file." . $section[1] . ".data=[url|", "])end");

                if (!empty($data['pageBuildLoadStaticFile'])) {

                    if (strpos($url, 'https://') === false)
                        $fileUrl = $data['urlsite'] . $url;
                    else
                        $fileUrl = $url;

                    $output = file_get_contents($fileUrl);

                    if ($section[1] == 'css') {
                        $output = '<style>' . $output . '</style>';
                    } else if ($section[1] == 'script') {
                        $output = '<script>' . $output . '</script>';
                    }
                } else {

                    $output = $url;

                    if ($section[1] == 'css') {

                        $output = '<link rel="stylesheet" href="' . $output . '">';
                    } else if ($section[1] == 'script') {
                        $output = '<script src="' . $output . '"></script>';
                    }
                }

                $replace = '@set(file.' . $section[1] . '.data=[url|' . $url . '])end';
            } else if ($section[0] == 'view') {

                if (!empty($stringData['type']) && $stringData['type'] == 'plugin') {

                    $output = '';

                    $stringcode = !empty($stringData['stringcode']) ? $stringData['stringcode'] : '';
                    $folder = !empty($stringData['folder']) ? $stringData['folder'] : '';

                    if (is_file('plugins/' . $folder . '/php/visualEditorBuild/loadItem/index.php')) {

                        ob_start();
                        include 'plugins/' . $folder . '/php/visualEditorBuild/loadItem/index.php';
                        $output = ob_get_contents();
                        ob_end_clean();
                    }

                    $replace = '@set(' . $sectionString . ')end';
                } else if (!empty($stringData['type']) && $stringData['type'] == 'admin') {

                    $output = '';

                    if (!isset($objectStructure[$stringData['file']]) || ( isset($objectStructure[$stringData['file']]) && $objectStructure[$stringData['file']])) {

                        if (!empty($stringData['db']) && $stringData['db'] == 'true') {

                            $data[$stringData['objectName']]->get($data, $stringData['file']);
                        }

                        ob_start();
                        include 'views/php/admin/tpl.' . $stringData['file'] . '.php';
                        $output = ob_get_contents();
                        ob_end_clean();
                    }

                    $replace = '@set(' . $sectionString . ')end';
                } else if (!empty($stringData['type']) && $stringData['type'] == 'template') {

                    $output = '';

                    if (!isset($objectStructure[$stringData['file']]) || ( isset($objectStructure[$stringData['file']]) && $objectStructure[$stringData['file']])) {

                        if (!empty($stringData['db']) && $stringData['db'] == 'true') {

                            $data[$stringData['objectName']]->get($data, $stringData['file']);
                        }

                        ob_start();
                        include 'views/php/template/website/public/' . $data['template']->name . '/tpl.' . $stringData['file'] . '.php';
                        $output = ob_get_contents();
                        ob_end_clean();
                    }

                    $replace = '@set(' . $sectionString . ')end';
                }
            } else if ($section[0] == 'data') {

                if (!isset($objectStructure[$stringData['type']]) || ( isset($objectStructure[$stringData['type']]) && $objectStructure[$stringData['type']])) {

                    $data[$stringData['objectName']]->get($data, $stringData['type']);
                }

                $output = '';
                $replace = '@set(' . $sectionString . ')end';
            } elseif ($section[0] == '') {
                
            } else {

                $output = '';
                $replace = '@set(' . $section[0] . ')end';
            }

            $this->output = str_replace($replace, $output, $this->output);

            $this->setSectionView($data);
        }
    }

    private function setMasterSection(array &$data) {

        $masterSection = $this->getStringBetween($this->output, "@masterSection(", ")end");

        $sectionString = $masterSection;

        if (!empty($masterSection)) {

            $stringData = StringData::get($data, $masterSection);

            $masterSection = explode('.', $masterSection);

            $this->output = str_replace('@masterSection(' . $sectionString . ')end', '', $this->output);

            $type = empty($stringData['type']) ? 'template' : $stringData['type'];

            if ($type == 'template') {

                ob_start();
                include 'views/php/template/website/public/' . $data['template']->name . '/tpl.' . $stringData['file'] . '.php';
                $masterSectionPage = ob_get_contents();
                ob_end_clean();

                $this->output = str_replace('@section(view.data=[type|template,name|' . $stringData['name'] . '])end', $this->output, $masterSectionPage);
            } else if ($type == 'admin') {

                ob_start();
                include 'views/php/admin/tpl.' . $stringData['file'] . '.php';
                $masterSectionPage = ob_get_contents();
                ob_end_clean();

                $this->output = str_replace('@section(view.data=[type|admin,name|' . $stringData['name'] . '])end', $this->output, $masterSectionPage);
            }

            $this->setMasterSection($data);
        }
    }

    private function setCss(array &$data) {

        $css = $this->getStringBetween($this->output, "@css(view.head)start", "@css(view.head)end");

        if (!empty($css)) {

            $this->css .= $css;

            $this->output = $this->strReplaceFirst('@css(view.head)start', '', $this->output);
            $this->output = $this->strReplaceFirst($css, '', $this->output);
            $this->output = $this->strReplaceFirst('@css(view.head)end', '', $this->output);

            $this->setCss($data);
        }
    }

    public function show(array &$data): string {

        return $this->output;
    }

}
