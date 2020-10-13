<?php
namespace App\Models;

use CodeIgniter\Model;

class VisualsLibModel extends Model
{

    protected $dbCategory = 'visuals_categories';
    protected $dbCompany_feat = 'visuals_company_features';
    protected $dbVisuals = 'visuals';
    protected $db;

    /*
     * ['id_category' => 1, 'id_parent' => 0, 'name' => 'Landing', 'created' => time()],
      ['id_category' => 2, 'id_parent' => 0, 'name' => 'Email', 'created' => time()],
      ['id_category' => 3, 'id_parent' => 0, 'name' => 'SMS', 'created' => time()],
     * ['id_category' => 4, 'id_parent' => 0, 'name' => 'Banners', 'created' => time()],
     */

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Get Category
     * @param type $data
     * @return type
     */
    public function getCategory($data = false)
    {
        $retour = false;
        $builder = $this->db->table($this->dbCategory);

        if (isset($data['id_category']) && !isset($data['id_parent'])):
            $builder->where('id_category', $data['id_category']);
        endif;
        if (isset($data['id_parent']) && !isset($data['id_category'])):
            $builder->where('id_parent', $data['id_parent']);
        endif;

        //Both
        if (isset($data['id_category']) && isset($data['id_parent'])):
            $builder->where('id_category', $data['id_category'])
                ->orWhere('id_parent', $data['id_parent']);
        endif;

        $query = $builder->get();
        if (isset($data['id_category']) && !isset($data['id_parent'])):
            $result = $query->getRowArray();
        else:
            $result = $query->getResultArray();
        endif;

        if ($result):
            $retour = $result;
        endif;

        return $retour;
    }

    /**
     * Format Category
     */
    public function formatCategory($data = false)
    {
        $retour = false;
        if ($get = $this->getCategory($data)):
            $retour = array();
            foreach ($get as $k => $v) :
                if ($v['id_parent'] == 0):
                    $retour[$v['id_category']] = $v;
                    $retour[$v['id_category']]['created_date'] = format_date($v['created'], 'Y-m-d H:i:s', true);
                    $retour[$v['id_category']]['updated_date'] = format_date($v['updated'], 'Y-m-d H:i:s', true);
                else:
                    $retour[$v['id_parent']]['subCategory'][$v['id_category']] = $v;
                    $retour[$v['id_parent']]['subCategory'][$v['id_category']]['created_date'] = format_date($v['created'], 'Y-m-d H:i:s', true);
                    $retour[$v['id_parent']]['subCategory'][$v['id_category']]['updated_date'] = format_date($v['updated'], 'Y-m-d H:i:s', true);
                endif;

            endforeach;
        endif;
        return $retour;
    }

    /**
     * Form category
     */
    public function formCategory($data = false)
    {
        $form = [
            'id_category' => [
                'field' => 'id_category',
                'label' => 'Category',
                'post' => isset($data['id_category']) ? $data['id_category'] : '',
                'options' => $this->optionsCategory(),
                'rules' => 'required'
            ],
        ];
        return $form;
    }

    /**
     * Form category
     */
    public function formFeatures($data = false)
    {
        $form = [
            'id_company' => [
                'field' => 'id_company',
                'label' => 'Company',
                'post' => isset($data['id_company']) ? $data['id_company'] : '',
                'options' => $this->optionsCompanies(),
                'rules' => 'required'
            ],
            'name' => [
                'field' => 'name',
                'label' => 'Feature name',
                'post' => isset($data['name']) ? $data['name'] : '',
                'rules' => 'required'
            ],
            'comment' => [
                'field' => 'comment',
                'label' => 'Comment',
                'post' => isset($data['comment']) ? $data['comment'] : '',
                'rules' => 'string'
            ],
        ];
        return $form;
    }

    public function optionsCompanies()
    {
        return ['2' => 'DEMO 1'];
    }

