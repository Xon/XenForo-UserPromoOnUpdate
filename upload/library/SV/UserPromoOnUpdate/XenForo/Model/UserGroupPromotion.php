<?php

class SV_UserPromoOnUpdate_XenForo_Model_UserGroupPromotion extends XFCP_SV_UserPromoOnUpdate_XenForo_Model_UserGroupPromotion
{
    public function updatePromotionsForUser(array $user, array $promotionStates = null, array $promotions = null)
    {
        if ($promotions === null)
        {
            $promotions = $this->getPromotions(array(
                'active' => 1
            ));
        }
        if (!$promotions)
        {
            return 0;
        }

        // copied from XenForo_Helper_Criteria
        $_userFieldPrefix = '__userField_';
        $_userFieldPrefixLength = 12;
        if (!isset($user['customFields']))
        {
            $user['customFields'] = !empty($user['custom_fields']) ? XenForo_Helper_Php::safeUnserialize($user['custom_fields']) : array();
        }

        foreach($promotions as &$promotion)
        {
            $promotion['user_criteria'] = XenForo_Helper_Criteria::unserializeCriteria($promotion['user_criteria']);
            if (empty($promotion['user_criteria']))
            {
                continue;
            }
            foreach($promotion['user_criteria'] as &$criterion)
            {
                if (strpos($criterion['rule'], $_userFieldPrefix) === 0)
                {
                    $userFieldId = substr($criterion['rule'], $_userFieldPrefixLength);

                    if (!isset($user['customFields'][$userFieldId]))
                    {
                        break;
                    }

                    if (empty($criterion['data']))
                    {
                        // force to a single value so the match works as expected
                        // need to keep the rule if it exists
                        $criterion['data']['choices'] = array(1);
                        $user['customFields'][$userFieldId] = empty($user['customFields'][$userFieldId]) ? 1 : 0;
                    }
                }
            }
        }

        return parent::updatePromotionsForUser($user, $promotionStates, $promotions);
    }
}