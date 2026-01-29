<?php

namespace App\Models;

use CodeIgniter\Model;

class FrontModel extends Model
{
    protected $table; // Remove fixed table assignment
    protected $primaryKey = 'id'; // Assuming 'id' is the primary key
    protected $db;
    public function __construct($tableName = 'event_tbl')
    {
        $this->db = \Config\Database::connect();
        $this->table = $tableName;
    }

    public function setTable($tableName)
    {
        $this->table = $tableName;
    }

    public function event_artist()
    {
       
		$builder = $this->db->table($this->table)
						 ->distinct()
                        ->select('event_title')
                        ->where('is_active', 1)
                        ->orderBy('event_title');
    
        $query = $builder->get();
		if (!$query) {
            log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
            return false;
        }
		$result = $query->getResultArray();
        return !empty($result) ? $result : false;
    }
    public function festival_artist()
    {
      // echo"herere3";die;
    	$builder = $this->db->table('festival_tbl')
					->distinct()
					->select('festival_name')
					->where('is_active', '1')
					->orderBy('festival_name');

		$query = $builder->get();
		if (!$query) {
			log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
			return false;
		}
    
       	$result = $query->getResultArray();
        return !empty($result) ? $result : false;
    }
   
    public function event_artist1()
    {
		$builder = $this->db->table($this->table)
                        ->distinct()                 // ✅ DISTINCT instead of GROUP BY
                        ->select('event_tags')
                        ->where('is_active', 1)
                        ->orderBy('event_tags');
        $query = $builder->get();
		if (!$query) {
            log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
            return false;
        }
		$result = $query->getResultArray();
        return !empty($result) ? $result : false;
    }

    public function get_event_sponsored($data)
    {
        $date = date('Y-m-d');
       // echo"$date";
       // echo"<pre>";print_r($this->table);die;
        $builder = $this->db->table($this->table)
                            ->select("$this->table.*, jazz_types.name AS jazz_type_name, artist_tbl.artist_name, artist_tbl.artist_image")
                            ->join('jazz_types', "$this->table.jazz_types_id = jazz_types.id", 'left')
                            ->join('artist_tbl', "$this->table.artist_id = artist_tbl.id", 'left')
                            ->where('DATE(start_date) >=', $date)
                            ->where("$this->table.is_active", '1')
                            ->where("$this->table.is_boosted", '1')
                            ->orderBy('start_date', 'asc')
                            ->limit(50);

        if (!empty($data['event_title'])) {
            $builder->like('event_title', $data['event_title']);
        }
        if (!empty($data['keyword'])) {
            $builder->like('event_title', $data['keyword']);
        }
        if (!empty($data['location'])) {
            $builder->like('save_location_id', $data['location']);
        }
        if (!empty($data['venue'])) {
            $builder->where('venue_id', $data['venue']);
        }
        if (!empty($data['jazz'])) {
            $builder->where('jazz_types_id', $data['jazz']);
        }

        return $builder->get()->getResultArray();
    }

    public function get_event_featured($data)
    {
        $date = date('Y-m-d');

        $builder = $this->db->table($this->table)
                            ->select("$this->table.*, jazz_types.name AS jazz_type_name, artist_tbl.artist_name, artist_tbl.artist_image")
                            ->join('jazz_types', "$this->table.jazz_types_id = jazz_types.id", 'left')
                            ->join('artist_tbl', "$this->table.artist_id = artist_tbl.id", 'left')
                            ->where('DATE(start_date) >=', $date)
                            ->where("$this->table.is_active", '1')
                            ->where("$this->table.is_featured", '1')
                            ->orderBy('start_date', 'asc')
                            ->limit(50);

        if (!empty($data['event_title'])) {
            $builder->like('event_title', $data['event_title']);
        }
        if (!empty($data['keyword'])) {
            $builder->like('event_title', $data['keyword']);
        }
        if (!empty($data['location'])) {
            $builder->like('save_location_id', $data['location']);
        }
        if (!empty($data['venue'])) {
            $builder->where('venue_id', $data['venue']);
        }
        if (!empty($data['jazz'])) {
            $builder->where('jazz_types_id', $data['jazz']);
        }

        return $builder->get()->getResultArray();
    }

