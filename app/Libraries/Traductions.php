<?php
namespace App\Libraries;

class Traductions
{

    private $db;
    public $languages;
    public $lang_default = 'en';
    public $file_prefix = 'Custom_';

    /*
      |===============================================================================
      | Construct
      |===============================================================================
     */

    public function __construct($db)
    {
        $this->db = $db;
        $languages = new \Config\Languages();
        $this->languages = $languages->langs;
    }

    /**
     * Get traductions
     * 
     * @param type $data
     * @return type
     */
    public function get_traductions($data = false)
    {
        $retour = false;
        $builder = $this->db->table('traductions t');
        $builder->join('traductions_zone tz', 'tz.id_zone = t.id_zone', 'inner');
        if (isset($data['lang'])):
            $builder->where('lang', $data['lang']);
        endif;
        if (isset($data['id_trad'])):
            $builder->where('id_trad', $data['id_trad']);
        endif;
        if (isset($data['id_zone'])):
            $builder->where('t.id_zone', $data['id_zone']);
        endif;
        if (isset($data['zone'])):
            $builder->where('tz.zone', $data['zone']);
        endif;
        if (isset($data['token'])):
            $builder->where('token', $data['token']);
        endif;
        $query = $builder->get();
        if (isset($data['id_trad'])):
            $result = $query->getRowArray();
        else:
            $result = $query->getResultArray();
        endif;

        if ($result):
            $retour = $result;
        endif;


        return $retour;
    }
    /*     * *
     * Format les traductions
     */

    public function formatTraductions($trads)
    {
        $retour = false;
        if ($trads && is_array($trads) && count($trads) > 0):
            $retour = array();
            $tokens = array();
            foreach ($trads as $k_trad => $trad) :
                if ($trad['lang'] == $this->lang_default):
                    $retour[$trad['token']] = $trad;
                endif;
                /**
                 * On implémente toutes les langues par défaut
                 */
                if (!array_key_exists($trad['token'], $tokens)):
                    foreach ($this->languages as $k_lang => $lang):
                        $retour[$trad['token']]['traductions'][$k_lang] = false;
                    endforeach;
                endif;
                $retour[$trad['token']]['traductions'][$trad['lang']] = $trad;

                $tokens[$trad['token']] = $trad['token'];
            endforeach;
        endif;
        return $retour;
    }

    public function getZones($data = false)
    {
        $retour = false;
        $builder = $this->db->table('traductions_zone');

        if (isset($data['id_zone'])):
            $builder->where('id_zone', $data['id_zone']);
        endif;
        if (isset($data['zone'])):
            $builder->where('zone', $data['zone']);
        endif;

        $query = $builder->get();
        if (isset($data['id_zone']) || isset($data['zone'])):
            $result = $query->getRowArray();
        else:
            $result = $query->getResultArray();
        endif;
        if ($result):
            $retour = $result;
        endif;


        return $retour;
    }

    public function addZone($zone)
    {
        $retour = false;
        $builder = $this->db->table('traductions_zone');
        if ($builder->insert(['zone' => $zone])):
            $retour = $this->db->getLastQuery();
        endif;
        return $retour;
    }

    /**
     * Add traduction
     * @param type $data
     */
    public function addTraduction($content, $zone)
    {
        //Test if this traductions already exist
        if (!$exist_trad = $this->get_traductions(['token' => md5($zone . '-' . $content), 'zone' => $zone])):
            if ($exist_zone = $this->getZones(['zone' => $zone])):
                $id_zone = $exist_zone['id_zone'];
            else:
                $id_zone = $this->addZone($zone);
            endif;
            $builder = $this->db->table('traductions');
            $builder->insert([
                'id_zone' => $id_zone,
                'token' => md5($zone . '-' . $content),
                'content' => $content,
                'lang' => $this->lang_default
            ]);
        endif;

        return $content;
    }

