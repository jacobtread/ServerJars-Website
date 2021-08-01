<?php

class Documentation {

    const ROUTES = [
        'fetchJar' => [
            'name' => 'Downloading Jars',
            'description' => 'Fetch a direct download link to a specific jar type with either the latest version or a specified one.',
            'method' => 'GET',
            'url' => '/api/fetchJar/{type}/{version}',
            'fields' => [
                'type' => [
                    'type' => 'String',
                    'description' => 'The type of jar (spigot, bukkit, paper, etc..)',
                    'optional' => false
                ],
                'version' => [
                    'type' => 'Version',
                    'description' => 'The version of the jar (dont provide for latest)',
                    'optional' => true
                ],
            ],
            'response' => [
                'type' => 'file',
            ]
        ],
        'fetchLatest' => [
            'name' => 'Latest Details',
            'description' => 'Fetch details on the latest jar for a type',
            'method' => 'GET',
            'url' => '/api/fetchLatest/{type}',
            'fields' => [
                'type' => [
                    'type' => 'String',
                    'description' => 'The type of jar (spigot, bukkit, paper, etc..)',
                    'optional' => false
                ]
            ],
            'response' => [
                'type' => 'json',
                'example' => [
                    'status' => 'success',
                    'response' => [
                        'version' => '1.15.2',
                        'file' => 'paper-1.15.2.jar',
                        'md5' => 'bd4e45da91c6e60de5060c700e2d86e6',
                        'built' => 1588583784
                    ]
                ]
            ]
        ],
        'fetchAll' => [
            'name' => 'All Details',
            'description' => 'Fetch details on the all the jars for a type',
            'method' => 'GET',
            'url' => '/api/fetchAll/{type}/{max}',
            'fields' => [
                'type' => [
                    'type' => 'String',
                    'description' => 'The type of jars (spigot, bukkit, paper, etc..)',
                    'optional' => false
                ],
                'max' => [
                    'type' => 'Integer',
                    'description' => 'The maximum amount of results to respond with',
                    'optional' => true
                ]
            ],
            'response' => [
                'type' => 'json',
                'example' => [
                    'status' => 'success',
                    'response' => [
                        [
                            'version' => '1.15.2',
                            'file' => 'paper-1.15.2.jar',
                            'md5' => 'bd4e45da91c6e60de5060c700e2d86e6',
                            'built' => 1588583784
                        ], [
                            'version' => '1.15.1',
                            'file' => 'paper-1.15.1.jar',
                            'md5' => '9e4259bf560e0cfb0486f118839e7bcf',
                            'built' => 1588583786
                        ],
                    ]
                ]
            ]
        ],
        'fetchTypes' => [
            'name' => 'Jar Types',
            'description' => 'Fetch a list of the possible jar types',
            'method' => 'GET',
            'url' => '/api/fetchTypes/{type}',
            'fields' => [
                'type' => [
                    'type' => 'String',
                    'description' => 'The category of types (bedrock, proxies, servers, etc...)',
                    'optional' => true
                ],
            ],
            'response' => [
                'type' => 'json',
                'example' => [
                    'status' => 'success',
                    'response' => [
                        "proxies" => [
                            "bungeecord",
                            'velocity',
                            "waterfall"
                        ],
                        "bedrock" => [
                            "pocketmine",
                            "nukkitx"
                        ],
                        "vanilla" => [
                            "vanilla",
                            "snapshot"
                        ],
                        "servers" => [
                            "paper",
                            "bukkit",
                            "spigot"
                        ],
                        "more" => [
                            "travertine",
                            "mohist",
                            "magma"
                        ]
                    ]
                ]
            ]
        ],
    ];

}