    public function get_eventsBySearch($date, $data)
    {
        $builder = $this->db->table($this->table)
                            ->select("$this->table.*, jazz_types.name AS jazz_type_name, artist_tbl.artist_name, artist_tbl.artist_image")
                            ->join('jazz_types', "$this->table.jazz_types_id = jazz_types.id", 'left')
                            ->join('artist_tbl', "$this->table.artist_id = artist_tbl.id", 'left')
                            ->where("$this->table.is_active", '1')
                            ->orderBy('is_boosted', 'desc')
                            ->orderBy('is_featured', 'desc')
                            ->orderBy('DATE(start_date)', 'asc');

        $applyDateFilter = true;

        if (!empty($data['event_title'])) {
            $builder->like('event_title', $data['event_title']);
            $applyDateFilter = false;
        }
        if (!empty($data['event_ids'])) {
            $builder->whereIn('event_id', $data['event_ids']);
            $applyDateFilter = false;
        }
        if (!empty($data['keyword'])) {
            $builder->like('event_title', $data['keyword']);
            $applyDateFilter = false;
        }
        if (!empty($data['location'])) {
            $builder->like('save_location_id', $data['location']);
            $applyDateFilter = false;
        }
        if (!empty($data['venue'])) {
            $builder->where('venue_id', $data['venue']);
            $applyDateFilter = false;
        }
        if (!empty($data['jazz'])) {
            $builder->where('jazz_types_id', $data['jazz']);
            $applyDateFilter = false;
        }

        if ($applyDateFilter && !empty($date)) {
            $builder->where('DATE(start_date)', $date);
        } else {
            $builder->where('DATE(start_date) >=', date('Y-m-d'));
        }

        return $builder->get()->getResultArray();
    }

    function event_detail($eventId)
	{
        $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('event_id', $eventId);
    
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
	}

	function artist_detail($artistId)
	{
		$builder = $this->db->table('artist_tbl')
                            ->select('*')
                            ->where('id', $artistId);
    
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
	}

