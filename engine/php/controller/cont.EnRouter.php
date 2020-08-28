<?php

class EnRouter {

    private static function engine(array &$data, string $url, $callbackOrClass, string $method = '') {

        if (!$data['router']['choice']) {

            $url = rtrim($url, '/');
            $url = explode('/', $url);

            $i = 0;
            $bool = false;
            $count = count($url);

            while (!$bool && $i < $count && !empty($url[$i])) {

                $StringData = StringData::get($data, $url[$i]);

                $url[$i] = StringData::clearData($data, $url[$i]);

                $dataToCheck = explode('||', $url[$i]);
                $countDataToCheck = count($dataToCheck);

                $boolDataToCheck = true;
                $j = 0;

                while ($boolDataToCheck && $j < $countDataToCheck) {

                    $val = isset($data['url'][$i]) ? $data['url'][$i] : null;

                    if (StringChecks::get($data, $dataToCheck[$j], $val, $StringData)) {

                        $boolDataToCheck = false;
                    }

                    $j++;
                }

                $bool = $boolDataToCheck;

                $i++;
            }

            if (!$bool) {

                if (is_callable($callbackOrClass)) {

                    $callbackOrClass($data);
                } else {

                    if ($callbackOrClass == 'openClassInLastValueOfUrl') {

                        $method = !empty($data['url'][$i]) ? $data['url'][$i] : 'index';

                        $stringFirstInClassName = !empty($data['stringFirstInClassName']) ? $data['stringFirstInClassName'] : '';

                        $className = getClass($data['url'][$i - 1], $stringFirstInClassName, '');

                        $methodName = getMethod($method);

                        $className::$methodName($data);
                    } else {

                        $callbackOrClass = getClass($callbackOrClass, '', '');

                        $method = !empty($method) ? getMethod($method) : 'index';

                        $callbackOrClass::$method($data);
                    }
                }

                $data['router']['choice'] = true;
            }
        }
    }

    public static function request(array &$data, string $url, $callbackOrClass, string $method = '') {

        self::engine($data, $url, $callbackOrClass, $method);
    }

    public static function get(array &$data, string $url, $callbackOrClass, string $method = '') {

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            self::engine($data, $url, $callbackOrClass, $method);
        }
    }

    public static function post(array &$data, string $url, $callbackOrClass, string $method = '') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            self::engine($data, $url, $callbackOrClass, $method);
        }
    }

    public static function put(array &$data, string $url, $callbackOrClass, string $method = '') {

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

            self::engine($data, $url, $callbackOrClass, $method);
        }
    }

    public static function delete(array &$data, string $url, $callbackOrClass, string $method = '') {

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

            self::engine($data, $url, $callbackOrClass, $method);
        }
    }

}
