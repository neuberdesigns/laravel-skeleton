<?php
class ZurbPresenter extends Illuminate\Pagination\Presenter {

    public function getActivePageWrapper($text)
    {
        return '<li class="current"><span class="show-for-sr">'.$text.'</span>'.$text.'</li>';
    }

    public function getDisabledTextWrapper($text)
    {
        return '<li class="disabled">'.$text.'<span class="show-for-sr">'.$text.'</span></li>';
    }

    public function getPageLinkWrapper($url, $page, $rel = null)
    {
        return '<li><a href="'.$url.'">'.$page.'</a></li>';
    }

}

?>
