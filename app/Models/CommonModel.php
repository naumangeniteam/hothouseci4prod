<?php
// if (!defined('BASEPATH')) exit('No direct script access allowed');
namespace App\Models;

use CodeIgniter\Model;

class CommonModel extends Model
{
    protected $db;
    protected $archiveddb;
    protected array $allowedFieldsMap = [
        'id'               => 'el.id',
        'venue_id'         => 'el.venue_id',
        'location_name'    => 'el.location_name',
        'location_address' => 'el.location_address',
        'state'            => 'el.state',
        'county'           => 'el.county',
        'is_active'        => 'el.is_active',
        'location_source'  => 'el.location_source',
    ];


    public function __construct()
    {

        $this->db = \Config\Database::connect();
        $this->archiveddb = \Config\Database::connect('archivedDB');
    }

    /***********************************************************************
     ** Function name : addData
     ** Developed By : Manoj Kumar
     ** Purpose  : This function used for add data
     ** Date : 23 JUNE 2022
     ************************************************************************/
    public function addData($tableName = '', $param = [])
    {
        try {
            $builder = $this->db->table($tableName);
            $success = $builder->insert($param);

            if (!$success) {
                $dbError = $this->db->error();
                log_message('error', "Insert failed into {$tableName}. Code: {$dbError['code']} - Message: {$dbError['message']}");
                log_message('error', "Failed Query: " . $this->db->getLastQuery());
                throw new \RuntimeException("Insert failed. Code: {$dbError['code']} - Message: {$dbError['message']}");
            }

            // Use CodeIgniter's insertID() — works for MySQL and PostgreSQL
            $id = $this->db->insertID();

            log_message('info', "Database insertion success in addData, ID: {$id}");
            log_message('debug', "Table: {$tableName}, Data: " . json_encode($param));

            return $id;
        } catch (\Throwable $th) {
            log_message('error', 'Database insertion error in addData: ' . $th->getMessage());
            log_message('error', "Failed Query: " . $this->db->getLastQuery());
            return 0;
        }
    }


    /* * *********************************************************************
	 * * Function name : editData
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for edit data
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
    public function editData($tableName = '', $param = [], $fieldName = '', $fieldValue = '')
    {
        try {
            if (!$this->isValidTable($tableName)) {
                throw new \InvalidArgumentException('Invalid table name');
            }
            $builder = $this->db->table($tableName);

            $builder->where([$fieldName => $fieldValue]);
            $sucess = $builder->update($param);
            if (!$sucess) {
                log_message('error', "editData Update failed: " . $this->db->getLastQuery());
                return false;
            }
            log_message('info', "Database update success in editData, Table: {$tableName}, {$fieldName}: {$fieldValue}");
            log_message('debug', "Updated Data: " . json_encode($param));
            log_message('debug', "Last Query: " . $this->db->affectedRows());
            // echo"herer122"; ;die;
            return ($this->db->affectedRows() > 0); // Returns true if any row is updated
        } catch (\InvalidArgumentException $e) {
            log_message('error', 'editData error: ' . $e->getMessage());
            return false;
        }
    }
    /***********************************************************************
     ** Function name: getDataByQuery
     ** Developed By: Manoj Kumar
     ** Purpose: This function used for get data by query
     ** Date : 23 JUNE 2022
     ************************************************************************/
    // Get Data (Generalized Fetch Function)
    public function getData($action = '', $tbl_name = '', $wcon = [], $shortField = '', $num_page = '', $cnt = '')
    {
        //echo"<pre>";print_r($tbl_name);die;
        $builder = $this->db->table($tbl_name);
        $builder->select('*'); // Select all columns
        // echo"<pre>";print_r($tbl_name);die; 
        // Apply filters based on GET parameters
        if (!empty($_GET['event_name'])) {
            $builder->like('event_title', $_GET['event_name']);
        }
        if (!empty($_GET['name'])) {
            $builder->like('festival_name', $_GET['name']);
        }
        if (!empty($_GET['location_name'])) {
            $builder->like('location_name', $_GET['location_name']);
        }
        if (!empty($_GET['venue_id'])) {
            $builder->where('venue_id', $_GET['venue_id']);
        }
        if (!empty($_GET['jazz_types_id'])) {
            $builder->where('jazz_types_id', $_GET['jazz_types_id']);
        }
        if (!empty($_GET['city'])) {
            $builder->where('save_location_id', $_GET['city']);
        }
        if (!empty($_GET['state'])) {
            $builder->where('save_location_id', $_GET['state']);
        }
        if (!empty($_GET['artist_id'])) {
            $builder->where('artist_id', $_GET['artist_id']);
        }
        if (isset($_GET['status']) && $_GET['status'] != 100) {
            $builder->where('is_active', (string)$_GET['status']);
        }
        if (!empty($_GET['start_date'])) {
            $builder->where('start_date >=', $_GET['start_date']);
        }
        if (!empty($_GET['end_date'])) {
            $builder->where('end_date <=', $_GET['end_date']);
        }

        // in ci3
        // if (isset($wcon['where_gte']) && $wcon['where_gte']) :
        // 	foreach ($wcon['where_gte'] as $whereGteData) :
        // 		$this->mongo_db->where_gte($whereGteData[0], $whereGteData[1]);
        // 	endforeach;
        // endif;

        // Additional WHERE conditions
        //   if (!empty($wcon['where'])) {
        // 	  $builder->where($wcon['where']);
        //   }

        if (!empty($wcon['where'])) {
            if (is_array($wcon['where'])) {
                $builder->where($wcon['where']);
            } else {
                $whereArray = explode('AND', $wcon['where']);
                foreach ($whereArray as $condition) {
                    // Validate the condition format or extract field name for validation
                    $trimmedCondition = trim($condition);
                    if (preg_match('/^([a-zA-Z0-9_]+)\s*[=<>!]+/', $trimmedCondition, $matches)) {
                        if ($this->isValidField($matches[1])) {
                            $builder->where($trimmedCondition);
                        }
                    }
                }
            }
        }

        //   if (!empty($wcon['like'])) {
        // 	  $builder->like($wcon['like']);
        //   }

        // ✅ Apply LIKE conditions correctly
        if (!empty($wcon['like']) && is_array($wcon['like'])) {
            //  echo"<pre>";print_r($wcon['like']);die;
            $builder->groupStart(); // Start a grouping for OR conditions
            foreach ($wcon['like'] as $column => $value) {
                $builder->orLike($column, $value);
            }
            $builder->groupEnd(); // End grouping
        }

        // Sorting and Pagination
        if (!empty($shortField)) {
            $builder->orderBy($shortField);
        }
        //   if (!empty($num_page)) {
        // 	  $builder->limit($num_page, $cnt);
        //   }
        if (!empty($num_page)) {
            $num_page = (int) $num_page; // Ensure it's an integer
            $cnt = (int) $cnt; // Convert offset to integer
            $builder->limit($num_page, $cnt);
        }


        // Execute query
        $query = $builder->get();

        if (!$query) {
            log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
            return false;
        }

        // Handle different return types
        if ($action == 'count') {
            return $query->getNumRows();
        } elseif ($action == 'single') {
            return $query->getRowArray();
        } elseif ($action == 'multiple') {

            return $query->getResultArray();
        }

        return false;
    }    // END OF FUNCTION


    public function getLastOrderByFields1($action = '', $field = '', $tbl_name = '', $fieldName = '', $fieldValue = '', $shortField55 = '')
    {
        $builder = $this->db->table($tbl_name);
        $builder->select($shortField55);

        if (!empty($fieldName) && !empty($fieldValue)) {
            $builder->where([$fieldName => $fieldValue]);
        }

        $builder->orderBy($field . ' ASC');

        $query = $builder->get();

        if ($action == 'count') {
            return $query->getNumRows();
        } elseif ($action == 'single') {
            return $query->getRowArray();
        } elseif ($action == 'multiple') {
            return $query->getResultArray();
        }

        return false;
    }    // END OF 


    function subscribeEmail($email = "")
    {
        $builder = $this->db->table('subscribe_tbl');
        $builder->select('id');
        $builder->where('email', $email);
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getRow() : false;
    }
    public function getCategoryJazz($isArray = true)
    {
        $builder = $this->db->table('jazz_types')
            ->select('id, name')
            ->where('is_active', '1')
            ->orderBy('name', 'asc');

        if ($isArray) {
            $data = $builder->get()->getResultArray();
        } else {
            $data = $builder->get()->getResult();
        }
        return !empty($data) ? $data : false;
    }

