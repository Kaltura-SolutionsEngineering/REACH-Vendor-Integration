# REACH-Vendor-Integration
Guidance and examples on how REACH vendor partners can integrate with Kaltura.

# Audience
This guide is a reference for external Vendors who integrate with the Kaltura REACH framework.

# Summary
Kaltura REACH framework allows vendors to process Kaltura customer media and return enrichment assets, such as captions, transcripts, audio description, etc. Vendors get access to special APIs which allow them to periodically pull new REACH task requests (Each Task will contain metadata required for the vendor to process the request), process them, and update the customer's media with the resulting assets and/or metadata.

# Prerequisites
* A Vendor partner account.
  * This account must be configured by Kaltura.  Once configured, and the relevant REACH services are added to it, then you will be able to use the Kaltura API to retrieve tasks that have been submitted for the service(s) offered by you as a Vendor.
  * A list of supported service(s).  These are referred to as catalog items, and typically consist of 4 main parts (see https://developer.kaltura.com/api-docs/service/vendorCatalogItem for more details):
    * serviceFeature - ex: CAPTIONS
    * serviceType - ex: MACHINE
    * sourceLanguage - ex: EN
    * turnAroundTime - ex: THIRTY_MINUTES [1800]
  
* A Customer Persona partner account.
  * This is a standard Kaltura account, which will be enabled with your service(s).  You may use this account to simulate requests for your service(s).  Upon submission, those task service requests will be able to be retrieved and serviced by your Vendor partner account. 
  
# Getting started with the Kaltura API
see https://developer.kaltura.com/api-docs/VPaaS-API-Getting-Started/Getting-Started-VPaaS-API.html for guides and general reference on using the Kaltura API, including access to [Client Libraries in a number of languages](https://developer.kaltura.com/api-docs/Client_Libraries).

Kaltura strongly encourages the [use of appTokens for authenticating to the API.](https://developer.kaltura.com/api-docs/VPaaS-API-Getting-Started/application-tokens.html)

# Basic Workflow
![Vendor REACH Flow](https://user-images.githubusercontent.com/17254753/219496027-82a11fd9-6f27-418c-a2f0-ee31df28dc63.png)

![Sequence diagram outlining the Vendor to Kaltura API interaction flow](/Vendor REACH Flow.png)

# Workflow Details
The general flow implemented by a vendor would follow this outline:
1. Connect to Kaltura API and establish a valid session
2. Call [entryVendorTask.getJobs()](https://developer.kaltura.com/api-docs/service/entryVendorTask/action/getJobs) to get a list of jobs that have been submitted by any customer users for your services
3. Loop through the job objects in the response and begin to process accordingly based on the requested service
4. Retrieve the asset related to the job request by using the partnerId and accessKey provided in the job object
5. Update the job status to PROCESSING using [entryVendorTask.updateJob()](https://developer.kaltura.com/api-docs/service/entryVendorTask/action/updateJob)
6. Process the requested job in the vendor backend/app
7. Upon job completion on the vendor side, use the partnerId and accessKey (from the job request object) to add the generated assets (ex: captions, transcript, chapters, etc) to the requested media in the customer account
8. Update the job status to READY using [entryVendorTask.updateJob()](https://developer.kaltura.com/api-docs/service/entryVendorTask/action/updateJob)

## Notes for flow steps
1. Connect to the Kaltura API using your Vendor account.  The Kaltura Partners team can provide you with this account and the relevant details.  We strongly suggest provisioning an appToken for this account and using that to spawn your API sessions. See [Getting started with application tokens](https://developer.kaltura.com/api-docs/VPaaS-API-Getting-Started/application-tokens.html) for more information on appToken sessions.  Also, you can find [pre-compiled Kaltura API client libraries in a number of languages](https://developer.kaltura.com/api-docs/Client_Libraries) to help you get started.
   - The client lib exposes the ability to define a Client Tag for each API request. This property is used later by Kaltura to track which application issued which call. With REACH we make another use of this field. To ensure Kaltura has a way to determine the Task processing E2E, the vendor should follow the following standards:
     - For non task-specific API calls, the client tag should be set to be '<default clientTag>_vendorName-vendorPartnerId”. (default clientTag consists of the client library programming language and the library build date) . Example: 'php5:18-11-11_vendorName_12345'
     - For task-specific API calls, the Task ID should also be added to the clientTag: Example, for PHP5 client library, Task ID (9292) and vendor account id (12345), the resulting client tag should be "php5:18-11-11_vendorName-12345-9292"
   - The appToken should be set with 'disableentitlement' privilege.  The default expiry is 24 hours, but can be set as desired.
2. Once you have a valid client session established, make a call to [entryVendorTask.getJobs()](https://developer.kaltura.com/api-docs/service/entryVendorTask/action/getJobs).  This will return a list of requested jobs for your Vendor account.  Make sure to use the following parameters in your request:
   - entryVendorTaskFilter->vendorPartnerIdEqual - set this parameter to be the vendor partner ID (should be the same value for all tasks)
   - entryVendorTaskFilter->statusEqual = PENDING - this will only return a list of tasks that are PENDING processing by the vendor.
   - depending on the number of tasks, you may need to use the pager object to paginate results
   


