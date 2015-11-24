Rocketgraph SDK for PHP
=======

[Rocketgraph][1] is a cloud reporting service and this is the SDK for developing and testing reports written in PHP.

[![Build Status](https://travis-ci.org/rocketgraph/sdk-php.svg?branch=master)](https://travis-ci.org/rocketgraph/sdk-php)

Prerequisites
-----------------

1. PHP >= 5.5.0
2. Composer (see [here][2] how to install composer)

Installation
------------

1. Clone the repository

    `git clone git@github.com:rocketgraph/sdk-php.git`

2. Install dependencies via composer

    `composer install`

3. SSL Certificate (**FOR WINDOWS USERS ONLY**)

  Add the following line to your php.ini file:

    `curl.cainfo="sdkpath/certs/cacert.pem"`

Update
------

1. Pull the repository

    `git pull origin master`

2. Install dependencies via composer

    `composer update`


Configuration
------------------

All configuration files are in app/config folder and are in [YAML][3] format. The configuration files that needed to be created are `connectors.yml` and `responses.yml`. Inside the folder you will find the according distribution files with the `.dist` extension. You can use them as a template.

connectors.yml
---------------
Inside `connectors.yml` file you will define API keys for the required connector(s) for your report.

1. [Oauth1][4] API connectors

 For Oauth1 connectors you have to setup `api_key`, `api_secret`, `access_token` and `access_token_secret` fields that you will acquire from your API provider.

 *`connector.yml` for [Twitter][5]*
    
        connectors:
            twitter: {api_key: 1234567890, api_secret: 1234567890, access_token: 1234567890, access_token_secret: 1234567890} 

2. [Oauth2][6] API connectors

 For Oauth2 connectors you have to setup only `access_token` field that you will acquire from your API provider.

 *`connector.yml` for [Facebook][7]*
    
        connectors:
            facebook: {access_token: 1234567890} 

3. Combined connectors

 You can use multiple connectors to your report by just define them all without any specific order

        connectors:
            twitter: {api_key: 1234567890, api_secret: 1234567890, access_token: 1234567890, access_token_secret: 1234567890} 
            facebook: {access_token: 1234567890} 

The keys you can use for your connector(s) can be found [here][8]

responses.yml
-------------------

In case you don't want or you can't create an app in an API provider, you can mock the api responses based on API provider's documentation. In order to be able to use your own responses you have to change the parameter `connection` inside the app/config/config.yml from `live` to `sandbox`.

After that you can define your own responses to any API endpoint you want by using the according connector key.

ex. **responses.yml** for *twitter/facebook*

    responses:
        facebook:
          'me/photos':
              data:
                  0:
                      created_time: 2015-03-31T12:00:43+0000
                      name: "My photo name"
                      id: 111111111
                  1:
                      created_time: 2015-01-31T12:00:43+0000
                      name: "My other photo name"
                      id: 222222222
        twitter:
            'account/verify_credentials':
                screen_name: 'MyName'
            'statuses/user_timeline?screen_name=MyName':
                tweet1:
                    retweeted_status:
                        retweet_count: 100
                    created_at: 2014-01-01 00:00:00
                    text: 'tweet tweet tweet1'
                tweet2:
                    retweeted_status:
                        retweet_count: 120
                    created_at: 2014-12-01 00:00:00
                    text: 'tweet tweet tweet2'
                tweet3:
                    retweeted_status:
                        retweet_count: 300
                    created_at: 2014-03-01 00:00:00
                    text: 'tweet tweet tweet3'
                tweet4:
                    created_at: 2014-01-01 00:00:00
                    text: 'tweet tweet tweet4'


For mocking up the open connector responses you have to use the key `open` in the responses file and the whole URL of the API as endpoint to your response.

For Example:

    reponses:
        facebook:
            ...
        twitter:
            ...
        open:
            'http://my.apiendpoint.com':
                param1: value1
                param2: value2
            'http://my.apiendpoint.com?query1=x&query2=y':
                param1: value1
                param2: value2
            

Report Development
---------------------------

Your report code lies inside the `/src` folder. There is also a report example inside `/src_demo`, while the public folder of the report render (webserver root) is the `/web` folder.

For example, if you have installed the sdk to your `localhost` root folder, you can access the rendered report in the url: `http://localhost/web`

For more details on how to create a report read the [Developer's Manual][9].

Run server
----------

You can run directly the SDK using PHP build-in web server:

    php -S localhost:80 -t web/
    

Aggregation
------------

Reports can support an aggregation function in order to store processed data in a daily basis.

In order to emulate the aggregation process you can run the following command from the root folder:

    php app/aggregate.php
    
For more information about this functionality you can read the [Developer's manual][9]

Sentiment Analysis
------------------

Sentiment analysis API cannot be implemented inside the SDK, that's why the `caclulate` method will return a random number between -1 and 1.

    $this->sentiment->calculate('Phrase to test sentiment'); // Random -1 to 1


[1]: https://rocketgraph.com
[2]: https://getcomposer.org/doc/00-intro.md
[3]: http://yaml.org/
[4]: https://tools.ietf.org/html/rfc5849
[5]: https://apps.twitter.com/
[6]: http://tools.ietf.org/html/rfc6749
[7]: https://developers.facebook.com/
[8]: CONNECTORS.md
[9]: DeveloperManual.pdf
