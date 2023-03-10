<?php

// This is an example app that would connect to Kaltura under your Vendor account and get a list of pending jobs.  It is meant only as a reference point for the workflow.


// Grab the Kaltura client library SDK
require_once('kaltura-client/KalturaClient.php'); //hardcoded path, but you can use relative paths, or use something like composer to import the lib

// using constants here, but ideally you'd store these in a config file or ENV variable
const VENDOR_PARTNER_ID = ; //your Vendor account partnerId
const APPTOKEN_ID = ''; //your Vendor appTokenId
const APPTOKEN = ''; //your Vendor appToken string
const TIMEZONE = 'America/New_York'; // or whatever timezone you want to log in, probably just GMT
const REACH_VENDOR_NAME = ''; // your vendor name

date_default_timezone_set(TIMEZONE); //set the expected timezone

//Configure Kaltura client
$config = new KalturaConfiguration(VENDOR_PARTNER_ID);
$config->serviceUrl = 'https://www.kaltura.com'; //change this url to the appropriate environment for regional clouds, or on-prem environments
$client = new KalturaClient($config);

// set the appropriate client tag
$clientTag = $client->getClientTag() . '_' . REACH_VENDOR_NAME . '_' . VENDOR_PARTNER_ID;
$client->setClientTag($clientTag);


// using echo statements for readable output, but ideally you'd just have adequate logging to capture needed details

echo date('r') . ": Creating widget session\n";

//Start widget session
$widgetKs = startWidgetSession(VENDOR_PARTNER_ID,$client);

// Set the returned unprivileged KS onto the Client object
$client->setKs($widgetKs);

echo date('r') . ": Received ks: " . $widgetKs . "\n";

echo date('r') . ": Hashing widget session ks with appToken\n";
//Compute hash
$hash = getHash($widgetKs, APPTOKEN); //using SHA256 hash in this example, but the hash should match the hash settings from your appToken

echo date('r') . ": Generated hash: " . $hash . "\n";


echo date('r') . ": Creating app token session\n";

//Start App Token session 
$ks = startTokenSession(APPTOKEN_ID,$hash,$client);
// Update the Client object with the returned appToken KS
$client->setKs($ks);

echo date('r') . ": AppToken session ks: " . $ks . "\n";


echo date('r') . ": Getting list of REACH jobs\n";
$reachPlugin = KalturaReachClientPlugin::get($client);
$jobsFilter = new KalturaEntryVendorTaskFilter();
$jobsFilter->statusEqual = KalturaEntryVendorTaskStatus::PENDING;
$jobsFilter->vendorPartnerIdEqual = VENDOR_PARTNER_ID;
$jobsPager = new KalturaFilterPager();
$jobsPager->pageSize = 500;

try {
	// Get a list of all the PENDING jobs for your Vendor account
    $jobsListResult = $reachPlugin->entryVendorTask->getJobs($jobsFilter, $jobsPager);

    //@TODO: add logic here to handle paging for result sets past 500

    echo date('r') . ": Found " . $jobsListResult->totalCount . " REACH jobs in PENDING status\n";
    //var_dump($jobsListResult);
} catch (Exception $e) {
	echo date('r') . ": ERROR:\n";
    echo $e->getMessage();
}

echo date('r') . ": Getting specific details for each job\n";
if ($jobsListResult->totalCount > 0) {
	foreach ($jobsListResult->objects as $reachJob) {
		try {
			// update the clientTag for tracking
			$client->setClientTag($clientTag . '_' . $reachJob->id);
			// Reset the KS to the accessKey so we swap to the customer account
			$client->setKs($reachJob->accessKey);
			// Set the responseProfile to hold additional details for the job request
			$responseProfile = new KalturaResponseProfileHolder();
			$responseProfile->systemName = 'reach_vendor';
			$client->setResponseProfile($responseProfile);
			// Make the get request
		    $jobDetails = $reachPlugin->entryVendorTask->get($reachJob->id);
		    echo date('r') . ": Details for job " . $reachJob->id . ":\n";
		    var_dump($jobDetails);
		    echo date('r') . ": Getting url for media for job " . $reachJob->id . ":\n";
		    $contextDataParams = new KalturaPlaybackContextOptions();
		    $contextDataParams->ks = $reachJob->accessKey;
		    // Streamer type will vary depending on if you want the direct download, or an HLS link
		    $contextDataParams->streamerType = "http"; // for direct download
		    //$contextDataParams->streamerType = "applehttp"; // for HLS
		    $mediaContextResult = $client->baseEntry->getPlaybackContext($jobDetails->entryId, $contextDataParams);
    		var_dump($mediaContextResult);

    		//@TODO: add parsing logic for mutiple flavor assets returned as url's in the getPlaybackContext result.  Also, note that you'd need to append the ks to the url as a param, so  {URL}?ks=$accessKey

		    // At this point, you'd store the job details in your own system for reference
		    // particularly, you'll need the accessKey and the entryId to be able to connect back to the customer account to update the asset once your processing is complete, probably triggered as some kind of callback

		    //@TODO: update the job status to 'PROCESSING', noting that you have the information needed to process the request and will begin when resources are available
		    

		} catch (Exception $e) {
			echo date('r') . ": ERROR:\n";
		    echo $e->getMessage();
		}
		//reset the clientTag
		$client->setClientTag($clientTag);
	}
}




function startWidgetSession($partnerId,$client){
	$widgetId = '_' . $partnerId;
	$session = '';

	try {
		$result = $client->session->startWidgetSession($widgetId, 86400);
		$session = $result->ks;
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	return $session;

}

function getHash($wKs,$token){
	$theHash = hash('SHA256', $wKs . $token);
	return $theHash;
}


function startTokenSession($id,$hash,$client){
	$id = $id;
	$tokenHash = $hash;
	$userId = "REACH_JOB_PROCESSOR"; //use any generic userId you want the sessions to be associated with.  this is primarily useful for debugging later
	$type = KalturaSessionType::ADMIN;
	$expiry = 86400; // set whatever desired session expiry timer you wish
	$sessionPrivileges = "disableentitlement";
	$session = '';

	try {
		$result = $client->appToken->startSession($id, $tokenHash, $userId, $type, $expiry, $sessionPrivileges);
		$session = $result->ks;
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	return $session;

}

?>
