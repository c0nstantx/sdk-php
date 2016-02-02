# 5. Helper methods

Inside your	report class you can find some methods that will help you utilize Rocketgraph’s	infrastructure and provider connection. There are also many handy tools that can boost a report, like generic storage, API response proxy, aggregation process, public API calls, sentiment analysis etc.


## 5.1 Connectors

You can access any connector that is defined in report's creation process *([Seller dashboard][1])* inside your report and easily make calls to its API. There are some methods that will help you interact with provider's API.

### 5.1.1 Get connector

```php 
$this->getConnector(string $provider)
```

With this helper method you can have access to the desired provider connector, which 
will be properly installed and ready to use. It takes as an argument connector provider's name. 

In case a non existing connector is asked, a `RAM\Exception\ConnectorNotFoundException` is thrown. So, a good practice is that you wrap that call in a `try/catch` block 
 
*Example:*

```php
try {
    $connector = $this->getConnector('twitter');
} catch (\RAM\Exceptions\ConnectorNotFoundException $ex) {
    return 'ERROR';
}
```

### 5.1.2 API call using path

```php
$connector->get(string $path, array $params = [], array $headers = [], $array = false, 
                $useProxy = true, $permanent = false, $force = false)
```

When you retrieve a connector, via the `getConnector()` method, you can make calls to the according API, using the `get()` method, providing only the API relative path.

The method takes the following arguments:
* `$path`: The API path/route
* `$params`: Extra parameters for the specifed route (They are appended as query string)
* `$headers`: Custom request headers
* `$array`: If `TRUE` returns the result as an associative array
* `$useProxy`: If `TRUE` the response is saved to the proxy storage for 1 day max
* `$permantent`: If `TRUE` the response is saved permanently to the proxy storage and is never refreshed (needs `$useProxy = TRUE`)
* `$force`: If `TRUE` forces record update in proxy storage even if it's permanent or not expired (needs `$useProxy = TRUE`)

*Example:*

```php
$response = $connector->get('users/show', [
    'screen_name' => $handle, 
    'include_entities' => 'true'
]);
```

### 5.1.3 API call using absolute URL

```php
$connector->getAbsolute(string $url, array $params = [], array $headers = [], 
                        $array = false, $useProxy = true, $permanent = false, 
                        $force = false)
```

You can make calls to an API by using it’s absolute url instead of just the path. This is very usefull for some APIs that have pagination or references inside their responses and return absolute URLs. This method works exatly as `$connector->get()` ([5.1.2][1]), except that instead of API path you provide the absolute API URL.
 
*Example:*

```php
$response = $connector->get("https://api.twitter.com/1.1/users/show.json", [
    'screen_name'=>$handle, 
    'include_entities' => 'true'
]);
```

### 5.1.4 Get response headers

```php
$connector->getLastHeaders()
```

You can have access to the last response headers by calling connector's method `getLastHeaders()`. The response is an array of connector's response headers transformed to lowercase.

*Example:*

```php
$headers = $connector->getLastHeaders();
```

### 5.1.5 Call public APIs

```php
$this->openConnector->get(string $url, array $params = [], $array = false, 
                          $useProxy = true, $permanent = false, 
                          $force = false)
```

In case you want to use any API that doesn't need any connection to user, you can use the embedded open connector that makes `GET` calls to any endpoint you define. This is very usefull for generic APIs, like maps, weather etc. Open connector's parameters are the same as making an API call using absolute URL ([5.1.3][2])

*Example:*

```php
$response1 = $this->openConnector->get('http://my.apiendpoint.com');
$response2 = $this->openConnector->get('http://my.apiendpointwithparams.com', [
    'access_token' => 'my_access_token'
]);
```

### 5.1.6 Sentiment analysis

```php
$this->sentiment->calculate(string $phrase)
```

*"Sentiment"*, is our implementation of a sentiment analysis tool. This is very handy and gives you the opportunity to make reports even more interesting. The sentiment analysis API parses a given text and returns a float from -1 to 1 where the -1 is the most negative sentiment and 1 the most positive, with absolute neutral to be 0.

*Example:*

```php
$sent = $this->sentiment->calculate('This is neither good or bad video'); //Returns 0.0
```

## 5.2 Render engine, scripts and styles

You can use our own rendering engine for HTML generation and script/style injection in the report headers. These are the helper methods you can use inside your report class.


### 5.2.1 Add styles

```php
$this->addStyle(string $style)
```

This helper method will register your stylesheets for your report. The file path has to be relative to the public folder ([Folder Structure][3]). The styles are inserted with the same order they are defined.

*Example:*

```php
$this->addStyle('css/style.css');
```

### 5.2.2 Add scripts (JavaScript)

```php
$this->addScript(string $script)
```

This helper method will register your scripts for your report. The file path has to be relative to the public folder ([Folder Structure][3]). The scripts are inserted with the same order they are defined.

*Example:*

```php
$this->addScript('scripts/script.js');
```

