<?php

namespace App\Controllers;

use App\Libraries\Layout;
use Exception;

class BlacklistFile extends BaseController
{

    private $blacklistFileModel;

    public function __construct()
    {
        $this->blacklistFileModel = model('BlacklistFileModel', true, $this->db);
        helper(['order']);
    }

    public function list()
    {
        if (!isMemberAdmin()) {
            return accessDenied();
        }

        $id = isset($_SESSION['current_sub_company'])
            ? $_SESSION['current_sub_company'] : null;
        $blacklistFiles = [];
        if ($id) {
            $blacklistFiles = $this->blacklistFileModel->getBlacklistFilesCompany(
                $id,
                'blacklist_file.id, blacklist_file.upload_date, blacklist_file.send_date, blacklist_file.status, blacklist_file.name, blacklist_file.company_id, blacklist_file.files'
            );
        }
        $options = [
            'title'           => trad('Blacklist File'),
            'metadescription' => trad('List of blacklist', 'user'),
            'content_only'    => false,
            'no_js'           => false,
            'nofollow'        => true,
            'top_content'     => [
                'layout/default/header',
                'layout/default/sidebar',
            ],
            'bottom_content'  => 'layout/default/footer',
            'content'         => 'layout/default/content',
            'page_title'      => '<i class="fas fa-chart-pie"></i>' . trad(
                ' Blacklist File'
            ),
            'blacklistFiles'  => $blacklistFiles,
            'companyId'       => $id,
        ];

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_css(
            [
                LIBRARY . 'datatables-bs4/css/dataTables.bootstrap4.min',
                LIBRARY . 'datatables-responsive/css/responsive.bootstrap4.min',
            ]
        );
        $layout->add_js(
            [
                LIBRARY . 'datatables/jquery.dataTables.min',
                LIBRARY . 'datatables-bs4/js/dataTables.bootstrap4.min',
                LIBRARY . 'datatables-responsive/js/dataTables.responsive.min',
                LIBRARY . 'datatables-responsive/js/responsive.bootstrap4.min',
                ASSETS . 'js/blacklistfile',
                ASSETS . 'js/helper',
            ]
        );

        return $layout->view('blacklistfile/list', $options);
    }

    public function detail(int $id)
    {
        if (!isMemberAdmin()) {
            return accessDenied();
        }

        $options = [
            'title'           => trad('Blacklist File detail'),
            'metadescription' => trad('Blacklist File detail'),
            'content_only'    => false,
            'no_js'           => false,
            'nofollow'        => true,
            'top_content'     => [
                'layout/default/header',
                'layout/default/sidebar',
            ],
            'bottom_content'  => 'layout/default/footer',
            'content'         => 'layout/default/content',
            'page_title'      => '<i class="fas fa-chart-pie"></i>' . trad(
                'Blacklist File detail'
            ),
            'blacklistFile'   => $this->blacklistFileModel->getBlacklistFileDetail(
                $id,
                'blacklist_file.id, blacklist_file.name, blacklist_file.upload_date, blacklist_file.send_date, blacklist_file.status, blacklist_file.company_id, blacklist_file.files, ' .
                'c.fiscal_name, c.commercial_name, c.address_1, c.address_2, c.address_1, c.city, c.zip_code, c.city_display'
            ),
        ];

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js(
            [
                ASSETS . 'js/blacklistfile',
            ]
        );

        return $layout->view('blacklistfile/detail', $options);
    }

    public function send(int $id)
    {
        if (!isMemberAdmin()) {
            return accessDenied();
        }
        $return = $this->blacklistFileModel->sendBlacklistFile($id);
        if (array_key_exists('resultat', $return)) {
            return json_encode($return);
        }
        return new Exception(trad('An error has occured'));
    }

    public function delete()
    {
        if (!isMemberAdmin()) {
            return accessDenied();
        }
        $return = $this->blacklistFileModel->deleteBlacklistFile();
        if (array_key_exists('resultat', $return)) {
            return json_encode($return);
        }
        return new Exception(trad('An error has occured'));
    }

    public function upload()
    {
        if (!isMemberAdmin()) {
            return accessDenied();
        }
        $data = [
            'top_content' => [
                'layout/default/header',
                'layout/default/sidebar'
            ],
            'bottom_content' => 'layout/default/footer',
            'content'        => 'layout/default/content',
        ];
        $this->layout = new Layout();
        $this->layout->load_assets('default');
        $this->layout->add_js(ASSETS . 'js/visualsLib');
        $this->layout->add_js(
            [
                ASSETS . 'js/blacklistfile',
                ASSETS . 'js/helper',
            ]
        );
        $data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Upload blacklistfile',
            'breadcrumb' => [
                '/blacklistfile/list' => trad('Blacklistfile List'),
                '' => trad('Upload blacklistfile')
            ]
        ];
        if ($this->request->getMethod() == 'post') {
            $return = $this->blacklistFileModel->createBlacklistFile($this->request);
            if ($return['code'] == 200) {
                return redirect()->to(route_to('blacklistfile_detail', $return['blacklistFileId']));
            } else {
                throw new Exception(trad($return['msg']));
            }
        }

        return $this->layout->view('blacklistfile/upload', $data);
    }
}