    /**
     * Get Company Features
     * @param type $data
     */
    public function getCompanyFeatures($data = false)
    {
        $retour = false;
        $builder = $this->db->table($this->dbCompany_feat);

        if (isset($data['id_client_feature'])):
            $builder->where('id_client_feature', $data['id_client_feature']);
        endif;
        if (isset($data['main_company_id'])):
            $builder->where('main_company_id', $data['main_company_id']);
        endif;

        $query = $builder->get();
        if (isset($data['id_client_feature'])):
            $result = $query->getRowArray();
        else:
            $result = $query->getResultArray();
        endif;

        if ($result):
            $retour = $result;
        endif;


        return $retour;
    }

    public function formVisual($data = false)
    {
        $visualRules = '';
        $visualLabel = 'Visual code / url';
        $visualType = 'textarea';
        if (!empty($data['id_category'])):
            if ($cat = $this->getCategory(['id_category' => $data['id_category']])):
                switch ($cat['id_parent']):
                    case 1:
                        $visualLabel = 'Landing URL';
                        $visualType = 'text';
                        $visualRules .= '|valid_url';
                        break;
                    case 2:
                        $visualLabel = 'Email HTML code';
                        break;
                    case 3:
                        $visualLabel = 'SMS Text';
                        $visualRules .= '|max_length[140]';
                        break;
                    case 4:
                        $visualLabel = 'Banner image URL';
                        $visualType = 'text';
                        $visualRules .= '|valid_url';
                        break;
                endswitch;
            endif;
        endif;
        $form = [
            'main_company_id' => [
                'field' => 'main_company_id',
                'label' => 'Company',
                'post' => isset($data['main_company_id']) ? $data['main_company_id'] : '',
                'options' => $this->optionsCompanies(),
                'extra' => ['class' => 'custom-select select2'],
                'rules' => 'required'
            ],
//            'id_category' => [
//                'field' => 'id_category',
//                'label' => 'Category',
//                'post' => isset($data['id_category']) ? $data['id_category'] : '',
//                'rules' => 'required',
//                'options' => $this->optionsCategories(),
//                'extra' => ['class' => 'custom-select select2'],
//            ],
            'id_user' => [
                'field' => 'id_user',
                'label' => 'Id user',
                'post' => isset($data['id_user']) ? $data['id_user'] : '',
                'rules' => 'required'
            ],
            'name' => [
                'field' => 'name',
                'label' => 'Visual name',
                'post' => isset($data['name']) ? $data['name'] : '',
                'rules' => 'required'
            ],
            'comment' => [
                'field' => 'comment',
                'label' => 'Comment',
                'post' => isset($data['comment']) ? $data['comment'] : '',
                'rules' => 'string'
            ],
//            'visual' => [
//                'field' => 'visual',
//                'label' => $visualLabel,
//                'post' => isset($data['visual']) ? $data['visual'] : '',
//                'rules' => 'required' . $visualRules,
//                'type' => $visualType
//            ],
        ];
        return $form;
    }

    public function formVisualCode($parent_category_id = false, $data = false)
    {
        //$parent_category_id = 1;
        if (!empty($data['id_category']) && !$parent_category_id):
            if ($cat = $this->getCategory(['id_category' => $data['id_category']])):
                $parent_category_id = $cat['id_parent'];
            endif;
        endif;
        $addID = [];
        $visualRules = '';
        $rules_url_sms = 'string';
        $visualLabel = 'Visual code / url';
        $visualType = 'textarea';
        switch ($parent_category_id):
            case 1:
                $visualLabel = 'Landing URL';
                $visualType = 'text';
                $visualRules = '|valid_url|regex_match[/^(?:([^:]*)\:)?\/\/(.+)$/]';
                break;
            case 2:
                $visualLabel = 'Email HTML code';
                break;
            case 3:
                $visualLabel = 'SMS Text';
                $visualRules = '|max_length[140]';
                $rules_url_sms = 'required|valid_url|regex_match[/^(?:([^:]*)\:)?\/\/(.+)$/]';
                $addID = ['id' => 'visual-sms'];
                break;
            case 4:
                $visualLabel = 'Banner image URL';
                $visualType = 'text';
                $visualRules .= '|valid_url|regex_match[/^(?:([^:]*)\:)?\/\/(.+)$/]';
                break;
            default:

                break;
        endswitch;

        return $form = [
            'parent_category_id' => [
                'field' => 'parent_category_id',
                'label' => 'parent_category_id',
                'post' => $parent_category_id,
                'rules' => 'required'
            ],
            'id_category' => [
                'field' => 'id_category',
                'label' => 'Category',
                'post' => isset($data['id_category']) ? $data['id_category'] : '',
                'rules' => 'required',
                'options' => $this->optionsCategories(),
                'extra' => ['class' => 'custom-select select2'],
            ],
            'sms_url' => [
                'field' => 'sms_url',
                'label' => 'SMS URL',
                'post' => isset($data['sms_url']) ? $data['sms_url'] : '',
                'rules' => $rules_url_sms,
                'type' => $visualType,
                'addID' => $addID
            ],
            'visual' => [
                'field' => 'visual',
                'label' => $visualLabel,
                'post' => isset($data['visual']) ? $data['visual'] : '',
                'rules' => 'required' . $visualRules,
                'type' => $visualType,
                'addID' => $addID
            ],
        ];
    }

