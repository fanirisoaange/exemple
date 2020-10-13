<?php

namespace App\Models;

use App\Enum\BlacklistFileStatus;
use CodeIgniter\Model;
use Exception;

class BlacklistFileModel extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->builder = $this->db->table("blacklist_file");
    }

    public function getBlacklistFilesCompany(int $id, string $columns = '*'): array
    {
        return $this->builder
            ->select($columns)
            ->orderBy('upload_date DESC')
            ->join('companies as c', 'blacklist_file.company_id = c.id', 'LEFT')
            ->where('blacklist_file.company_id', $id)
            ->groupBy('blacklist_file.id')
            ->get()
            ->getResultArray();
    }

    public function getBlacklistFileDetail(int $id, string $columns = '*'): array
    {
        $blacklistFile = $this->selectBlacklistFile($id, $columns);
        if (! empty($blacklistFile)) {
            $blacklistFile['upload_date'] = \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $blacklistFile['upload_date']
            )->format('d/m/Y H:i:s');
            $blacklistFile['send_date'] = $blacklistFile['send_date'] ? \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $blacklistFile['send_date']
            )->format('d/m/Y H:i:s') : null;
        }

        return $blacklistFile;
    }

    public function selectBlacklistFile(int $id, string $columns): array
    {
        return $this->builder
            ->select($columns)
            ->join('companies as c', 'blacklist_file.company_id = c.id', 'LEFT')
            ->getWhere(['blacklist_file.id' => $id])
            ->getRowArray();
    }

    public function sendBlacklistFile(int $id): array
    {
        $blacklistFile = $this->selectBlacklistFile($id, 'blacklist_file.id, blacklist_file.company_id, blacklist_file.files');
        $return = json_decode($this->importBlacklistFile($blacklistFile), true);
        if (array_key_exists('resultat', $return) && $return['resultat'] == 1) {
            $pathTempUploads = ROOTPATH . 'public'.getenv('path.temp.upload');
            unlink(str_replace('/', DIRECTORY_SEPARATOR, $pathTempUploads.'/'.$blacklistFile['files']));
            $blacklistFile = [
                'status'    => BlacklistFileStatus::SENT,
                'send_date' => date('Y-m-d H:i:s', time()),
            ];
            $this->db->table('blacklist_file')->update($blacklistFile, ['id' => $id]);
        }

        return $return;     
    }

    public function getBlacklistFilesToDelete(int $id, string $columns = '*'): array
    {
        return $this->builder
            ->select($columns)
            ->orderBy('upload_date DESC')
            ->join('companies as c', 'blacklist_file.company_id = c.id', 'LEFT')
            ->where('blacklist_file.company_id', $id)
            ->where('blacklist_file.status', BlacklistFileStatus::SENT)
            ->groupBy('blacklist_file.id')
            ->get()
            ->getResultArray();
    }

    public function deleteBlacklistFile(): array
    {
        $return = json_decode($this->purgeBlacklistFile(), true);
        if (array_key_exists('resultat', $return) && $return['resultat'] == 1) {
            $listBlacklistFile = $this->getBlacklistFilesToDelete((int)$_SESSION['current_sub_company']);
            foreach ($listBlacklistFile as $blacklistFile) {
                $file = ROOTPATH . str_replace('/', DIRECTORY_SEPARATOR, getenv('path.upload.blacklist').'/'.$blacklistFile['company_id'].'/'.$blacklistFile['files']);
                unlink($file);
            }
            $this->db->table('blacklist_file')->delete(
                [
                    'company_id' => $_SESSION['current_sub_company'],
                    'status'     => BlacklistFileStatus::SENT,
                ]
            );
        }

        return $return;
    }

    public function createBlacklistFile($request)
    {
        $name = $request->getPost('name');
        $companyId = $_SESSION['current_sub_company'];
        $files = $request->getFiles()['files'];
        $uploadBlacklistFile = $this->uploadBlacklistFile($request, $companyId);
        if ($uploadBlacklistFile['code'] = 200) {
            $this->db->transStart();
            $blacklistFile = [
                'name'        => $name,
                'status'      => BlacklistFileStatus::ACTIF,
                'company_id'  => $companyId,
                'upload_date' => date('Y-m-d H:i:s', time()),
                'files'       => $uploadBlacklistFile['fullnameFile'],
            ];
            $this->db->table('blacklist_file')->insert($blacklistFile);
            return [
                'code'            => 200,
                'blacklistFileId' => $this->db->insertID(),
                'msg'             => trad('blacklist File uploaded successfully'),
            ];
        }
        return [
            'code'            => $uploadBlacklistFile['code'],
            'blacklistFileId' => null,
            'msg'             => $uploadBlacklistFile['msg'],
        ];
    }

    public function uploadBlacklistFile($request, string $companyId): array
    {
        $uploadBlacklistFile = [
            'code'         => 500,
            'msg'          => trad('An error has occured'),
            'fullnameFile' => '',
        ];
        if ($files = $request->getFiles()) {
            $files = $files['files'];
            if (count($files) > 0) {
                $file = $files[0];
                $postMaxSize = ini_get('post_max_size');
                $uploadMaxFilesize = ini_get('upload_max_filesize');
                $uploadMaxFilesize = 1024 * 1024 * str_replace('M', '', $uploadMaxFilesize);
                if ($file->getSize() > $uploadMaxFilesize) {
                    $uploadBlacklistFile['msg'] = trad('The maximum file size to upload is '.ini_get('upload_max_filesize').'o');
                }
                if (!in_array($file->getExtension(), ['csv'])) {
                    $uploadBlacklistFile['msg'] = trad('Please upload CSV files only');
                }
                $pathUpload = ROOTPATH . str_replace('/', DIRECTORY_SEPARATOR, getenv('path.upload'));
                $pathUploadBlacklist = ROOTPATH . str_replace('/', DIRECTORY_SEPARATOR, getenv('path.upload.blacklist'));
                if (!file_exists($pathUpload))
                    mkdir($pathUpload);
                if (!file_exists($pathUploadBlacklist))
                    mkdir($pathUploadBlacklist);
                if (!file_exists($pathUploadBlacklist.'/'.$companyId))
                    mkdir($pathUploadBlacklist.'/'.$companyId);
                $file = $files[0];
                if ($file->isValid() && ! $file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($pathUploadBlacklist.'/' . $companyId, $newName);
                    $uploadBlacklistFile['code'] = 200;
                    $uploadBlacklistFile['msg'] = trad('The upload was successfull');
                    $uploadBlacklistFile['fullnameFile'] = $newName;
                }
            } else {
                $uploadBlacklistFile['msg'] = trad('The upload field is required');
            }
        }

        return $uploadBlacklistFile;
    }

    public function importBlacklistFile(array $blacklistFile): string
    {
        $file = ROOTPATH . str_replace('/', DIRECTORY_SEPARATOR, getenv('path.upload.blacklist').'/'.$blacklistFile['company_id'].'/'.$blacklistFile['files']);
        $pathTempUploads = ROOTPATH.'public'.getenv('path.temp.upload');
        if (!file_exists($pathTempUploads))
            mkdir($pathTempUploads);
        @copy($file, str_replace('/', DIRECTORY_SEPARATOR, $pathTempUploads.'/'.$blacklistFile['files']));
        $pathTempUpload = getenv('app.baseURL') . getenv('path.temp.upload');
        $company = model('CompanyModel')->selectCompany((int)$blacklistFile['company_id'], 'companies.id, companies.fiscal_name, companies.id_client_datawork, companies_to_company.main_id');
        $return = json_encode(['resultat'=>0, 'erreur'=>trad('An error has occured')]);

        $arrayFieldPost = [
            'type_base_client' => 'blackliste_local',
            'id_client'        => (int)$company['id_client_datawork'],
            'canal'            => blacklistFileLines($blacklistFile)[0][0],
            'fichier_url'      => $pathTempUpload.'/'.$blacklistFile['files']
        ];

        return connectApi('POST', '/'.$company['id_client_datawork'].'/importation', $arrayFieldPost);
    }

    public function purgeBlacklistFile(): string
    {
        $blacklistFiles = $this->getBlacklistFilesToDelete((int)$_SESSION['current_sub_company']);
        if (count($blacklistFiles) > 0) {
            $blacklistFile = $blacklistFiles[0];
        } else {
            return json_encode(['resultat'=>0, 'erreur'=>trad('No repeller base to remove')]);
        }
        $company = model('CompanyModel')->selectCompany((int)$blacklistFile['company_id'], 'companies.id, companies.fiscal_name, companies.id_client_datawork, companies_to_company.main_id');
        $arrayFieldPost = [
            'type_base_client' => 'repoussoir_local',
            'canal'            => blacklistFileLines($blacklistFile)[0][0]
        ];

        return connectApi('POST', '/'.$company['id_client_datawork'].'/suppression', $arrayFieldPost);
    }
}
