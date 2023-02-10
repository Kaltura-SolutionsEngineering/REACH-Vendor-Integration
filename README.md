# REACH-Vendor-Integration
Guidance and examples on how REACH vendor partners can integrate with Kaltura.

# Audience
This guide is a reference for external Vendors who integrate with the Kaltura REACH framework.

# Summary
Kaltura REACH framework allows vendors to process Kaltura customer media and return enrichment assets, such as captions, transcripts, audio description, etc. Vendor get access to special APIs which allow them to periodically pull new REACH task requests (Each Task will contain metadata required for the vendor to process the request), process them, and update the customer's media with the resulting assets and/or metadata.

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



