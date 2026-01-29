<?php
// if (!defined('BASEPATH')) exit('No direct script access allowed');
namespace App\Models;

use CodeIgniter\Model;
class ElasticModel extends Model{

    protected $db;

    public function __construct(){
		 $this->db = \Config\Database::connect();
	}

     /**
     * Get all events and related data for indexing
     */
    public function getEventDataForIndexing($offset = 0, $varlimit = 20)
    {
        $today = date('Y-m-d');

        $builder = $this->db->table('event_tbl e')
            ->select("
                e.event_id, e.event_title, e.start_date, e.end_date, e.is_active,
                e.creation_date, e.description, e.venue_id, e.artist_id, e.jazz_types_id,
                e.event_start_time, e.save_location_id, e.website, e.phone_number,
                e.cover_charge, e.event_source, e.ticket_status_code, e.is_featured, e.is_boosted,
                j.name AS jazz_name,
                v.venue_title, v.image AS v_image, v.position AS v_order,
                l.location_name, l.location_address, l.latitude, l.longitude,
                a.artist_name, a.cover_url AS a_cover_url, a.artist_bio,
                STRING_AGG(ett.event_tags, ', ') AS event_tags_f_t
            ")
            ->join('artist_tbl a', 'a.id = e.artist_id', 'left')
            ->join('jazz_types j', 'j.id = e.jazz_types_id', 'left')
            ->join('venue_tbl v', 'v.id = e.venue_id', 'left')
            ->join('event_location_tbl l', 'l.id = e.save_location_id', 'left')
            ->join('event_tags_tbl ett', 'ett.event_id = e.event_id', 'left')
            ->where('e.is_active', '1')
            ->where('e.start_date >=', $today)
            ->groupBy('
                e.event_id, e.event_title, e.start_date, e.end_date, e.is_active,
                e.creation_date, e.description, e.venue_id, e.artist_id, e.jazz_types_id,
                e.event_start_time, e.save_location_id, e.website, e.phone_number,
                e.cover_charge, e.event_source, e.ticket_status_code, e.is_featured, e.is_boosted,
                j.name, v.venue_title, v.image, v.position,
                l.location_name, l.location_address, l.latitude, l.longitude,
                a.artist_name, a.cover_url, a.artist_bio
            ')
            ->orderBy('e.creation_date', 'DESC')
            ->limit($varlimit, $offset);


        $query = $builder->get();
        if ($query === false) {
            // Show database error
            $dbError = $this->db->error();
            log_message('error', 'Database error: ' . print_r($dbError, true));
            throw new \RuntimeException('Database query failed: ' . $dbError['message']);
        }
        $result = $query->getResultArray();
        return !empty($result) ? $result : [];
    }

    /**
     * Get single event and related data
     */
    public function getEventFromId($varEventId)
    {
        $builder = $this->db->table('event_tbl e')
            ->select('
                e.*, 
                j.name AS jazz_name, 
                v.venue_title, v.image AS v_image, v.position AS v_order,
                l.location_name, l.location_address, l.latitude, l.longitude,
                a.artist_name, a.cover_url AS a_cover_url, a.artist_bio,
                STRING_AGG(ett.event_tags, \', \') AS event_tags_f_t
            ')
            ->join('artist_tbl a', 'a.id = e.artist_id', 'left')
            ->join('jazz_types j', 'j.id = e.jazz_types_id', 'left')
            ->join('venue_tbl v', 'v.id = e.venue_id', 'left')
            ->join('event_location_tbl l', 'l.id = e.save_location_id', 'left')
            ->join('event_tags_tbl ett', 'ett.event_id = e.event_id', 'left')
            ->where('e.event_id', $varEventId)
            ->groupBy('
                e.event_id, j.name, v.venue_title, v.image, v.position,
                l.location_name, l.location_address, l.latitude, l.longitude,
                a.artist_name, a.cover_url, a.artist_bio
            ');

        $query = $builder->get();
        $result = $query->getRowArray();
        return !empty($result) ? $result : [];
    }

    /**
     * Get all festivals and related data for indexing
     */
    public function getFestivalDataForIndexing($offset = 0, $varlimit = 20)
    {
        $today = date('Y-m-d');

        $builder = $this->db->table('festival_tbl')
            ->select('*')
            ->where('is_active', '1')
            ->where('end_date >=', $today)
            ->orderBy('creation_date', 'DESC')
            ->limit($varlimit, $offset);

        $query = $builder->get();
        $result = $query->getResultArray();
        return !empty($result) ? $result : [];
    }

    // add the locations if not existed
    // private function addEventLocations($arrVal)
    // {
    //     // chck location suing its name 
    //     $query = $this->db->select('*')->from('event_location_tbl')
    //                                 ->where('LOWER(location_name)',strtolower($arrVal['location_name']))
    //                                 ->limit(1)->get();
    //     if ($query->num_rows() > 0){
    //         $arrDataReturn = $query->row_array();
    //         $varId = $arrDataReturn['id'];
    //     }else{
    //         $this->db->insert('event_location_tbl', $arrVal);
    //         $varId =$this->db->insert_id();
    //     }
    //     return $varId;
    // }


}
