<?php
namespace AndreKeher\WPDP;

class MenuPage
{
    protected $pageTitle;
    protected $menuTitle;
    protected $menuSlug;
    protected $formFunction;
    protected $saveFunction;
    protected $capability;
    protected $iconUrl;
    protected $position;

    public function __construct(
        $pageTitle,
        $menuTitle,
        $menuSlug,
        $capability = 'manage_options',
        $iconUrl = '',
        $position = null
    ) {
        $this->pageTitle = $pageTitle;
        $this->menuTitle = $menuTitle;
        $this->menuSlug = $menuSlug;
        $this->capability = $capability;
        $this->iconUrl = $iconUrl;
        $this->position = $position;
    }
    
    public function setFormFunction($formFunction)
    {
        $this->formFunction = $formFunction;
    }

    public function setSaveFunction($saveFunction)
    {
        $this->saveFunction = $saveFunction;
    }
    
    public function init()
    {
        add_action('admin_init', function () {
            if ($_POST) {
                call_user_func($this->saveFunction);
            }
        });
        add_action('admin_menu', function () {
            add_menu_page($this->pageTitle, $this->menuTitle, $this->capability, $this->menuSlug, function () {
                if (isset($_GET['page']) && $_GET['page'] === $this->menuSlug) {
                    call_user_func($this->formFunction);
                }
            }, $this->iconUrl, $this->position);
        });
    }
}
