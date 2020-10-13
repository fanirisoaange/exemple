<?php

namespace App\Controllers;

//use \IonAuth\Controllers\Auth;
use App\Libraries\Layout;

class AuthenticationController extends BaseController
{
    private $layout;

    /**
     *
     * @var array
     */
    public $data = [];

    /**
     * Configuration
     *
     * @var \IonAuth\Config\IonAuth
     */
    protected $configIonAuth;

    /**
     * IonAuth library
     *
     * @var \IonAuth\Libraries\IonAuth
     */
    protected $ionAuth;

    /**
     * Session
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Validation library
     *
     * @var \CodeIgniter\Validation\Validation
     */
    protected $validation;

    /**
     * Validation list template.
     *
     * @var string
     * @see https://bcit-ci.github.io/CodeIgniter4/libraries/validation.html#configuration
     */
    protected $validationListTemplate = 'list';

    /**
     * Views folder
     * Set it to 'auth' if your views files are in the standard application/Views/auth
     *
     * @var string
     */
    protected $viewsFolder = 'IonAuth\Views\auth';

    /**
     * Authentication library
     *
     * @var \App\Libraries\Authentication
     */
    protected $authentication;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->validation = \Config\Services::validation();

        helper(['form', 'url', 'html', 'text', 'custom']);


        $this->configIonAuth = config('IonAuth');
        $this->session = \Config\Services::session();

        if (!empty($this->configIonAuth->viewsFolder)) {
            $this->viewsFolder = $this->configIonAuth->viewsFolder;
        }

