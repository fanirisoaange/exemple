<?php


use App\Enum\Notifications;
use App\Models\CompanyModel;
use App\Models\UserModel;

if ( ! function_exists('notification_status')) {
     function notification_status(int $notifStatus)
        {
            $status = trad(
                Notifications::getDescriptionById(
                    $notifStatus
                )
            );
            switch ($notifStatus) {
                case 1:
                    echo '<span class="badge badge-draft">'.$status.'</span>';
                    break;
                case 2:
                    echo '<span class="badge badge-success">'.$status.'</span>';
                    break;
                case 3:
                    echo '<span class="badge badge-warning">'.$status.'</span>';
                    break;
                case 4:
                    echo '<span class="badge badge-danger">'.$status.'</span>';
                    break;
                case 10:
                    echo '<span class="badge badge-success">'.$status.'</span>';
                    break;
                case 11:
                    echo '<span class="badge badge-draft">'.$status.'</span>';
                    break;
                case 12:
                    echo '<span class="badge badge-success">'.$status.'</span>';
                    break;
                default:
                    throw new Exception('invalid order status');
            }
        }
}


if ( ! function_exists('getSegment')) {

    /**
     * Returns segment value for given segment number or false.
     *
     * @param int $number The segment number for which we want to return the value of
     *
     * @return string||false
     */
    function getSegment(int $number)
    {
        try {
            $request = Config\Services::request();

            $uri = $request->uri;

            return $uri->getSegment($number);
        } catch (\CodeIgniter\HTTP\Exceptions\HTTPException $e) {
            return false;
        }
    }
}

use App\Libraries\Traductions;
use Config\Database;
use Config\Services;

if ( ! function_exists('trad')) {

    /**
     *
     */
    function trad($content, $zone = false, $vars = false)
    {
        $controller = getSegment(1);
        $getZone = $zone ? $zone : $controller;

        $ci_trad = lang('Custom_'.$getZone.'.'.md5($getZone.'-'.$content));
        if ($ci_trad == 'Custom_'.$getZone.'.'.md5($getZone.'-'.$content)):
            $trad = new Traductions(Database::connect());
            $retour = $trad->addTraduction($content, $getZone);
        else:
            $retour = $ci_trad;
        endif;

        /**
         * Vars replacement
         */
        $key = [];
        $var = [];
        if ($vars && is_array($vars) && count($vars) > 0):
            foreach ($vars as $k => $v):
                $key[] = $k;
                $var[] = $v;
            endforeach;
            $retour = str_replace($key, $var, $retour);
        endif;

        return $retour;
    }
}

if ( ! function_exists('format_date')) {
    /*
     * Transforme un datetime en format voulu
     * Précise si il s'agit d'un timestamp
     */

    function format_date($string, $format, $timestamp = false)
    {
        if ( ! empty($string)):
            if ($timestamp):
                $date = new DateTime();
                $date->setTimestamp($string);
            else:
                $date = new DateTime($string);
            endif;
            $retour = $date->format($format);
        else:
            $retour = false;
        endif;

        return $retour;
    }
}

if ( ! function_exists('trim_data')) {

    /**
     * Strip whitespace from the beginning and end of a string or array values
     *
     * @param array/string $data
     *
     * @return array/string
     */
    function trim_data($data)
    {
        if (is_array($data)) {
            $res = [];
            foreach ($data as $key => $value) {
                $res[$key] = trim_data($value);
            }
        } else {
            $res = trim($data);
        }

        return $res;
    }
}

if ( ! function_exists('format_vat_number')) {
    /**
     * Format VAT number
     * Remove all alphanumeric characters and convert it to uppercase
     *
     * @param string $str
     *
     * @return string
     */
    function format_vat_number($str)
    {
        $str = preg_replace('/[^A-Za-z0-9]/', '', $str);
        $str = strtoupper($str);

        return $str;
    }
}

