<?php

class SV_UserPromoOnUpdate_XenForo_Model_UserUpgrade extends XFCP_SV_UserPromoOnUpdate_XenForo_Model_UserUpgrade
{
    public function downgradeUserUpgrades(array $upgrades, $sendAlert = true)
    {
        parent::downgradeUserUpgrades($upgrades, $sendAlert);

        $userIds = XenForo_Application::arrayColumn($upgrades, 'user_id');
        $userIds = array_filter(array_unique($userIds));
        if ($userIds)
        {
            /** @var XenForo_Model_User $userModel */
            $userModel = $this->getModelFromCache('XenForo_Model_User');
            /** @var XenForo_Model_UserGroupPromotion $promotionModel */
            $promotionModel = $this->getModelFromCache('XenForo_Model_UserGroupPromotion');

            $users = $userModel->getUsersByIds($userIds, ['join' => XenForo_Model_User::FETCH_USER_FULL]);
            if ($users)
            {
                foreach ($users as $userId => $user)
                {
                    if (!isset(SV_UserPromoOnUpdate_Globals::$RunPromotion[$userId]) || SV_UserPromoOnUpdate_Globals::$RunPromotion[$userId])
                    {
                        // ensure we don't attempt to run the promotion twice in the same request
                        SV_UserPromoOnUpdate_Globals::$RunPromotion[$userId] = false;
                        $promotionModel->updatePromotionsForUser($user);
                    }
                }
            }
        }
    }

    public function upgradeUser($userId, array $upgrade, $allowInsertUnpurchasable = false, $endDate = null)
    {
        $upgradeRecordId = parent::upgradeUser($userId, $upgrade, $allowInsertUnpurchasable, $endDate);
        if ($upgradeRecordId)
        {
            if (!isset(SV_UserPromoOnUpdate_Globals::$RunPromotion[$userId]) || SV_UserPromoOnUpdate_Globals::$RunPromotion[$userId])
            {
                // ensure we don't attempt to run the promotion twice in the same request
                SV_UserPromoOnUpdate_Globals::$RunPromotion[$userId] = false;
                /** @var XenForo_Model_User $userModel */
                $userModel = $this->getModelFromCache('XenForo_Model_User');
                $user = $userModel->getFullUserById($userId);
                /** @var XenForo_Model_UserGroupPromotion $promotionModel */
                $promotionModel = $this->getModelFromCache('XenForo_Model_UserGroupPromotion');
                if ($user)
                {
                    $promotionModel->updatePromotionsForUser($user);
                }
            }
        }

        return $upgradeRecordId;
    }
}