    public function getCount(string $table, array $where = [])
    {
        $builder = $this->db->table($table);
        if (!empty($where)) {
            $builder->where($where);
        }
        return $builder->countAllResults();
    }


    public function getCategoryArtist($isArray = true)
    {
        $builder = $this->db->table('artist_tbl')
            ->select('id, artist_name, artist_image, artist_url, cover_image, cover_url, buy_now_link, website_link, artist_bio')
            ->where('is_active', '1')
            ->orderBy('artist_name', 'asc');

        // in ci3
        // order_by('artist_name, artist_image, artist_url, cover_image, cover_url, buy_now_link, website_link, artist_bio', 'asc')
        if ($isArray) {
            $data = $builder->get()->getResultArray();
        } else {
            $data = $builder->get()->getResult();
        }
        return !empty($data) ? $data : false;
    }


    public function    getMapLoc()
    {
        $query = $this->db->table('location_tbl')->where('is_active', 1)->get();
        $result = $query->getResultArray();
        if ($result) :
            return $result;
        else :
            return false;
        endif;
    }



    public function getFestLoc()
    {
        $query = $this->db->table('festival_tbl')->where('is_active', 1)->get();
        $result = $query->getResultArray();
        if ($result) :
            return $result;
        else :
            return false;
        endif;
    }
    public function getLocation($isArray = true)
    {
        $builder = $this->db->table('event_location_tbl')
            ->select('id, location_name')
            ->where('is_active', '1')
            ->orderBy('location_name', 'ASC');

        if ($isArray) {
            $data = $builder->get()->getResultArray();
        } else {
            $data = $builder->get()->getResult();
        }
        return !empty($data) ? $data : false;
    }

    public function getCategory($isArray = true)
    {
        $builder = $this->db->table('venue_tbl')
            ->select('id, venue_title, position')
            ->where('is_active', '1')
            ->orderBy('venue_title', 'asc')
            ->orderBy('position', 'asc');

        if ($isArray) {
            $data = $builder->get()->getResultArray();
        } else {
            $data = $builder->get()->getResult();
        }
        return !empty($data) ? $data : false;
    }

    public function getState($isArray = true)
    {
        $builder = $this->db->table('state_tbl')
            ->select('id, name')
            ->orderBy('id', 'asc');

        if ($isArray) {
            $data = $builder->get()->getResultArray();
        } else {
            $data = $builder->get()->getResult();
        }
        return !empty($data) ? $data : false;
    }

    public function getCounty()
    {
        $builder = $this->db->table('event_location_tbl')
            ->select('county')
            ->where('county IS NOT NULL')
            ->where('county !=', '')
            ->groupBy('county')
            ->orderBy('county', 'asc');

        $data = $builder->get()->getResultArray();
        return !empty($data) ? $data : false;
    }

    // Utility function to validate allowed field names (add this to your model)
    private function isValidField($field, $allowedFields = [])
    {
        // Optionally, pass an array of allowed fields for each table
        return preg_match('/^[a-zA-Z0-9_]+$/', $field) && (empty($allowedFields) || in_array($field, $allowedFields));
    }

    /***********************************************************************
     ** Function name: getDataByParticularField
     ** Developed By: Manoj Kumar
     ** Purpose: This function used for get data by encryptId
     ** Date : 23 JUNE 2022
     ************************************************************************/
    public function getDataByParticularField($tableName = '', $fieldName = '', $fieldValue = '')
    {
        // Empty parameter check for better security
        if (empty($tableName) || empty($fieldName)) {

            throw new \InvalidArgumentException('Invalid parameters');
        }

        // Table validation with explicit security context
        if (!$this->isValidTable($tableName)) {

            throw new \InvalidArgumentException('Invalid table name');
        }

        // Fetch actual field names for the table to use as a whitelist
        $allowedTableFields = $this->db->getFieldNames($tableName);

        if (empty($allowedTableFields)) {

            log_message('error', 'Could not retrieve fields for table: ' . $tableName);
            throw new \RuntimeException('Unable to retrieve table structure for validation.');
        }

        // Validate fieldName against the actual columns of the table using the existing isValidField method
        if (!$this->isValidField($fieldName, $allowedTableFields)) {
            throw new \InvalidArgumentException('Field "' . $fieldName . '" is not a valid or allowed field for table "' . $tableName . '".');
        }
        try {
            // Parameterized query using CodeIgniter's query builder
            $builder = $this->db->table($tableName)
                ->select('*')
                ->where([$fieldName => $fieldValue]);

            $data = $builder->get()->getRowArray();

            return !empty($data) ? $data : [];
        } catch (\Exception $e) {

            throw new \RuntimeException('Database error occurred');
        }
    }


    public function getAllDataByParticularField($tableName = '', $fieldName = '', $fieldValue = '')
    {

        if (!$this->isValidField($fieldName)) {
            throw new \InvalidArgumentException('Invalid field name');
        }
        // Use Query Builder
        $query = $this->db->table($tableName)
            ->where($fieldName, $fieldValue)
            ->get();

        if (!$query) {
            // Log the error for debugging
            log_message('error', 'DB Query failed: ' . $this->db->error()['message']);
            log_message('debug', 'Executed Query: ' . $this->db->getLastQuery());
            return [];
        }

        return $query->getNumRows() > 0 ?  $query->getResultArray() : [];
    }

    public function editDataByMultipleCondition(string $tableName, array $param, array $whereCondition)
    {
        if (empty($tableName) || empty($param) || empty($whereCondition)) {
            return false; // Prevent empty updates
        }

        $builder = $this->db->table($tableName);
        $builder->where($whereCondition);
        return $builder->update($param);
    }

    public function totalTrashevent()
    {
        return $this->db->table('event_tbl')
            ->select('event_id')
            ->where('is_active', '2')
            ->get()
            ->getResult() ?: [];
    }

    public function newVenuesForCurrentMonth()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $startDate = date('Y-m-01'); // First day of current month
        $endDate   = date('Y-m-t');
        log_message("info", "currentMonth: $currentMonth");
        log_message("info", "currentYear: $currentYear");
        log_message("debug", "StartDate: $startDate");
        log_message("debug", "endDate: $endDate");


