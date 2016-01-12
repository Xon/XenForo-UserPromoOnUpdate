<?php

class SV_UserPromoOnUpdate_Listener
{
    const AddonNameSpace = 'SV_UserPromoOnUpdate_';

    public static function controller_pre_dispatch(XenForo_Controller $controller, $action, $controllerName)
    {
        SV_UserPromoOnUpdate_Globals::$RunPromotion = true;
    }

    public static function load_class($class, array &$extend)
    {
        $extend[] = self::AddonNameSpace.$class;
    }    
}