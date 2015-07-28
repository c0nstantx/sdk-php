<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RAM\Connectors;

/**
 * Description of FacebookPageConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class FacebookPageConnector extends FacebookConnector
{
    protected $pageId = null;

    protected $pageName = null;

    protected $pageToken = null;

    public function getPageName()
    {
        return $this->pageName;
    }

    public function getPageId()
    {
        return $this->pageId;
    }

    public function getPageToken()
    {
        return $this->pageToken;
    }

    public function needsExtraParameters()
    {
        return ['facebook_page'];
    }

    public function getExtraParameters()
    {
        $pages = $this->get('me/accounts');
        if (isset($pages->data)) {
            $pageOptions = [];
            foreach($pages->data as $page) {
                $key = [
                    $page->id,
                    $page->name,
                    $page->access_token
                ];
                $pageOptions[implode(';', $key)] = $page->name;
            }
            return [
                'select' => [
                    'name' => 'facebook_page',
                    'options' => $pageOptions,
//                'next' => [
//                    'select' => [
//
//                    ]
//                ]
                ]
            ];
        }
        return null;
    }

    public function setExtraParameters($parameters = [])
    {
        if (is_array($parameters)) {
            foreach($parameters as $parameter => $value) {
                if ($parameter == 'facebook_page') {
                    $parts = explode(';', $value);
                    $this->pageId = $parts[0];
                    $this->pageName = $parts[1];
                    $this->pageToken = $parts[2];
                    $this->token->accessToken = $this->pageToken;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        if (null !== $this->pageName) {
            return $this->pageName;
        }
        return parent::getDisplayName();
    }
}
