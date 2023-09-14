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

## Creating Additional Audio Tracks (Dubbing and Audio Description services)
For Dubbing and Audio Description services, there is a need to create new added audio tracks.  To do so:
1. In the REACH job, there should have been an indication of the appropriate FlavorParamId to be used for the new audio track.  Using that, call the [flavorAsset.add() service](https://developer.kaltura.com/api-docs/service/flavorAsset/action/add) and provide the entryId from the job request, the flavorParamsId from the job request, and other relevant fields.  This will create the new flavorAsset shell.
2. Next, you'll attach the actual flavor file to the shell you just created.  Using the [flavorAsset.setContent() method](https://developer.kaltura.com/api-docs/service/flavorAsset/action/setContent), you will reference the id of the flavorAsset you created in step 1, then set the appropiate contentResource object type.
   - For uploaded assets (see "Uploading via the uploadToken method" above), you'll choose the KalturaUploadedFileTokenResource type, then reference the uploadToken for your file in the 'token' field.
   - For files that are publicly available in your platform, you'd choose the KalturaUrlResource type, and supply the url to the resource (along with any needed urlHeaders). 
3. Additionally, the REACH job should have also specified a ClearAudioFlavorParamsId.  Use this flavorParam to supply the original audio track extracted from the video.  The Kaltura player will need this original audio track, in addition to the dubbed/AD track to be able to offer the viewer the ability to swap between tracks.  For standard video, Kaltura will just play the audio track embedded in the video, but in the scenario where multiple audio tracks are provided, then each track needs to be represented as it's own flavor, including the original audio.  Repeat steps 1 & 2 using the supplied ClearAudioFlavorParamsId to add the original audio track you extracted (before performing the dubbing/AD operation to generate a new track) as a separate flavorAsset.

## Adding Advanced/Extended Audio Description files
Advanced/Extended audio description consists of a .VTT file that holds the extended audio captions.  During media playback, the Kaltura player will pause playback at the notated descriptive sections, play out the descriptive audio, then resume playback of the media.
1. Create an attachmentAsset with the [attachmentAsset.add() service](https://developer.kaltura.com/api-docs/service/attachmentAsset/action/add).  You will specify the entryId that you are creating this attachment for, use the KalturaTranscriptAsset attachmentAsset object type, and populate the relevant attributes.  This is the shell data for your transcript.  ***NOTE: be sure when adding the attachment to set the 'tags' attribute value to 'AAD'.  This will be used to notify the Kaltur player that this attachment represents the Advanced Audio Description .VTT file***
2. Next, you'll attach the actual transcript file to the shell you just created.  Using the [attachmentAsset.setContent() method](https://developer.kaltura.com/api-docs/service/attachmentAsset/action/setContent), you will reference the id of the attachmentAsset you created in step 1, then set the appropiate contentResource object type.
   - For uploaded assets (see "Uploading via the uploadToken method" above), you'll choose the KalturaUploadedFileTokenResource type, then reference the uploadToken for your file in the 'token' field.
   - For files that are publicly available in your platform, you'd choose the KalturaUrlResource type, and supply the url to the resource (along with any needed urlHeaders).

## Adding Intelligent Tagging
Different vendors could handle Intelligent Tagging in different ways, but a common use case would be to alter the tags, description, and possibly name of a media asset.  To achieve this, you would:
1. Call the [baseEntry.get() service](https://developer.kaltura.com/api-docs/service/baseEntry/action/get) with the requested media entryId to retrieve the baseEntry object.
2. Append any generated tags to the baseEntry.tags field.
3. Append/replace any generated description/summary to the baseEntry.description field. 
4. Append/replace any generated title to the baseEntry.name field.
5. Finally, call the [baseEntry.update() service](https://developer.kaltura.com/api-docs/service/baseEntry/action/update) and supply the amended fields to update the asset.

## Adding Live Captions
Live caption services are implemented as Kaltura relaying an RTMP(S) stream to the vendor, then pulling caption data back over a websocket connection.  To supply these needed parameters to Kaltura while fulfilling a live captions task, you will have received a scheduleEventId along with a startDate and endDate (epoch timestamps).  Using that data to provision live caption services on the Vendor end, then you'll:
1. Call the [scheduleEvent.updateLiveFeature()](https://developer.kaltura.com/api-docs/service/scheduleEvent/action/updateLiveFeature) to update the Kaltura event with the following fields so that we can send you the stream data, and retrieve the needed caption data:
   - scheduleEventId: this is the scheduleEventId that you should have received in the entryVendorTask request
   - featureName: for live captions, the featureName string should follow the format "LiveCaptionFeature-reach-<taskID>" where <taskID> is the id of the entryVendorTask you are fulfilling
   - liveFeature: use the KalturaLiveCaptionFeature object
   - captionToken: this should be a security token used when accessing the websocket that you will be outputting the caption data stream to
   - captionUrl: this is the url (Vendor hosted) of the websocket that you will be outputting the caption data stream to
   - mediaKey: this is the stream key/name for the RTMP(S) stream where we will send you a relay of the media stream
   - mediaUrl: this is the RTMP(S) stream url (Vendor hosted) where we will send you a relay of the media stream
2. At the time of the live session, Kaltura will relay the media stream to your provided mediaUrl/mediaKey for you to process the stream and output the caption feed back on the captionUrl/captionToken websocket that you provide.  Kaltura will handle ingesting the caption data and transforming to WebVTT format to inject into the player along with the HLS stream that viewers will consume.
   - see [Websocket data example](/resources/WebsocketCaptionDataExample.json) for an example of a JSON caption data payload from a websocket.




