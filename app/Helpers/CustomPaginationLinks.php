<?php namespace App\Custom;

use Illuminate\Pagination\BootstrapThreePresenter;

class CustomPaginationLinks extends BootstrapThreePresenter {

//<li><a href="#">&laquo;</a></li>
//<li><a href="#">1</a></li>
//<li><span>2</span></li>
//<li><a href="#">3</a></li>
//<li><a href="#">4</a></li>
//<li><a href="#">5</a></li>
//<li><a href="#">&raquo;</a></li>

    public function getActivePageWrapper($text)
    {
        return '<li class="active"><a href="#">'.$text.'</a></li>';
    }

    public function getDisabledTextWrapper($text)
    {
        return '<li class="disabled"><a href="#">'.$text.'</a></li>';
    }

    public function getAvailablePageWrapper($url, $page, $rel = null)
    {
        return '<li><a href="'.$url.'">'.$page.'</a></li>';
    }

    public function render()
    {
        if ($this->hasPages())
        {
            return sprintf(
                '%s %s %s',
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton()
            );
        }

        return '';
    }

}