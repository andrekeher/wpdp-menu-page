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
    
    public function setFormFunction(callable $formFunction)
    {
        $this->formFunction = $formFunction;
    }

    public function setSaveFunction(callable $saveFunction)
    {
        $this->saveFunction = $saveFunction;
    }
    
    public function init()
    {
        add_action('admin_init', function () {
            if ($_POST && isset($_GET['page']) && $_GET['page'] === $this->menuSlug) {
                call_user_func($this->saveFunction);
            }
        });
        
        if (is_subclass_of($this, 'AndreKeher\WPDP\MenuPage')) {
            add_action('admin_menu', function () {
                add_submenu_page($this->parentSlug, $this->pageTitle, $this->menuTitle, $this->capability, $this->menuSlug, array($this, 'form'), $this->iconUrl, $this->position);
            });
        } else {
            add_action('admin_menu', function () {
                add_menu_page($this->pageTitle, $this->menuTitle, $this->capability, $this->menuSlug, array($this, 'form'), $this->iconUrl, $this->position);
            });
        }
    }
    
    public function form()
    {
        if (isset($_GET['page']) && $_GET['page'] === $this->menuSlug) {
            call_user_func($this->formFunction);
        }
    }
}
