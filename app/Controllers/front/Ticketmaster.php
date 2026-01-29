<?php
namespace App\Controllers\front;
use App\Controllers\BaseController;

/**
 * this controller will manage the api import data from the ticket master api's
 */
class Ticketmaster  extends BaseController
{
    private $baseUrlForApi;
    private $apiKey;
    private $vargenreId;
    private $arrStateCodes;
    private $venusBasedOnStates;

    public function __construct()
    {
        
        error_reporting(E_ALL ^ E_NOTICE);
        //error_reporting(0);
        $this->apiKey = getenv('TICKETMASTER_API_KEY');
        $this->baseUrlForApi = "https://app.ticketmaster.com/discovery/v2/";
        $this->vargenreId = "KnvZfZ7vAvE";
        $this->arrStateCodes = ['NY', 'NJ', 'MI', 'CT', 'PA', 'MA', 'MD', 'DC', 'RI', 'DE', 'WA'];
        $this->venusBasedOnStates = [
            'NY' => 5, 'NJ' => 17, 'CT' => 16, 'PA' => 18, 'MA' => 25,
            'MD' => 21, 'DC' => 28, 'RI' => 26, 'DE' => 22, 'MI' => 21,
            'WA' => 28
        ];
        $this->load->model(array('common_model'));
    }

