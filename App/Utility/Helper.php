<?php


namespace App\Utility;


class Helper
{
    public static function array_get(array $array,string $key,$default = null)
    {
        if(!is_array($array) || !$array){
            return $default;
        }

        if (strpos($key, '.') === false) {
            if(isset($array[$key])){
                return $array[$key];
            }else{
                return $default;
            }
        }

        foreach (explode('.', $key) as $segment) {
            if (isset($array[$segment]) && !empty($segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function createTree($data, $pid = 0)
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value['menu_pid'] == $pid) {
                unset($data[$key]);
                $value['child'] = self::createTree($data, $value['id']);
                $result[] = $value;
            }
        }
        return $result;
    }

}
