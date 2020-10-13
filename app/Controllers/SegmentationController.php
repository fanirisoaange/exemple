<?php
namespace App\Controllers;
use App\Libraries\Layout;

class SegmentationController extends BaseController
{
	protected $campaignModel;

    public function __construct()
    {
        $this->campaignModel = model('CampaignModel', true, $this->db);
    }

    public function create() {
        $post = $this->request->getPost();

        $form_visual_view_data = [
            'form' => $this->campaignModel->formVisual($post, 1),
            'validation' => $this->validation,
        ];

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_css([
            LIBRARY . 'bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min',
            LIBRARY . 'codemirror/lib/codemirror',
            LIBRARY . 'codemirror/theme/dracula',
        ]);
        $layout->add_js([
            LIBRARY . 'bootstrap-switch/js/bootstrap-switch.min',
            LIBRARY . 'moment/moment-with-locales.min',
            LIBRARY . 'bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min',
            LIBRARY . 'codemirror/lib/codemirror',
            LIBRARY . 'codemirror/mode/xml/xml',
            LIBRARY . 'codemirror/mode/javascript/javascript',
            LIBRARY . 'codemirror/mode/css/css',
            LIBRARY . 'codemirror/mode/htmlmixed/htmlmixed',
            LIBRARY . 'codemirror/addon/selection/active-line',
            ASSETS . 'js/campaigns'
        ]);

        $data = array(
            'title' => trad('Campaign detail', 'campaign'),
            'metadescription' => trad('Campaign detail', 'campaign'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => trad('Campaign detail', 'campaign'),
            'campaign_id' => 1,
            'form_visual' => view('campaigns/forms/visual', $form_visual_view_data),
            'current_uri' => uri_string()
        );

        return $layout->view('campaigns/detail', $data);
    }
}