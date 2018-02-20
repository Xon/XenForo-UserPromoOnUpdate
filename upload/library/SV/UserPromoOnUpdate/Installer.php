<?php

class SV_UserPromoOnUpdate_Installer
{
    public static function install(/** @noinspection PhpUnusedParameterInspection */$existingAddOn, array $addOnData, SimpleXMLElement $xml)
    {
        $required = '5.4.0';
        $phpversion = phpversion();
        if (version_compare($phpversion, $required, '<'))
        {
            throw new XenForo_Exception(
                "PHP {$required} or newer is required. {$phpversion} does not meet this requirement. Please ask your host to upgrade PHP",
                true
            );
        }
        if (XenForo_Application::$versionId < 1030070)
        {
            throw new XenForo_Exception('XenForo 1.3.0+ is Required!', true);
        }
    }
}
