## Definitions

**REACH**

Kaltura REACH is the Kaltura service for providing captions and enrichment services. Kaltura REACH supports captioning, transcription, translation, dubbing, audio description (standard and extended), chaptering, in-video, and cross-library search and discovery, deep-linking capabilities, and metadata and keyword extraction.

**Kaltura Session**

Every call (request) made to the Kaltura API requires an authentication key, the Kaltura Session (aka KS), identifying the account on which the action to be carried, the authenticated user and its role.  

**appToken**

An Application Token is useful in cases where different applications with varying permissions need access to your Kaltura account, without using your Admin Secret. It enables clients to provide their development partners or internal technical teams with restricted access to the Kaltura API.

**cURL**

cURL is a computer software project providing a library (libcurl) and command-line tool (curl) for transferring data using various network protocols.

**CSV**

A comma-separated values file is a text file that has a specific format which allows data to be saved in a table structured format.

**Dictionary**

A dictionary is a list of custom words or phrases to be added for each caption or transcription job. This helps when a specific word is not recognised during captioning and transcription. It could be that it's not in the vocabulary for that language, for example a company or person's name. Adding custom words or phrases can improve the likelihood they will be output.

**Glossary**

A translation glossary consists of one or more entries with a term in the source language (source text) and its corresponding translation (target text). A translation glossary helps you increase the accuracy of machine translation outputs by allowing users to specify how a specific term or phrase should be translated.

**m3u8 manifest**

In HLS world, everything begins with a manifest file :.m3u8 Manifest files reference video files (split in small chunks) and other assets like subtitles, audio, etc.

You generally read a master manifest which references different sub-manifests - one per video encoding quality - containing the video files (.ts files)

**mp4**

A file with the MP4 file extension is an abbreviation for an MPEG-4 Videofile, which is a compressed file format that can contain not only video and still images but also audio and subtitles.

**FFmpeg**

FFmpeg is a collection of libraries and tools to process multimedia content such as audio, video, subtitles and related metadata.

**Metadata extraction**

After generating captions, the next step is to extract relevant metadata from the textual content of these captions. Metadata refers to information that describes the video's content, context, and characteristics. This metadata can include details such as the video's topic, key themes, names of individuals or locations mentioned, and other relevant information.

With the extracted metadata, tags and/or description are created to identify and describe the video.

- Tags: Tags are essentially keywords or labels that provide a quick and efficient way to categorize and search for the video within a media library or database. These tags help users and content creators organize, search for, and discover videos more effectively.

- Description: The description is a text-based summary or explanation of the video content. During metadata extraction, information from the captions can be used to create a concise and informative description. This description provides context and additional details about the video, making it more accessible and understandable to viewers.

In summary, metadata extraction involves converting spoken content in a video into textual data, using that data to generate tags and descriptions.

**Dubbing**

Dubbing (aka. Voice-over) is the process of adding new dialogue or other sounds to the sound track of a video.

**Alignment**

Alignment services convert a text file (word-for-word transcript) to captions.

**Flavors and assets**

Kaltura flavors represent the required renditions of the source file with distinct codecs, frame sizes and bitrates. Kaltura customers can select to generate or add multiple flavors to an entry, including flavors geared towards displaying media on mobile devices (low bandwidth, small screens, and/or HTML5 supporting devices). An asset is a single output file with its specific file type, video/audio codecs, bit-rate, GOP size, that may be used for playback, download or editing.

**Standard Audio Description**

Standard audio description allows snippets of narration to be interspersed within the natural pauses in dialogue of the original content. This type of audio description works very well for videos that have lengthy pauses in dialogue, a limited amount of visual detail that needs to be described, or do not contain any speech. 

The describer can add concise descriptions of the visual content where space allows. The original video source plays continuously, and information is described in the pauses throughout.

**Extended Audio Description**

Videos that lack natural pauses or contain a lot of important visual information can be tricky to describe without interrupting the original audio. In these cases, extended audio description are more appropriate. 

Unlike standard audio description, extended audio description is not constrained to the natural pauses of a video, but rather introduces a pause the original source content to make room for description as needed.

When a viewer presses play, the video and description begin playing. Then, the source video pauses temporarily while the description continues. After that portion of the description is complete, the video resumes playing again. This happens several times throughout the video.

**Cuepoint**

A cue point is a marker at a precise time point in the duration of a video.

**Audio tags (aka. atmospherics)**

Sound effects are sounds other than music, narration, or dialogue. They are captioned if it is necessary for the understanding and/or enjoyment of the media.

A description of sound effects, in brackets, should include the source of the sound. However, the source may be omitted if it can be clearly seen onscreen.

Examples of audio tags:

- [dried leaves crunching]

- [coins jangling]

- [siren screaming]

- [creaking chair rocking]

- [horse galloping]

**Websocket**

WebSocket is a two-way computer communication protocol over a single TCP.

