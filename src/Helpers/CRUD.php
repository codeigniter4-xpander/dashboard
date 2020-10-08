<?php namespace CI4Xpander_Dashboard\Helpers;

class CRUD
{
    public static function renderField($data, $name, $label)
    {
        if (is_array($label)) {
            if (is_callable($label['value'])) {
                return $label['value'](is_object($data) ? $data->{$name} : $data[$name], $data);
            } if (is_array($label['value'])) {
                $subValue = '<ul>';
                foreach ($label['value'] as $subName => $subLabel) {
                    if (is_object($data)) {
                        $subValue .= "<li>{$data->{$subName}}</li>";
                    } else {
                        $subValue .= "<Li>{$data[$subName]}</li>";
                    }
                }
                return $subValue . '</ul>';
            } else {
                if (is_object($data)) {
                    return $data->{$label['value']};
                } else {
                    return $data[$label['value']];
                }
            }
        } else {
            if (is_object($data)) {
                return $data->{$name};
            } else {
                return $data[$name];
            }
        }
    }
}