### 5.2.3 Render report

```php
$this->renderEngine->render(string $view, array $data)
```

The `renderEngine` parameter is the [Twig][4] engine that passes data in a view, renders its content and returns the generated HTML.

*Example:*

```php
$content = $this->renderEngine->render('views/report.html', ['data' => $data]);
```

### 5.2.4 View helpers

Rocketgraph's rendering engine is based on [Twig][4] and therefor it uses its syntax. Inside your view files, you can use some helpers like `path` to generate relative URLs for images and/or other files.

*Example: Inside a view file* 

```twig
<img src="{{ path('images/logo.png') }}" />
```

## 5.3 Custom rendering engine

In case you don't want to use our embedded rendering engine, the only rule you have to follow is the path of your assets. The path has to be relative to your public folder ([Folder Structure][3]).

*Example:*

```html
<img src="images/top_header.jpg" />
```

## 5.4 Report parameters

Reports can have request parameters for any data needed by the end user in order for the report to be properly rendered.

These parameters can be a date, a date range, a number or anything else that can be passed through a form (**except files**). All parameters are passed as a `GET` query parameter.

For example, if you want to pass `handle` and `limit`, the url will be appended like this:

```
?handle=myTwitterHandle&limit=10
```

All the necessary parameters are declared in the report submit form inside the **Input** box. If the report needs date range, then you check the *"Show date range"* checkbox in the report form. The according parameters that are generated by Rocketgraph are `start_date` and `end_date` and are passed to the report in the form `YYYY-MM-DD`.

### 5.4.1 Get all parameters

```php
$this->getParams()
```

That method returns an array with all the available parameters that are filled by user.

*Example:*

```php
$parameters = $this->getParams();
```

`getParam()` returns the value of a parameter or `NULL` if the parameter does not exist.

*Example:*

```php
$dateFrom = $this->getParam('start_date');
```

### 5.4.2 Get a single parameter

```php
$this->getParam(string $parameter)
```

## 5.5 Storage


Reports can use our storage system in order to save processed data and retrieve them when necessary. With that feature you can create better and more interesting report analytics. 

The service is a key/value storage engine and is accessible within the report inside the `storage` parameter.

### 5.5.1 Save to storage

```php
$this->storage->save($key, $value)
```
You can save data using storage's `save()` method. You can use as key and/or value any type of number, string or array. All the data are stored separately for each subscription of your report and are totally isolated.

*Example:*

```php
$this->storage->save([
    'total_views',
    '2016-01-01'
], 154);

$this->storage->save('2016-01-01', [
    'total_views' => 50,
    'total_shares' => 32
]);
```

### 5.5.2 Retrieve from storage

```php
$this->storage->find($key)
```

You can retrieve any saved value by searching its key. If the record does not exist, a `NULL` value will be returned.

*Example:*

```php
$this->storage->find(['total_views', '2016-01-01']); // Returns: 154

$this->storage->find('2016-01-01'); // Returns: ['total_views' => 50, 'total_shares' => 32]

$this->storage->find('2016-01-02'); // Returns: NULL
```

## 5.6 Aggregations

For some reports, there is the need of repeating calculating tasks. These tasks can be daily/hourly stats, comparisons with past values, difficult calculations that take too much time to run on the fly etc. This feature is best when used along with the storage service ([5.5][5])

For the moment, these aggregation tasks run every hour and use one single point of entry (`aggregate` method), but it will become more customizable in the future.
In order to run an aggregation you have to implement the `aggregate` method inside your report (**MUST BE public**). Whatever action is implemented in that method will be run every hour and with the use of storage, you will be able to aggregate metrics, that will be easy to use in the report render.

*Example:*

```php
public function aggregate()
{
    $today = new \DateTime();
    $totalFollowers = $twitterParser->getCurrentFollowers();
    $this->storage->save([
        'total_followers',
        $today->format('Y-m-d') //2016-01-01
    ], $totalFollowers);
}
```

In this example we implemented a method that brings user's current followers. We take as granted that we already have a parser that parses user's profile and fetches the number of its followers.

We can, now, retrieve that data in the render process by fetching them from storage, using the same key.

*Example:*

```php
$followers = $this->storage->find(['total_followers', '2016-01-0-1']);
```

Previous: [Main file structure][6]

[Index][7]

[1]:https://github.com/rocketgraph/sdk-php/blob/master/doc/METHODS.md#512-api-call-using-path
[2]:https://github.com/rocketgraph/sdk-php/blob/master/doc/METHODS.md#513-api-call-using-absolute-url
[3]:https://github.com/rocketgraph/sdk-php/blob/master/doc/FOLDER.md
[4]:http://twig.sensiolabs.org/
[5]:https://github.com/rocketgraph/sdk-php/blob/master/doc/METHODS.md#55-storage
[6]:https://github.com/rocketgraph/sdk-php/blob/master/doc/FILES.md
[7]:https://github.com/rocketgraph/sdk-php/blob/master/doc/MANUAL.md