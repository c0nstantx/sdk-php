# 4. Main file structure

The entry point to your report is the main PHP class file. That file has to be an 
extension of `RAM\BaseReport` and be named exactly as the class name. 
It also has to implement the `render()` method where all the report magic will 
happen and it will return the final report. 
 
*Example:* **my_report.php**

```php
<?php

class my_report extends RAM\BaseReport
{
    public function render()
    {
        return 'Report content';
    }
}
````
This is pretty much the basic structure of a report. Below we will create our first 
report as an example. 

Previous: [Report folder structure][1]

Next: [Helper methods][2]

[Index][3]

[1]:https://github.com/rocketgraph/sdk-php/blob/master/doc/FOLDER.md
[2]:https://github.com/rocketgraph/sdk-php/blob/master/doc/METHODS.md
[3]:https://github.com/rocketgraph/sdk-php/blob/master/doc/MANUAL.md