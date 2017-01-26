# bpulse/bpulse-sdk-php

This is a conector between any PHP based application subscribed to BPULSE Service and the PULSES COLLECTOR REST SERVICE. This README explains how to integrate the conector with the target client application, configuration parameters and how to use it.


## REQUIREMENTS

* protocol buffers compiler library (libprotoc)

## INSTALLING

The recommended way to install this connector is through Composer.

Add to your composer.json

```sh
"require": {
	"bpulse/bpulse-php-connector": "dev-master"
}
```

run composer install

After installing, you need to require Composer's autoloader:

```sh
require 'vendor/autoload.php';
```


## STARTING YOUR APPLICATION

the first thing you should to do is generate the PHP classes you'll need to send Pulses. To do this, you need to run the following command on bpulse-php-connector folder

```sh
make build
```

The connector needs some configuration properties that indicate the client where to connect so you must provide a properties file, so to do that, append this config.json to your main folder

```sh
{
	"host": "http://[bpulse.host]/app.collector/collector/pulses:port",
	"username": "collector@bpulse.com",
	"password": "collector123"
}
```


## USAGE

The starting point are the BPulse\Entity classes and Bpulse\Rest\Connector class. Then use a combination of methods to build the pulses you want to send according to the Pulse Definition made in BPULSE, for example:

```php
//Add the namespaces

use Bpulse\Entity\Pulse;
use Bpulse\Entity\PulsesRQ;
use Bpulse\Entity\Value;
use Bpulse\Rest\Connector;


//Generate Pulses

$pulses= new PulsesRQ();
$pulses->setVersion("1.0");
$pulse= new Pulse();

$pulse->setInstanceId("1");
$pulse->setTypeId("bpulse_bpulse_processedPulses");
$milliseconds = round(microtime(true) * 1000);
$pulse->setTime($milliseconds);

$value= new Value;
$value->setName("nErrors");
$value->addValues("1");
$pulse->addValues($value);

$value= new Value;
$value->setName("nPulses");
$value->addValues("19");
$pulse->addValues($value);


$value= new Value;
$value->setName("rsInstance");
$value->addValues("Angel");
$pulse->addValues($value);

$value= new Value;
$value->setName("clientId");
$value->addValues("demo");
$pulse->addValues($value);

$value= new Value;
$value->setName("rsTime");
$value->addValues("1200");
$pulse->addValues($value);


$pulses->addPulse($pulse);                         

$connector= new Connector;


$code=$connector->send($pulses); //This return the http response status code
```
