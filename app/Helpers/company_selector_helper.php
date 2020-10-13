<?php


use App\Models\CompanyModel;
use App\Models\UserModel;

if (!function_exists("companySelector")) {
    function companySelector($withLabel = true, $with = "col-6", $displayFlex = true, $multiple = false)
    {

        $companyModel = new CompanyModel();
        $userModel = new UserModel();
        $id = isset($_SESSION['current_main_company'])
            ? $_SESSION['current_main_company'] : 0;
        $userSubCompany = isset($_SESSION['current_sub_company'])
            ? $_SESSION['current_sub_company'] : 0;
        $label = $withLabel ? '<span style="font-weight: bold; font-size: 95%; margin-right: 0.7em">' . trad('Company') . '</span>' : '';
        $html = '<div class="' . $with . '" style="align-items: center; ' . ( $displayFlex ? 'display: flex' : '' ) . '">' . $label . '<select id="sub_company" class="form-control select2" ' . ( $multiple ? 'multile' : '' ) . '>'; 
        $userCompanies = $userModel->getUsersCompanies($_SESSION['user_id'],true,true);
        $html .= companySelectorOptions($userCompanies,$userSubCompany,0,$id);

        $html .= '</select></div>';

        echo $html;
    }
}

if (!function_exists("companySelectorOptions")) {
    function companySelectorOptions($userCompanies,$userSubCompany,$dept=0,$id=0)
    {
        $html="";
        $newUserCompanies =[];
        foreach ($userCompanies as $key => $company) {
            if( $company['main_id'] != $id ) continue;

            if($company['position'] == 3) $newUserCompanies[name_dept($company['zip_code'])][] = $company;
            else $html .= '<option ' . ($company['id'] == $userSubCompany ? 'selected' : null) . ' value="' . $company['id'] . '">' . str_repeat('&nbsp;', 3 * $dept) . $company['fiscal_name'] . '</option>';
            $html .=companySelectorOptions($company['children'],$userSubCompany,$dept+1,$id);
        }
        foreach ($newUserCompanies as $k => $companies) {
            $html .= '<optgroup label="' . str_repeat('&nbsp;', 3 * $dept) . $k . '">';
            foreach ($companies as $company) {
                $html .= '<option ' . ($company['id'] == $userSubCompany ? 'selected' : null) . ' value="' . $company['id'] . '">' . str_repeat('&nbsp;', 3 * $dept) . $company['fiscal_name'] . '</option>';
            }
            $html .= '</optgroup>';
        }

        return $html;
    };
}