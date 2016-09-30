Yii2 Facebook AccountKit Tools
==============================
Given app secret and client id, returns the user's accessToken and user information from Facebook's AccountKit Graph User API. The basic idea is to use the 'code' flow from your native Android or iOS app which is using a REST API from your Yii2 site.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist dhiraj/yii2-facebook-accountkit "*"
```

or add

```
"dhiraj/yii2-facebook-accountkit": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \traversient\yii\AutoloadExample::widget(); ?>```