    /**
     * import data from tk api and import into tmp table and then import their venue as well
     * and if dest=main_tbl then it will fetch data from tmp table and insert into main event table with 
     * other tables impacted as well like location, artist, jazz type etc 
     */
    public function importEvents()
    {
        $varIsInsertToEventTable = $this->request->getPost('dest', true);
        if (!empty($varIsInsertToEventTable) && $varIsInsertToEventTable == 'main_tbl') {
            die;// die added for stopping automation and switching to manual
            // fetch all the data from the impoted tmp table from ticketmaster 
            $where['where'] = ["is_imported_event_tbl" => 0];
            $tbl            =    'ticketmaster_event_tbl as ftable';
            $arrDataReturn = $this->common_model->getData('multiple', $tbl, $where);
          
            if (!empty($arrDataReturn)) {
             
                foreach ($arrDataReturn as $arrEventData) {
                    // get a single object of an event from the json
                    $arrCompleteSingleEventData = json_decode($arrEventData['all_data'], true);
                    $varLocationId = 0;
                    $varJazzTypeId = 0;
                    $varArtistId = 0;
                    $varIsActive = 0;
                    if (!empty($arrCompleteSingleEventData['_embedded']['venues'])) {
                        // insert into event locations if that location is not there
                        $varLocationId = $this->addEventLocations($arrCompleteSingleEventData['_embedded']['venues'][0]);
                        $param['latitude']  =  !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['location']['latitude']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['location']['latitude'] : 0;
                        $param['longitude'] =     !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['location']['longitude']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['location']['longitude'] : 0;
                        $param['location_name']  =     $arrCompleteSingleEventData['_embedded']['venues'][0]['name'];
                        $param['location_address'] =     !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['address']['line1']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['address']['line1'] : "";
                    }
                    if (!empty($arrCompleteSingleEventData['_embedded']['attractions'])) {
                        // insert into artists if that artist is not there
                        $varArtistId = $this->addArtists($arrCompleteSingleEventData['_embedded']['attractions'][0]);
                    }
                    if (!empty($arrCompleteSingleEventData['classifications'][0]['subGenre'])) {
                        // insert into jaz types if that jaz type is not there
                        $varJazzTypeId = $this->addJazzType($arrCompleteSingleEventData['classifications'][0]['subGenre']);
                    }
                    $param['event_title']  = $arrCompleteSingleEventData['name'];
                    $param['event_source'] = "ticketmaster";
                    $param['event_source_id'] = $arrCompleteSingleEventData['id'];

                    $param['start_date'] = !empty($arrCompleteSingleEventData['dates']['start']['localDate']) ? $arrCompleteSingleEventData['dates']['start']['localDate'] : "";
                    $param['end_date']     = !empty($arrCompleteSingleEventData['dates']['end']['localDate']) ? $arrCompleteSingleEventData['dates']['end']['localDate'] : $param['start_date'];

                    $param['event_start_time']    =     !empty($arrCompleteSingleEventData['dates']['start']['localTime']) ? $arrCompleteSingleEventData['dates']['start']['localTime'] : "";
                    $param['event_end_time']    =     !empty($arrCompleteSingleEventData['dates']['end']['localTime']) ? date("H:i", strtotime($arrCompleteSingleEventData['dates']['end']['localTime'])) : "";
                    //echo $param['event_end_time']    =     !empty($arrCompleteSingleEventData['dates']['start']['localTime']) ?$arrCompleteSingleEventData['dates']['end']['localTime'] : "";

                   if (!empty($arrCompleteSingleEventData['dates']['status']['code'])) {
                        $param['ticket_status_code'] = $arrCompleteSingleEventData['dates']['status']['code'];
                    }
                    $param['event_start_time'] = date("H:i", strtotime($param['event_start_time']));
                     $combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
                    $param['date'] = strtotime($combined_date_and_time);
                    $param['event_source_image'] = !empty($arrCompleteSingleEventData['images'][0]['url']) ? $arrCompleteSingleEventData['images'][0]['url'] : "";
                    $param['cover_url'] = $param['event_source_image'];
                    $param['repeating_event']        =     "No";
                    $param['save_location_id']        =     $varLocationId;
                    $param['website']                =     $arrCompleteSingleEventData['url'];
                    $param['phone_number']            =     "";
                    $param['venue_id']                =     $this->venusBasedOnStates[$arrCompleteSingleEventData['_embedded']['venues'][0]['state']['stateCode']];
                    //$param['cover_charge']            =     "";
                    $param['added_by']                =     'Scraping_bot';
                    $param['jazz_types_id']            =      $varJazzTypeId;
                    $param['artist_id']                =     $varArtistId;

                    $varDescription = "";
                    if (!empty($arrCompleteSingleEventData['info'])) {
                        $varDescription = $arrCompleteSingleEventData['info'];
                    }
                    if (!empty($arrCompleteSingleEventData['info'])) {
                        $varDescription = $varDescription . "<br/>" . $arrCompleteSingleEventData['pleaseNote'];
                    }
                    $param['description'] =  $varDescription;
                    $param['requested_boost']       =  0;
                    $param['creation_date']            =     date('Y-m-d h:i:s');
                    $param['is_active']                =    '0';
                    $param['created_by'] =  'scraping_api';
                    $param['is_front']   =  '0';
                    // price ranges
                    $varprice = "";
                   
                    if (!empty($arrCompleteSingleEventData['priceRanges'][0])) {
                        $varMin = !empty($arrCompleteSingleEventData['priceRanges'][0]['min']) ? $arrCompleteSingleEventData['priceRanges'][0]['min'] : "";
                        $varMax = !empty($arrCompleteSingleEventData['priceRanges'][0]['max']) ? $arrCompleteSingleEventData['priceRanges'][0]['max'] : "";
                        if (!empty($varMin))
                            $varprice =   ($varMin == $varMax) ? "$" . $varMin : "$" . $varMin . " - $" . $varMax;
                    } else {
                        $param['cover_charge'] = "Free";
                    }
                    $param['cover_charge'] = $varprice;


                    // check if that event is already in our system or not
                    $whereEvent['where'] = [
                        'event_title' => $param['event_title'],
                        'start_date' => $param['start_date'],
                        'location_address'=>$param['location_address'],
                        'event_start_time' => $param['event_start_time'] 
                    ];
                    $tblE            =    'event_tbl as ftable';
                    $arrIsEventExistData = $this->common_model->getData('single', $tblE, $whereEvent);

                    if (empty($arrIsEventExistData)) {
                        // inserted into event table if that is not existed
                        $isInserted = $this->common_model->addData('event_tbl', $param);
                    } else {
                        $paramForEditEvent = $param;
                        $paramForEditEvent['is_active'] = $arrIsEventExistData['is_active'];
                        $paramForEditEvent['creation_date'] = $arrIsEventExistData['creation_date'];
                        $paramForEditEvent['is_front'] = $arrIsEventExistData['is_front'];
                        $paramForEditEvent['requested_boost'] = $arrIsEventExistData['requested_boost'];
                        // update the data 
                        $whereT = "event_id={$arrIsEventExistData['event_id']}";
                        $this->common_model->updateData('event_tbl', $paramForEditEvent, $whereT);
                        echo "already existed" . "<br/>";
                    }
                    // update the status that this record is imported into main table
                    $whereTT = "event_id={$arrEventData['event_id']}";
                    $dataT = ['is_imported_event_tbl' => 1];
                    $this->common_model->updateData('ticketmaster_event_tbl', $dataT, $whereTT);
                }
            }
          
        } else {
            if (!empty($this->arrStateCodes)) {
                foreach ($this->arrStateCodes as $key => $val) {
                    $varStateCode = $val;
                    $varFilters = "locale=*&stateCode={$varStateCode}&genreId={$this->vargenreId}&size=200";
                    $objJsonOutput = file_get_contents($this->baseUrlForApi . "events?apikey={$this->apiKey}&{$varFilters}");
                    $arrOutput = json_decode($objJsonOutput, true);
                    
                    if (!empty($arrOutput['page']['totalPages'])) {
                        for ($i = 0; $i < $arrOutput['page']['totalPages']; $i++) {
                            echo "page - " . $i . "state-code{$val}\n";
                            if ($i != 0) {
                                $varFilters = "locale=*&stateCode={$varStateCode}&genreId={$this->vargenreId}&size=200&page=$i";
                                $objJsonOutput = file_get_contents($this->baseUrlForApi . "events?apikey={$this->apiKey}&{$varFilters}");
                                $arrOutput = json_decode($objJsonOutput, true);
                            }
                            if (!empty($arrOutput['_embedded']['events'])) {
                                foreach ($arrOutput['_embedded']['events'] as $key => $arrVal) {
                                    // echo "<pre>";
                                    // print_r($arrVal);
                                    // die;
                                    $arrDataToinsert = [
                                        'state_code' => $val,
                                        'tk_event_id' => $arrVal['id'],
                                        'creation_date' => date('Y-m-d H:i:s'),
                                        'all_data' => json_encode($arrVal)
                                    ];
                                    $this->common_model->saveData('ticketmaster_event_tbl', $arrDataToinsert,$arrVal['id'],'tk_event_id');
                                }
                            }
                        }
                    } else {
                        if (!empty($arrOutput['_embedded']['events'])) {
                            foreach ($arrOutput['_embedded']['events'] as $key => $arrVal) {
                                $arrDataToinsert = [
                                    'state_code' => $val,
                                    'tk_event_id' => $arrVal['id'],
                                    'creation_date' => date('Y-m-d H:i:s'),
                                    'all_data' => json_encode($arrVal)
                                ];
                                $this->common_model->saveData('ticketmaster_event_tbl', $arrDataToinsert,$arrVal['id'],'tk_event_id');
                            }
                        }
                    }
                }
            }
        }
    }
    // add jazz type if not existed with us
    private function addJazzType($arrVal)
    {
        $where['where'] = ['LOWER(name)' => strtolower($arrVal['name'])];
        $tbl  =    'jazz_types as ftable';
        $arrDataReturn = $this->common_model->getData('single', $tbl, $where);
        if (empty($arrDataReturn)) {
            $arrDataToinsert = [
                'jazz_source_id' => $arrVal['id'],
                'jazz_source' => "ticketmaster",
                'name' => $arrVal['name'],
                'is_active' => 1
            ];
            $varId = $this->common_model->addData('jazz_types', $arrDataToinsert);
        } else {
            $varId = $arrDataReturn['id'];
        }
        return $varId;
    }

