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
        if (!empty($post['id_category'])):
            if ($cat = $this->VLM->getCategory(['id_category' => $post['id_category']])):
                $is_sms = false;
                switch ($cat['id_parent']):
                    case 1:
                        $label = 'Landing URL';
                        break;
                    case 2:
                        $label = 'Email HTML code';
                        break;
                    case 3:
                        $label = 'SMS Text';
                        $is_sms = 'visual-sms';
                        break;
                    case 4:
                        $label = 'Banner image URL';
                        break;
                endswitch;

                if ($cat['id_parent'] == 2 || $cat['id_parent'] == 3):
                    $return = ['return' => 'ok', 'type' => 'textarea', 'label' => $label, 'is_sms' => $is_sms, 'parent_category_id' => $cat['id_parent']];
                else:
                    $return = ['return' => 'ok', 'type' => 'text', 'label' => $label, 'is_sms' => $is_sms, 'parent_category_id' => $cat['id_parent']];
                endif;
            endif;
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
        if (!empty($post['parent_category_id'])):
            $regen = !empty($post['regen']) ? true : false;
            if ($thumbnail = $this->VLM->generateThumbnail($post['parent_category_id'], $post, $regen)):
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
