<?php
require_once '../vendor/autoload.php';
require_once '../config.php';

use Ak86\SlashCommandRequestValidator;
use Ak86\CF\ApiClient as CloudflareApiClient;

try {

	// validate incoming request
	SlashCommandRequestValidator::validate();

	// get the requested action
	$action = filter_var(trim($_GET['a']), FILTER_SANITIZE_STRING);

	// exit if the action is not found or action is not valid
	if(!$action || !in_array($action, ['clear-cache','dev-mode']))
	{
		throw new \Exception('invalid action');
	}

	// get the domain name
	$domain = isset($_POST['text']) ? filter_var($_POST['text'], FILTER_VALIDATE_DOMAIN) : '';


	// validate the domain and throw exceptions if the validation criterias haven't been met
	if(!$domain)
	{
		throw new \Exception('Oops! please give me a domain name to proceed with your request.');
	}
	else if(!preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/', $domain))
	{
		throw new \Exception('Invalid Domain Name Syntax. Please check the rules and try again!');
	}
	else if(!in_array($domain, $config['domains']))
	{
		throw new \Exception($domain.' is not present in our cloudflare account! If you are sure this is one of our domains, please contact Administrator.');
	}
	else
	{
		// The domain is good. We can initialize CloudflareApiClient
		$cfapi = new CloudflareApiClient($config['api_token']);

		// initialize a var to hold the response with a default message
		$msg = 'Something went wrong!';

		// perform respective actions
		switch($action)
		{
			case 'clear-cache':

				$res = $cfapi->clearCache($domain);

				if($res)
				{
					// success
					$msg = $domain.' Cache is Cleared!';
				}

			break;

			case 'dev-mode':

				$res = $cfapi->enableDevMode($domain);

				if($res)
				{
					switch($res['set'])
					{
						case 'now':
							$msg = 'Successfully switched '.$domain.' to the dev. mode. It will expire in another '. ($res['time_remaining'] / 60) .' minutes.';
						break;

						case 'before':
							$msg = $domain.' is already switched to the development mode. It will expire in another '. ($res['time_remaining'] / 60) .' minutes.';
						break;
					}
				}

			break;

			default:

				$msg = 'Oops! You didn\'t tell me what to do?';
			
			break;
		}

		echo $msg;

	}

} catch (Exception $e) {
	echo $e->getMessage();
}

exit;
