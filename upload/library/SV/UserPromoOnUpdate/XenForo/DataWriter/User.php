<?php

class SV_UserPromoOnUpdate_XenForo_DataWriter_User extends XFCP_SV_UserPromoOnUpdate_XenForo_DataWriter_User
{
    protected function _postSaveAfterTransaction()
    {
        parent::_postSaveAfterTransaction();

        $userId = $this->get('user_id');
        if (!isset(SV_UserPromoOnUpdate_Globals::$RunPromotion[$userId]) || SV_UserPromoOnUpdate_Globals::$RunPromotion[$userId])
        {
            // ensure we don't attempt to run the promotion twice in the same request
            SV_UserPromoOnUpdate_Globals::$RunPromotion[$userId] = false;
            $user = $this->getMergedData();
            $user['customFields'] = is_array($user['custom_fields']) ? $user['custom_fields'] : @unserialize($user['custom_fields']);
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