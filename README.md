# Publisher 

Craft Publisher enables you to publish saved Drafts on a future date without 
the need to handle the cache expiration logic. 
The cronjob handles the publication and the cache invalidation.

It also handles entries which are to expire or published in the 
future correctly and will invalidate the cache.

![Screenshot](resources/img/example1.png)

## Installation

Install using `composer require goldinteractive/craft-publisher` and install in Craft.

## Setup

Setup a Cron Job which runs *every minute*.

Call it via CLI or web:

Web:
```shell
* * * * * /usr/bin/curl --silent --compressed {siteUrl}/actions/publisher/api/publish
```

CLI:
```shell
* * * * * [PATH_TO_CRAFT_INSTALLATION]/craft publisher/publish
```
