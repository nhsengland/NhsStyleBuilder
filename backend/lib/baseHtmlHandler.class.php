<?php

class baseHtmlHandler{
  protected $defaultCssTemplate = '
<style>
.theme--templateuse .icon:before {
    color: #111111;
}

.theme--templateuse .bg-default.icon *, .theme--templateuse .bg-default.kd_button * {
    color: #222221;
}

.theme--templateuse .bg-default.icon:before, .theme--templateuse .bg-default.kd_button:before {
    color: #222221;
}

.theme--templateuse .main-column p a, .theme--templateuse .container p a, .theme--templateuse .main-column ul li a, .theme--templateuse .container ul li a, .theme--templateuse .main-column ol li a, .theme--templateuse
.container ol li a, .theme--templateuse .text p a, .theme--templateuse .card p a, .theme--templateuse p.icon a, .theme--templateuse .icon_block span.like-a, .theme--templateuse .image_block span.like-a, .theme--templateuse
.entity-listing .entity-item p a {
    color: #111111;
}

.theme--templateuse .icon.icon-right-arrow-on-circle:before, .theme--templateuse
.icon.icon-right-arrow:before {
    background-color: #111111;
}

.theme--templateuse .icon.icon-right-arrow-on-circle:before, .theme--templateuse
.icon.icon-right-arrow:before {
    color: #222221;
}

.theme--templateuse .icon.icon-right-arrow-on-circle:hover:before, .theme--templateuse
.icon.icon-right-arrow:hover:before {
    background-color: #111113;
}

.theme--templateuse .kd_glossary .kd_glossary_body > li > span {
    border-color: #111111;
}

.theme--templateuse .kd_accordions .kd_accordion > h2.active, .theme--templateuse .kd_accordions .kd_accordion > h2:hover {
    background-color: #111111;
}

.theme--templateuse .kd_accordions .kd_accordion > h2.active, .theme--templateuse .kd_accordions .kd_accordion > h2:hover {
    color: #222221;
}

.theme--templateuse .kd_accordions .kd_accordion > h2 {
    color: #111111;
}

.theme--templateuse .icon_block span.like-a {
    color: #111111;
}

/* --- BgColor --- */
.theme--templateuse .bg-default {
    background-color: #111111;
}

.theme--templateuse .bg-gray {
    background-color: "#medium_gray_color";
}

.theme--templateuse .bg-light-gray {
    background-color: "#light_gray_color";
}

.theme--templateuse .bg-dark-gray {
    background-color: "#dark_gray_color";
}

.theme--templateuse .bg-dark-blue {
    background-color: "#dark_blue_color";
}

.theme--templateuse .bg-blue {
    background-color: "#medium_blue_color";
}

.theme--templateuse .bg-light-blue {
    background-color: "#light_blue_color";
}

/* --- BgColor --- */
/* --- BgHoverColor --- */
.theme--templateuse a.bg-default.icon_block:hover, .theme--templateuse .bg-default.kd_button:hover {
    background-color: #111113;
}

.theme--templateuse a.bg-gray.icon_block:hover, .theme--templateuse .bg-gray.kd_button:hover {
    background-color: "#medium_gray_color_hover";
}

.theme--templateuse a.bg-dark-gray.icon_block:hover, .theme--templateuse .bg-dark-gray.kd_button:hover {
    background-color: "#dark_gray_color_hover";
}

.theme--templateuse a.bg-light-gray.icon_block:hover, .theme--templateuse .bg-light-gray.kd_button:hover {
    background-color: "#light_gray_color_hover";
}

.theme--templateuse a.bg-dark-blue.icon_block:hover, .theme--templateuse .bg-dark-blue.kd_button:hover {
    background-color: "#dark_blue_color_hover";
}

.theme--templateuse a.bg-blue.icon_block:hover, .theme--templateuse .bg-blue.kd_button:hover {
    background-color: "#medium_blue_color_hover";
}

.theme--templateuse a.bg-light-blue.icon_block:hover, .theme--templateuse .bg-light-blue.kd_button:hover {
    background-color: "#light_blue_color_hover";
}

/* --- BgHoverColor --- */
.theme--templateuse .kd_glossary .kd_glossary_filter > div input {
    border-color: #111111;
}

.theme--templateuse .kd_glossary .kd_glossary_body > li > ul > li.highlighted {
    background-color: #111114;
}

.theme--templateuse .main-column h1, .theme--templateuse .container h1, .theme--templateuse .builder h1 {
    font-size: "#h1_font_size";
}

.theme--templateuse .main-column h1, .theme--templateuse .container h1, .theme--templateuse .builder h1 {
    color: "#h1_color";
}

.theme--templateuse .main-column h2, .theme--templateuse .container h2, .theme--templateuse .builder h2 {
    font-size: "#h2_font_size";
}

.theme--templateuse .main-column h2, .theme--templateuse .container h2, .theme--templateuse .builder h2 {
    color: "#h2_color";
}

.theme--templateuse .main-column h3, .theme--templateuse .container h3, .theme--templateuse .builder h3 {
    font-size: "#h3_font_size";
}

.theme--templateuse .main-column h3, .theme--templateuse .container h3, .theme--templateuse .builder h3 {
    color: "#h3_color";
}

.theme--templateuse .main-column p, .theme--templateuse .container p, .theme--templateuse .main-column ul li, .theme--templateuse .container ul li, .theme--templateuse
.main-column ol li, .theme--templateuse .container ol li, .theme--templateuse .text p, .theme--templateuse
.icon_block span.like-a, .theme--templateuse .image_block span.like-a, .theme--templateuse .entity-listing .entity-item h3, .theme--templateuse
.entity-listing .entity-item p, .theme--templateuse
.kd_glossary .kd_glossary_filter > div label, .theme--templateuse
.kd_glossary .kd_glossary_body > li > span {
    color: "#p_color";
}

.theme--templateuse .main-column p, .theme--templateuse .container p, .theme--templateuse .main-column ul li, .theme--templateuse .container ul li, .theme--templateuse
.main-column ol li, .theme--templateuse .container ol li, .theme--templateuse .text p, .theme--templateuse
.icon_block span.like-a, .theme--templateuse .image_block span.like-a, .theme--templateuse .entity-listing .entity-item h3, .theme--templateuse
.entity-listing .entity-item p, .theme--templateuse
.kd_glossary .kd_glossary_filter > div label, .theme--templateuse
.kd_glossary .kd_glossary_body > li > span {
    font-size: "#p_font_size";
}
</style>
';

  protected function encodeBuildSnippets($buildSnippets) {
    return base64_encode(json_encode($buildSnippets));
  }

  protected function decodeBuildSnippets($buildSnippetsEncoded) {
    return json_decode(base64_decode($buildSnippetsEncoded));
  }

  protected function debug($var) {
    file_put_contents(__DIR__ . '/log.txt', print_r($var, true), FILE_APPEND);
  }
}