if ( ! function_exists('buildRecursiveArray')) {

    function buildRecursiveArray(
        array $elements,
        $parentId = 0,
        $parent_index = 'parent_id',
        $id_index = 'id'
    ) {
        $branch = [];
        foreach ($elements as $element) {
            if ($element[$parent_index] == $parentId) {
                $children = buildRecursiveArray($elements, $element[$id_index]);
                if ($children) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }
}

/* * ****************************************************
 * Form
 */
if ( ! function_exists('custom_dropdown')) {
    function custom_dropdown($form, $extra = false)
    {
        $request = Services::request();
        $validation = Services::validation();
        $post = $request->getPost();
        if (isset($post[$form['field']])):
            if ($validation->showError($form['field'])):
                $class = ! empty($extra['class']) ? trim($extra['class'])
                    .' is-invalid' : false;
            else:
                $class = ! empty($extra['class']) ? trim($extra['class'])
                    .' is-valid' : false;
            endif;
            $extra['class'] = $class;
        endif;
        $return = '<div class="form-group">';
        if (isset($form['label'])):
            $return .= form_label(trad($form['label']));
        endif;
        $return .= form_dropdown(
            $form['field'],
            $form['options'],
            $form['post'],
            $extra
        );
        $return .= $validation->showError($form['field']);
        $return .= '</div>';

        return $return;
    }

}

if ( ! function_exists('custom_input')) {

    function custom_input($form, $extra = false)
    {
        $request = Services::request();
        $validation = Services::validation();
        $post = $request->getPost();
        if (isset($post[$form['field']])):
            if ($validation->showError($form['field'])):
                $class = ! empty($extra['class']) ? trim($extra['class'])
                    .' is-invalid' : false;
            else:
                $class = ! empty($extra['class']) ? trim($extra['class'])
                    .' is-valid' : false;
            endif;
            $extra['class'] = $class;
        endif;
        $return = '<div class="form-group">';
        if (isset($form['label'])):
            $return .= form_label(trad($form['label']));
        endif;
        $return .= form_input($form['field'], $form['post'], $extra);
        $return .= $validation->showError($form['field']);
        $return .= '</div>';

        return $return;
    }

}


if ( ! function_exists('custom_textarea')) {

    function custom_textarea($form, $extra = false)
    {
        $request = Services::request();
        $validation = Services::validation();
        $post = $request->getPost();
        if (isset($post[$form['field']])):
            if ($validation->showError($form['field'])):
                $class = ! empty($extra['class']) ? trim($extra['class'])
                    .' is-invalid' : false;
            else:
                $class = ! empty($extra['class']) ? trim($extra['class'])
                    .' is-valid' : false;
            endif;
            $extra['class'] = $class;
        endif;
        $return = '<div class="form-group">';
        if (isset($form['label'])):
            $return .= form_label(trad($form['label']));
        endif;
        $return .= form_textarea($form['field'], $form['post'], $extra);
        $return .= $validation->showError($form['field']);
        $return .= '</div>';

        return $return;
    }

}

if ( ! function_exists('name_dept')) {
    function name_dept(string $zip): string
    {
        $name_dept = [
            "01" => "Ain",
            "02" => "Aisne",
            "03" => "Allier",
            "04" => "Alpes-de-Haute Provence",
            "05" => "Hautes-Alpes",
            "06" => "Alpes Maritimes",
            "07" => "Ardèche",
            "08" => "Ardennes",
            "09" => "Ariège",
            "10" => "Aube",
            "11" => "Aude",
            "12" => "Aveyron",
            "13" => "Bouches-du-Rhône",
            "14" => "Calvados",
            "15" => "Cantal",
            "16" => "Charente",
            "17" => "Charente-Maritime",
            "18" => "Cher",
            "19" => "Corrèze",
            "20" => "Corse",
            "21" => "Côte d'Or",
            "22" => "Côtes d'Armor",
            "23" => "Creuse",
            "24" => "Dordogne",
            "25" => "Doubs",
            "26" => "Drôme",
            "27" => "Eure",
            "28" => "Eure-et-Loire",
            "29" => "Finistère",
            "30" => "Gard",
            "31" => "Haute-Garonne",
            "32" => "Gers",
            "33" => "Gironde",
            "34" => "Hérault",
            "35" => "Ille-et-Vilaine",
            "36" => "Indre",
            "37" => "Indre-et-Loire",
            "38" => "Isère",
            "39" => "Jura",
            "40" => "Landes",
            "41" => "Loir-et-Cher",
            "42" => "Loire",
            "43" => "Haute-Loire",
            "44" => "Loire-Atlantique",
            "45" => "Loiret",
            "46" => "Lot",
            "47" => "Lot-et-Garonne",
            "48" => "Lozère",
            "49" => "Maine-et-Loire",
            "50" => "Manche",
            "51" => "Marne",
            "52" => "Haute-Marne",
            "53" => "Mayenne",
            "54" => "Meurthe-et-Moselle",
            "55" => "Meuse",
            "56" => "Morbihan",
            "57" => "Moselle",
            "58" => "Nièvre",
            "59" => "Nord",
            "60" => "Oise",
            "61" => "Orne",
            "62" => "Pas-de-Calais",
            "63" => "Puy-de-Dôme",
            "64" => "Pyrenées-Atlantiques",
            "65" => "Hautes-Pyrenées",
            "66" => "Pyrenées-Orientales",
            "67" => "Bas-Rhin",
            "68" => "Haut-Rhin",
            "69" => "Rhône",
            "70" => "Haute-Saône",
            "71" => "Saône-et-Loire",
            "72" => "Sarthe",
            "73" => "Savoie",
            "74" => "Haute-Savoie",
            "75" => "Paris",
            "76" => "Seine-Maritime",
            "77" => "Seine-et-Marne",
            "78" => "Yvelines",
            "79" => "Deux-Sèvres",
            "80" => "Somme",
            "81" => "Tarn",
            "82" => "Tarn-et-Garonne",
            "83" => "Var",
            "84" => "Vaucluse",
            "85" => "Vendée",
            "86" => "Vienne",
            "87" => "Haute-Vienne",
            "88" => "Vosges",
            "89" => "Yonne",
            "90" => "Territoire de Belfort",
            "91" => "Essonne",
            "92" => "Hauts-de-Seine",
            "93" => "Seine-Saint-Denis",
            "94" => "Val-de-Marne",
            "95" => "Val-d'Oise",
        ];

        $dept = substr($zip, 0, 2);

        return isset($name_dept[$dept]) ? $name_dept[$dept] : "----";
    }
}

if ( ! function_exists("session_message")) {
    function session_message(): string
    {
        $m = '';
        if (isset($_SESSION['message'])) {
            $m = $_SESSION['message'];
            unset($_SESSION['message']);
        }

        return $m;
    }
}

if ( ! function_exists("checkFormatDate")) {
    function checkFormatDate(string $dateTime): bool
    {
        $year = (int)substr($dateTime, 0, 4);
        $month = (int)substr($dateTime, 5, 2);
        $day = (int)substr($dateTime, 8, 2);

        return checkdate($month, $day, $year);
    }
}

if ( ! function_exists('hasCampaignAccess')) {
    function hasCampaignAccess(string $companyId = null)
    {
        return true;
    }
}

/*
 * End Form
 * * ****************************************************/
