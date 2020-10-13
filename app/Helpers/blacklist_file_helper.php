<?php

use App\Enum\BlacklistFileStatus;

if ( ! function_exists('blacklistFileStatus')) {

    /**
     * Return status text for blacklist File
     *
     * @param int $blacklistFileStatus The database value of the status
     *
     * @return void
     */
    function blacklistFileStatus(int $blacklistFileStatus)
    {
        $status = trad(
            BlacklistFileStatus::getDescriptionById(
                $blacklistFileStatus
            )
        );
        switch ($blacklistFileStatus) {
            case 1:
                echo '<span class="badge badge-warning">'.$status.'</span>';
                break;
            case 2:
                echo '<span class="badge badge-success">'.$status.'</span>';
                break;
            default:
                throw new Exception('invalid blacklist File status');
        }
    }
}

if ( ! function_exists('blacklistFileLines')) {

    /**
     * Return all lines in blacklist File
     *
     * @param array $blacklistFile The database line returned
     *
     * @return array
     */
    function blacklistFileLines(array $blacklistFile): array
    {
        $file = ROOTPATH . str_replace('/', DIRECTORY_SEPARATOR, getenv('path.upload.blacklist').'/'.$blacklistFile['company_id'].'/'.$blacklistFile['files']);
        $lines=[];
        $file = fopen($file, 'r');
        while (!feof($file) ) {
            $lines[] = fgetcsv($file, 1024);
        }
        fclose($file);
        return $lines;
    }
}

if ( ! function_exists('blacklistFileNbline')) {

    /**
     * Return Number of lines in blacklist File
     *
     * @param array $blacklistFile The database line returned
     *
     * @return int
     */
    function blacklistFileNbline(array $blacklistFile): int
    {
        return count(blacklistFileLines($blacklistFile)) - 1;
    }
}