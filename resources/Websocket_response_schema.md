### Websocket response schema
Responses returned by the Streaming Speech Recognition services should have the following schema:

`
{
    "response": {
        "id": string (UUID),
        "type": "transcript" | "captions",
        "start": float,
        "end": float,
        "start_pts": float,
        "start_epoch": float,
        "is_final": boolean,
        "is_end_of_stream": boolean,
        "speakers": [
            {
                "id": string (UUID),
                "label": string | null
            }
        ],
        "alternatives": [
            {
                "transcript": string,
                "start": float,
                "end": float,
                "start_pts": float,
                "start_epoch": float,
                "items": [
                    {
                        "start": float,
                        "end": float,
                        "kind": "text" | "punct",
                        "value": string,
                        "speaker_id": string (UUID)
                    }
                ]
            }
        ]
    }
}
`
