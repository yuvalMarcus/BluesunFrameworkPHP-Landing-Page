<?php

class StringData {

    public static function get(array &$data, $stringData = ''): array {

        $stringData = getStringBetween($stringData, 'data=[', ']');

        $newData = [];

        if (!empty($stringData)) {

            $stringData = explode(',', $stringData);

            foreach ($stringData as $dataItem) {

                $arr = explode('|', $dataItem);

                $newData[$arr[0]] = isset($arr[1]) ? $arr[1] : '';
            }
        }

        return $newData;
    }

    public static function clearData(array &$data, $string = ''): string {

        return str_replace('(data=[' . getStringBetween($string, '(data=[', '])') . '])', '', $string);
    }

}