        if (!empty($this->configIonAuth->templates['errors']['list'])) {
            $this->validationListTemplate = $this->configIonAuth->templates['errors']['list'];
        }
    }

    /**
     * Log the user in
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function login()
    {
        $this->data['title'] = lang('Auth.login_heading');

        // validate form input
        $this->validation->setRule('identity', str_replace(':', '', lang('Auth.login_identity_label')), 'required');
        $this->validation->setRule('password', str_replace(':', '', lang('Auth.login_password_label')), 'required');

        if ($this->request->getPost() && $this->validation->withRequest($this->request)->run()) {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->request->getVar('remember');

            if ($this->ionAuth->login($this->request->getVar('identity'), $this->request->getVar('password'), $remember)) {
                //if the login is successful
                $currentUser = model('UserModel')->selectUserByEmail($this->request->getVar('identity'));
                $_SESSION['currentUser_first_name'] = $currentUser['first_name'];
                $_SESSION['currentUser_last_name'] = $currentUser['last_name'];
                $_SESSION['currentUser_photo'] = $currentUser['last_name'];
                $this->permissions->setMainCompanies();
                $this->permissions->setCurrentMainCompany();
                $this->permissions->setDefaultCurrentSubMainCompany();
                $this->session->setFlashdata('message', $this->ionAuth->messages());
                return redirect()->route('dashboard');
            } else {
                // if the login was un-successful
                // redirect them back to the login page
                $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                // use redirects instead of loading views for compatibility with MY_Controller libraries
                return redirect()->back()->withInput();
            }
        } else {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');

            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => set_value('identity'),
            ];

            $this->data['password'] = [
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
            ];

            /**
             * @cardata
             */
            $this->data += [
                'content' => 'layout/default/empty',
                'body_class' => '',
                'body_id' => 'login',
            ];

            return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'login', $this->data);
        }
    }

    /**
     * Log the user out
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        $this->data['title'] = 'Logout';

        // log the user out
        $this->ionAuth->logout();

        // redirect them to the login page
        //$this->session->setFlashdata('message', $this->ionAuth->messages());
        return redirect()->route('login');
    }

    /**
     * Change password
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function change_password()
    {
        $this->validation->setRule('old', lang('Auth.change_password_validation_old_password_label'), 'required');
        $this->validation->setRule('new', lang('Auth.change_password_validation_new_password_label'), 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[new_confirm]');
        $this->validation->setRule('new_confirm', lang('Auth.change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ionAuth->loggedIn()) {
            return redirect()->route('login');
        }

        $user = $this->ionAuth->user()->row();

        if ($this->validation->run() === false) {
            // display the form
            // set the flash data error message if there is one
            $this->data['message'] = ($this->validation->getErrors()) ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');

            $this->data['minPasswordLength'] = $this->configIonAuth->minPasswordLength;
            $this->data['old_password'] = [
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            ];
            $this->data['new_password'] = [
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
            ];
            $this->data['new_password_confirm'] = [
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
            ];
            $this->data['user_id'] = [
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            ];

            // render
            return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'change_password', $this->data);
        } else {
            $identity = $this->session->get('identity');

            $change = $this->ionAuth->changePassword($identity, $this->request->getPost('old'), $this->request->getPost('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->setFlashdata('message', $this->ionAuth->messages());
                $this->logout();
            } else {
                $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                return redirect()->route('change_password');
            }
        }
    }

    /**
     * Forgot password
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function forgot_password()
    {
        $this->data['title'] = lang('Auth.forgot_password_heading');

        // setting validation rules by checking whether identity is username or email
        if ($this->configIonAuth->identity !== 'email') {
            $this->validation->setRule('identity', lang('Auth.forgot_password_identity_label'), 'required');
        } else {
            $this->validation->setRule('identity', lang('Auth.forgot_password_validation_email_label'), 'required|valid_email');
        }

        if (!($this->request->getPost() && $this->validation->withRequest($this->request)->run())) {
            $this->data['type'] = $this->configIonAuth->identity;
            // setup the input
            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
            ];

            if ($this->configIonAuth->identity !== 'email') {
                $this->data['identity_label'] = lang('Auth.forgot_password_identity_label');
            } else {
                $this->data['identity_label'] = lang('Auth.forgot_password_email_identity_label');
            }

            // set any errors and display the form
            $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
            /**
             * @cardata
             */
            $this->data += [
                'content' => 'layout/default/empty',
                'body_class' => '',
                'body_id' => 'login',
            ];

            return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'forgot_password', $this->data);
        } else {
            $identityColumn = $this->configIonAuth->identity;
            $identity = $this->ionAuth->where($identityColumn, $this->request->getPost('identity'))->users()->row();

            if (empty($identity)) {
                if ($this->configIonAuth->identity !== 'email') {
                    $this->ionAuth->setError('Auth.forgot_password_identity_not_found');
                } else {
                    $this->ionAuth->setError('Auth.forgot_password_email_not_found');
                }

                $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                return redirect()->route('forgot_password');
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ionAuth->forgottenPassword($identity->{$this->configIonAuth->identity});

            if ($forgotten) {
                // if there were no errors
                $this->session->setFlashdata('message', $this->ionAuth->messages());
                return redirect()->route('login'); //we should display a confirmation page here instead of the login page
            } else {
                $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                return redirect()->route('forgot_password');
            }
        }
    }

    /**
     * Reset password - final step for forgotten password
     *
     * @param string|null $code The reset code
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function reset_password($code = null)
    {
        if (!$code) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->data['title'] = lang('Auth.reset_password_heading');

        $user = $this->ionAuth->forgottenPasswordCheck($code);

        if ($user) {
            // if the code is valid then display the password reset form

            $this->validation->setRule('new', lang('Auth.reset_password_validation_new_password_label'), 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[new_confirm]');
            $this->validation->setRule('new_confirm', lang('Auth.reset_password_validation_new_password_confirm_label'), 'required');

            if (!$this->request->getPost() || $this->validation->withRequest($this->request)->run() === false) {
                // display the form
                // set the flash data error message if there is one
                $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');

                $this->data['minPasswordLength'] = $this->configIonAuth->minPasswordLength;
                $this->data['new_password'] = [
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
                ];
                $this->data['new_password_confirm'] = [
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
                ];
                $this->data['user_id'] = [
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                ];
                $this->data['code'] = $code;

                // render
                return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'reset_password', $this->data);
            } else {
                $identity = $user->{$this->configIonAuth->identity};

                // do we have a valid request?
                if ($user->id != $this->request->getPost('user_id')) {
                    // something fishy might be up
                    $this->ionAuth->clearForgottenPasswordCode($identity);

                    throw new \Exception(lang('Auth.error_security'));
                } else {
                    // finally change the password
                    $change = $this->ionAuth->resetPassword($identity, $this->request->getPost('new'));

                    if ($change) {
                        // if the password was successfully changed
                        $this->session->setFlashdata('message', $this->ionAuth->messages());
                        return redirect()->route('login');
                    } else {
                        $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                        //return redirect()->to('/auth/reset_password/' . $code);
                        return redirect()->route('reset_password', $code);
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
            return redirect()->route('forgot_password');
        }
    }

    public function getUserMainCompaniesHeader(int $main_companies_override)
    {
        $data = null;

        if ($main_companies_override) {
            $data = $this->setMainCompanyHeaderTitle(model('CompanyModel')->getMainCompanies(['companies_to_company.company_id'=>$main_companies_override]));
        } elseif (isAdmin() || isCardata()) {
            $companyModel = model('CompanyModel');
            $data = $this->setMainCompaniesHeaderDropdown($companyModel->getMainCompanies());
        } elseif ($this->session->main_companies) {
            $permissionModel = model('PermissionModel');
            $main_companies = $permissionModel->selectUserMainCompanies($this->session->user_id);

            if (count($main_companies) > 1) {
                $data = $this->setMainCompaniesHeaderDropdown($main_companies);
            } else {
                $data = $this->setMainCompanyHeaderTitle($main_companies);
            }
        }

        echo $data;
    }

    public function changeUserMainCompany()
    {
        $request = $this->request->getPost('request');
        $mainCompany = $this->request->getPost('user_main_company');

        if (!is_null($mainCompany) && $mainCompany != '') {
            $this->permissions->setCurrentMainCompany((int) $mainCompany);
            $_SESSION['current_sub_company'] = (int)$mainCompany;

            if ($request == 'ajax') {
                echo 1;
            }

            return true;
        }

        return false;
    }

    private function setMainCompanyHeaderTitle($company)
    {
        $data = '<h5 class="form-control">' . $company[0]['fiscal_name'] . '</h5>';

        return $data;
    }

    private function setMainCompaniesHeaderDropdown($companies)
    {
        $data = '<select class="custom-select form-control-navbar" data-url="' . route_to('user_main_company_change') . '">';

        if (isAdmin() || isCardata()) {
            $data .= '<option value="0">' . trad('All companies', 'permission') . '</option>';
        }

        foreach ($companies as $company) {
            $data .= '<option value="' . $company['id'] . '" ' . ($company['id'] == $this->session->current_main_company ? 'selected' : '') . '>' . $company['fiscal_name'] . '</option>';
        }

        $data .= '</select>';

        return $data;
    }

    /**
     * Render the specified view
     *
     * @param string     $view       The name of the file to load
     * @param array|null $data       An array of key/value pairs to make available within the view.
     *
     * @return string|void
     */
    protected function renderPage(string $view, $data = null): string
    {
        $this->layout = new Layout();
        $this->layout->load_assets('default');

        $viewdata = $data ?: $this->data;

        return $this->layout->view($view, $viewdata);
    }
}
