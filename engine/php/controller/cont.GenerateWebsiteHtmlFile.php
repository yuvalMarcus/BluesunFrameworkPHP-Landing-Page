<?php

class GenerateWebsiteHtmlFile {

    private $folder;
    private $convertImageToBase64;
    private $clearSpaces;

    public function __construct(array &$data) {

        $data['mysql']['executearray'] = ['simple-html'];
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['table_name'] = 'compiler_settings';
        $data['mysql']['WHERE'] = 'WHERE metatype = ?';
        $data['mysql']['clear'] = true;

        \Database::getDatas($data, 'desdata');

        $data['compilerSettings'] = [];

        foreach ($data['desdata'] as $val) {

            $data['compilerSettings'][$val['metakey']] = json_decode($val['metavalue']);
        }

        $data['compilerSettings'] = (object) $data['compilerSettings'];

        $this->folder = 'static/';

        $this->convertImageToBase64 = $data['compilerSettings']->convertImageToBase64->value;

        $this->clearSpaces = $data['compilerSettings']->clearSpaces->value;

        $data['pageBuildLoadStaticFile'] = $data['compilerSettings']->pageBuildLoadStaticFile->value;
    }

    public function set(array &$data) {

        $data['mysql']['executearray'] = [0, 'bluesun_framework_php-system-pages-manager', 'bluesun_framework_php-system-pages-manager', 0, 'bluesun_framework_php-system-pages-manager-protected', 'bluesun_framework_php-system-pages-manager-protected'];
        $data['mysql']['table_name'] = 'zone_page item';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE home = ? AND type = ? AND stringcode = ? AND (SELECT zone_page_metadata.metavalue metavalue  FROM zone_page_metadata WHERE zoneid = item.id AND metakey = "recyclingbin") = "false" OR home = ? AND type = ? AND stringcode = ? AND (SELECT zone_page_metadata.metavalue metavalue  FROM zone_page_metadata WHERE zoneid = item.id AND metakey = "recyclingbin") = "false"';
        $data['mysql']['clear'] = true;

        Database::getDatas($data, 'pagesdata');

        $this->setHomePage($data);

        foreach ($data['languages'] as $language) {

            $data['languageClass'] = strtoupper($language::getMetadata($data)['metaname'][0]) . substr($language::getMetadata($data)['metaname'], 1);

            \Language::$typeLang = $language::getMetadata($data)['metaname'];
            \Language::$typeLangKey = $language::getMetadata($data)['metakey'];

            $data['defaultLanguage'] = \Language::$typeLang;

            foreach ($data['pagesdata'] as $val) {

                ob_start();

                $data['pageBuild'] = new PageBuild($data);
                $data['pageBuild']->clearSpaces = $this->clearSpaces;

                $data['zone'] = new Zone($data, $val['slug']);

                $data['board']->setPage($data);

                $output = ob_get_contents();
                ob_end_clean();

                if ($this->convertImageToBase64) {

                    $match = [];

                    preg_match_all('@src="([^"]+)"@', $output, $match);

                    $imgArray = array_pop($match);

                    foreach ($imgArray as $imgSrc) {

                        $type = pathinfo($imgSrc, PATHINFO_EXTENSION);

                        $url = '';

                        if (strpos($imgSrc, 'https') !== false || strpos($imgSrc, 'http') !== false)
                            $url = $imgSrc;
                        else
                            $url = $data['urlsite'] . $imgSrc;

                        if (@getimagesize($url)) {

                            $imgContent = @file_get_contents($data['urlsite'] . $imgSrc) ? file_get_contents($data['urlsite'] . $imgSrc) : file_get_contents($imgSrc);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imgContent);

                            $output = str_replace($imgSrc, $base64, $output);
                        }
                    }
                }

                file_put_contents($this->folder . '' . $language::getMetadata($data)['metakey'] . $val['slug'] . '.html', $output . PHP_EOL);
            }
        }
    }

    public function setHomePage(array &$data) {

        $data['mysql']['executearray'] = [1];
        $data['mysql']['table_name'] = 'zone_page';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE home = ? ';
        $data['mysql']['clear'] = TRUE;

        Database::getData($data, 'zonedata');

        foreach ($data['languages'] as $language) {

            $data['languageClass'] = strtoupper($language::getMetadata($data)['metaname'][0]) . substr($language::getMetadata($data)['metaname'], 1);

            \Language::$typeLang = $language::getMetadata($data)['metaname'];
            \Language::$typeLangKey = $language::getMetadata($data)['metakey'];

            $data['defaultLanguage'] = \Language::$typeLang;

            ob_start();

            $data['pageBuild'] = new PageBuild($data);
            $data['pageBuild']->clearSpaces = $this->clearSpaces;

            $data['zone'] = new Zone($data, $data['zonedata']['slug']);

            $data['board']->setPage($data);

            $output = ob_get_contents();
            ob_end_clean();

            if ($this->convertImageToBase64) {

                $match = [];

                preg_match_all('@src="([^"]+)"@', $output, $match);

                $imgArray = array_pop($match);

                foreach ($imgArray as $imgSrc) {

                    $type = pathinfo($imgSrc, PATHINFO_EXTENSION);

                    $url = '';

                    if (strpos($imgSrc, 'https') !== false || strpos($imgSrc, 'http') !== false)
                        $url = $imgSrc;
                    else
                        $url = $data['urlsite'] . $imgSrc;

                    if (@getimagesize($url)) {

                        $imgContent = @file_get_contents($data['urlsite'] . $imgSrc) ? file_get_contents($data['urlsite'] . $imgSrc) : file_get_contents($imgSrc);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imgContent);

                        $output = str_replace($imgSrc, $base64, $output);
                    }
                }
            }

            file_put_contents($this->folder . '' . $language::getMetadata($data)['metakey'] . 'index.html', $output . PHP_EOL);
        }
    }

    public function delete(array &$data) {

        $data['mysql']['executearray'] = [0, 'bluesun_framework_php-system-pages-manager', 'bluesun_framework_php-system-pages-manager', 0, 'bluesun_framework_php-system-pages-manager-protected', 'bluesun_framework_php-system-pages-manager-protected'];
        $data['mysql']['table_name'] = 'zone_page item';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE home = ? AND type = ? AND stringcode = ? AND (SELECT zone_page_metadata.metavalue metavalue  FROM zone_page_metadata WHERE zoneid = item.id AND metakey = "recyclingbin") = "false" OR home = ? AND type = ? AND stringcode = ? AND (SELECT zone_page_metadata.metavalue metavalue  FROM zone_page_metadata WHERE zoneid = item.id AND metakey = "recyclingbin") = "false"';
        $data['mysql']['clear'] = true;

        Database::getDatas($data, 'pagesdata');

        $this->deleteHomePage($data);

        foreach ($data['languages'] as $language) {

            foreach ($data['pagesdata'] as $val) {

                $url = $val['slug'];

                if (is_file($this->folder . '' . $language::getMetadata($data)['metakey'] . $url . '.html')) {
                    unlink($this->folder . '' . $language::getMetadata($data)['metakey'] . $url . '.html');
                }
            }
        }
    }

    public function deleteHomePage(array &$data) {

        foreach ($data['languages'] as $language) {

            if (is_file($this->folder . '' . $language::getMetadata($data)['metakey'] . 'index.html')) {
                unlink($this->folder . '' . $language::getMetadata($data)['metakey'] . 'index.html');
            }
        }
    }

}