    public function form_selectController($data = false)
    {
        $form = [
            'id_zone' => [
                'field' => 'id_zone',
                'label' => 'Zone',
                'post' => isset($data['id_zone']) ? $data['id_zone'] : '',
                'options' => $this->optionsZone(),
                'rules' => 'required'
            ],
        ];
        return $form;
    }

    /**
     * Get Controller list
     */
    public function getZone()
    {
        $retour = false;
        $builder = $this->db->table('traductions_zone');
        $query = $builder->get();

        $result = $query->getResultArray();

        if ($result):
            $retour = $result;
        endif;
        return $retour;
    }

    /**
     * Options controller
     */
    public function optionsZone()
    {
        $retour = array('' => 'Select zone');
        if ($zones = $this->getZone()):
            foreach ($zones as $k => $v) :
                $retour[$v['id_zone']] = ucfirst($v['zone']);
            endforeach;
        endif;
        return $retour;
    }

    /**
     * Edit traduction
     */
    public function editTraduction($post, $traductions)
    {
        $retour = false;
        if (!empty($post['token']) && (isset($post['traductions']) && is_array($post['traductions']))):
            $upd_tab = array();
            $insert_tab = array();

            $builder = $this->db->table('traductions');
            foreach ($post['traductions'] as $lang => $trad):
                if (isset($traductions[$post['token']]['traductions'][$lang]) && is_array($traductions[$post['token']]['traductions'][$lang])):
                    $upd_tab = [
                        'content' => $trad
                    ];
                    //Update
                    if (count($upd_tab) > 0):
                        $builder->update($upd_tab, ['token' => $post['token'], 'lang' => $lang]);
                    endif;
                else:
                    $insert_tab[] = [
                        'lang' => $lang,
                        'content' => $trad,
                        'token' => $post['token'],
                        'id_zone' => $post['id_zone']
                    ];
                endif;
            endforeach;
            //Insert new language
            if (count($insert_tab) > 0):
                $builder->insertBatch($insert_tab);
            endif;
            $retour = true;
        endif;
        return $retour;
    }
    /* <?php
     * return [
      'test'   => 'UN SUPER TEST EN',
      ];

     */

    public function genTraductionsFiles()
    {
        $retour = array();
        $path = APPPATH . 'Language' . DIRECTORY_SEPARATOR;
        if ($tradList = $this->get_traductions()):
            $head_file = '<?php' . "\n" . 'return [' . "\n";
            $foot_file = '];';
            $files = array();
            foreach ($tradList as $k => $v) :
                if (!isset($files[$v['lang']][$v['zone']])):
                    $files[$v['lang']][$v['zone']] = $head_file;
                endif;
                $files[$v['lang']][$v['zone']] .= "'" . $v['token'] . "' => '" . str_replace("'", "\'", $v['content']) . "', \n";
            endforeach;

            if (is_array($files) && count($files) > 0):
                foreach ($files as $lang => $datazone) :
                    foreach ($datazone as $zone => $file):
                        if (!is_dir($path . $lang . DIRECTORY_SEPARATOR)):
                            mkdir($path . $lang . DIRECTORY_SEPARATOR, 0777);
                        endif;
                        $filePath = $path . $lang . DIRECTORY_SEPARATOR . $this->file_prefix . $zone . '.php';
                        //if exist   
                        if (file_exists($filePath)):
                            unlink($filePath);
                        endif;
                        if ($this->create_fichier($filePath, $file . $foot_file)):
                            $retour[$lang][$zone] = [
                                'path' => $filePath,
                                'write' => 'OK'
                            ];
                        else:
                            $retour[$lang][$zone] = [
                                'path' => $filePath,
                                'write' => 'NOK'
                            ];
                        endif;
                    endforeach;
                endforeach;
            endif;
        endif;
        return $retour;
    }

    public function create_fichier($nom, $content)
    {
        $f = fopen($nom, 'a+') or show_error("Can't open $nom");
        if (!fwrite($f, $content . "\n")) {
            show_error("Can't write line ");
        }
        fclose($f) or show_error("Can't close $nom");
        return true;
    }
}