        return $this->db->table('venue_tbl')
            ->select('id')
            // ->where('MONTH(creation_date)', $currentMonth)
            // ->where('YEAR(creation_date)', $currentYear)
            ->where('creation_date >=', $startDate)
            ->where('creation_date <=', $endDate)
            ->get()
            ->getResult() ?: false;
    }

    public function newEventsForCurrentMonth()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');

        $startDate = date('Y-m-01'); // First day of current month
        $endDate   = date('Y-m-t');

        return $this->db->table('event_tbl')
            ->select('event_id')
            // ->where('MONTH(start_date)', $currentMonth)
            // ->where('YEAR(start_date)', $currentYear)
            ->where('start_date >=', $startDate)
            ->where('start_date <=', $endDate)
            ->get()
            ->getResult() ?: false;
    }
    public function totalEvents()
    {
        return $this->db->table('event_tbl')
            ->select('event_id')
            ->get()
            ->getResult() ?: false;
    }
    public function newUsersForCurrentMonth()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');

        $startDate = date('Y-m-01'); // First day of current month
        $endDate   = date('Y-m-t');

        return $this->db->table('admin')
            ->select('admin_id')
            ->where('role', 2)
            // ->where('MONTH(creation_date)', $currentMonth)
            // ->where('YEAR(creation_date)', $currentYear)
            ->where('creation_date >=', $startDate)
            ->where('creation_date <=', $endDate)
            ->get()
            ->getResult() ?: false;
    }

    public function trashEventsCurrentMonth()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $firstDayOfMonth = date('Y-m-01', strtotime("{$currentYear}-{$currentMonth}-01"));
        $lastDayOfMonth = date('Y-m-t', strtotime("{$currentYear}-{$currentMonth}-01"));

        return $this->db->table('event_tbl')
            ->select('event_id')
            ->where('is_active', '2')
            ->where("start_date BETWEEN '{$firstDayOfMonth}' AND '{$lastDayOfMonth}'")
            ->get()
            ->getResult() ?: [];
    }

    public function totalPublishevent()
    {
        return $this->db->table('event_tbl')
            ->select('event_id')
            ->where('is_active', '1')
            ->get()
            ->getResult() ?: false;
    }

    // public function getData_event_report($action = '', $tbl_name = '', $whereCon = [], $shortField = '', $num_page = '', $cnt = '')
    // {

    //     // $builder = $this->db->table(event_tbl); // ✅ Use actual table name (no alias)
    //     $builder = $this->db->table("event_tbl");
    //     $builder->select("event_location_tbl.state, event_location_tbl.city, venue_tbl.venue_title, venue_tbl.position");

    //     // ✅ Use actual table name in JOIN
    //     $builder->join('event_location_tbl', "event_tbl.save_location_id = event_location_tbl.id", 'left');
    //     $builder->join('venue_tbl', "event_tbl.venue_id = venue_tbl.id", 'left');

    //     $request = service('request'); // CI4 way to access request data

    //     if ($request->getGet('event_name')) {
    //         $builder->like("event_tbl.event_title", $request->getGet('event_name')); // ✅ Use actual table name
    //     }

    //     if ($request->getGet('location_name')) {
    //         $builder->where("event_tbl.save_location_id", $request->getGet('location_name')); // ✅ Use actual table name
    //     }

    //     if ($request->getGet('venue_id') && $request->getGet('venue_id') !== 'All') {
    //         $builder->where("event_tbl.venue_id", $request->getGet('venue_id')); // ✅ Use actual table name
    //     }

    //     if ($request->getGet('start_date')) {
    //         $builder->where("event_tbl.start_date >=", $request->getGet('start_date')); // ✅ Use actual table name
    //     }

    //     if ($request->getGet('end_date')) {
    //         $builder->where("event_tbl.end_date <=", $request->getGet('end_date')); // ✅ Use actual table name
    //     }

    //     $builder->orderBy('venue_tbl.position', 'asc'); // ✅ Ensure correct column for sorting


    //     if (!empty($num_page)) {
    //         $builder->limit((int)$num_page, (int)$cnt); // ✅ Convert to integer before using
    //     }

    //     // Execute Query
    //     $query = $builder->get();

    //     // Return Data Based on `$action`
    //     if ($action == 'count') {
    //         return $query->getNumRows();
    //     } elseif ($action == 'single') {
    //         return $query->getRowArray();
    //     } elseif ($action == 'multiple') {
    //         return $query->getResultArray();
    //     } else {
    //         return false;
    //     }
    // }
    public function getData_event_report($action = '', $tbl_name = '', $whereCon = [], $shortField = '', $num_page = '', $cnt = '')
    {
        log_message("info", "getData_event_report");
        log_message("debug", "action: $action");
        $builder = $this->db->table($tbl_name);
        $builder->select("event_location_tbl.state, event_location_tbl.city, venue_tbl.venue_title, venue_tbl.position, ftable.venue_id");
        //$builder->join('venue_tbl', 'venue_tbl.id = ftable.venue_id', 'left');
        // ✅ Selecting the correct columns
        //$builder->select("event_location_tbl.state, event_location_tbl.city, venue_tbl.venue_title, venue_tbl.position, ftable.venue_id");

        // ✅ Correct JOIN conditions using alias `ftable`
        $builder->join('event_location_tbl', "event_location_tbl.id = ftable.save_location_id", 'left');

        $builder->join('venue_tbl', "venue_tbl.id = ftable.venue_id", 'left');
        $currentMonthStart = date('Y-m-01');
        $currentMonthEnd = date('Y-m-t');
        // echo"herer1sd2";die;
        $request = service('request'); // CI4 way to access request data

        // ✅ Ensure the alias `ftable` is used for ambiguous columns
        if ($request->getGet('event_name')) {
            $builder->like("ftable.event_title", $request->getGet('event_name'));
        }

        if ($request->getGet('location_name')) {
            $builder->where("ftable.save_location_id", $request->getGet('location_name'));
        }

        if ($request->getGet('venue_id') && $request->getGet('venue_id') !== 'All') {
            $builder->where("ftable.venue_id", $request->getGet('venue_id')); // ✅ Specify alias
        }

        if ($request->getGet('start_date')) {
            $builder->where("ftable.start_date >=", $request->getGet('start_date'));
        } else {
            $builder->where('ftable.start_date >=', $currentMonthStart);
        }


        if ($request->getGet('end_date')) {
            $builder->where("ftable.end_date <=", $request->getGet('end_date'));
        } else {
            $builder->where('ftable.end_date <=', $currentMonthEnd);
        }
        $builder->where('ftable.end_date !=', '');
        $builder->where('ftable.end_date is NOT NULL', NULL, FALSE);

        // ✅ Order by with table prefix
        $builder->orderBy('venue_tbl.position', 'asc');

        if (!empty($num_page)) {
            $builder->limit((int)$num_page, (int)$cnt); // ✅ Convert to integer before using
        }

        // Execute Query
        $query = $builder->get();

        // ✅ Handle Different Actions
        if ($action == 'count') {
            return $query->getNumRows();
        } elseif ($action == 'single') {
            return $query->getRowArray();
        } elseif ($action == 'multiple') {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    // public function getData_festival($action = '', $tbl_name = '', $wcon = [], $shortField = '', $num_page = '', $cnt = '')
    // {
    //    // $builder = $this->db->table('festival_tbl');  // ✅ CI4 Query Builder
    //    $builder = $this->db->table($tbl_name);
    //     // ✅ Select fields with alias
    //     $builder->select("festival_tbl.*, venue_tbl.venue_title");
    //     $builder->join('venue_tbl', 'venue_tbl.id = festival_tbl.venue_id', 'left');

    //     // ✅ Request Service (Avoid Direct `$_GET`)
    //     $request = service('request');

    //     if ($request->getGet('name')) {
    //         $builder->like('festival_tbl.festival_name', $request->getGet('name'));
    //     }

    //     if ($request->getGet('location_name')) {
    //         $builder->like('festival_tbl.location_name', $request->getGet('location_name'));
    //     }

    //     if ($request->getGet('artist_id')) {
    //         $builder->where('festival_tbl.artist_id', $request->getGet('artist_id'));
    //     }

    //     if ($request->getGet('start_date')) {
    //         $builder->where('festival_tbl.start_date >=', $request->getGet('start_date'));
    //     }

    //     if ($request->getGet('end_date')) {
    //         $builder->where('festival_tbl.end_date <=', $request->getGet('end_date'));
    //     }
    //     if (!empty($wcon['where'])) {
    //         if (is_array($wcon['where'])) {
    //             $builder->where($wcon['where']);
    //         } else {
    //             $whereArray = explode('AND', $wcon['where']);
    //             foreach ($whereArray as $condition) {
    //                 $builder->where(trim($condition));
    //             }
    //         }
    //     }

    //       // ✅ Apply LIKE conditions correctly
    //       if (!empty($wcon['like']) && is_array($wcon['like'])) {
    //         $builder->groupStart(); // Start a grouping for OR conditions
    //         foreach ($wcon['like'] as $column => $value) {
    //             $builder->orLike($column, $value);
    //         }
    //         $builder->groupEnd(); // End grouping
    //     }

    //     // Sorting and Pagination
    //     if (!empty($shortField)) {
    //         $builder->orderBy($shortField);
    //     }
    //     //   if (!empty($num_page)) {
    //     // 	  $builder->limit($num_page, $cnt);
    //     //   }
    //     if (!empty($num_page)) {
    //         $num_page = (int) $num_page; // Ensure it's an integer
    //         $cnt = (int) $cnt; // Convert offset to integer
    //         $builder->limit($num_page, $cnt);
    //     }


    //     // Execute query
    //     $query = $builder->get();
    //    // echo"<pre>";print_r($query);die;
    //     // Handle different return types
    //     if ($action == 'count') {
    //         return $query->getNumRows();
    //     } elseif ($action == 'single') {
    //         return $query->getRowArray();
    //     } elseif ($action == 'multiple') {

    //         return $query->getResultArray();
    //     }

    //     return false;
    //     // ✅ Additional Filters (CI4 Format)
    //     // if (isset($wcon['where']) && !empty($wcon['where'])) {
    //     //     $builder->where($wcon['where']);
    //     // }

    //     // if (isset($wcon['like']) && !empty($wcon['like'])) {
    //     //     foreach ($wcon['like'] as $field => $value) {
    //     //         $builder->like($field, $value);
    //     //     }
    //     // }

    //     // ✅ Sorting
    //     // if (!empty($shortField) && is_string($shortField)) {
    //     //     $builder->orderBy($shortField); // ✅ Correct format
    //     // }

    //     // ✅ Pagination
    //     // if (!empty($num_page)) {
    //     //     $builder->limit((int)$num_page, (int)$cnt); // ✅ Convert to integer before using
    //     // }


    //     // ✅ Execute Query
    //     // $query = $builder->get();

    //     // // ✅ Return Results
    //     // if ($action == 'count') {
    //     //     return $query->getNumRows(); // CI4 Replacement for num_rows()
    //     // } elseif ($action == 'single') {
    //     //     return $query->getRowArray(); // CI4 Replacement for row_array()
    //     // } elseif ($action == 'multiple') {
    //     //     return $query->getResultArray(); // CI4 Replacement for result_array()
    //     // } else {
    //     //     return false;
    //     // }

    // }
    public function getData_festival($action = '', $tbl_name = '', $wcon = [], $shortField = '', $num_page = '', $cnt = '')
    {  // echo"<pre>";print_r($tbl_name);die;
        $builder = $this->db->table($tbl_name);
        $builder->select("ftable.*, venue_tbl.venue_title");
        $builder->join('venue_tbl', 'venue_tbl.id = ftable.venue_id', 'left');
        // ✅ Select fields with alias
        // $builder->select("festival_tbl.*, venue_tbl.venue_title");
        // $builder->join('venue_tbl', 'venue_tbl.id = festival_tbl.venue_id', 'left');
        $request = service('request');

        if ($request->getGet('name')) {
            $builder->like('festival_name', $request->getGet('name'));
        }

        if ($request->getGet('location_name')) {
            $builder->like('location_name', $request->getGet('location_name'));
        }

        if ($request->getGet('artist_id')) {
            $builder->where('artist_id', $request->getGet('artist_id'));
        }

        if ($request->getGet('start_date')) {
            $builder->where('start_date >=', $request->getGet('start_date'));
        }

        if ($request->getGet('end_date')) {
            $builder->where('end_date <=', $request->getGet('end_date'));
        }
        // ✅ Apply WHERE conditions from filters
        if (!empty($wcon['where']) && is_array($wcon['where'])) {
            $builder->where($wcon['where']);
        }

        // ✅ Apply LIKE conditions correctly
        if (!empty($wcon['like']) && is_array($wcon['like'])) {
            $builder->groupStart(); // Start a grouping for OR conditions
            foreach ($wcon['like'] as $column => $value) {
                $builder->orLike($column, $value);
            }
            $builder->groupEnd(); // End grouping
        }

        // ✅ Ensure `$shortField` is a string before applying `orderBy()`
        if (!empty($shortField) && is_string($shortField)) {
            $builder->orderBy($shortField);
        }

        // ✅ Pagination
        if (!empty($num_page)) {
            $num_page = (int) $num_page; // Ensure it's an integer
            $cnt = (int) $cnt; // Convert offset to integer
            $builder->limit($num_page, $cnt);
        }

        // ✅ Execute query
        $query = $builder->get();

        // ✅ Handle different return types
        if ($action == 'count') {
            return $query->getNumRows();
        } elseif ($action == 'single') {
            return $query->getRowArray();
        } elseif ($action == 'multiple') {
            return $query->getResultArray();
        }

        return false;
    }

    public function totalEventsBySearch($action = '', $tbl_name = '', $whereCon = [], $shortField = '')
    {
        log_message('info', "totalEventsBySearch");
        log_message('debug', "action: $action tbl_name: $tbl_name");
        $builder = $this->db->table($tbl_name);

        $builder->select('COUNT(*) as count');
        $builder->join('event_location_tbl', 'event_location_tbl.id = ftable.save_location_id', 'left');

        $request = service('request');

        if ($request->getGet('event_name')) {
            $builder->like('ftable.event_title', $request->getGet('event_name'));
        }

        if ($request->getGet('location_name')) {
            $builder->like('ftable.location_name', $request->getGet('location_name'));
        }

        if ($request->getGet('venue_id')) {
            $builder->where('ftable.venue_id', $request->getGet('venue_id'));
        }

        if ($request->getGet('artist_id')) {
            $builder->where('ftable.artist_id', $request->getGet('artist_id'));
        }

        if ($request->getGet('state')) {
            $builder->where('event_location_tbl.state', $request->getGet('state'));
        }

        if ($request->getGet('city')) {
            $builder->where('event_location_tbl.city', $request->getGet('city'));
        }

        if ($request->getGet('start_date')) {
            $builder->where('ftable.start_date >=', $request->getGet('start_date'));
        }

        if ($request->getGet('end_date')) {
            $builder->where('ftable.end_date <=', $request->getGet('end_date'));
        }

        // if ($request->getGet('sort_by') && $request->getGet('order')) {
        //     $builder->orderBy($request->getGet('sort_by'), $request->getGet('order'));
        // } else {
        //     $builder->orderBy('ftable.event_id', 'DESC');
        // }

        $query = $builder->get();

        if (!$query) {
            log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
            return false;
        }

        if ($action == 'count') {
            return $query->getNumRows();
        } elseif ($action == 'single') {
            return ($query->getNumRows() > 0) ? $query->getResultArray() : false;
        } elseif ($action == 'multiple') {
            return ($query->getNumRows() > 0) ? $query->getResultArray() : false;
        } else {
            return false;
        }
    }


    public function totalFestivals()
    {
        $query =  $this->db->table('festival_tbl')->select('festival_id')->get();
        $result = $query->getResult();

        if ($result) :
            return $result;
        else :
            return false;
        endif;
    }

    public function totalTrashFestival()
    {
        $query = $this->db->table('festival_tbl')
            ->select('festival_id')
            ->where('is_active', '2')
            ->get();

        if ($query->getNumRows() > 0) : // ✅ Use `getNumRows()` in CI4
            return $query->getResult();
        else :
            return false;
        endif;
    }
    public function totalPublisFestival()
    {
        $data = $this->db->table('festival_tbl')
            ->select('festival_id')
            ->where('is_active', '1')
            ->get()
            ->getResult();

        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }
    }
    public function getData_submittedEvent($action = '', $tbl_name = '', $wcon = '', $shortField = '', $num_page = '', $cnt = '')
    {
        //echo"herererere";
        //echo"<pre>";print_r($tbl_name);die;
        $builder = $this->db->table($tbl_name);
        $builder->select('ftable.*');
        $builder->where('added_by', 'user');
        $builder->where('is_imported', '0');

        if (!empty($_GET['event_name'])) {
            $builder->like('ftable.event_title', $_GET['event_name']);
        }

        if (!empty($_GET['name'])) {
            $builder->like('ftable.festival_name', $_GET['name']);
        }

        if (!empty($_GET['location_name'])) {
            $builder->like('ftable.location_name', $_GET['location_name']);
        }

        if (!empty($_GET['venue_id'])) {
            $builder->where('venue_id', $_GET['venue_id']);
        }

        if (!empty($_GET['jazz_types_id'])) {
            $builder->where('jazz_types_id', $_GET['jazz_types_id']);
        }

        if (!empty($_GET['city'])) {
            $builder->where('save_location_id', $_GET['city']);
        }

        if (!empty($_GET['state'])) {
            $builder->where('save_location_id', $_GET['state']);
        }

        if (!empty($_GET['artist_id'])) {
            $builder->where('artist_id', $_GET['artist_id']);
        }

        if (!empty($_GET['start_date'])) {
            $builder->where('start_date >=', $_GET['start_date']);
        }

        if (!empty($_GET['end_date'])) {
            $builder->where('end_date <=', $_GET['end_date']);
        }

        // if (isset($wcon['where_gte']) && $wcon['where_gte']) :
        // 	foreach ($wcon['where_gte'] as $whereGteData) :
        // 		$this->mongo_db->where_gte($whereGteData[0], $whereGteData[1]);
        // 	endforeach;
        // endif;

        if (isset($wcon['where_gte']) && !empty($wcon['where_gte']) && is_array($wcon['where_gte'])) :
            foreach ($wcon['where_gte'] as $whereGteData) :
                if ($this->isValidField($whereGteData[0])) {
                    if ($this->isValidField($whereGteData[0])) {
                        $builder->where("{$whereGteData[0]} >=", $whereGteData[1]);
                    }
                }
            endforeach;
        endif;

        if (!empty($wcon['where'])) {
            $builder->where($wcon['where']);
        }

        if (!empty($wcon['like'])) {
            $builder->like($wcon['like']);
        }

        if (!empty($shortField)) {
            $builder->orderBy($shortField);
        }


        if (!empty($num_page)) {
            $builder->limit((int)$num_page, (int)$cnt); // ✅ Convert to integer before using
        }



        $query = $builder->get();

        // ✅ Handle response based on `$action`
        if ($action == 'count') :
            return $query->getNumRows(); // ✅ `getNumRows()` replaces `num_rows()` in CI4
        elseif ($action == 'single') :
            return ($query->getNumRows() > 0) ? $query->getRowArray() : false; // ✅ `getRowArray()` replaces `row_array()`
        elseif ($action == 'multiple') :
            return ($query->getNumRows() > 0) ? $query->getResultArray() : false; // ✅ `getResultArray()` replaces `result_array()`
        else :
            return false;
        endif;
    }
    public function getData_api_import_event($action = '', $tbl_name = '', $whereCon = [], $shortField = '', $num_page = '', $cnt = '')
    {
        $builder = $this->db->table($tbl_name);
        $builder->select('ftable.*, event_location_tbl.state, event_location_tbl.city');
        $builder->join('event_location_tbl', 'event_location_tbl.id = ftable.save_location_id', 'left');

        $builder->where('ftable.event_source IS NOT NULL', null, false);
        $builder->where('ftable.is_imported', true);

        // Handling GET parameters securely in CI4
        $request = service('request');

        if ($request->getGet('event_name')) {
            $builder->like('ftable.event_title', $request->getGet('event_name'));
        }

        if ($request->getGet('location_name')) {
            $builder->like('ftable.location_name', $request->getGet('location_name'));
        }

        if ($request->getGet('venue_id')) {
            $builder->where('ftable.venue_id', $request->getGet('venue_id'));
        }

        if ($request->getGet('artist_id')) {
            $builder->where('ftable.artist_id', $request->getGet('artist_id'));
        }

        if ($request->getGet('state')) {
            $builder->where('event_location_tbl.state', $request->getGet('state'));
        }

        if ($request->getGet('city')) {
            $builder->where('event_location_tbl.city', $request->getGet('city'));
        }

        if ($request->getGet('start_date')) {
            $builder->where('ftable.start_date >=', $request->getGet('start_date'));
        }

        if ($request->getGet('end_date')) {
            $builder->where('ftable.end_date <=', $request->getGet('end_date'));
        }

        if ($request->getGet('sort_by') && $request->getGet('order')) {
            $builder->orderBy($request->getGet('sort_by'), $request->getGet('order'));
        } else {
            $builder->orderBy('ftable.event_id', 'DESC');
        }


        if (!empty($num_page)) {
            $builder->limit((int)$num_page, (int)$cnt); // ✅ Convert to integer before using
        }

        $query = $builder->get();

        if (!$query) {
            log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
            return false;
        }


        // Debugging: Uncomment below to see the generated query
        // echo $this->db->getLastQuery(); exit;

        if ($action == 'count') {
            return $query->getNumRows();
        } elseif ($action == 'single') {
            return ($query->getNumRows() > 0) ? $query->getRowArray() : false;
        } elseif ($action == 'multiple') {
            return ($query->getNumRows() > 0) ? $query->getResultArray() : false;
        } else {
            return false;
        }
    }

    public function getData_archive($action = '', $tbl_name = '', $whereCon = [], $shortField = '', $num_page = '', $cnt = '')
    {
        $builder = $this->db->table($tbl_name);
        $builder->select('ftable.*');

        $request = service('request');

        if ($request->getGet('event_name')) {
            $builder->like('ftable.event_title', $request->getGet('event_name'));
        }

        if ($request->getGet('location_name')) {
            $builder->like('ftable.location_name', $request->getGet('location_name'));
        }

        if ($request->getGet('venue_id')) {
            $builder->where('ftable.venue_id', $request->getGet('venue_id'));
        }

        if ($request->getGet('artist_id')) {
            $builder->where('ftable.artist_id', $request->getGet('artist_id'));
        }

        if ($request->getGet('state')) {
            $builder->where('event_location_tbl.state', $request->getGet('state'));
        }

        if ($request->getGet('city')) {
            $builder->where('event_location_tbl.city', $request->getGet('city'));
        }

        if ($request->getGet('start_date')) {
            $builder->where('ftable.start_date >=', $request->getGet('start_date'));
        }

        if ($request->getGet('end_date')) {
            $builder->where('ftable.end_date <=', $request->getGet('end_date'));
        }


        if (!empty($num_page)) {
            $builder->limit((int)$num_page, (int)$cnt); // ✅ Convert to integer before using
        }

        $query = $builder->get();

        if ($action == 'count') {
            return $query->getNumRows();
        } elseif ($action == 'single') {
            return ($query->getNumRows() > 0) ? $query->getRowArray() : false;
        } elseif ($action == 'multiple') {
            return ($query->getNumRows() > 0) ? $query->getResultArray() : false;
        } else {
            return false;
        }
    }


    public function getduplicates($action = '', $tbl_name = '', $whereCon = [], $shortField = '', $num_page = '', $cnt = '')
    {

        $builder = $this->db->table('event_tbl');

        // Subquery to find duplicate event_title, location_name, and start_date
        $subquery = $this->db->table('event_tbl')
            ->select('event_title, location_name, start_date, event_start_time')
            ->where('is_active', '1')
            ->groupBy('event_title, location_name, start_date, event_start_time')
            ->having('COUNT(*) > 1')
            ->getCompiledSelect();

        // Main Query: Join with the subquery to fetch full records of duplicates
        $builder->select('event_tbl.event_id, event_tbl.event_title, event_tbl.location_name, event_tbl.location_address, event_tbl.start_date, event_tbl.event_start_time, event_tbl.event_end_time');
        $builder->where('event_tbl.is_active', '1');
        $builder->join("($subquery) as dupes", 'event_tbl.event_title = dupes.event_title AND event_tbl.location_name = dupes.location_name AND event_tbl.start_date = dupes.start_date', 'inner');
        $builder->orderBy('event_tbl.event_title, event_tbl.location_name, event_tbl.start_date', 'asc');

        $request = service('request'); // CI4 way to access request data

        if ($request->getGet('event_name')) {
            $builder->like("event_tbl.event_title", $request->getGet('event_name')); // ✅ Use actual table name
        }
        if ($request->getGet('start_date')) {
            $builder->where("event_tbl.start_date >=", $request->getGet('start_date')); // ✅ Use actual table name
        }

        if ($request->getGet('end_date')) {
            $builder->where("event_tbl.end_date <=", $request->getGet('end_date')); // ✅ Use actual table name
        }
        if (!empty($num_page)) {
            $builder->limit((int)$num_page, (int)$cnt); // ✅ Convert to integer before using
        }

        $query = $builder->get();

        // Handle Action Results
        if ($action == 'count') {
            return $query->getNumRows();
        } elseif ($action == 'single') {
            return ($query->getNumRows() > 0) ? $query->getRowArray() : false;
        } elseif ($action == 'multiple') {
            return ($query->getNumRows() > 0) ? $query->getResultArray() : false;
        } else {
            return false;
        }
    }


    public function getLocAPIData($action = '', $tbl_name = '', $wcon = '', $num_page = '', $cnt = '')
    {
        log_message("info", "getLocAPIData");
        log_message("debug", "tbl_name: $tbl_name");
        // Validate table name first
        // if (!$this->isValidTable($tbl_name)) {
        //     throw new \InvalidArgumentException('Invalid table name');
        // }

        $builder = $this->db->table($tbl_name);
        $builder->select('ftable.*');

        $today_timestamp = strtotime(date('Y-m-d 00:00:00'));

        // Handling WHERE conditions safely
        if (!empty($wcon['where'])) {
            // Only accept array conditions (parameterized)
            if (is_array($wcon['where'])) {
                // Validate all keys for column names
                foreach (array_keys($wcon['where']) as $field) {
                    if (!$this->isValidField($field)) {
                        throw new \InvalidArgumentException('Invalid field name in where condition');
                    }
                }
                // Safe to use with validated array (CodeIgniter will parameterize values)
                $builder->where($wcon['where']);
            } else {
                // String conditions are risky - reject or parse them

                throw new \InvalidArgumentException('String where conditions are not allowed for security reasons');
            }
        }

        if (!empty($wcon['like'])) {
            $builder->like($wcon['like']);
        }

        $query = $builder->get();

        if (!$query) {
            log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
            return false;
        }

        if ($query->getNumRows() > 0) {
            $results = $query->getResultArray();

            $filteredResults = [];
            $request = service('request');

            $eventName = $request->getGet('event_name') ? trim($request->getGet('event_name')) : null;
            $startDate = $request->getGet('start_date') ? trim($request->getGet('start_date')) : null;
            $endDate = $request->getGet('end_date') ? trim($request->getGet('end_date')) : null;

            foreach ($results as $row) {
                $eventData = json_decode($row['all_data'], true);
                if (!$eventData) {
                    continue; // Skip invalid JSON
                }

                $event_end_date = $eventData['dates']['end']['localDate'] ?? $eventData['dates']['start']['localDate'];
                $event_end_timestamp = strtotime($event_end_date . ' 00:00:00');

                // Skip events that have already ended
                if ($event_end_timestamp < $today_timestamp) {
                    continue;
                }

                // If no filters are applied, add the event
                if (empty($eventName) && empty($startDate) && empty($endDate)) {
                    $filteredResults[] = $row;
                    continue;
                }

                // Conditions for filtering
                $matchEvent = $eventName && isset($eventData['name']) && stripos($eventData['name'], $eventName) !== false;
                $matchStart = $startDate && isset($eventData['dates']['start']['localDate']) && $eventData['dates']['start']['localDate'] == $startDate;
                $matchEnd = $endDate && (
                    (isset($eventData['dates']['end']['localDate']) && $eventData['dates']['end']['localDate'] == $endDate) ||
                    (isset($eventData['dates']['start']['localDate']) && $eventData['dates']['start']['localDate'] == $endDate)
                );

                // Apply filtering based on selected filters
                if ($eventName && $startDate && $endDate) {
                    if ($matchEvent && $matchStart && $matchEnd) {
                        $filteredResults[] = $row;
                    }
                } elseif ($eventName && $startDate) {
                    if ($matchEvent && $matchStart) {
                        $filteredResults[] = $row;
                    }
                } elseif ($eventName && $endDate) {
                    if ($matchEvent && $matchEnd) {
                        $filteredResults[] = $row;
                    }
                } elseif ($startDate && $endDate) {
                    if ($matchStart && $matchEnd) {
                        $filteredResults[] = $row;
                    }
                } elseif ($matchEvent || $matchStart || $matchEnd) {
                    $filteredResults[] = $row;
                }
            }

            $totalRecords = count($filteredResults);
            $num_page = (int) $num_page;
            $cnt = (int) $cnt;
            $paginatedResults = array_slice($filteredResults, $cnt, $num_page);

            return ($action == 'count') ? $totalRecords : $paginatedResults;
        }

        return false;
    }


    public function venue()
    {
        $db = db_connect();
        $builder = $db->table('venue_tbl');
        $builder->select('id, venue_title');
        $builder->where('is_active', '1');

        $query = $builder->get();

        return ($query->getNumRows() > 0) ? $query->getResultArray() : [];
    }


    public function getEventlocationtype()
    {

        $builder = $this->db->table('event_location_type');
        $builder->select('id, name');
        $builder->where('is_active', '1');
        $builder->orderBy('name', 'ASC'); // Order by name in ascending order

        $query = $builder->get();
        return ($query->getNumRows() > 0) ? $query->getResult() : false;
    }

    public function getData_event($action = '', $tbl_name = '', $whereCon = [], $shortField = '', $num_page = '', $cnt = '')
    {
        log_message("info", "getData_event -->");
        log_message('debug', "action: $action");
        log_message("debug", "tbl_name: $tbl_name");

        $builder = $this->db->table($tbl_name);
        $builder->select('ftable.*, event_location_tbl.state, event_location_tbl.city');
        $builder->join('event_location_tbl', 'event_location_tbl.id = ftable.save_location_id', 'left');

        $request = service('request');

        if ($request->getGet('event_name')) {
            $event_name = $this->db->escapeLikeString($request->getGet('event_name'));
            $builder->like('ftable.event_title', $event_name);
        }

        if ($request->getGet('location_name')) {
            $location_name = $this->db->escapeLikeString($request->getGet('location_name'));
            $builder->like('ftable.location_name', $location_name);
        }

        if ($request->getGet('venue_id')) {
            $builder->where('ftable.venue_id', $request->getGet('venue_id'));
        }

        if ($request->getGet('artist_id')) {
            $builder->where('ftable.artist_id', $request->getGet('artist_id'));
        }

        if ($request->getGet('state')) {
            $builder->where('event_location_tbl.state', $request->getGet('state'));
        }

        if ($request->getGet('city')) {
            $builder->where('event_location_tbl.city', $request->getGet('city'));
        }

        if ($request->getGet('start_date')) {
            $builder->where('ftable.start_date >=', $request->getGet('start_date'));
        }

        if ($request->getGet('search_boosted') == 1) {
            $builder->where('ftable.is_boosted', 1);
        }

        if ($request->getGet('search_featured') == 1) {
            $builder->where('ftable.is_featured', 1);
        }

        if ($request->getGet('search_api_data') == 1) {
            $builder->where('ftable.event_source !=', '');
        }

        if ($request->getGet('end_date')) {
            $builder->where('ftable.end_date <=', $request->getGet('end_date'));
            $builder->where('ftable.end_date !=', '');
            $builder->where('ftable.end_date IS NOT NULL', null, false);
        }

        if (!empty($whereCon)) {
            $builder->where($whereCon['where']);
        }

        $active_events = "(((ftable.is_imported=true or ftable.is_active=1 or ftable.is_active=1) AND ftable.event_source!='') 
        OR ((ftable.is_imported=true or ftable.is_active=1 or ftable.is_active=1) AND ftable.added_by='user') 
        OR (ftable.added_by='admin'))";
        $builder->where($active_events);

        if ($shortField) {
            $builder->orderBy($shortField);
        }

        if (!empty($num_page)) {
            $builder->limit((int)$num_page, (int)$cnt); // ✅ Convert to integer before using
        }

        $query = $builder->get();

        if (!$query) {
            log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
            return false;
        }

        if ($action == 'count') {
            return $query->getNumRows();
        } elseif ($action == 'single') {
            return ($query->getNumRows() > 0) ? $query->getRowArray() : false;
        } elseif ($action == 'multiple') {
            return ($query->getNumRows() > 0) ? $query->getResultArray() : false;
        } else {
            return false;
        }
    }

    // Get Active Cities
    public function getCategoryCity()
    {
        $builder = $this->db->table('event_location_tbl');
        $builder->select('city');
        $builder->where("is_active", 1);
        $builder->groupBy('city');
        $builder->orderBy('city', 'asc');

        $query = $builder->get();
        if (!$query) {
            log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
            return false;
        }
        return ($query->getNumRows() > 0) ? $query->getResult() : false;
    }

    // Get Active States
    public function getCategoryState()
    {
        $builder = $this->db->table('event_location_tbl');
        $builder->select('state');
        $builder->where("is_active", "1");
        $builder->groupBy('state');
        $builder->orderBy('state', 'asc');

        $query = $builder->get();
        if (!$query) {
            log_message('error', 'Query failed: ' . print_r($this->db->error(), true));
            return false;
        }
        return ($query->getNumRows() > 0) ? $query->getResult() : false;
    }

    // Get All Comedy and Spoken Word Events
    public function totalComedyandSpokenWord()
    {
        $builder = $this->db->table('comedyandspokenword_tbl');
        $builder->select('event_id');

        $query = $builder->get();
        return ($query->getNumRows() > 0) ? $query->getResult() : false;
    }

    // Get Comedy and Spoken Word Events Marked as Trash
    public function totalTrashComedyandSpokenWord()
    {
        $builder = $this->db->table('comedyandspokenword_tbl');
        $builder->select('event_id');
        $builder->where('is_active', '2');

        $query = $builder->get();
        return ($query->getNumRows() > 0) ? $query->getResult() : false;
    }

    // Get Published Comedy and Spoken Word Events
    public function totalPublishComedyandSpokenWord()
    {
        $builder = $this->db->table('comedyandspokenword_tbl');
        $builder->select('event_id');
        $builder->where('is_active', '1');

        $query = $builder->get();
        return ($query->getNumRows() > 0) ? $query->getResult() : false;
    }
    public function deleteData($tableName = '', $fieldName = '', $fieldValue = '')
    {
        // Validate table name against whitelist
        if (!$this->isValidTable($tableName)) {
            throw new \InvalidArgumentException('Invalid table name');
        }

        // Validate field name
        if (!$this->isValidField($fieldName)) {
            throw new \InvalidArgumentException('Invalid field name');
        }

        $builder = $this->db->table($tableName);
        $builder->where([$fieldName => $fieldValue]);
        $builder->delete();

        return ($this->db->affectedRows() > 0); // Returns true if a row was deleted
    }

    /**
     * Validate if a table name is allowed
     * 
     * @param string $tableName The table name to validate
     * @return bool True if valid, false otherwise
     */
    private function isValidTable($tableName)
    {
        // Whitelist of allowed tables
        $allowedTables = [
            'about_team_tbl',
            'about_us_tbl',
            'admin',
            'admin_login_log',
            'admin_module',
            'admin_module_child',
            'admin_module_child_permission',
            'admin_module_permission',
            'advertisement_tbl',
            'advertise_tbl',
            'archived_tbl',
            'artist_tbl',
            'banner_tbl',
            'blog_tbl',
            'comedyandspokenword_tbl',
            'contact_details_tbl',
            'current_issue_tbl',
            'event_jazz_tbl',
            'event_location_tbl',
            'event_location_type',
            'event_tags_tbl',
            'event_tbl',
            'festival_tbl',
            'footer_tbl',
            'get_hh_tbl',
            'home_image',
            'home_slider_image',
            'import_tbl',
            'jazz_types',
            'lineup_tbl',
            'location_tbl',
            'matching_loc_data',
            'module_permission',
            'previous_issues_tbl',
            'report_problem_tbl',
            'role_permission',
            'role_tbl',
            'seo_tbl',
            'setting_tbl',
            'slider_tbl',
            'state_tbl',
            'submit_event_tbl',
            'subscribe_tbl',
            'ticketmaster_event_tbl',
            'user',
            'user_event_jazz_tbl',
            'user_event_tags_tbl',
            'user_event_tbl',
            'venue_tbl'
        ];

        // var_dump($tableName);
        // var_dump(in_array($tableName, $allowedTables, true));
        // die;

        return in_array($tableName, $allowedTables, true);
    }
    public function checkEvent($save_location_id, $start_date, $end_date, $event_start_time, $event_end_time)
    {

        $builder = $this->db->table('event_tbl'); // Define the table

        $builder->select('*');
        $builder->where('save_location_id', $save_location_id);

        // Date range conditions (safe, parameterized)
        $builder->groupStart()
            ->groupStart()
            ->where('start_date <=', $start_date)
            ->where('end_date >=', $start_date)
            ->groupEnd()
            ->orGroupStart()
            ->where('start_date <=', $end_date)
            ->where('end_date >=', $end_date)
            ->groupEnd()
            ->orGroupStart()
            ->where('start_date >=', $start_date)
            ->where('end_date <=', $end_date)
            ->groupEnd()
            ->groupEnd();

        // Time range conditions (safe, parameterized)
        $builder->groupStart()
            ->groupStart()
            ->where('event_start_time <', $event_end_time)
            ->where('event_end_time >', $event_start_time)
            ->groupEnd()
            ->orGroupStart()
            ->where('event_start_time >=', $event_start_time)
            ->where('event_end_time <=', $event_end_time)
            ->groupEnd()
            ->groupEnd();

        $query = $builder->get();
        $result = $query->getResultArray();
        return $result ?: false;
    }


    public function editMultipleDataByMultipleCondition(string $tableName, array $param, array $whereCondition): bool
    {

        $builder = $this->db->table($tableName);

        $builder->where($whereCondition);
        $builder->update($param);

        return ($this->db->affectedRows() > 0);
    }

    function deleteEventTags($eventId)
    {
        $builder = $this->db->table('event_tags_tbl');
        $builder->where('event_id', $eventId);
        $builder->delete();

        return ($this->db->affectedRows() > 0);
    }

    function getAllData()
    {

        $builder = $this->db->table('role_tbl');
        $builder->select('*');
        $builder->where('is_active', 1);
        $query = $builder->get();

        return $query->getResultArray();
    }
    public function getEventData($eventId)
    {
        $eventId = (int) $eventId; // Ensure it's an integer

        $builder = $this->db->table('event_tbl');
        $query = $builder->select('*')
            ->where('event_id', $eventId)
            ->get();

        return $query->getRowArray() ?: false;
    }
    function DuplicateData($tableName = '', $data = array())
    {

        $builder = $this->db->table($tableName);
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function getDataByMultipleParticularField(string $table, string $field, $value)
    {
        if (!$this->isValidField($field)) {
            throw new \InvalidArgumentException('Invalid field name');
        }

        $builder = $this->db->table($table);

        // Apply the WHERE condition
        $builder->where($field, $value);

        // Execute the query
        $query = $builder->get();

        // Return the result as an array of objects
        return $query->getResult();
    }
    function deleteAllLineup($festivalId)
    {
        $builder = $this->db->table('lineup_tbl');
        $builder->where('festival_id', $festivalId)->delete();
        return true;
    }
    function deleteAllPermissions($roleId)
    {
        $builder = $this->db->table('role_permission');
        $builder->where('role_id', $roleId)->delete();
        return true;
    }


    function artistData($id)
    {
        $builder = $this->db->table('artist_tbl')
            ->select('artist_name')
            ->where('id', $id)
            ->get();

        // Fetch a single row as an object
        $result = $builder->getRow();

        return $result ? $result->artist_name : null;
    }

    public function checkFestival($festival_name, $start_date, $end_date)
    {
        $builder = $this->db->table('festival_tbl');
        $builder->where('festival_name', $festival_name);

        // Properly use Query Builder for complex WHERE conditions
        $builder->groupStart()
            ->where('start_date <=', $start_date)
            ->where('end_date >=', $start_date)
            ->orGroupStart()
            ->where('start_date <=', $end_date)
            ->where('end_date >=', $end_date)
            ->groupEnd()
            ->orGroupStart()
            ->where('start_date >=', $start_date)
            ->where('end_date <=', $end_date)
            ->groupEnd()
            ->groupEnd();

        $query = $builder->get();
        return $query->getResultArray(); // Fetch results as an array
    }

    public function getDataByCondition($table, $conditions)
    {
        $builder = $this->db->table($table);
        $builder->where($conditions);
        $query = $builder->get();
        $result = $query->getResultArray();

        return $result ?: false;
    }
    public function getUpdateOldEvents($thirtyDaysAgo, $currentDate)
    {
        log_message("info", "getupdate old events");
        log_message("debug", "thirtyDaysAgo: $thirtyDaysAgo");
        log_message("debug", "currentDate: $currentDate");

        // Validate inputs (optional but recommended)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $thirtyDaysAgo)) {
            throw new \InvalidArgumentException('Invalid date format for thirtyDaysAgo');
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $currentDate)) {
            throw new \InvalidArgumentException('Invalid date format for currentDate');
        }

        // Start transaction
        $this->db->transStart();

        // Use query builder's structured methods for complex conditions instead of raw strings
        $builder = $this->db->table('event_tbl');
        $builder->groupStart()
            ->groupStart()
            ->where('end_date !=', '')
            ->where('end_date <=', $thirtyDaysAgo)
            ->groupEnd()
            ->orGroupStart()
            ->where('end_date', '')
            ->where('start_date <=', $thirtyDaysAgo)
            ->groupEnd()
            ->groupEnd()
            ->where('is_archive !=', 1);

        $builder->update(['is_archive' => 1]);

        // Complete transaction
        $this->db->transComplete();

        // Start transaction for moving archived events
        $this->db->transStart();


        $chunkSize = 1000;
        $lastId = 0;

        do {
            // $archivedEvents = $this->db->table('event_tbl')->where('is_archive', 1)->get()->getResultArray();
            $query = $this->db->table('event_tbl')
                ->where('is_archive', 1)
                ->where('event_id >', $lastId)
                ->orderBy('event_id', 'ASC')
                ->limit($chunkSize)
                ->get();

            if (!$query) {
                log_message("error", "Query failed: " . $this->db->getLastQuery());
                return [];
            }

            $archivedEvents = $query->getResultArray();

            if (empty($archivedEvents)) {
                break;
            }

            foreach ($archivedEvents as &$event) {
                // Convert is_archive from int to bool
                $event['is_archive'] = ($event['is_archive'] == 1) ? true : false;

                // Convert start_date and end_date from varchar to date (if valid format)
                $event['start_date'] = date('Y-m-d', strtotime($event['start_date']));
                $event['end_date'] = date('Y-m-d', strtotime($event['end_date']));

                // Convert boolean to varchar(1) for is_boosted, is_featured
                $event['is_boosted'] = $event['is_boosted'] ? '1' : '0';
                $event['is_featured'] = $event['is_featured'] ? '1' : '0';

                // Ensure all other necessary fields are in place
                if (empty($event['created_at'])) {
                    $event['created_at'] = date('Y-m-d H:i:s'); // Use current timestamp if missing
                }
                if (empty($event['updated_at'])) {
                    $event['updated_at'] = date('Y-m-d H:i:s');
                }
            }
            unset($event);
            // process events...

            $resul = $this->archiveddb->table('archived_tbl_1')->insertBatch($archivedEvents);

            if (!$resul) {
                log_message("error", "Insert failed: " . $this->archiveddb->getLastQuery());
            }
            $lastId = end($archivedEvents)['event_id']; // move cursor forward
            log_message("info", "Processed up to event_id: $lastId Total: " . count($archivedEvents) . " records");
            // break;
        } while (true);

        // print_r($archivedEvents);
        // die;
        // if (!empty($archivedEvents)) {
        //     // Transform the $archivedEvents to match the archived_tbl structure
        //     foreach ($archivedEvents as &$event) {
        //         // Convert is_archive from int to bool
        //         $event['is_archive'] = ($event['is_archive'] == 1) ? true : false;

        //         // Convert start_date and end_date from varchar to date (if valid format)
        //         $event['start_date'] = date('Y-m-d', strtotime($event['start_date']));
        //         $event['end_date'] = date('Y-m-d', strtotime($event['end_date']));

        //         // Convert boolean to varchar(1) for is_boosted, is_featured
        //         $event['is_boosted'] = $event['is_boosted'] ? '1' : '0';
        //         $event['is_featured'] = $event['is_featured'] ? '1' : '0';

        //         // Ensure all other necessary fields are in place
        //         if (empty($event['created_at'])) {
        //             $event['created_at'] = date('Y-m-d H:i:s'); // Use current timestamp if missing
        //         }
        //         if (empty($event['updated_at'])) {
        //             $event['updated_at'] = date('Y-m-d H:i:s');
        //         }
        //     }
        //     unset($event); // Break reference to the last element
        //     // print_r($archivedEvents);
        //     // die;
        //     // Now insert into the archived_tbl
        //     $this->archiveddb->table('archived_tbl')->insertBatch($archivedEvents);

        //     // $this->archiveddb->table('archived_tbl')->insertBatch($archivedEvents);
        //     // echo $this->archiveddb->getLastQuery(); // Logs the query
        //     // die; // Stops execution here to inspect the output
        // }

        $this->db->transComplete();

        // Step 3: Delete archived events from original table
        $this->db->transStart();
        $this->db->table('event_tbl')->where('is_archive', 1)->delete();
        $this->db->transComplete();
        log_message("info", "getupdate old events <--");
    }

    public function markEventsFestsInactive($oneDaysAgo, $currentDate)
    {
        $this->db->transStart();

        // Use Query Builder for safe conditions
        $this->db->table('event_tbl')
            ->groupStart()
            ->groupStart()
            ->where('end_date !=', '')
            ->where('end_date <=', $oneDaysAgo)
            ->groupEnd()
            ->orGroupStart()
            ->where('end_date', '')
            ->where('start_date <=', $oneDaysAgo)
            ->groupEnd()
            ->groupEnd()
            ->where('is_active !=', 0)
            ->update(['is_active' => 0]);

        $this->db->transComplete();

        $this->db->transStart();

        $this->db->table('festival_tbl')
            ->groupStart()
            ->groupStart()
            ->where('end_date !=', '')
            ->where('end_date <=', $oneDaysAgo)
            ->groupEnd()
            ->orGroupStart()
            ->where('end_date', '')
            ->where('start_date <=', $oneDaysAgo)
            ->groupEnd()
            ->groupEnd()
            ->where('is_active !=', 0)
            ->update(['is_active' => 0]);

        $this->db->transComplete();
    }

    public function executeCustomQuery($query, $params = [])
    {
        $result = $this->db->query($query, $params);

        if ($result) {
            return $result->getResultArray();
        }

        $error_message = $this->db->error();
        log_message('error', 'Database Error: ' . $error_message['message']);
        return false;
    }

    public function totalArtist()
    {
        $builder = $this->db->table('lineup_tbl')->select('lineup_id');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function getDuplicateEventLocations($action = '', $tbl_name = '', $whereCon = [], $shortField = '', $num_page = '', $cnt = '')
    {
        $builder = $this->db->table('event_location_tbl el');

        // 🔹 Step 1: Subquery for duplicate venue_id + location_name
        $subquery = $this->db->table('event_location_tbl')
            ->select('venue_id, location_name')
            ->where('location_name !=', '')
            ->groupBy(['venue_id', 'location_name'])
            ->having('COUNT(*) >', 1)
            ->getCompiledSelect();

        // 🔹 Step 2: PostgreSQL-compatible join using ILIKE + concatenation (||)
        $joinCondition = "el.venue_id = dupes.venue_id AND LOWER(el.location_name) LIKE CONCAT('%', LOWER(dupes.location_name), '%')";

        $builder->select('
                el.id,
                el.venue_id,
                el.location_name,
                el.location_address,
                el.state,
                el.county,
                el.is_active,
                el.location_source
            ')
            ->join("($subquery) AS dupes", $joinCondition, 'inner')
            ->orderBy('el.location_name', 'ASC');

        // 🔹 Step 3: Dynamic filters from controller ($whereCon)
        if (!empty($whereCon['like'])) {
            $builder->groupStart();
            foreach ($whereCon['like'] as $like) {
                foreach ($like as $field => $value) {
                    $shortField = explode('.', $field);
                    $shortField = end($shortField);
                    if (!isset($this->allowedFieldsMap[$shortField])) {
                        log_message('warning', 'Invalid LIKE field rejected', ['field' => $field]);
                        continue;
                    }

                    $builder->orLike($this->allowedFieldsMap[$shortField], $value);
                }
            }
            $builder->groupEnd();
        }


        if (!empty($whereCon['where'])) {
            $builder->groupStart(); // wrap all WHERE conditions
            foreach ($whereCon['where'] as $field => $value) {
                $shortField = explode('.', $field);
                $shortField = end($shortField);
                if (!isset($this->allowedFieldsMap[$shortField])) {
                    log_message('warning', 'Invalid WHERE field rejected', ['field' => $field]);
                    continue;
                }
                $builder->where($this->allowedFieldsMap[$shortField], $value);
            }
            $builder->groupEnd();
        }

        // 🔹 Step 4: Sorting
        if (!empty($shortField)) {
            $sortParts = explode(' ', trim($shortField));
            $column = $sortParts[0] ?? '';
            $direction = strtoupper($sortParts[1] ?? 'ASC');
        
            if (isset($this->allowedFieldsMap[$column]) && in_array($direction, ['ASC', 'DESC'], true)) {
                $builder->orderBy($this->allowedFieldsMap[$column], $direction);
            }
        }

        // 🔹 Step 5: Pagination
        if ($num_page > 0) {
            $builder->limit($num_page, $cnt);
        }

        // 🔹 Step 6: Execute query safely
        $query = $builder->get();

        if ($query === false) {
            log_message(
                'error',
                'getDuplicateEventLocations() failed SQL',
                ['query' => (string) $this->db->getLastQuery()]
            );
            return ($action === 'count') ? 0 : [];
        }


        // ✅ Step 6: Return Data
        if ($action === 'count') {
            return $query->getNumRows();
        } elseif ($action === 'single') {
            return ($query->getNumRows() > 0) ? $query->getRowArray() : false;
        } elseif ($action === 'multiple') {
            return ($query->getNumRows() > 0) ? $query->getResultArray() : false;
        }

        return false;
    }
}
