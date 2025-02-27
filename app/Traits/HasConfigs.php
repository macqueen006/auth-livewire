<?php

namespace App\Traits;

trait HasConfigs
{
    public $settings = [];
    public $language = [];
    public $appearance = [];

    public function loadConfigs()
    {
        $this->settings = $this->configToArrayObject('settings');
        $this->language = $this->configToArrayObject('language');
        $this->appearance = $this->configToArrayObject('appearance');
    }

    private function configToArrayObject($configPath)
    {
        $configArray = config($configPath);

        return $this->arrayToObject($configArray);
    }

    private function arrayToObject($array)
    {
        if (!is_array($array)) {
            return $array;
        }

        $object = new \stdClass;
        foreach ($array as $key => $value) {
            $object->$key = $this->arrayToObject($value);
        }

        return $object;
    }
}