    public function get_festivalsBySearch($date, $data)
    {
        
        // $builder = $this->db->table('festival_tbl')->select('festival_tbl.*');
		$builder = $this->db->table('festival_tbl');
		// DISTINCT ON requires manual SQL piece
    	$builder->select('DISTINCT ON (festival_name) festival_tbl.*', false);

        $applyDateFilter = true;

        if (isset($data['festival_name']) && trim($data['festival_name']) != '') {
            $builder->like('festival_name', $data['festival_name']);
            $applyDateFilter = false;
        }

        if (isset($data['keyword']) && trim($data['keyword']) != '') {
            $builder->like('festival_name', $data['keyword']);
            $applyDateFilter = false;
        }

       	if ($applyDateFilter && isset($date)) {
			$builder->where('DATE(start_date) <=', $date);
			$builder->where('DATE(end_date) >=', $date);
		}

		$builder->where('festival_tbl.is_active', '1');

		// DISTINCT ON requires ordering by the DISTINCT column first
		$builder->orderBy('festival_name', 'asc');
		$builder->orderBy('is_boosted', 'desc');
		$builder->orderBy('is_featured', 'desc');

		$query = $builder->get();
		$query = $builder->get();
		if (!$query) {
			log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
			return false;
		}

        return $query->getResultArray();
    }
    function search_results($keywords)
    {
        $query = $this->db->table('festival_tbl')
            ->select('festival_name')
            ->distinct()
            ->like('festival_name', $keywords)
            ->orderBy('festival_name')
            ->get();
    
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

	/***********************************************************************
	 ** Function name : get_events
	 ** Developed By : Ritu Mishra
	 ** Purpose  : This function used for get events
	 ** Date : 14 April 2023
	 ************************************************************************/
	public function get_events($date)
	{
		$builder = $this->db->table('event_tbl');

		$builder->select('event_tbl.*, jazz_types.name AS jazz_type_name, artist_tbl.artist_name, artist_tbl.artist_image');

		$builder->join('jazz_types', 'event_tbl.jazz_types_id = jazz_types.id', 'left');
		$builder->join('artist_tbl', 'event_tbl.artist_id = artist_tbl.id', 'left');

		$builder->where('start_date <=', $date);
		$builder->where('end_date >=', $date);
		$builder->where('event_tbl.is_active', '1');
		$builder->where('date !=', '');

		$builder->orderBy('is_boosted', 'desc');
		$builder->orderBy('is_featured', 'desc');
		$builder->groupBy('event_title');

		$query = $builder->get();

		if ($query && $query->getNumRows() > 0) {
			return $query->getResultArray();
		}

		return array();
		
	}

    
    // public function get_events1($select_data, $today_date, $Date_selected_, $locationId = "", $jazzId = "", $artist = "")
    // {
       
    //     $builder = $this->db->table('event_tbl')
    //         ->select('event_tbl.*, jazz_types.name AS jazz_type_name, artist_tbl.artist_name, artist_tbl.artist_image')
    //         ->join('jazz_types', 'event_tbl.jazz_types_id = jazz_types.id', 'left')
    //         ->join('artist_tbl', 'event_tbl.artist_id = artist_tbl.id', 'left')
    //         ->where("event_tbl.is_active = '1'")
    //         ->where("event_tbl.date != ''")
    //         ->where('start_date', $Date_selected_);

    //     if (!empty($locationId)) {
    //         $builder->where('save_location_id', $locationId);
    //     }

    //     if (!empty($jazzId)) {
    //         $builder->where('jazz_types_id', $jazzId);
    //     }

    //     if (!empty($select_data)) {
    //         $builder->where('venue_id', $select_data);
    //     }

    //     if (!empty($artist)) {
    //         $builder->where('event_id', $artist);
    //     }

    //     $builder->orderBy('is_boosted', 'desc');
    //     $builder->orderBy('is_featured', 'desc');
    //     $builder->groupBy('event_title');

    //     return $builder->get()->getResultArray();
    // }

    // public function get_festivals1($select_data, $today_date, $Date_selected_, $locationId = "", $jazzId = "", $artist = "")
	// {
	// 	$this->db->select('festival_tbl.*, artist_tbl.artist_name');
	// 	$this->db->from('festival_tbl');
	// 	$this->db->join('jazz_types', 'festival_tbl.jazz_types_id = jazz_types.id', 'left');
	// 	$this->db->join('artist_tbl', 'festival_tbl.artist_id = artist_tbl.id', 'left');
	// 	$this->db->where("festival_tbl.is_active = '1' AND festival_tbl.date != ''");
	// 	//$this->db->where('venue_id',$venueid);
	// 	$this->db->where('start_date', $Date_selected_);
	// 	/*$this->db->where('start_date >=',$today_date);
	// 	$this->db->where('end_date <=', $today_date);*/

	// 	if (!empty($locationId)) {
	// 		$this->db->where('save_location_id', $locationId);
	// 	}

	// 	if (!empty($select_data)) {
	// 		$this->db->where('venue_id', $select_data);
	// 	}
	// 	if (!empty($artist)) {
	// 		$this->db->where('festival_id', $artist);
	// 	}
	// 	// $this->db->order_by('date', 'asc');
	// 	$this->db->order_by('is_boosted', 'desc');
	// 	$this->db->order_by('is_featured', 'desc');
	// 	$this->db->group_by('festival_name');

	// 	$query = $this->db->get();
	// 	$result = $query->result_array();

	// 	return $result;
	// }

	// public function get_events2($select_data, $today_date, $Date_selected_, $locationId = "", $artist = "")
	// {

	// 	$this->db->select('*');
	// 	$this->db->from('event_tbl');
	// 	$this->db->where("is_active = '1' AND date != ''");
	// 	//$this->db->where('venue_id',$venueid);
	// 	$this->db->where('start_date', $Date_selected_);
	// 	/*$this->db->where('start_date >=',$today_date);
	// 	$this->db->where('end_date <=', $today_date);*/

	// 	if (!empty($select_data)) {
	// 		$this->db->where('jazz_types_id', $select_data);
	// 	}
	// 	if (!empty($artist)) {
	// 		$this->db->where('event_id', $artist);
	// 	}
	// 	$this->db->order_by('date', 'asc');
	// 	$this->db->group_by('event_title');

	// 	$query = $this->db->get();
	// 	$result = $query->result_array();

	// 	return $result;
	// } 	

	// public function get_events3($select_data, $today_date, $Date_selected_, $locationId = "", $artist = "")
	// {
	// 	$this->db->select('*');
	// 	$this->db->from('event_tbl');
	// 	$this->db->where("is_active = '1' AND date != ''");
	// 	//$this->db->where('venue_id',$venueid);
	// 	$this->db->where('start_date', $Date_selected_);
	// 	/*$this->db->where('start_date >=',$today_date);
	// 	$this->db->where('end_date <=', $today_date);*/

	// 	if (!empty($locationId)) {
	// 		$this->db->where('save_location_id', $locationId);
	// 	}
	// 	if (!empty($select_data)) {
	// 		$this->db->where('artist_id', $select_data);
	// 	}
	// 	if (!empty($artist)) {
	// 		$this->db->where('event_id', $artist);
	// 	}
	// 	$this->db->order_by('date', 'asc');
	// 	$this->db->group_by('event_title');

	// 	$query = $this->db->get();
	// 	$result = $query->result_array();

	// 	return $result;
	// }

	// /* * *********************************************************************
	//  * * Function name 	: getlocationData
	//  * * Developed By 	: Ritu Mishra
	//  * * Purpose  		: This function used for Location Data
	//  * * Date 			: 15 June 23
	//  * * **********************************************************************/
	// public function getlocationData($venue_id)
	// {
	// 	$this->db->select('*');
	// 	$this->db->from('event_location_tbl');
	// 	$this->db->where('venue_id', $venue_id);
	// 	$this->db->order_by('location_name');
	// 	$query	=	$this->db->get();
	// 	if ($query->num_rows() > 0) :
	// 		return $query->result();
	// 	else :
	// 		return false;
	// 	endif;
	// }	// END OF FUNCTION


	// /* * *********************************************************************
	//  * * Function name 	: searchartist
	//  * * Developed By 	: Ritu Mishra
	//  * * Purpose  		: This function used for Location Data
	//  * * Date 			: 15 June 23
	//  * * **********************************************************************/
	// public function searchartist($Date_selected_, $artist = "")
	// {

	// 	$this->db->select('*');
	// 	$this->db->from('event_tbl');
	// 	$this->db->where('is_active', '1');
	// 	//$this->db->where('venue_id',$venueid);
	// 	$this->db->where('start_date', $Date_selected_);
	// 	/*$this->db->where('start_date >=',$today_date);
	// 	$this->db->where('end_date <=', $today_date);*/
	// 	//$this->db->group_by('event_title');


	// 	$this->db->where('event_title', $artist);

	// 	$this->db->order_by('date', 'asc');
	// 	$this->db->group_by('event_title');
	// 	$query = $this->db->get();
	// 	$result = $query->result_array();
	// 	return $result;
	// }

	// public function searchartistfestival($Date_selected_, $artist = "")
	// {

	// 	$this->db->select('*');
	// 	$this->db->from('festival_tbl');
	// 	$this->db->where('is_active', '1');
	// 	//$this->db->where('venue_id',$venueid);
	// 	$this->db->where('start_date', $Date_selected_);
	// 	/*$this->db->where('start_date >=',$today_date);
	// 	$this->db->where('end_date <=', $today_date);*/
	// 	//$this->db->group_by('event_title');


	// 	$this->db->where('festival_name', $artist);

	// 	$this->db->order_by('date', 'asc');
	// 	$this->db->group_by('festival_name');
	// 	$query = $this->db->get();
	// 	$result = $query->result_array();
	// 	return $result;
	// }

	// public function searchartisted($Date_selected_, $artist = "")
	// {

	// 	$this->db->select('*');
	// 	$this->db->from('event_tbl');
	// 	$this->db->where('is_active', '1');
	// 	$this->db->where('start_date', $Date_selected_);

	// 	$this->db->where('event_tags', $artist);

	// 	$this->db->order_by('date', 'asc');
	// 	$this->db->group_by('event_tags');
	// 	$query = $this->db->get();
	// 	$result = $query->result_array();
	// 	return $result;
	// }


	// /***********************************************************************
	//  ** Function name 	: search_result
	//  ** Developed By 	: Ritu Mishra
	//  ** Purpose  		: This function used for get sub category id
	//  ** Date 			: 21 Dec 2022
	//  ************************************************************************/
	// function search_result($keywords)
	// {
	// 	$dataarray		=	array();
	// 	$this->db->select('event_title');
	// 	$this->db->distinct();
	// 	$this->db->from('event_tbl');
	// 	$this->db->like('event_title', $keywords);
	// 	$this->db->where('DATE(start_date)>=', date('Y-m-d'));
	// 	$this->db->where('event_tbl.is_active', '1');
	// 	$this->db->order_by('event_title');
	// 	$query = $this->db->get();
	// 	if ($query->num_rows() > 0) :
	// 		$data	=	$query->result_array();
	// 		return $data;
	// 	else :
	// 		return false;
	// 	endif;
	// }




	// public function get_new_events($date, $data)
	// {
	// 	$this->db->select('event_tbl.*, jazz_types.name AS jazz_type_name, artist_tbl.artist_name, artist_tbl.artist_image');
	// 	$this->db->from('event_tbl');
	// 	$this->db->join('jazz_types', 'event_tbl.jazz_types_id = jazz_types.id', 'left');
	// 	$this->db->join('artist_tbl', 'event_tbl.artist_id = artist_tbl.id', 'left');

	// 	$applyDateFilter = true;

	// 	if (isset($data['event_title']) && trim($data['event_title']) != '') {
	// 		$this->db->like('event_title', $data['event_title']);
	// 		$applyDateFilter = false;
	// 	}

	// 	if (isset($data['event_ids']) && !empty($data['event_ids'])) {
	// 		$this->db->where_in('event_id', $data['event_ids']);
	// 		$applyDateFilter = false;
	// 	}
	// 	if (isset($data['keyword']) && trim($data['keyword']) != '') {
	// 		$this->db->like('event_title', $data['keyword']);
	// 		$applyDateFilter = false;
	// 	}
	// 	if (isset($data['location']) && trim($data['location']) != '') {
	// 		$this->db->like('save_location_id', $data['location']);
	// 		$applyDateFilter = false;
	// 	}
	// 	if (isset($data['venue']) && trim($data['venue']) != '') {
	// 		$this->db->where('venue_id', $data['venue']);
	// 		$applyDateFilter = false;
	// 	}
	// 	if (isset($data['jazz']) && trim($data['jazz']) != '') {
	// 		$this->db->where('jazz_types_id', $data['jazz']);
	// 		$applyDateFilter = false;
	// 	}
	// 	if ($applyDateFilter && isset($date)) {
	// 		$this->db->where('DATE(start_date)', $date);
	// 	}

	// 	// if ($applyDateFilter && $data) {
	// 	// 	$this->db->where('event_tbl.event_types !=', '');
	// 	// }

	// 	$this->db->where('event_tbl.is_active', '1');
	// 	$this->db->order_by('is_boosted', 'desc');
	// 	$this->db->order_by('is_featured', 'desc');
	// 	$this->db->group_by('event_title');

	// 	$query = $this->db->get();
	// 	$result = $query->result_array();
	// 	// $str = $this->db->last_query();
	// 	// echo "<pre>";print_r($str);die;
	// 	return $result;
	// }
}
