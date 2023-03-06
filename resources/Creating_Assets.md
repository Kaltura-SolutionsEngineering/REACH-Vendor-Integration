# REACH Vendor Asset Creation Guide
When creating assets in Kaltura (captions, transcripts, additional audio tracks, chapters, etc) to relate to a piece of media, a Vendor may take different approaches based on the assets they are creating.

Here are some examples for various asset types:
**When creating assets like transcripts or captions, you have the option to upload to Kaltura, or have Kaltura download from you (if publicly available).  If leveraging the upload option, then see the uploadToken services below.**

## Notes about REACH service requests
Depending on the REACH request job you are servicing, there would have been additional attributes in the reach_vendor_profile object to provide further directions:
- defaultOutputFormat: [default caption format](https://developer.kaltura.com/api-docs/General_Objects/Enums/KalturaVendorCatalogItemOutputFormat) the customer would like to use.
- boolean values for 'autoDisplayMachineCaptionsOnPlayer' and/or 'autoDisplayHumanCaptionsOnPlayer', which indicate how you should set the caption object (notes below in 'Creating Caption Objects')
- enableMetadataExtraction: whether or not tags/topics should be generated with the caption job.  If so, they should be appended to the entry (see step 3 in "Creating Caption Assets" below)
- obey the (where applicable and/or possible) 'enableSpeakerChangeIndication', 'enableAudioTags', and 'enableProfanityRemoval' settings to filter and/or format your output captions and transcripts
- maxCharactersPerCaptionLine: dictates how many characters should be allowed in each line of captions
- 'labelAdditionForMachineServiceType' and 'labelAdditionForHumanServiceType' dictate what you should use for the 'label' attribute on the captionAsset when you create it
- contentDeletionPolicy: [deletion policy](https://developer.kaltura.com/api-docs/General_Objects/Enums/KalturaReachProfileContentDeletionPolicy) that should be adhered to regarding customer's content that you are processing
- dictionaries: string of [dictionary data](https://developer.kaltura.com/api-docs/General_Objects/Objects/KalturaDictionary) that a customer supplied for caption processing
- flavorParamsIds: specific media flavor/rendition that the customer requires for you to ingest.  This could be a single flavorParam, or prioritized list of potential flavorParams
- vendorTaskProcessingRegion: the [geographical region](https://developer.kaltura.com/api-docs/General_Objects/Enums/KalturaVendorTaskProcessingRegion) the customer requests the job to be processed in


#### Uploading via the uploadToken method
1. Create an uploadToken with the [uploadToken.add() service](https://developer.kaltura.com/api-docs/service/uploadToken/action/add).  This will return a token id that is representative of a placeholder in storage for your asset.
2. Using the token id from step 1, call the [uploadToken.upload() service](https://developer.kaltura.com/api-docs/service/uploadToken/action/upload) to upload your asset into the storage placeholder represented by your uploadToken.  Keep track of the token id still, as it is the reference point for your uploaded asset.

# Creating Assets 

## Creating Transcript Assets
1. Create an attachmentAsset with the [attachmentAsset.add() service](https://developer.kaltura.com/api-docs/service/attachmentAsset/action/add).  You will specify the entryId that you are creating this attachment for, use the KalturaTranscriptAsset attachmentAsset object type, and populate the relevant attributes.  This is the shell data for your transcript.
2. Next, you'll attach the actual transcript file to the shell you just created.  Using the [attachmentAsset.setContent() method](https://developer.kaltura.com/api-docs/service/attachmentAsset/action/setContent), you will reference the id of the attachmentAsset you created in step 1, then set the appropiate contentResource object type.
- For uploaded assets (see "Uploading via the uploadToken method" above), you'll choose the KalturaUploadedFileTokenResource type, then reference the uploadToken for your file in the 'token' field.
- For files that are publicly available in your platform, you'd choose the KalturaUrlResource type, and supply the url to the resource (along with any needed urlHeaders).

## Creating Caption Assets
1. Create a captionAsset with the [captionAsset.add() service](https://developer.kaltura.com/api-docs/service/captionAsset/action/add).  You will specify the entryId that you are creating this caption for, and populate the relevant attributes.  This is the shell data for your caption.
- make sure to set the associatedTranscriptIds field with the id of the transcript that you created.
2. Next, you'll attach the actual caption file to the shell you just created.  Using the [captionAsset.setContent() method](https://developer.kaltura.com/api-docs/service/captionAsset/action/setContent), you will reference the id of the captionAsset you created in step 1, then set the appropiate contentResource object type.
- For uploaded assets (see "Uploading via the uploadToken method" above), you'll choose the KalturaUploadedFileTokenResource type, then reference the uploadToken for your file in the 'token' field.
- For files that are publicly available in your platform, you'd choose the KalturaUrlResource type, and supply the url to the resource (along with any needed urlHeaders).
3. Depending on the caption request job you are servicing, there would have been the following attributes in the reach_vendor_profile object to provide further directions:
- a boolean value for 'enableMetadataExtraction'.  If 'true', then you should call the [baseEntry.get() service](https://developer.kaltura.com/api-docs/service/baseEntry/action/get) with the requested media entryId to retrieve the baseEntry object, then append any generated tags to the baseEntry.tags attribute, and call the [baseEntry.update() service](https://developer.kaltura.com/api-docs/service/baseEntry/action/update).
- a boolean value for the 'autoDisplayMachineCaptionsOnPlayer' and/or 'autoDisplayHumanCaptionsOnPlayer' (depending on the service type).  If 'true', then set the displayOnPlayer attribute of the captionAsset appropriately (in step 1).

## Creating Chapters
1. To create chapters for a piece of media, use the [cuePoint.add() service](https://developer.kaltura.com/api-docs/service/cuePoint/action/add) for each chapter.  Reference the media the chapters should be associated to using the entryId field.  Use the cuePoint object type of KalturaThumbCuePoint and the subType of CHAPTER, then populate the relevant fields, such as title, description, tags, and startTime.

## Creating Additional Audio Tracks



