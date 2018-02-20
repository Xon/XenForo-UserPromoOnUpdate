<?php

class SV_UserPromoOnUpdate_Listener
{
    public static function load_class($class, array &$extend)
    {
        $extend[] = 'SV_UserPromoOnUpdate_' . $class;
    }
}
