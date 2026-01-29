<?php

namespace App\Models;

use CodeIgniter\Model;

class ScrapingModel extends Model
{
    protected $db;
    private $venusBasedOnStates;
    private $varJazzTypeId;
    
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        $this->varJazzTypeId = 30;
    }

    /**
     * Get all the state codes of venues and their ids
     * 
     * @return array|bool Array of state codes or false
     */
    public function getUniqueStateCodesOfVenue()
    {
        $builder = $this->db->table('venue_tbl');
        $builder->select('state_code')->distinct();
        $result = $builder->get();
        
        if (count($result->getResultArray()) > 0) {
            $arrDataReturn = $result->getResultArray();
            $arrVenueData = [];
            if (!empty($arrDataReturn)) {
                foreach ($arrDataReturn as $key => $val) {
                    $arrVenueData[] = $val['state_code'];
                }
            }
            return $arrVenueData;
        } else {
            return false;
        }
    }

    /**
     * Get venues ids based on the state code
     * 
     * @return array|bool Array of venues or false
     */
    public function getIdsWithStateCodeArray()
    {
        $builder = $this->db->table('venue_tbl');
        $builder->select('id, state_code')->distinct();
        $result = $builder->get();
        
        if (count($result->getResultArray()) > 0) {
            $arrDataReturn = $result->getResultArray();
            $arrVenueData = [];
            if (!empty($arrDataReturn)) {
                foreach ($arrDataReturn as $key => $val) {
                    if (!array_key_exists($val['state_code'], $arrVenueData)) {
                        $arrVenueData[$val['state_code']] = $val['id'];
                    }
                }
            }
            return $arrVenueData;
        } else {
            return false;
        }
    }

    /**
     * Filter out the params and convert to an array form which will be used as event data
     * and also insert into different tables as well
     *
     * @param array $arrParams Event data from Eventbrite
     * @return string Return data with inserted or updated ID
     */
    public function addEventDataFromEventbrite($arrParams)
    {
        $varLocationId = 0;
        $varJazzTypeId = $this->varJazzTypeId;
        $varArtistId = 0;
        $this->venusBasedOnStates = $this->getIdsWithStateCodeArray();
        $param = [];
        
        if (!empty($arrParams['venue'])) {
            // insert into event locations if that location is not there
            $arrVanueData = $arrParams['venue'];
            
            $arrLocationDataToinsert = [
                'location_source_id' => $arrVanueData['id'],
                'location_source' => "eventbrite",
                'location_name' => $arrVanueData['name'],
                'description' => "",
                'location_address' => !empty($arrVanueData['address']['localized_area_display']) ? $arrVanueData['address']['localized_area_display'] : "",
                'short_description' => '',
                'latitude' => !empty($arrVanueData['latitude']) ? $arrVanueData['latitude'] : 0,
                'longitude' => !empty($arrVanueData['longitude']) ? $arrVanueData['longitude'] : 0,
                'zipcode' => !empty($arrVanueData['address']['postal_code']) ? $arrVanueData['address']['postal_code'] : '',
                'phone_number' => '',
                'website' => "",
                'location_type' => 2,
                'venue_id' => !empty($this->venusBasedOnStates[$arrVanueData['address']['region']]) ? $this->venusBasedOnStates[$arrVanueData['address']['region']] : 100,
                'ip_address' => "",
                'created_by' => 1,
                'creation_date' => date('Y-m-d H:i:s'),
                'is_active' => 1
            ];

            // copy variables for event table
            $param['latitude'] = $arrLocationDataToinsert['latitude'];
            $param['longitude'] = $arrLocationDataToinsert['longitude'];
            $param['location_name'] = $arrLocationDataToinsert['location_name'];
            $param['location_address'] = $arrLocationDataToinsert['location_address'];
            $param['venue_id'] = $arrLocationDataToinsert['venue_id'];
            
            // check and insert the location data into database
            $varLocationId = $this->addEventLocations($arrLocationDataToinsert);
        }

        $param['event_title'] = $arrParams['name']['text'];
        $param['event_source'] = "eventbrite";
        $param['event_source_id'] = $arrParams['id'];
        $param['website'] = $arrParams['url'];
        $param['phone_number'] = "";
        $param['description'] = !empty($arrParams['description']['text']) ? $arrParams['description']['text'] : "";
        
        // date times
        $param['start_date'] = date('Y-m-d', strtotime($arrParams['start']['local']));
        $param['end_date'] = date('Y-m-d', strtotime($arrParams['end']['local']));
        $param['event_start_time'] = date('H:i', strtotime($arrParams['start']['local']));
        $param['event_end_time'] = date('H:i', strtotime($arrParams['end']['local']));
        
        // combined date time
        $combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
        $param['date'] = strtotime($combined_date_and_time);

        $param['event_source_image'] = !empty($arrParams['logo']['original']['url']) ? $arrParams['logo']['original']['url'] : "";
        $param['cover_url'] = $param['event_source_image'];
        $param['repeating_event'] = "No";
        $param['added_by'] = 'Scraping_bot';
        $param['jazz_types_id'] = $varJazzTypeId;
        $param['artist_id'] = $varArtistId;
        $param['requested_boost'] = 0;
        $param['creation_date'] = date('Y-m-d H:i:s');
        $param['is_active'] = '0';
        $param['created_by'] = 'scraping_api';
        $param['is_front'] = '0';

        // states of the ticket
        if ($arrParams['ticket_availability']['is_sold_out'] === true) {
            $param['ticket_status_code'] = "sold out";
        } else {
            $param['ticket_status_code'] = "onsale";
        }

        $param['save_location_id'] = $varLocationId;
        
        // price ranges
        $varprice = "";
        if (!empty($arrParams['ticket_availability']['minimum_ticket_price']['major_value'])) {
            $varMin = !empty($arrParams['ticket_availability']['minimum_ticket_price']['major_value']) ? 
                $arrParams['ticket_availability']['minimum_ticket_price']['major_value'] : "";
            $varMax = !empty($arrParams['ticket_availability']['maximum_ticket_price']['major_value']) ? 
                $arrParams['ticket_availability']['maximum_ticket_price']['major_value'] : "";
            
            if (!empty($varMin)) {
                $varprice = ($varMin == $varMax) ? "$" . $varMin : "$" . $varMin . " - $" . $varMax;
            }
            $param['cover_charge'] = $varprice;
        } else {
            $param['cover_charge'] = "Free";
        }
        
        // check if that event is already in our system or not
        $builder = $this->db->table('event_tbl');
        $builder->select('*');
        $builder->where('event_title', $param['event_title']);
        $builder->where('location_address', $param['location_address']);
        $builder->where('start_date', $param['start_date']);
        $builder->where('event_start_time', $param['event_start_time']);
        $builder->limit(1);
        $queryEvent = $builder->get();
        
        if (count($queryEvent->getResultArray()) > 0) {
            $arrDataReturn = $queryEvent->getRowArray();
            // update the data
            $builder->where('event_id', $arrDataReturn['event_id']);
            $builder->update($param);
            $varReturnedData = $arrDataReturn['event_id'] . " - updated";
        } else {
            // inserted into event table if that is not existed
            $builder->insert($param);
            $isInserted = $this->db->insertID();
            $varReturnedData = $isInserted . " - inserted";
        }
        
        return $varReturnedData;
    }

    /**
     * Filter out the params and convert to an array form which will accept as event data
     * and also insert into different tables as well
     * 
     * @param array $arrParams Event data from TicketMaster
     * @return void
     */
    public function addEventDataFromTicketMaster($arrParams)
    {
        // Implementation to be added
    }

    /**
     * Add the locations if not existed
     * 
     * @param array $arrVal Location data to insert
     * @return int Location ID
     */
    private function addEventLocations($arrVal)
    {
        // check location using its name
        $builder = $this->db->table('event_location_tbl');
        $builder->select('*');
        $builder->where('LOWER(location_name)', strtolower($arrVal['location_name']));
        $builder->limit(1);
        $query = $builder->get();
        
        if (count($query->getResultArray()) > 0) {
            $arrDataReturn = $query->getRowArray();
            $varId = $arrDataReturn['id'];
        } else {
            $builder->insert($arrVal);
            $varId = $this->db->insertID();
        }
        
        return $varId;
    }
}