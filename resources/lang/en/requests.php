<?php

return [
    "installation" => [
        "attributes" => [
            "default_client_id" => "Default client ID",
            "default_client_secret" => "Default client secret"
        ]
    ],
    "setting" => [
        "attributes" => [
            "key" => "Key",
            "value" => "Value"
        ]
    ],
    "account" => [
        "messages" => [
            "client_id_required" => "Field client ID is required",
            "client_secret_required" => "Field client secret is required",
            "application_required" => "Select application"
        ],
        "attributes" => [
            "client_id" => "client ID",
            "client_secret" => "client secret",
            "application" => "application"
        ]
    ],
    "application" => [
        "attributes" => [
            "client_id" => "client ID",
            "client_secret" => "client secret",
            "name" => "Name"
        ]
    ]
];