    //add the artists if not exist with us 
    private function addArtists($arrVal)
    {
       
        $where['where'] = ['LOWER(artist_name)' => strtolower($arrVal['name'])];
        $tbl  =    'artist_tbl as ftable';
        $arrDataReturn = $this->common_model->getData('single', $tbl, $where);
        $varId = 0;
        if (empty($arrDataReturn)) {
            $arrDataToinsert = [
                'artist_source_id' => $arrVal['id'],
                'artist_source' => "ticketmaster",
                'artist_name' => $arrVal['name'],
                'artist_source_image' => !empty($arrVal['images'][0]['url']) ? $arrVal['images'][0]['url'] : "",
                'artist_url' => $arrVal['url'],
                'cover_url' => !empty($arrVal['images'][0]['url']) ? $arrVal['images'][0]['url'] : "",
                'creation_date' => date('Y-m-d H:i:s'),
                'is_active' => 1
            ];
            if (!empty($arrVal['name'])) {
                $varId = $this->common_model->addData('artist_tbl', $arrDataToinsert);
            }
        } else {
            $varId = $arrDataReturn['id'];
        }
        return $varId;
    }
    // add the locations if not existed
    private function addEventLocations($arrVal)
    {

        $where['where'] = ['LOWER(location_name)' => strtolower($arrVal['name'])];
        $tbl  =    'event_location_tbl as ftable';
        $arrDataReturn = $this->common_model->getData('single', $tbl, $where);
        if (empty($arrDataReturn)) {
            $arrDataToinsert = [
                'location_source_id' => $arrVal['id'],
                'location_source' => "ticketmaster",
                'location_name' => $arrVal['name'],
                'description' => "",
                'location_address' => !empty($arrVal['address']['line1']) ? $arrVal['address']['line1'] : "",
                'short_description' => '',
                'latitude' => !empty($arrVal['location']['latitude']) ? $arrVal['location']['latitude'] : 0,
                'longitude' => !empty($arrVal['location']['longitude']) ? $arrVal['location']['longitude'] : 0,
                'zipcode' => !empty($arrVal['postalCode']) ? $arrVal['postalCode'] : '',
                'phone_number' => '',
                'website' => !empty($arrVal['PA']) ? $arrVal['PA'] : "",
                'location_type' => 2,
                'venue_id' => $this->venusBasedOnStates[$arrVal['state']['stateCode']],
                'ip_address' => "",
                'location_type' => 2,
                'created_by' => 1,
                'creation_date' => date('Y-m-d H:i:s'),
                'is_active' => 1
            ]; 
            $varId = $this->common_model->addData('event_location_tbl', $arrDataToinsert);
        } else {
            $varId = $arrDataReturn['id'];
        }
        return $varId;
    }
}
