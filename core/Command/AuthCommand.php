<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\Command;
use RG\ConsoleCommand;
use RG\Interfaces\ConnectorInterface;
use RG\Oauth1Connector;
use RG\Oauth2Connector;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of AuthCommand
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class AuthCommand extends ConsoleCommand
{
    protected $connectors;

    public function execute()
    {
        $this->displayAvailableConnectors();
        $this->getConnector();
        $connector = $this->buildConnector();

        $this->authConnector($connector);
    }

    protected function displayAvailableConnectors()
    {
        $this->output('Available Connectors');
        $this->output('--------------------');

        $connectorSerivce = $this->container->get('connector_service');
        $this->connectors = $connectorSerivce->getAvailableConnectors();

        foreach ($this->connectors as $key => $name) {
            $this->output("$key ($name)");
        }
        $this->output();
    }

    protected function getConnector()
    {
        do {
            $connectorName = $this->readline("Select connector: ");
        } while (!isset($this->connectors[$connectorName]));

        $this->setInput(['connectorName' => $connectorName]);
    }

    protected function buildConnector()
    {
        $connectorSerivce = $this->container->get('connector_service');
        $connector = $connectorSerivce->buildPrimitiveConnector($this->input['connectorName']);

        return $connector;
    }

    protected function authConnector($connector)
    {
        $clientId = $this->readline("Enter Client ID: ");
        $clientSecret = $this->readline("Enter Client Secret: ");
        $callbackUrl = $this->readline("Enter callback URL: ");

        if ($connector instanceof Oauth2Connector) {
            $scopes = $this->readline("Enter scopes (optional): ");
            if ($scopes !== '') {
                $scopes = explode(',', str_replace(' ', '', $scopes));
            }
        } else {
            $scopes = [];
        }

        $connector->key = $clientId;
        $connector->secret = $clientSecret;
        $connector->callbackUrl = $callbackUrl;
        $connector->scopes = $scopes;
        $connector->setupProvider();

        try {
            if ($connector instanceof Oauth1Connector) {
                $temporaryCredentials = $connector->getTemporaryCredentials();
                $redirectUrl = $connector->getAuthorizationUrl($temporaryCredentials);
                $request = $this->getTokenRequest($connector, $redirectUrl);

                $credentials = $connector->getTokenCredentials($temporaryCredentials,
                    $request->get('oauth_token'), $request->get('oauth_verifier'));

                $this->output("Your access token is: {$credentials->getIdentifier()}");
                $this->output("Your access token secret is : {$credentials->getSecret()}");
            } else {
                $redirectUrl = $connector->getAuthorizationUrl([]);
                $request = $this->getTokenRequest($connector, $redirectUrl);

                $tokenParams = $connector->getDefaultTokenParameters();
                $tokenParams = array_merge($tokenParams, ['code' => $request->get('code')]);
                $token = $connector->getAccessToken('authorization_code', $tokenParams);

                $this->output("Your access token is: {$token->getToken()}");
            }
        } catch (\Exception $ex) {
            $this->output("ERROR: {$ex->getMessage()}");
        }
    }

    /**
     * @param string $callbackUrl
     *
     * @return Request
     */
    protected function buildRequestFromCallback($callbackUrl)
    {
        return Request::create($callbackUrl);
    }

    /**
     * @param ConnectorInterface $connector
     * @param string $redirectUrl
     *
     * @return Request
     */
    protected function getTokenRequest(ConnectorInterface $connector, $redirectUrl)
    {
        $this->output('', 2);
        $this->output("Paste the following URL to your browser.");
        $this->output('', 2);
        $this->output($redirectUrl);
        $this->output('', 2);
        $this->output("Paste the URL that API redirected you below");
        $this->output();

        do {
            $callbackUrl = $this->readline('Paste URL: ');
            $request = $this->buildRequestFromCallback($callbackUrl);
            if (!$connector->isResponse($request)) {
                $this->output("This is not a valid callback URL");
            }
        } while (!$connector->isResponse($request));

        return $request;
    }
}