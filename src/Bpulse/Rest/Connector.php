<?php

namespace Bpulse\Rest;

use GuzzleHttp\Client;

/**
 * Rest Connector: Entity Connector
 */

class Connector
{
	public function send($pulses)
	{

		$config = json_decode(file_get_contents('config.json'), true);

		$basicAuth=base64_encode ( $config['username'].":".$config['password'] );

		$client= new Client();


		$json['version']=$pulses->getVersion();
		$pulseList=$pulses->getPulseList();
		$pulse_array="";
		$pulse_array=[];
		$json['pulse']=[];
		foreach ($pulseList as $pulse) {
			$pulse_array['typeId']=$pulse->getTypeId();
			$pulse_array['instanceId']=$pulse->getInstanceId();
			$pulse_array['time']=$pulse->getTime();
			$valueList=$pulse->getValuesList();
			$value_array="";
			$value_array=[];
			foreach($valueList as $value)
			{
				
				$val['name']=$value->getName();
				$aux="";
				$aux=[];
				foreach($value->getValuesList() as $v)
				{
					if($val['name']=='longDataAttr')
					{
						$v=base64_encode($v);
					}
					array_push($aux,$v);
				}
				$val['values']=$aux;
				array_push($value_array,$val);
			}
			$pulse_array['values']=$value_array;
			
		}

		array_push($json['pulse'],$pulse_array);
		$new_pulses= (json_encode($json));
		

		$r = $client->request('POST', $config['host'], [
	    'headers' => [
	    	'Content-Type'=>'application/json',
	        'Accept'     => 'application/json',
	        'Authorization'      => 'Basic '.$basicAuth,
	       ],
	     'body' => $new_pulses
	    ]);

	    return $r->getStatusCode();



	}
}
