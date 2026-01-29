<?php 
namespace App\Libraries;
/**
 * Custom Library for Elastic Seearch
 *
 * @author	Shiv Kumar Tiwari
 */

use Elastic\Elasticsearch;

class Elastichh
{

    private $objClient;
    private $returnData;
    public function __construct()
    {
        $this->objClient = Elasticsearch\ClientBuilder::create()->build();
    }

    private function getSettingsOfMapping($type = 'events')
    {
        return $arrSettings = [
            'number_of_shards' => 1,
            'number_of_replicas' => 0,
            'analysis' => [
                'filter' => [
                    'shingle' => [
                        'type' => 'shingle'
                    ]
                ],
                'char_filter' => [
                    'pre_negs' => [
                        'type' => 'pattern_replace',
                        'pattern' => '(\\w+)\\s+((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\b',
                        'replacement' => '~$1 $2'
                    ],
                    'post_negs' => [
                        'type' => 'pattern_replace',
                        'pattern' => '\\b((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\s+(\\w+)',
                        'replacement' => '$1 ~$2'
                    ]
                ],
                'analyzer' => [
                    $type . '_analyzer' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => ['lowercase', 'hh_stop_words', 'kstem']
                    ]
                ],
                "filter" => [
                    "hh_stop_words" => [
                        "type" => 'stop',
                        'stopwords' => [
                            "show me all events",
                            "all events",
                            "all events in",
                            "events having",
                            "events of artist",
                            "show",
                            "me",
                            "in",
                            "all",
                            "events",
                            "event",
                            "festivals",
                            "festival",
                            "near",
                            "with",
                            "price",
                            "range",
                            "have",
                            "having"
                        ]
                    ]
                ]
            ]
        ];
    }

    // the mapping of festival index 
    private function getFestivalMappingParam()
    {
        $settings = $this->getSettingsOfMapping('festivals');
        return $params = [
            'index' => 'festivals',
            'body' => [
                'settings' => $settings,
                'mappings' => [
                    'properties' => [
                        'festival_name' => [
                            'type' => 'text',
                            'analyzer' => 'festivals_analyzer',
                            'copy_to' => 'combined'
                        ],
                        'summary' => [
                            'type' => 'text',
                            'analyzer' => 'festivals_analyzer',
                            'copy_to' => 'combined'
                        ],
                        'lineup' => [
                            'type' => 'text',
                            'analyzer' => 'festivals_analyzer',
                            'copy_to' => 'combined'
                        ],
                        'phone_number' => [
                            'type' => 'keyword',
                            'copy_to' => 'combined'
                        ],
                        'start_date' => [
                            'type' => 'date',
                            "format" => "yyyy-MM-dd"
                            // "null_value" => "NULL"
                        ],
                        'end_date' => [
                            'type' => 'date',
                            "format" => "yyyy-MM-dd"
                            // "null_value" => "NULL"
                        ],
                        "locations" => [
                            "properties" => [
                                'latitude' => ['type' => 'keyword'],
                                'longitude' => ['type' => 'keyword'],
                                'location_address' => ['type' => 'keyword', 'copy_to' => 'combined'],
                                'location_name' => ['type' => 'keyword', 'copy_to' => 'combined']
                            ]
                        ],
                        'is_featured' => ['type' => 'boolean'],
                        'is_boosted' => ['type' => 'boolean'],
                        'artists' => [
                            'type' => 'text',
                            'copy_to' => 'combined'
                        ],
                        'jazz_types' => ['type' => 'keyword']
                    ]
                ]
            ]
        ];
    }
    // the mapping of event index 
    private function getEventMappingParam()
    {
        // params for the event indexes
        return $params = [
            'index' => 'events',
            'body' => [
                'settings' => $this->getSettingsOfMapping('events'),
                'mappings' => [
                    'properties' => [
                        'event_title' => [
                            'type' => 'text',
                            'analyzer' => 'events_analyzer',
                            'copy_to' => 'combined'
                        ],
                        'event_title_sort' => ['type' => 'keyword'],
                        'description' => [
                            'type' => 'text',
                            'analyzer' => 'events_analyzer',
                            'copy_to' => 'combined'
                        ],
                        'phone_number' => [
                            'type' => 'keyword',
                            'copy_to' => 'combined'
                        ],
                        'start_date' => [
                            'type' => 'date',
                            "format" => "yyyy-MM-dd"
                            // "null_value" => "NULL"
                        ],
                        'end_date' => [
                            'type' => 'date',
                            "format" => "yyyy-MM-dd"
                            // "null_value" => "NULL"
                        ],
                        'start_date_time' => ['type' => 'date'],
                        'end_date_time' => ['type' => 'date'],
                        'website' => ['type' => 'text'],
                        'cover_charge' => ['type' => 'text', 'copy_to' => 'combined'],
                        'location_id' => ['type' => 'integer'],
                        "locations" => [
                            "properties" => [
                                'latitude' => ['type' => 'keyword'],
                                'longitude' => ['type' => 'keyword'],
                                'location_address' => ['type' => 'keyword', 'copy_to' => 'combined'],
                                'location_name' => ['type' => 'keyword', 'copy_to' => 'combined']
                            ]
                        ],
                        'location_name' => ['type' => 'keyword', 'copy_to' => 'combined'],
                        'venue_id' => ['type' => 'integer'],
                        "venue" => [
                            "properties" => [
                                'venue_name' => ['type' => 'keyword', 'copy_to' => 'combined'],
                                'image' => ['type' => 'keyword'],
                            ]
                        ],
                        'venue_name' => ['type' => 'keyword', 'copy_to' => 'combined'],
                        'venue_order' => ['type' => 'integer'],
                        'jazz_types_id' => ['type' => 'integer'],
                        'jazz_type' => [
                            "properties" => [
                                'name' => ['type' => 'keyword'],
                            ]
                        ],
                        'artist_id' => ['type' => 'integer'],
                        'artist' => [
                            "properties" => [
                                'artist_name' => ['type' => 'keyword', 'copy_to' => 'combined'],
                                'cover_url' => ['type' => 'text'],
                                'artist_bio' => ['type' => 'text']
                            ]
                        ],
                        'artist_name' => ['type' => 'keyword', 'copy_to' => 'combined'],
                        'event_tags' => [
                            'type' => 'keyword',
                            'copy_to' => 'combined'
                        ],
                        'is_featured' => ['type' => 'boolean'],
                        'is_boosted' => ['type' => 'boolean'],
                        'ticket_status_code' => ['type' => 'keyword'],
                        'event_source' => ['type' => 'keyword'],
                        'cover_url' => ['type' => 'text'],
                        'creation_date' => [
                            'type' => 'date'
                        ],
                        'combined' => [
                            'type' => 'text',
                            'analyzer' => 'events_analyzer'
                        ],
                    ]
                ]

            ]
        ];
    }

    /** 
     * create the index and mapping the fields
     */
    public function index_add_mapping($varIndexName = 'events')
    {
        $arrParams = $this->getEventMappingParam();
        //$arrParamsF = $this->getFestivalMappingParam();
        return $this->objClient->indices()->create($arrParams);
    }

    /** 
     * create the index and mapping the fields
     */
    public function index_add_mapping_festivals()
    {
        $arrParamsF = $this->getFestivalMappingParam();
        return $this->objClient->indices()->create($arrParamsF);
    }

    /**
     * delete the index with the name passed
     * @use pas the $varIndexName to which we have to delete 
     */
    public function  deleteIndex($varIndexName)
    {
        $response = $this->objClient->indices()->delete(['index' => $varIndexName]);
        return $this->returnData = [
            'message' => 'Index Deleted Successfully!.',
            'data' => $response
        ];
    }

    /**
     * delete the index with the name passed
     * @use pas the $varIndexName and $varId to which we have to delete 
     */
    public function  deleteSingleEventFromIndex($varIndexName, $varId)
    {
        $eventsParams = [
            'index' => 'events',
            'size'   => 1,
            'profile' => true,
            'body' => [
                'query' => [
                    "terms"=>[
                        "_id"=>[$varId]
                    ]
                ],
            ]

        ];
        $responseIsExists = $this->objClient->search($eventsParams);
        if(count($responseIsExists['hits']['hits']) > 0){
            $response = $this->objClient->delete(['index' => $varIndexName, 'id' => $varId]);
            return $this->returnData = [
                'message' => 'Event Deleted Successfully!.',
                'data' => $response
            ];
        }else{
            return $this->returnData = [
                'message' => 'No Data with the event id is found',
                'data' => []
            ];
        }
    }

    /**
     * full indexing
     */
    public function fullReIndexingOfEvents($eventsData)
    {
        $params = ['body' => []];
        // check if data is exists
        if (!empty($eventsData)) {
            $varMaxCount = count($eventsData);
         
            // create the indexes in batch
            for ($i = 1; $i <= $varMaxCount; $i++) {
                $data = $eventsData[$i];
                $varStartDateTime = $data['start_date'] . " " . trim(str_replace(['AM', 'PM'], ["", ""], $data['event_start_time']));
                
                $params['body'][] = [
                    'index' => [
                        '_index' => 'events',
                        '_id' => $data['event_id']
                    ]
                ];
                $params['body'][] = [
                    'event_title' => $data['event_title'],
                    'event_title_sort' => $data['event_title'],
                    'description' => $data['description'],
                    'location_id' => $data['save_location_id'],
                    'start_date' => $data['start_date'],
                    'end_date' => !empty($data['end_date']) ? $data['end_date'] : $data['start_date'],
                    'start_date_time' => !empty($data['start_date']) ? strtotime($varStartDateTime) : strtotime(date('Y-m-d H:i')),
                    'end_date_time' => !empty($data['end_date']) ? strtotime($data['end_date']) : strtotime($varStartDateTime),
                    'creation_date' => !empty($data['creation_date']) ? strtotime($data['creation_date']) : strtotime(date('Y-m-d H:i')),
                    'website' => $data['website'],
                    'phone_number' => $data['phone_number'],
                    'venue_id' => $data['venue_id'],
                    'cover_charge' => $data['cover_charge'],
                    'event_types' => $data['event_types'],
                    'jazz_types_id' => $data['jazz_types_id'],
                    'artist_id' => $data['artist_id'],
                    'cover_url' => $data['cover_url'],
                    'artist' => [
                        'artist_name' => $data['artist_name'],
                        'cover_url' => $data['a_cover_url'],
                        'artist_bio' => $data['artist_bio']
                    ],
                    'artist_name' => $data['artist_name'],
                    'locations' => [
                        'location_name' => $data['location_name'],
                        'location_address' => $data['location_address'],
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude']
                    ],
                    'location_name' => $data['location_name'],
                    "venue" => [
                        'venue_name' => $data['venue_title'],
                        'image' => $data['v_image'],
                    ],
                    'venue_name' => $data['venue_title'],
                    'venue_order'=>$data['v_order'],
                    'jazz_type' => ['name' => $data['jazz_name']],
                    'event_source' => $data['event_source'],
                    'ticket_status_code' => $data['ticket_status_code'],
                    'is_featured' => !empty($data['is_featured']) ? true : false,
                    'is_boosted' => !empty($data['is_boosted']) ? true : false,
                    'event_tags' => !empty($data['event_tags_f_t']) ? $data['event_tags_f_t'] : ""
                ];

                if ($i % 1000 == 0) {
                    

                    $responses = $this->objClient->bulk($params);
              
                    // erase the old bulk request
             
                    $params = ['body' => []];

                    // unset the bulk response when you are done to save memory
                    unset($responses);
                }
            }
            // check if any batch left then run to index again
            if (!empty($params['body'])) {
                $responses = $this->objClient->bulk($params);
                $this->returnData = [
                    'message' => 'All data Index Done',
                    'data' => $responses
                ];
            }
        } else {
            $this->returnData = [
                'message' => 'No data available to index',
                'data' => []
            ];
        }
        return $this->returnData;
    }

    /**
     * full indexing
     */
    public function fullReIndexingOfFestivals($fData)
    {
        $params = ['body' => []];
        // check if data is exists
        if (!empty($fData)) {
            $varMaxCount = count($fData);
            // create the indexes in batch
            for ($i = 1; $i <= $varMaxCount; $i++) {
                $data = $fData[$i];
                $params['body'][] = [
                    'index' => [
                        '_index' => 'festivals',
                        '_id' => $data['festival_id']
                    ]
                ];

                $params['body'][] = [
                    'festival_name' => $data['festival_name'],
                    'summary' => $data['summary'],
                    'lineup' => $data['lineup'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'website' => $data['website'],
                    'phone_number' => $data['phone_number'],
                    'jazz_types' => $data['jazz_types_id'],
                    'artists' => $data['artist_id'],
                    'locations' => [
                        'location_name' => $data['location_name'],
                        'location_address' => $data['location_address'],
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude']
                    ],
                    'is_featured' => !empty($data['is_featured']) ? true : false,
                    'is_boosted' => !empty($data['is_boosted']) ? true : false
                ];

                if ($i % 1000 == 0) {
                  
                    $responses = $this->objClient->bulk($params);
         
                    // erase the old bulk request
                    $params = ['body' => []];

                    // unset the bulk response when you are done to save memory
                    unset($responses);
                }
            }
            // check if any batch left then run to index again
            if (!empty($params['body'])) {
                $responses = $this->objClient->bulk($params);
                $this->returnData = [
                    'message' => 'All data Index Done',
                    'data' => $responses
                ];
            }
        } else {
            $this->returnData = [
                'message' => 'No data available to index',
                'data' => []
            ];
        }
        return $this->returnData;
    }

    /**
     * index or update single data of events
     * @use call elastic model to get teh $eventData from fuction getEventFromId($varEventId)
     * $varEventId is the id of the event to get the data
     * and the call this function and pass the $eventData array 
     */
    public function addUpdateSingleEvent($eventData)
    {
        $data = $eventData;
      
        $varStartDateTime = $data['start_date'] . " " . trim(str_replace(['AM', 'PM'], ["", ""], $data['event_start_time']));
        $params = [
            'index' => 'events',
            'id' => $data['event_id'],
            'body' => [
                'event_title' => $data['event_title'],
                'event_title_sort' => $data['event_title'],
                'description' => $data['description'],
                'location_id' => $data['save_location_id'],
                'start_date' => $data['start_date'],
                'end_date' => !empty($data['end_date']) ? $data['end_date'] : $data['start_date'],
                'start_date_time' => !empty($data['start_date']) ? strtotime($varStartDateTime) : strtotime(date('Y-m-d H:i')),
                'creation_date' => !empty($data['creation_date']) ? strtotime($data['creation_date']) : strtotime(date('Y-m-d H:i')),
                'website' => $data['website'],
                'phone_number' => $data['phone_number'],
                'venue_id' => $data['venue_id'],
                'cover_charge' => $data['cover_charge'],
                'event_types' => $data['event_types'],
                'jazz_types_id' => $data['jazz_types_id'],
                'artist_id' => $data['artist_id'],
                'cover_url' => $data['cover_url'],
                'artist' => [
                    'artist_name' => $data['artist_name'],
                    'cover_url' => $data['a_cover_url'],
                    'artist_bio' => $data['artist_bio']
                ],
                'artist_name' => $data['artist_name'],
                'locations' => [
                    'location_name' => $data['location_name'],
                    'location_address' => $data['location_address'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude']
                ],
                'location_name' => $data['location_name'],
                "venue" => [
                    'venue_name' => $data['venue_title'],
                    'image' => $data['v_image'],
                ],
                'venue_name' => $data['venue_title'],
                'venue_order'=>$data['v_order'],
                'jazz_type' => ['name' => $data['jazz_name']],
                'event_source' => $data['event_source'],
                'ticket_status_code' => $data['ticket_status_code'],
                'is_featured' => !empty($data['is_featured']) ? true : false,
                'is_boosted' => !empty($data['is_boosted']) ? true : false,
                'event_tags' => !empty($data['event_tags_f_t']) ? $data['event_tags_f_t'] : ""
            ]

        ];
        $response = $this->objClient->index($params);
        return $this->returnData = [
            'message' => 'Event Added / Updated Successfully!.',
            'data' => $response
        ];
    }


    /** 
     * event lsearch isting data
     */

    public function eventSearchListingData($arrParams = [])
    {
      try {
        $varSize = !empty($arrParams['size']) ? $arrParams['size'] : 200;
        if (!empty($arrParams['Selected_Date_'])) {
            $today = date('Y-m-d', strtotime($arrParams['Selected_Date_']));
        } else {
            $today = date('Y-m-d');
        }
        
        // Initialize the query structure properly
        $arrQuery = [
            'query' => [
                'bool' => [
                    'must' => [],
                    'filter' => []
                ]
            ]
        ];
        
        $isFilters = false;
        
        // Add filters if they exist
        if (!empty($arrParams['venue'])) {
            $arrQuery['query']['bool']['filter'][] = ['term' => ["venue_id" => trim($arrParams['venue'])]];
            $isFilters = true;
        }
        
        if (!empty($arrParams['location'])) {
            $arrQuery['query']['bool']['filter'][] = ["term" => ['location_id' => trim($arrParams['location'])]];
            $isFilters = true;
        }
        
        if (!empty($arrParams['jazz'])) {
            $arrQuery['query']['bool']['must'][] = ["term" => ['jazz_types_id' => trim($arrParams['jazz'])]];
            $isFilters = true;
        }
        
        if (!empty($arrParams['event_title'])) {
            $arrQuery['query']['bool']['must'][] = [
                "query_string" => [
                    "query" => $arrParams['event_title'] . "*",
                    "analyze_wildcard" => true,
                    "default_operator" => "AND"
                ]
            ];
            $isFilters = true;
        }
        
        // Add date range filter
        if ($isFilters) {
            $arrQuery['query']['bool']['filter'][] = [
                'range' => [
                    'start_date' => [
                        "gte" => $today
                    ]
                ]
            ];
        } else {
            if (!empty($arrParams['Selected_Date_'])) {
                $arrQuery['query']['bool']['must'][] = [
                    "term" => ['start_date' => $today]
                ];
            } else {
                // If no filters and no date, use match_all
                $arrQuery['query'] = [
                    'match_all' => new \stdClass()
                ];
            }
        }
        
        // If empty query structure, use match_all
        if (empty($arrQuery['query']['bool']['must']) && empty($arrQuery['query']['bool']['filter'])) {
            $arrQuery['query'] = [
                'match_all' => new \stdClass()
            ];
        }
        
        $eventsParams = [
            'index' => 'events',
            'size' => $varSize,
            'profile' => true,
            'body' => [
                'query' => $arrQuery['query'],
                "sort" => [
                    "is_boosted" => ["order" => "desc"],
                    "is_featured" => ["order" => "desc"],
                    "venue_order" => ["order" => "asc"],
                    "event_title_sort" => ["order" => "asc"],
                    "artist_name" => ["order" => "asc"],
                    "start_date_time" => ["order" => "asc"],
                ]
            ]
        ];
        
        return $this->objClient->search($eventsParams);
    } catch (\Exception $e) {
        // Log the error
        log_message('error', 'Elasticsearch search error: ' . $e->getMessage());
        
        // Return a structured error response
        return [
            'hits' => [
                'total' => ['value' => 0],
                'hits' => []
            ],
            'error' => $e->getMessage()
        ];
    }
    }

    public function globalSearchSearchListingData($arrParams = [])
    {
        $eventsParams = [
            'index' => '_all',
            'size'   => 50,
            'profile' => true,
            'body' => [
                //'query' => $arrQuery['query'],
                'query' => [
                    'match_all' => new \stdClass()
                ],
                "sort" => [
                    "is_boosted" => ["order" => "desc"],
                    //"start_date_time" => ["order" => "asc"]
                ]
            ]
        ];
        //echo json_encode($eventsParams);
        return $this->objClient->search($eventsParams);
    }

    /** 
     * event lsearch isting data
     */

    public function festivalSearchListingData($arrParams = [])
    {
        $varSize = !empty($arrParams['size']) ? $arrParams['size'] : 50;
        if (!empty($arrParams['Selected_Date_'])) {
            $today = date('Y-m-d', strtotime($arrParams['Selected_Date_']));
        } else {
            $today = date('Y-m-d');
        }
        $isFilters = false;
        if (!empty($arrParams['event_title'])) {
            $arrQuery['query']["bool"]['must'][] =  [
                "query_string" => [
                    "query" => $arrParams['event_title'] . "*",
                    "analyze_wildcard" => true,
                    "default_operator" => "AND",
                    "fields" => ["combined"]
                ]
            ];
            $isFilters = true;
        }
        if (!$isFilters) {
            $arrQuery['query']["bool"]['filter']['range'] = [
                'end_date' => [
                    "gte" => $today
                ]
            ];
        }
        $eventsParams = [
            'index' => 'festivals',
            'size'   => $varSize,
            'profile' => true,
            'body' => [
                'query' => $arrQuery['query'],
                // 'query' => [
                //     'match_all' => new \stdClass()
                // ],
                "sort" => [
                    "is_boosted" => ["order" => "desc"],
                    //"start_date_time" => ["order" => "asc"]
                ]
            ]
        ];
        //echo json_encode($eventsParams);
        return $this->objClient->search($eventsParams);
    }

        /** 
     * create the index and mapping the fields of Location
     */
    public function index_add_mapping_locations()
    {
        $indexName = 'locations';

        try {
            // Step 1: Check if the index exists before deleting
            if ($this->objClient->indices()->exists(['index' => $indexName])) {
                $deleteResponse = $this->objClient->indices()->delete(['index' => $indexName]);
                echo "Deleted existing index: $indexName\n";
            }
    
            // Step 2: Create the new index
            $arrParamsLoc = $this->getLocationMappingParam();
            $createResponse = $this->objClient->indices()->create($arrParamsLoc);
    
            // Step 3: Extract useful data from response
            return [
                'delete_response' => $deleteResponse->asArray(),
                'create_response' => $createResponse->asArray(),
            ];
    
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

// the mapping of Location index 
private function getLocationMappingParam()
{
    // params for the event indexes
    return $params = [
        'index' => 'locations',
        'body' => [
            'settings' => $this->getSettingsOfMapping('locations'),
            'mappings' => [
                'properties' => [
                    'id' => [
                        'type' => 'integer',
                        // 'copy_to' => 'combined'
                    ],
                    'location_name' => [
                        'type' => 'text',
                        'analyzer' => 'locations_analyzer',
                        'copy_to' => 'combined'
                    ],
                    'location_address' => [
                        'type' => 'text',
                        'analyzer' => 'locations_analyzer',
                        'copy_to' => 'combined'
                    ],
                    'latitude' => [
                        'type' => 'text',
                        'analyzer' => 'locations_analyzer',
                        'copy_to' => 'combined'
                    ],
                    'longitude' => [
                        'type' => 'text',
                        'analyzer' => 'locations_analyzer',
                        'copy_to' => 'combined'
                    ],
                    'venue_id' => [
                        'type' => 'integer',
                    ],
                    'combined' => [
                        'type' => 'text',
                        'analyzer' => 'locations_analyzer'
                    ],
                ]
            ]

        ]
    ];
}
public function bulkIndexLocations($locations)
{
    $params = ['body' => []];

    foreach ($locations as $location) {
        $location_name = !empty($location['location_name']) ? $location['location_name'] : "Unknown";
        $location_address = !empty($location['location_address']) ? $location['location_address'] : "Unknown";
        $location_lat = !empty($location['latitude']) ? $location['latitude'] : "Unknown";
        $location_long = !empty($location['longitude']) ? $location['longitude'] : "Unknown";
        $venue_id = !empty($location['venue_id']) ? $location['venue_id'] : "Unknown";
        

        // Bulk insert format
        $params['body'][] = [
            'index' => [
                '_index' => 'locations',
                '_id' => $location['id']
            ]
        ];
        $params['body'][] = [
            'id' => $location['id'],
            'location_name' => $location_name,
            'location_address' => $location_address,
            'location_lat' => $location_lat,
            'location_long' => $location_long,
            'venue_id' => $venue_id,
        ];
    }

    // Only run bulk insert if there is data
    if (!empty($params['body'])) {
        $response = $this->objClient->bulk($params);
        return $response->asArray(); // Return formatted response
    }

    return ['message' => 'No data to insert'];
}

public function getAllLocationsFromElastic()
{
    $params = [
        'index' => 'locations',
        'size' => 1000, // Fetch up to 1000 records
        'body' => [
            'query' => [
                'match_all' => new stdClass() // Get all records
            ]
        ]
    ];

    $response = $this->objClient->search($params);
    return $response['hits']['hits'] ?? [];
}

    public function addUpdateSingleLoc($locData)
    {
        $data = $locData;
        $params = [
            'index' => 'locations',
            'id' => $data['loc_id'],
            'body' => [
                'loc_name' => $data['event_title'],
                'loc_address' => $data['event_title'],
            ]

        ];
        $response = $this->objClient->index($params);
        return $this->returnData = [
            'message' => 'Location Added / Updated Successfully!.',
            'data' => $response
        ];
    }

// fuzzy search on the locations index.
public function searchLocation($query)
{
    // Split query into words
    $words = explode(" ", $query);
    $wordCount = count($words);

    // Set minimum words that must match (80% of words)
    $minMatch = ceil($wordCount * 0.8); // At least 80% words must match

    $params = [
        'index' => 'locations',
        'size' => 100, // Limit results
        'body' => [
            'query' => [
                'dis_max' => [
                    'queries' => [
                        [
                            'match' => [
                                'location_name' => [
                                    'query' => $query,
                                    'fuzziness' => 'AUTO',
                                    'minimum_should_match' => "$minMatch<90%" // Maximize word match
                                ]
                            ]
                        ]                        
                    ],
                    'tie_breaker' => 0.7 // Prioritizes higher match scores
                ]
            ]
        ]
    ];

    $response = $this->objClient->search($params);
    return $response['hits']['hits'] ?? [];
}


}
