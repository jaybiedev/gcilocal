<?php
require_once(get_template_directory() . '/lib/classes/AbstractComponent.php');


class BackgroundImage extends AbstractComponent {
    
    function getContent() {

        $largeTopTxt = $this->getAttribute('largeTopTxt');
        $largeBtmTxt = $this->getAttribute('largeBtmTxt');
        $image_url = $this->getAttribute('image_url');
        $smallTxt = $this->getAttribute('smallTxt');
        $bannerBtnTxt = $this->getAttribute('bannerBtnTxt', 'Learn More');
        $bannerBtnLink = $this->getAttribute('bannerBtnLink');
        $bannerBtnWin = $this->getAttribute('bannerBtnWin');

        $html =<<<HTML
            <span class="txt">
                <span class="desc">
                    <strong>{$largeTopTxt}</strong>
                    <span class="sub">{$largeBtmTxt}</span>
                    <span class="sub">{$smallTxt}</span>
                </span>
                <span class="btn large">
                    <a href="{$bannerBtnLink}" {$bannerBtnWin}>{$bannerBtnTxt}</a>
                </span>
            </span>
            <span class="bg-img" style="background:url({$image_url}) center top no-repeat;background-size:cover;"></span>
HTML;
        return $html;
    }
}