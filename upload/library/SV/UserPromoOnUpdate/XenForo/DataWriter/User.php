<?php

class SV_UserPromoOnUpdate_XenForo_DataWriter_User extends XFCP_SV_UserPromoOnUpdate_XenForo_DataWriter_User
{
    protected function _postSaveAfterTransaction()
    {
        parent::_postSaveAfterTransaction();

        if (SV_UserPromoOnUpdate_Globals::$RunPromotion)
        {
            // ensure we don't attempt to run the promotion twice in the same request
            SV_UserPromoOnUpdate_Globals::$RunPromotion = false;
            $user = $this->getMergedData();
            /** @var $promotionModel XenForo_Model_UserGroupPromotion */
            $promotionModel = $this->getModelFromCache('XenForo_Model_UserGroupPromotion');
            if ($promotionModel->updatePromotionsForUser($user))
            {
                $visitor = XenForo_Visitor::getInstance();
                // awarded promotions, reload session
                if (XenForo_Application::isRegistered('session') &&
                    $visitor['user_id'] &&
                    $visitor['user_id'] == $user['user_id'])
                {
                    XenForo_Application::getSession()->set('promotionChecked', true);
                    XenForo_Visitor::setup($user['user_id'], XenForo_Visitor::getVisitorSetupOptions());
                }
            }
        }
    }
}