    public function formVisualVisibility($data = false)
    {
        return $form = [
            'visibility' => [
                'field' => 'visibility',
                'label' => 'Visibility',
                'post' => isset($data['visibility']) ? $data['visibility'] : 0,
                'rules' => 'required',
                'options' => $this->optionsVisibility(),
            ]
        ];
    }

    public function optionsCategories()
    {
        $cat = $this->formatCategory();
        $options = ['' => trad('Select category')];
        foreach ($cat as $id_cat => $cat) :
            //$options[$cat['id_category']] = $cat['name'];
            if (isset($cat['subCategory']) && is_array($cat['subCategory']) && count($cat['subCategory']) > 0):
                foreach ($cat['subCategory'] as $id_sc => $sc) :
                    $options[$cat['name']][$sc['id_category']] = $sc['name'];
                endforeach;
            else:
            endif;
        endforeach;
        return $options;
    }

    public function optionsVisibility()
    {
        return [
            0 => trad('Invisible for network'), 1 => trad('Visible for network'), 2 => trad('Visible and useful for network')
        ];
    }

    /**
     * Form Features Visual
     * @param type $features
     * @param type $data
     * @return array
     */
    public function form_visual_features($features, $data = false)
    {
        $retour = false;
        if (!empty($features) && is_array($features) && count($features) > 0):
            $retour = [];
            foreach ($features as $k => $v) :
                $retour += [
                    $v['id_client_feature'] => [
                        'field' => 'feature_' . $v['id_client_feature'],
                        'label' => $v['name'],
                        'post' => isset($data['feature_' . $v['id_client_feature']]) ? $data['feature_' . $v['id_client_feature']] : '',
                        'rules' => 'string'
                    ],
                ];
            endforeach;
        endif;
        return $retour;
    }

    /**
     * Get Visuals
     * @param type $data
     * return array
     */
    public function getVisuals($data = false)
    {
        $retour = false;
        $builder = $this->db->table($this->dbVisuals);

        if (isset($data['id_visual'])):
            $builder->where('id_visual', $data['id_visual']);
        endif;
        if (isset($data['id_company'])):
            $builder->where('id_company', $data['id_company']);
        endif;
        if (isset($data['id_category'])):
            $builder->where('id_category', $data['id_category']);
        endif;
        if (isset($data['id_user'])):
            $builder->where('id_category', $data['id_user']);
        endif;

        $query = $builder->get();
        if (isset($data['id_visual'])):
            $result = $query->getRowArray();
        else:
            $result = $query->getResultArray();
        endif;

        if ($result):
            $retour = $result;
        endif;

        return $retour;
    }

    /**
     * Get href Urls from an html email
     * @param type $html
     * @return type
     */
    public function getReplaceUrlFromHtml($html)
    {
        $return = false;
        preg_match_all("'\<a.*?href=\"(.*?)\".*?\>(.*?)\<\/a\>'si", $html, $match);
        $new_html = $html;
        if ($match) :
            $urls = [];
            $i = 1;
            $hrefs = array_unique($match[1]);
            foreach ($hrefs as $k => $v) :
                $newlink = '{{link_' . $i . '}}';
                $urls[$v] = array(
                    'original' => $v,
                    'replace' => $newlink,
                );
                $i++;
                $new_html = str_replace($v, $newlink, $new_html);
            endforeach;
            $urls = array_values($urls);
            $return = [
                'html' => $new_html,
                'urls' => $urls
            ];
        endif;
        return $return;
    }

