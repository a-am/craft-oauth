<?php

namespace Craft;

require(CRAFT_PLUGINS_PATH.'oauth/vendor/autoload.php');

class Oauth_ConnectController extends BaseController
{
    protected $allowAnonymous = true;

    // --------------------------------------------------------------------

    public function init()
    {
        // request params

        $providerHandle = craft()->request->getParam('provider');
        $namespace     = craft()->request->getParam('namespace');
        $scope         = unserialize(base64_decode(craft()->request->getParam('scope')));


        // userMode

        $userMode = false;

        if(!$namespace) {
            $userMode = true;
        }


        // clean session vars

        if(!craft()->httpSession->get('oauth.social')) {
            craft()->oauth->sessionClean();
        }


        // set session vars

        craft()->oauth->sessionAdd('oauth.providerClass', $providerHandle);
        craft()->oauth->sessionAdd('oauth.userMode', $userMode);
        craft()->oauth->sessionAdd('oauth.referer', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null));
        craft()->oauth->sessionAdd('oauth.scope', $scope);


        // redirect

        $url = UrlHelper::getActionUrl('oauth/public/connect/', array(
                'provider' => $providerHandle,
                'namespace' => $namespace
            ));

        $this->redirect($url);
    }

    // --------------------------------------------------------------------
}