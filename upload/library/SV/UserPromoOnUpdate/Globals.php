<?php

// This class is used to encapsulate global state between layers without using $GLOBAL[] or
// relying on the consumer being loaded correctly by the dynamic class autoloader
class SV_UserPromoOnUpdate_Globals
{
    public static $RunPromotion = [];

    private function __construct() { }
}
