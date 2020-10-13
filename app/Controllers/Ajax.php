<?php
namespace App\Controllers;

class Ajax extends BaseController
{
    /*
     * Dispatch sur les fonctions ajax
     */

    public function index()
    {
        $return = ['return' => 'ko'];
        $post = $this->request->getPost();

        $tabMethode = get_class_methods($this);
        $action = isset($post['action']) ? $post['action'] : '';

        if (isset($post['action']) && in_array($post['action'], $tabMethode)):
            $action = $post['action'];
            if ($return_ajax = $this->$action($post)):
                $return = $return_ajax;
            endif;
        else:
            $return = ['return' => 'ko', 'message' => 'action inconnu'];
        endif;
        echo json_encode($return + ['action' => $action]);
    }

    public function validate_feature($post)
    {
        $this->VLM = model('VisualsLibModel', true, $this->db);
        $form_new_feature = $this->VLM->formFeatures($post);

        if ($this->validate($form_new_feature)):
            //Si ok on valide
            $id_feature = !empty($post['id_client_feature']) ? ['id_client_feature' => $post['id_client_feature']] : false;
            $return = ['message' => 'An error occured'];
            if ($send = $this->utils->insertOrUpdate($post, 'visuals_company_features', $id_feature)):
                if ($send['action'] == 'insert'):
                    $return = [
                        'return' => 'ok',
                        'message' => 'The new features is correctly added',
                        'addElem' => [
                            'blocId' => '#edit-feature',
                            'elem' => view('visualsLib/features_form', $post + ['id_client_feature' => $send['insertId']])
                        ]
                    ];
                else:
                    $return = ['return' => 'ok', 'message' => 'The features is correctly updated'];
                endif;
            endif;
        else:
            $return = ['return' => 'ko',
                'message' => '<b>Some fields are not valid !</b>',
                'errors' => $this->validation->getErrors()];
        endif;

        return $return;
    }

    public function getCategoryParent($post)
    {
        $this->VLM = model('VisualsLibModel', true, $this->db);
        if (!empty($post['category'])):
            $is_sms = false;

            switch ($post['category']):
                case 1:
                    $label = 'Email HTML code';
                    break;
                case 2:
                    $label = 'SMS Text';
                    break;
            endswitch;

            $return = ['return' => 'ok', 'type' => 'textarea', 'label' => $label, 'is_sms' => $is_sms, 'category' => $post['category']];
        endif;
        return $return;
    }

    /**
     * Generate Thumbnail for visual
     */
    public function generateVisualThumbnail($post)
    {
        $return = false;
        $this->VLM = model('VisualsLibModel', true, $this->db);
        if (!empty($post['category'])):
            $regen = !empty($post['regen']) ? true : false;
            if ($thumbnail = $this->VLM->generateThumbnail($post['category'], $post, $regen)):
                $return = $post;
                $return += [
                    'return' => 'ok',
                    'thumb' => $thumbnail
                ];
            endif;
        endif;
        return $return;
    }
}
