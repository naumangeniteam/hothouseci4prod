<?php
// if (!defined('BASEPATH')) exit('No direct script access allowed');
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Encryption\Encryption;
class AdminModel extends Model
{
	protected $table = 'admin'; // Define table
    protected $primaryKey = 'id'; // Define primary key (update if different)
    protected $allowedFields = ['admin_email', 'password', 'name']; // Specify updatable fields
    
	protected $db;
	protected $encrypter;
	protected $session;
	protected $router;

	public function __construct()
	{
		$this->router = service('router');
		$this->db = \Config\Database::connect();
		$this->encrypter = \Config\Services::encrypter();
		$this->session = session();
	}
	/* * *********************************************************************
	 * * Function name : Authenticate
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for imRD Admin Login Page
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function Authenticate($userEmail = '')
	{
		$query = $this->db->table('admin')
			->select('*')
			->where('admin_email', $userEmail)
			->get();

		if ($query->getNumRows() > 0) {
			return $query->getRowArray();
		} else {
			return false;
		}


		// return $this->where('admin_email', $userEmail)->first() ?: false;
	}
	
	/* * *********************************************************************
	 * * Function name : encriptPassword
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for encript_password
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function encriptPassword($password)
	{
		return base64_encode($this->encrypter->encrypt($password));
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : decryptsPassword
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for encript_password
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function decryptsPassword($password)
	{
		
		return $this->encrypter->decrypt(base64_decode($password));
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : authCheck
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for auth Check
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function authCheck($showType = '')
	{
		 helper('cookie'); // Move this to the top so it's available throughout the method

    if (empty($this->session->get('ILCADM_ADMIN_ID'))) {
        // Use set_cookie with HttpOnly flag
        set_cookie([
            'name'     => 'ILCADM_ADMIN_REFERENCE_PAGES',
            'value'    => uri_string(),
            'expire'   => 60 * 60 * 24 * 5, // 5 days
            'path'     => '/',
            'httponly' => true,
            'secure'   => ENVIRONMENT === 'production'
        ]);
			
			return redirect()->to(getCurrentBasePath() . '/hhjsitemgmt/logout')->send();
		} else {
			
			$db = \Config\Database::connect();

			// Define the table before querying
			$builder = $db->table('admin_login_log');
			
			$builder->select('*');
			$builder->where('admin_id', $this->session->get('ILCADM_ADMIN_ID'));
			$builder->where('login_status', 'Login');

			// $builder->where('admin_token', $_COOKIE['ILCADM_ADMIN_LOGIN_TOKEN'] ?? '');
			
			$token = get_cookie('ILCADM_ADMIN_LOGIN_TOKEN') ?? '';
			$builder->where('admin_token', $token);
			
			$query = $builder->get();
			$result = $query->getRowArray();
			if ($result) {
				if ($showType == '') {
					return true;
				} elseif ($this->checkPermission($showType)) {
					return true;
				} else {
					$this->session->setFlashdata('alert_warning', lang('statictext_lang.accessdenied'));
					return redirect()->to($this->session->get('ILCADM_ADMIN_CURRENT_PATH') . 'dashboard');
				}
			} else {
				 $this->session->setFlashdata('alert_warning', lang('statictext_lang.loginanothersystem'));
            
				// Use set_cookie with HttpOnly flag here too
				set_cookie([
					'name'     => 'ILCADM_ADMIN_REFERENCE_PAGES',
					'value'    => uri_string(),
					'expire'   => 60 * 60 * 24 * 5, // 5 days
					'path'     => '/',
					'httponly' => true,
					'secure'   => ENVIRONMENT === 'production'
				]);
				return redirect()->to(getCurrentBasePath() . '/hhjsitemgmt/logout');
			}
		}
	}
	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : checkPermission
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for check Permission
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function checkPermission($showType = '')
	{
		$returnType 			=	0;
		if ($this->session->get('ILCADM_ADMIN_TYPE') == 'Super Admin') :
			$returnType 		=	1;
		elseif ($this->session->get('ILCADM_ADMIN_TYPE') == 'Sub Admin') :
			$this->db->select($showType);
			$this->db->from('admin_module_permission');
			$this->db->where('module_name', $this->router->fetch_class());
			$this->db->where('admin_id', $this->session->get('ILCADM_ADMIN_ID'));
			$this->db->where("child_data = 'N'");
			$query	=	$this->db->get();
			if ($query->num_rows() > 0) :
				$mdata				=	$query->row_array();
				if ($mdata[$showType] == 'Y') :
					$returnType 	=	1;
				endif;
			else :
				$this->db->select($showType);
				$this->db->from('admin_module_child_permission');
				$this->db->where('module_name', $this->router->fetch_class());
				$this->db->where('admin_id', $this->session->get('ILCADM_ADMIN_ID'));
				$cquery	=	$this->db->get();
				if ($cquery->num_rows() > 0) :
					$cmdata			=	$cquery->row_array();
					if ($cmdata[$showType] == 'Y') :
						$returnType =	1;
					endif;
				endif;
			endif;
		endif;
		return $returnType == 1 ? true : false;
	}	// END OF FUNCTION

	/***********************************************************************
	 ** Function name: getPermissionType
	 ** Developed By: Manoj Kumar
	 ** Purpose: This function used for get permission type
	 ** Date : 23 JUNE 2022
	 ************************************************************************/
	public function getPermissionType(&$data)
{
    $adminType = $this->session->get('ILCADM_ADMIN_TYPE');
    $adminId = $this->session->get('ILCADM_ADMIN_ID');
    $moduleName = $this->router->controllerName();

    if ($adminType == 'Super Admin') {
        $data['view_data'] = 'Y';
        $data['add_data'] = 'Y';
        $data['edit_data'] = 'Y';
        $data['delete_data'] = 'Y';
    } elseif ($adminType == 'Sub Admin') {
        $data['view_data'] = 'N';
        $data['add_data'] = 'N';
        $data['edit_data'] = 'N';
        $data['delete_data'] = 'N';

        $builder = $db->table('admin_module_permission');
        $builder->select('view_data, add_data, edit_data, delete_data');
        $builder->where('module_name', $moduleName);
        $builder->where('admin_id', $adminId);
        $builder->where('child_data', 'N');
        
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $mdata = $query->getRowArray();
            $data['view_data'] = $mdata['view_data'];
            $data['add_data'] = $mdata['add_data'];
            $data['edit_data'] = $mdata['edit_data'];
            $data['delete_data'] = $mdata['delete_data'];
        } else {
            $builder = $db->table('admin_module_child_permission');
            $builder->select('view_data, add_data, edit_data, delete_data');
            $builder->where('module_name', $moduleName);
            $builder->where('admin_id', $adminId);

            $cquery = $builder->get();

            if ($cquery->getNumRows() > 0) {
                $cmdata = $cquery->getRowArray();
                $data['view_data'] = $cmdata['view_data'];
                $data['add_data'] = $cmdata['add_data'];
                $data['edit_data'] = $cmdata['edit_data'];
                $data['delete_data'] = $cmdata['delete_data'];
            }
        }
    }
}

	/***********************************************************************
	 ** Function name: getMenuModule
	 ** Developed By: Manoj Kumar
	 ** Purpose: This function used for getMenuModule
	 ** Date : 23 JUNE 2022
	 ************************************************************************/
	public function getMenuModule()
	{
		$returnArray 		=	array();
		if ($this->session->get('ILCADM_ADMIN_TYPE') == 'Super Admin') :
			$query = $this->db->table('admin_module')
            ->select('module_id, module_name, module_display_name, module_icone, child_data')
            ->orderBy('module_orders', 'ASC')
            ->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
		elseif ($this->session->get('ILCADM_ADMIN_TYPE') == 'Sub Admin') :
			$query = $this->db->table('admin_module_permission')
            ->select('module_permission_id, module_id, module_name, module_display_name, module_icone, child_data')
            ->where('admin_id',$this->session->get('ILCADM_ADMIN_ID'))
            ->groupStart()
                ->where('view_data', 'Y')
                ->where('child_data', 'N')
                ->orWhere('view_data', 'N')
                ->orWhere('child_data', 'Y')
            ->groupEnd()
            ->orderBy('module_orders', 'ASC')
            ->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getMenuChildModule  
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This is get menu child module
	 * * Date : 21 DECEMBER 2018
	 * * **********************************************************************/
	function getMenuChildModule($moduleId = '')
	{
	
		if ($this->session->get('ILCADM_ADMIN_TYPE') == 'Super Admin') :
			$query = $this->db->table('admin_module_child')
            ->select('child_module_id, module_name, module_display_name')
            ->where('module_id', $moduleId)
            ->orderBy('module_orders', 'ASC')
            ->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
		elseif ($this->session->get('ILCADM_ADMIN_TYPE') == 'Sub Admin') :
			$query = $this->db->table('admin_module_child_permission')
            ->select('module_child_permission_id, module_name, module_display_name')
            ->where('permission_id', $moduleId)
            ->where('admin_id', $this->session->get('ILCADM_ADMIN_ID'))
            ->where('view_data', 'Y')
            ->orderBy('module_orders', 'ASC')
            ->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	 ** Function name: getModule
	 ** Developed By: Manoj Kumar
	 ** Purpose: This function used for get Module
	 ** Date : 23 JUNE 2022
	 ************************************************************************/
	public function getModule()
	{
		$this->db->select('*');
		$this->db->from('admin_module');
		$this->db->order_by("module_orders ASC");
		$query	=	$this->db->get();
		if ($query->num_rows() > 0) :
			return $query->result_array();
		else :
			return false;
		endif;
	}

	/***********************************************************************
	 ** Function name : getModuleChild
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for get module child
	 ** Date : 23 JUNE 2022
	 ************************************************************************/
	function getModuleChild($miduleId = '')
	{
		$this->db->select('*');
		$this->db->from('admin_module_child');
		$this->db->where('module_id', $miduleId);
		$this->db->order_by("module_orders ASC");
		$query	=	$this->db->get();
		if ($query->num_rows() > 0) :
			return $query->result_array();
		else :
			return false;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	 ** Function name: getModulePermission
	 ** Developed By: Manoj Kumar
	 ** Purpose: This function used for get Module Permission
	 ** Date : 23 JUNE 2022
	 ************************************************************************/
	public function getModulePermission($adminId = '')
	{
		$selecarray				=	array();
		$this->db->select('*');
		$this->db->from('admin_module_permission');
		$this->db->where('admin_id', $adminId);
		$this->db->order_by("module_orders ASC");
		$query	=	$this->db->get();
		if ($query->num_rows() > 0) :
			$data	=	$query->result_array();
			foreach ($data as $info) :
				$selecarray['mainmodule_' . $info['module_id']]							=	'Y';
				if ($info['child_data'] == 'N') :
					if ($info['view_data'] == 'Y') :
						$selecarray['mainmodule_view_data_' . $info['module_id']]			=	'Y';
					endif;
					if ($info['add_data'] == 'Y') :
						$selecarray['mainmodule_add_data_' . $info['module_id']]			=	'Y';
					endif;
					if ($info['edit_data'] == 'Y') :
						$selecarray['mainmodule_edit_data_' . $info['module_id']]			=	'Y';
					endif;
					if ($info['delete_data'] == 'Y') :
						$selecarray['mainmodule_delete_data_' . $info['module_id']]		=	'Y';
					endif;
				else :
					$this->db->select('*');
					$this->db->from('admin_module_child_permission');
					$this->db->where('module_permission_id', $info['module_permission_id']);
					$this->db->where('admin_id', $adminId);
					$this->db->order_by("module_orders ASC");
					$query1	=	$this->db->get();
					if ($query1->num_rows() > 0) :
						$data1	=	$query1->result_array();
						foreach ($data1 as $info1) :
							$selecarray['childmodule_' . $info['module_id'] . '_' . $info1['module_id']]						=	'Y';
							if ($info1['view_data'] == 'Y') :
								$selecarray['childmodule_view_data_' . $info['module_id'] . '_' . $info1['module_id']]		=	'Y';
							endif;
							if ($info1['add_data'] == 'Y') :
								$selecarray['childmodule_add_data_' . $info['module_id'] . '_' . $info1['module_id']]			=	'Y';
							endif;
							if ($info1['edit_data'] == 'Y') :
								$selecarray['childmodule_edit_data_' . $info['module_id'] . '_' . $info1['module_id']]		=	'Y';
							endif;
							if ($info1['delete_data'] == 'Y') :
								$selecarray['childmodule_delete_data_' . $info['module_id'] . '_' . $info1['module_id']]		=	'Y';
							endif;
						endforeach;
					endif;
				endif;
			endforeach;
		endif;
		return $selecarray;
	}

	/* * *********************************************************************
	 * * Function name : checkOTP
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for Admin otp
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function checkOTP($userOtp = '')
	{
		 $query = $this->db->table('admin')
            ->select('*')
            ->where('admin_password_otp', (string)$userOtp)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray(); // Returns OTP row as an array
        } else {
            return false; // Returns false if no match found
        }

	}	// END OF FUNCTION

	/***********************************************************************
	 ** Function name : getDepartment
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for get Department
	 ** Date : 23 JUNE 2022
	 ************************************************************************/
	function getDepartment($departmentId = '')
	{
		$html			=	'<option value="">Select Department</option>';
		$this->db->select('*');
		$this->db->from('admin_department');
		$this->db->where("status = 'A'");
		$this->db->order_by("department_name ASC");
		$query	=	$this->db->get();
		$result = 	$query->result_array();
		if ($result) :
			foreach ($result as $info) :
				if ($info['department_id'] == $departmentId) :  $select = 'selected="selected"';
				else : $select = '';
				endif;
				$html		.=	'<option value="' . $info['department_id'] . '_____' . stripslashes($info['department_name']) . '" ' . $select . '>' . stripslashes($info['department_name']) . '</option>';
			endforeach;
		endif;
		return $html;
	}	// END OF FUNCTION

	/***********************************************************************
	 ** Function name : getDesignation
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for get Designation
	 ** Date : 23 JUNE 2022
	 ************************************************************************/
	function getDesignation($designationId = '')
	{
		$html			=	'<option value="">Select Designation</option>';
		$this->db->select('*');
		$this->db->from('admin_designation');
		$this->db->where("status = 'A'");
		$this->db->order_by("designation_name ASC");
		$query	=	$this->db->get();
		$result = 	$query->result_array();
		if ($result) :
			foreach ($result as $info) :
				if ($info['designation_id'] == $designationId) :  $select = 'selected="selected"';
				else : $select = '';
				endif;
				$html		.=	'<option value="' . $info['designation_id'] . '_____' . stripslashes($info['designation_name']) . '" ' . $select . '>' . stripslashes($info['designation_name']) . '</option>';
			endforeach;
		endif;
		return $html;
	}	// END OF FUNCTION

	/***********************************************************************
	 ** Function name: getTableAllData
	 ** Developed By: Manoj Kumar
	 ** Purpose: This function used for get Module
	 ** Date : 23 JUNE 2022
	 ************************************************************************/
	public function getTableAllData($tableName = '')
	{
		$query = $this->db->query($query);
		$result = $query->result_array();
		if ($result) :
			return $result;
		else :
			return false;
		endif;
	}

	/***********************************************************************
	 ** Function name : deletePermissionData
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for delete permission data
	 ** Date : 25 JUNE 2022
	 ************************************************************************/
	function deletePermissionData($adminId = '')
	{
		$this->db->delete('admin_module_permission', array('admin_id' => $adminId));
		$this->db->delete('admin_module_child_permission', array('admin_id' => $adminId));
		return true;
	}	// END OF FUNCTION

	/***********************************************************************
	 ** Function name : getLowerTyps
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for get Lower Typs
	 ** Date : 25 JUNE 2022
	 ************************************************************************/
	function getLowerTyps($lowertypeid = '')
	{
		$html			=	'<option value="">Select Lower Types</option>';
		$this->db->select('*');
		$this->db->from('types_of_lawyers');
		$this->db->where("status = 'A'");
		$this->db->order_by("lower_type_name ASC");
		$query	=	$this->db->get();
		$result = 	$query->result_array();
		if ($result) :
			foreach ($result as $info) :
				if ($info['lower_type_id'] == $lowertypeid) :  $select = 'selected="selected"';
				else : $select = '';
				endif;
				$html		.=	'<option value="' . $info['lower_type_id'] . '_____' . stripslashes($info['lower_type_name']) . '" ' . $select . '>' . stripslashes($info['lower_type_name']) . '</option>';
			endforeach;
		endif;
		return $html;
	}	// END OF FUNCTION

	/***********************************************************************
	 ** Function name : getState
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for get state
	 ** Date : 25 JUNE 2022
	 ************************************************************************/
	function getState($lowertstateid = '')
	{
		$html			=	'<option value="">Select State</option>';
		$this->db->select('*');
		$this->db->from('state');
		$this->db->where("status = 'A'");
		$this->db->order_by("state_title ASC");
		$query	=	$this->db->get();
		$result = 	$query->result_array();
		if ($result) :
			foreach ($result as $info) :
				if ($info['state_id'] == $lowertstateid) :  $select = 'selected="selected"';
				else : $select = '';
				endif;
				$html		.=	'<option value="' . $info['state_id'] . '" ' . $select . '>' . stripslashes($info['state_title']) . '</option>';
			endforeach;
		endif;
		return $html;
	}	// END OF FUNCTION

	/***********************************************************************
	 ** Function name : getCity
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for get City
	 ** Date : 25 JUNE 2022
	 ************************************************************************/
	function getCity($lowertstateid = '', $lowertcityid = '')
	{
		$html			=	'<option value="">Select City</option>';
		$this->db->select('*');
		$this->db->from('city');
		$this->db->where("state_id", $lowertstateid);
		$this->db->where("status = 'A'");
		$this->db->order_by("city_name ASC");
		$query	=	$this->db->get();
		$result = 	$query->result_array();
		if ($result) :
			foreach ($result as $info) :
				if ($info['city_id'] == $lowertcityid) :  $select = 'selected="selected"';
				else : $select = '';
				endif;
				$html		.=	'<option value="' . $info['city_id'] . '" ' . $select . '>' . stripslashes($info['city_name']) . '</option>';
			endforeach;
		endif;
		return $html;
	}	// END OF FUNCTION

	function getUser()
	{
		$this->db->select('*');
		$this->db->where("role", 2);
		$this->db->from('admin');
		$query	=	$this->db->get();
		$result = 	$query->result_array();
		if ($result) :
			return $result;
		else :
			return false;
		endif;
	}

	function getsubs()
	{
		$builder = $this->db->table('subscribe_tbl');
        $builder->select('*');
        $query = $builder->get();
        $result = $query->getResultArray();
    
        return !empty($result) ? $result : [];
	}

	function getEvents()
	{
		
		$builder = $this->db->table('event_tbl');
        $builder->select('*');
        $builder->where('added_by', 'user');
    
        $query = $builder->get();
        $result = $query->getResultArray();
    
        return !empty($result) ? $result : false;
    }
	
	public function getFilteredEventsForExport($filters = [])
    {
        $builder = $this->db->table('event_tbl as ftable')
            ->select('ftable.*, venue_tbl.venue_title, artist_tbl.artist_name, event_location_tbl.city, event_location_tbl.state')
            ->join('venue_tbl', 'venue_tbl.id = ftable.venue_id', 'left')
            ->join('artist_tbl', 'artist_tbl.id = ftable.artist_id', 'left')
            ->join('event_location_tbl', 'event_location_tbl.id = ftable.save_location_id', 'left');
			
        if (!empty($filters['event_name'])) {
            $builder->like('ftable.event_title', $filters['event_name']);
        }

        if (!empty($filters['location_name'])) {
            $builder->like('ftable.location_name', $filters['location_name']);
        }

        if (!empty($filters['venue_id'])) {
            $builder->where('ftable.venue_id', $filters['venue_id']);
        }

        if (!empty($filters['city'])) {
            $builder->where('ftable.save_location_id', $filters['city']);
        }

        if (!empty($filters['state'])) {
            $builder->where('ftable.save_location_id', $filters['state']);
        }

        if (!empty($filters['start_date'])) {
            $builder->where('ftable.start_date >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $builder->where('ftable.end_date <=', $filters['end_date']);
        }

        if (!empty($filters['artist_id'])) {
            $builder->where('ftable.artist_id', $filters['artist_id']);
        }

		$query = $builder->get();
		return ($query->getNumRows() > 0) ? $query->getResultArray() : [];
    }

	

	public function getManageAdvertisementsForExport($filters = array())
	{
		
		$builder = $this->db->table('advertise_tbl as ftable');
		$builder->select('ftable.*');
	
		// ✅ Apply Filters
		if (!empty($filters['name'])) {
			$builder->like('ftable.name', $filters['name']);
		}
	
		if (!empty($filters['venue_name'])) {
			$builder->like('ftable.venue_name', $filters['venue_name']);
		}
	
		if (!empty($filters['location_name'])) {
			$builder->where('ftable.location_name', $filters['location_name']);
		}
	
		if (!empty($filters['phone_number'])) {
			$builder->where('ftable.phone_number', $filters['phone_number']);
		}
	
		if (!empty($filters['email'])) {
			$builder->where('ftable.email', $filters['email']);
		}
	
		if (!empty($filters['advertising_interest'])) {
			$builder->where('ftable.advertising_interest >=', $filters['advertising_interest']);
		}
	
		if (!empty($filters['inquiry'])) {
			$builder->where('ftable.inquiry <=', $filters['inquiry']);
		}
	
		$query = $builder->get();
	
		return ($query->getNumRows() > 0) ? $query->getResultArray() : array(); // ✅ Use `getNumRows()` in CI4
	}
	
	public function getAllPermissionData($admin_role, $final_url)
	{
		
		$query = $this->db->table('role_permission')
		->select('*')
		->where('role_id', $admin_role)
		->like('permission', $final_url)
		->get();

			$result = $query->getResultArray(); // Equivalent to result_array() in CI3

			return !empty($result) ? $result : false;
	}
	public function getAllPermissionDataFirst($admin_role, $final_url)
	{
		$this->db->select('*');
		$this->db->from('role_permission');
		$this->db->where('role_id', $admin_role);
		$this->db->where('permission', $final_url);
		$query = $this->db->get();

		$result = $query->result_array();
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	public function getPermissionData2($userId, $url)
	{
		$this->db->select('*');
		$this->db->from('module_permission');
		$this->db->where('user_id', $userId);
		$this->db->like('permission', $url);
		$query = $this->db->get();
		// echo "<pre>";print_r($this->db->last_query());die;
		$result = 	$query->result_array();
		if ($result) :
			return $result;
		else :
			return false;
		endif;
	}

	public function getPermissionDataWithChild($userId, $child)
	{
		// echo "<pre>";print_r($child);die;
	}

	function getArtist()
	{
		$this->db->select('*');
		$this->db->from('artist_tbl');
		$query = $this->db->get();
		$result = $query->result_array();
		if ($result) :
			return $result;
		else :
			return false;
		endif;
	}
	public function getEventLocations()
    {
        $builder = $this->db->table('event_location_tbl');
        $builder->select('*');
       // $builder->where('added_by', 'user');
    
        $query = $builder->get();
        $result = $query->getResultArray();
    
        return !empty($result) ? $result : false;
    }
	// function getImportData()
	// {
	// 	$this->db->select('*');
	// 	$this->db->from('import_tbl');
	// 	$this->db->where("is_active  = '1'");
	// 	$query	=	$this->db->get();
	// 	$result = 	$query->result_array();
	// 	if ($result) :
	// 		return $result;
	// 	else :
	// 		return false;
	// 	endif;
	// }

}
