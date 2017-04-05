<?php namespace App\ClassesExtendidas;

use Illuminate\Pagination\BootstrapThreePresenter;

class CustomPaginationPresenter extends BootstrapThreePresenter {

    public function render()
    {
        if ($this->hasPages())
        {
            return sprintf(
               '<ul class="custom-pagination">%s</ul>',
                $this->getLinks()
            );
        }

        return '';
    }
    public function getPageLinkWrapper($url, $page, $rel = null)
    {
        if($page < 10){
            return '<li><a href="'.$url.'">0'.$page.'</a></li>';
        }else{
            return '<li><a href="'.$url.'">'.$page.'</a></li>';
        }
        
    }
}