    public function insertUpdateVisual($post, $table, $id_visual = false)
    {
        $return = false;

        /**
         * Traitement des différents type de visuels
         */
        echo $id_visual;
        //Pour un insert d'un html
        if (!empty($post['parent_category_id'])):

            switch ($post['parent_category_id']):
                case 1: //Landing URL

                    break;
                case 2: // EMail

                    if (!$id_visual && !empty($post['visual'])):
                        echo $post['parent_category_id'];
                        $html = $this->getReplaceUrlFromHtml($post['visual']);
                        echo '<pre style="background-color:#fff; color:#000">';
                        var_dump($html);
                        echo '</pre>';
                    endif;
                    break;
                case 3: //SMS

                    break;
                case 4: //Banner

                    break;

            endswitch;
        endif;
        //$send = $this->utils->insertOrUpdate($post, 'visuals', $id_visual);

        return $return;
    }

    public function create_url_thumbnails($url, $mobile = false, $new = false)
    {
        $retour = false;
        $params = [
            "access_key" => "fdfd206543454e638aca899a9f998975",
            "url" => $url,
            'width' => 1920,
            'height' => 1080,
            'response_type' => 'json',
            'thumbnail_width' => 1200,
            'no_cookie_banners' => true,
            'no_ads' => true,
            'no_tracking' => true,
            'quality' => 100
        ];

        if ($mobile):
            $params['user_agent'] = urlencode('Mozilla/5.0 (Linux; Android 10; SNE-LX1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile');
            $params['width'] = 414;
            $params['height'] = 896;
            $params['thumbnail_width'] = 675;
        endif;

        if ($new):
            $params['fresh'] = true;
        endif;

        $api = 'https://api.apiflash.com/v1/urltoimage';
        $client = \Config\Services::curlrequest();
        $response = $client->request('POST', $api, [
            'form_params' => $params
        ]);
        if ($response->getStatusCode() == 200):
            $retour = json_decode($response->getBody(), true);
        endif;
        return $retour;
    }

    public function generateThumbnail($category_parent, $visual, $regen = false)
    {
        $return = false;
        $thumb = false;
        $this->utils = \Config\Services::utils($this->db);
        switch ($category_parent):
            case 1: //Landing
                //On génère la miniature via API
                $is_mobile = in_array($visual['id_category'], [18]) ? true : false;
                $view = $is_mobile ? 'landing-mobile' : 'landing-desktop';

                if ($regen || empty($visual['thumbnail'])):
                    if ($thumb_api = $this->create_url_thumbnails($visual['visual'], $is_mobile, $regen)):
                    
                        //On enregistre l'image
                        $content_img = file_get_contents($thumb_api['url']);
                        $img_name = url_title($visual['name']) . '-' . $visual['id_visual'] . '.jpg';
                        if (file_put_contents(ROOTPATH . VISUAL_THUMB . $img_name, $content_img)):
                            $thumb = $img_name;
                            //On upd la base
                            $upd = $this->utils->insertOrUpdate(['thumbnail' => $img_name], 'visuals', ['id_visual' => $visual['id_visual']]);
                        endif;
                    endif;
                else:
                    $thumb = $visual['thumbnail'];
                endif;
                if ($thumb):
                    $return = view('visualsLib/thumbnailViews/' . $view, ['visual_name' => $thumb]);
                endif;
                break;
            case 2: //Email
                $label = 'Email HTML code';
                $return = view('visualsLib/thumbnailViews/html-frame', $visual);
                break;
            case 3: //SMS
                $label = 'SMS Text';
                $return = $visual['id_category'] == 22 ? view('visualsLib/thumbnailViews/sms-oneclick', $visual) : view('visualsLib/thumbnailViews/sms', $visual) ;
                break;
            default :
                //Rien
                break;
        endswitch;
        return $return;
    }
}
