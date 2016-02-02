# 2. Technical Principles

Rocketgraph is created in [PHP][1] and supports only that language for report creation (for  now...) 

The version used  is PHP 7, so this is the targeted version each report has to be implemented. The templating engine we use is Twig, which can be used to create your  views. This is just  to help you create your report view and doesn’t mean that this is the only way. You can use PHP files or the template engine of your preference, as long  as it is provided within the report. In short, the report has to return a valid HTML string. The tools  that  will  be used  are in developer’s discretion.

Every report must have a main PHP class file, which is the entry point to the [module][2]. That  class has to be an extension of `RAM\BaseReport` in order for the report to be properly implemented in the platform. The provider connectors that are needed for a report are declared upon report registration in Rocketgraph's
platform. In the same process, provider application's keys *(id, secret)* are also submitted.


Previous: [What is "RAM"?][1]

Next: [Report folder structure][3]

[Index][4]

[1]:http://php.net/
[2]:https://github.com/rocketgraph/sdk-php/blob/master/doc/RAM.md
[3]:https://github.com/rocketgraph/sdk-php/blob/master/doc/FOLDER.md
[4]:https://github.com/rocketgraph/sdk-php/blob/master/doc/MANUAL.md