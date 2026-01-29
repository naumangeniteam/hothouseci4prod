<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        $uri = service('uri');
        $excludedRoutes = ['hhjsitemgmt/login', 'hhjsitemgmt/logout'];
        if (in_array($uri->getPath(), $excludedRoutes)) {
            return;
        }
        // Check if admin is logged in
        if (empty($session->get('ILCADM_ADMIN_ID'))) {
            // Save the current page for reference
            // setcookie('ILCADM_ADMIN_REFERENCE_PAGES', uri_string(), time() + 60 * 60 * 24 * 5, '/');
            // With this:
            helper('cookie');
            // Get current URI and set as cookie with security flags
            $currentUri = uri_string();
            $cookieOptions = [
                'name'     => 'ILCADM_ADMIN_REFERENCE_PAGES',
                'value'    => $currentUri,
                'expire'   => 60 * 60 * 24 * 5, // 5 days
                'path'     => '/',
                'domain'   => '', // Auto domain
                'httponly' => true, // Prevent JavaScript access
                'secure'   => ENVIRONMENT === 'production', // HTTPS only in production
                'samesite' => 'Lax' // Protect against CSRF
            ];
            set_cookie($cookieOptions);

            // Log the redirect for debugging purposes (optional)
            log_message('info', 'User not authenticated. Redirecting to logout page. URI: ' . $currentUri);
            
            // Redirect to logout page
            return redirect()->to(base_url('hhjsitemgmt/logout'))->with('error', 'Session expired, please login again.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Not needed for authentication, leave empty
    }
}
?>