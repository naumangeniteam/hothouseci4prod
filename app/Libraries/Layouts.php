<?php 
// if ( ! defined('BASEPATH')) exit('No direct script access allowed');

namespace App\Libraries;
use CodeIgniter\View\Parser;
use CodeIgniter\Controller;
use Config\Services;
class Layouts extends Controller
{

	private $layout_title = NULL;
	private $layout_description = NULL;
	private $layout_keyword = NULL;
	protected $parser;
    protected $session;
    protected $router;

    public function __construct()
    {
        $this->parser = service('parser');
        $this->session = session();
        $this->router = service('router');
    }

    public function setTitle($title)
    {
        $this->layout_title = $title;
    }

    public function setDescription($description)
    {
        $this->layout_description = $description;
    }

    public function setKeyword($keyword)
    {
        $this->layout_keyword = $keyword;
    }

    
	// public function __construct()
	// {
	// 	return "tada";
	// 	$this->CI = & get_instance();
	// }
	private function getCurrentBasePath()
    {
        return base_url();
    }
	public function admin_view($view_name, $layouts = [], $params = [], $viewtype = '')
    {
        // $parser = Services::parser();
        // $session = Services::session();
		helper('url');

        $request = Services::request();
		$uri = $request->getUri(); 
		$csrf = Services::csrf(); 
        // Parse layouts if provided
        if (is_array($layouts) && count($layouts) >= 1) {
            foreach ($layouts as $layout_key => $layout) {
                $params[$layout_key] = view($layout, $params);
            }
        }

        // Set common parameters
        $params['BASE_URL'] = base_url();
        $params['FULL_SITE_URL'] =$this->session->get('ILCADM_ADMIN_CURRENT_PATH') ?:$this->getCurrentBasePath();
        $params['ASSET_URL'] = base_url() . 'assets/';
        $params['ASSET_INCLUDE_URL'] = base_url() . 'assets/admin/';
        // Get current route class and method
        // $params['CURRENT_CLASS'] = $uri->getSegment(1); // Controller
        // $params['CURRENT_METHOD'] = $uri->getSegment(2); // Method

		// $params['CURRENT_CLASS'] = 'Login';// Controller
        // $params['CURRENT_METHOD'] = 'index'; // Method
		 // ✅ Correctly fetch the CSRF service
		 $params['CURRENT_CLASS'] = $this->router->controllerName();
		 $params['CURRENT_METHOD'] = $this->router->methodName();

		$params['CSRF_API_KEY'] = csrf_token(); // ✅ Correct function to get CSRF token name
		$params['CSRF_API_VALUE'] = csrf_hash(); // ✅ Correct function to get CSRF hash
		

        // Set page metadata
        $pagedata['title'] = $this->layout_title ?? 'Login';
        $pagedata['description'] = $this->layout_description ?? '';
        $pagedata['keyword'] = $this->layout_keyword ?? '';
       
        if ($viewtype == 'onlyview') {
            echo view($view_name, $params);
        } elseif ($viewtype == 'login') {
            $pagedata['head'] = view("layouts/admin/login_head", $params);
            $pagedata['content'] = view('admin/' . $view_name, $params);
            $pagedata['footer_js'] = view("layouts/admin/login_footer_js", $params);
            echo view("layouts/admin_login", $pagedata);
        } else {
            $pagedata['head'] = view("layouts/admin/head", $params);
            $pagedata['menu'] = view("layouts/admin/menu", $params);
            $pagedata['navigation'] = view("layouts/admin/navigation", $params);
            $pagedata['content'] = view('admin/' . $view_name, $params);
            $pagedata['footer'] = view("layouts/admin/footer", $params);
            $pagedata['footer_js'] = view("layouts/admin/footer_js", $params);
            echo view("layouts/admin", $pagedata);
        }
    }
	public function front_view($view_name, $layouts = [], $params = [], $viewtype = '')
    {
	
        $pagerData = [
            'BASE_URL'         => base_url(),
            'FRONT_URL'        => base_url('front/'),
            'ADMIN_URL'        => base_url('admin/'),
            'ASSET_ADMIN_URL'  => base_url('assets/admin/'),
            'ASSET_URL'        => base_url('assets/'),
            'ASSET_FRONT_URL'  => base_url('assets/front/'),

            // 'CURRENT_CLASS'    => service('uri')->getSegment(1),  // CI4 alternative to fetch_class()
            // 'CURRENT_METHOD'   => service('uri')->getSegment(2),  // CI4 alternative to fetch_method()
// 'CURRENT_CLASS'  => strtolower(service('router')->controllerName() ?? 'home'), 
// 'CURRENT_METHOD' => strtolower(service('router')->methodName() ?? 'index'),
'CURRENT_CLASS'  => strtolower(class_basename(\Config\Services::router()->controllerName())),
'CURRENT_METHOD' => strtolower(\Config\Services::router()->methodName()),

            'title'            => $this->layout_title ?? 'EFL',
            'description'      => $this->layout_description,
            'keyword'          => $this->layout_keyword,
        ];
		
        // Merge layouts into params
        if (!empty($layouts)) {
            foreach ($layouts as $layout_key => $layout) {
                $params[$layout_key] = view($layout, $params);
            }
        }

        $params = array_merge($params, $pagerData);
        $params['capcha_key'] = getenv('MY_CAPCHA_KEY');
        if ($viewtype == 'onlyview') {
			
            return view('front/' . $view_name, $params);
        } elseif ($viewtype == 'login') {
			
            $pagedata['head'] = view('layouts/front/head', $params);
            $pagedata['menu'] = '';
            $pagedata['content'] = view('front/' . $view_name, $params);
            $pagedata['footer_js'] = '';

            return view('layouts/front', $pagedata);
        } else {
			
            $pagedata['head'] = view('layouts/front/head', $params);
			
            $pagedata['menu'] = '';
			
            $pagedata['navigation'] = view('layouts/front/navigation', $params);
            $pagedata['content'] = view('front/' . $view_name, $params);
            $pagedata['footer'] = view('layouts/front/footer', $params);
            $pagedata['footer_js'] = '';
			// print($pagedata['head']);
			// print($pagedata['navigation']);
			// print($pagedata['content']);
			// print($pagedata['footer']);
            // return view('layouts/front', $pagedata);

			echo  view('layouts/front', $pagedata);
			exit;
        }
    }

	/**
     * Set page title
     *
     * @param $title
     */
    public function set_title($title)
	{
		$this->layout_title = $title;
		return $this;
	}
	
	/**
     * Set page description
     *
     * @param $description
     */
    public function set_description($description)
	{
		$this->layout_description = $description;
		return $this;
	}
	
	/**
     * Set page keyword
     *
     * @param $keyword
     */
    public function set_keyword($keyword)
	{
		$this->layout_keyword = $keyword;
		return $this;
	}
}