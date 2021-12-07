<?php namespace Skripteria\Snowflake\Components;

use Cms\Classes\ComponentBase;
use Skripteria\Snowflake\Models\Page;
use Skripteria\Snowflake\Models\Layout;
use Cms\Classes\Page as CmsPage;
use Winter\Storm\Support\Facades\Markdown;

class SfPage extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Snowflake',
            'description' => 'skripteria.snowflake::lang.components.sf_page_desc',
            'icon' => 'icon-snowflake'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function getBlueprintsOptions() {
        // $blueprints = Page::getClassMethods();
        $pages = CmsPage::all();
        $op = [];
        foreach ($pages as $page) {
            if ($page->hasComponent('blueprint_page')) {
                $op[] = [$page->basefilename => $page->title];
            }
            \Log::info($op);
        }

         return $op;

    }

    public function onRun() {
        $page = Page::where('filename', $this->page->baseFileName)->with('elements')->first();
        $layout = $this->page->layout->baseFileName;
        // dump($layout);

        // dump($this->page);

        if (!$page) return;
        $layout = Layout::where('filename', $this->page->layout->baseFileName)->with('elements')->first();
        // dump($layout->elements);
        // $this->page["testkey"] = "Testkey Layout";
        // $this->page["testkey2"] = "Testkey auf layoutebene";
        if ($layout) $page->elements = $page->elements->merge($layout->elements);

        foreach ($page->elements as $element) {
            switch ($element->type_id) {
                case 3:
                    if ($element->image) {
                        $path = $element->image->getPath();
                    } else {
                        $path = '';
                    }

                    $this->page[$element->cms_key] = $path;
                    $this->page[$element->cms_key.'_alt'] = $element->alt;
                break;

                case 5:
                    $this->page[$element->cms_key] = Markdown::parse($element->content);
                break;

                case 10:
                    if ($element->file) {
                        $path = $element->file->getPath();
                    } else {
                        $path = '';
                    }
                    $this->page[$element->cms_key] = $path;
                    $this->page[$element->cms_key.'_name'] = $element->filename;
                break;

                default:
                    $this->page[$element->cms_key] = $element->content;
            }

        }

    }
}
