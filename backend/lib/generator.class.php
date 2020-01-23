<?php
require_once __DIR__ . '/baseHtmlHandler.class.php';
class htmlGenerator extends baseHtmlHandler{
  private $options = [];
  public $Js = [];
  private $Css = [];
  public $environment = 'local';
  public $environmentConfig = [
    'local' => [
      'rootUrl' => 'http://localhost:3000/'
    ]
  ];

  public function __construct($options = NULL) {
    if($options === NULL) {
      $this->options = [];
    }
    else {
      $this->setOptions($options);
    }
  }

  public function setOptions($options) {
    $this->options = $this->extendsOptions($this->options, $options);
  }

  private function extendsOptions($options_origion, $options) {
    if($options === NULL) {
      return $options_origion;
    }
    return array_merge($options_origion, $options);
  }

  /**
   * Render for button, icon buttons.
   *
   * @param [type] $path, path to template.
   * @param [type] $contents,
   *  an array with keys:
   *    text => button text
   *    url => button url
   * @param [type] $options,
   *  an array with keys:
   *    buttonElement/color => button background color
   *    buttonElement/txt_position => text position
   *    buttonElement/icon => button icon name
   *    buttonElement/icon_button_size => button size (normal or short)
   * @return void
   */
  public function renderButtonElement($contents = NULL, $options = NULL){
    $path = '../layout_blocks/button.element.html';
    $options = $this->extendsOptions($this->options, $options);

    list('text' => $text, 'url' => $url) = $contents;

    list(
      'buttonElement/color' => $color,
      'buttonElement/txt_position' => $txt_position,
      'buttonElement/icon' => $icon,
      'buttonElement/icon_button_size' => $icon_button_size) = $options;

    $needle = array(
      '/%text%/i', // $text
    );
    $replacements = array(
      "$1$text$3",
    );

    if(!empty($color)) {
      $color = preg_replace('/^bg-/i', '', $color);
      $needle[] = '/%bg_color%/';
      $replacements[] = "$1bg-$color$3";
    }
    if(!empty($txt_position)) {
      $txt_position = preg_replace('/^kd_button--txt-align-/i', '', $txt_position);
      $needle[] = '/%txt_position%/';
      $replacements[] = "$1kd_button--txt-align-$txt_position$3";
    }
    if(!empty($icon)) {
      $icon = preg_replace('/^icon\s/i', '', $icon);
      $icon = preg_replace('/^icon-/i', '', $icon);
      $needle[] = '/%icon%/';
      if(preg_match('/^\_icon\-/i', $icon)) {
        $replacements[] = "$1icon $icon$3";
      }
      else {
        $replacements[] = "$1icon icon-$icon$3";
      }
    }
    if(!empty($icon_button_size)) {
      $needle[] = '/%icon_text_distance%/';
      $replacements[] = "$1icon_button_size-$icon_button_size$3";
    }

    if($url !== NULL) {
      $needle[] = '/(href=\")(.*)(#\")/i';
      $replacements[] = "$1$url$3";
    }
    return preg_replace($needle, $replacements, file_get_contents($path));
  }

  public function renderTxtElement($contents = NULL, $options = NULL) {
    return $this->renderTextElement(null, $contents, $options);
  }

  public function renderTextElement($with, $contents = NULL, $options = NULL) {
    $options = $this->extendsOptions($this->options, $options);
    switch ($with) {
      case 'card':
        $path = '../layout_blocks/card_text.block.html';
        list('card_text' => $card_text) = $contents;
      break;
      case 'img':
        $path = '../layout_blocks/text_img.element.html';
        list('ImageSrc' => $src, 'ImageUrl' => $url, 'ImageTitle' => $title, 'ImageAlt' => $alt) = $contents;
      break;
      default:
        $path = '../layout_blocks/text.element.html';
      break;
    }
    list('text' => $text, 'collapsed_text' => $collapsed_text, 'img' => $img, 'url' => $url) = $contents;
    list('textElement/txt_position' => $txt_position) = $options;


    $img = $this->renderImageElement(['ImageSrc' => $src, 'ImageUrl' => $url, 'ImageTitle' => $title, 'ImageAlt' => $alt]);

    if($collapsed_text) {
      // Add html for expandable.
      $button = '<p><a class="collapsible-button collapsed" href="javascript:void(0);">Read More</a></p>';
    }

    $needle = array(
      '/%text%/i',
      '/%collapsed_text%/i',
      '/%button%/i',
      '/%img%/i',
      '/%card_text%/i',
      '/(text\-)(\w+)(\"|\s)/i', // $txt_position
    );
    $replacements = array(
      "$1$text$3",
      "$1$collapsed_text$3",
      "$1$button$3",
      "$1$img$3",
      "$1$card_text$3",
      "$1$txt_position$3"
    );

    $tplFile = file_get_contents($path);
    $tplFile = $this->attachScripts($tplFile);
    return preg_replace($needle, $replacements, $tplFile);
  }

  /**
   * Caller to addCss and addJs
   *
   * @param [type] $tpl
   * @return void
   */
  protected function attachScripts($tpl) {
    $needle = [];
    $replacements = [];
    if(preg_match_all('/%@import\s(.+):\s*(.+)%/im', $tpl , $matches)) {
      foreach($matches[2] AS $key => $assetPath) {
        $method = "add{$matches[1][$key]}";
        $this->$method($assetPath);
      }
      $needle[] = '/%@import\s(.+):\s*(.+)%/im';
      $replacements[] = '';
    }
    return preg_replace($needle, $replacements, $tpl);
  }

  public function renderImageBlockElement($contents = NULL, $options = NULL) {
    $options = $this->extendsOptions($this->options, $options);
    $path = '../layout_blocks/image_block.element.html';
    list(
      'text' => $text, 'url' => $url, 'title' => $title,
      'ImageSrc' => $src, 'ImageTitle' => $imgTitle, 'ImageAlt' => $alt,
    ) = $contents;
    list(
      'imageBlockElement/bgcolor' => $bgcolor,
      'imageBlockElement/icon' => $icon,
      'imageBlockElement/txt_position' => $txt_position,
      'imageBlockElement/txt_align' => $txt_align
    ) = $options;
    if($bgcolor === null) {
      $bgcolor = 'transparent';
    }

    if($url !== null) {
      if(preg_match('/localhost/i', $url)) {
        $target = '_self';
      }
      else {
        $target = '_blank';
      }
      if($bgcolor === 'white' || $bgcolor === 'transparent') {
        $text .= '<div class="icon_block_footer"><div><span class="like-a">Read more</span></div></div>';
      }
    }

    if($src) {
      $img = $this->renderImageElement(['ImageSrc' => $src, 'ImageTitle' => $imgTitle, 'ImageAlt' => $alt]);
    }
    else {
      $img = '';
    }

    $needle = array(
      0 => '/%text%/i', // $text
      1 => '/%url%/i', // $url
      2 => '/%bgcolor%/i', // $bgcolor
      3 => '/%img%/i', // $icon
      4 => '/%target%/i', // $target
      5 => '/%txt_position%/i', // $txt_position
      6 => '/%title%/i', // $title
      7 => '/%txt_align%/i'
    );

    $bgcolor = preg_replace('/^bg-/i', '', $bgcolor);
    $txt_position = preg_replace('/^icon_block--txt-align-/i', '', $txt_position);

    if($title) {
      $title = "<h3>$title</h3>";
    }
    if(!$txt_align) {
      $txt_align = 'left';
    }

    $replacements = array(
      "$1$text$3",
      "$1$url$3",
      "$1bg-$bgcolor$3",
      "$1$img$3",
      "$1$target$3",
      "$1image_block--txt-poisition-$txt_position$3",
      "$1$title$3",
      "$1image_block--txt-align-$txt_align$3"
    );

    if($url === null) {
      // If no url provided, convert <a> to <div>
      $needle[] = '/<a/i';
      $needle[] = '/<\/a/i';
      $needle[] = '/href=.*target=".*%"/i';
      $replacements[] = "$1<div$3";
      $replacements[] = "$1</div$3";
      $replacements[] = "";
    }
    return preg_replace($needle, $replacements, file_get_contents($path));
  }

  public function renderIconBlockElement($contents = NULL, $options = NULL) {
    $options = $this->extendsOptions($this->options, $options);
    $path = '../layout_blocks/icon_block.element.html';
    list('text' => $text, 'url' => $url, 'title' => $title) = $contents;
    list(
      'iconBlockElement/bgcolor' => $bgcolor,
      'iconBlockElement/icon' => $icon,
      'iconBlockElement/txt_position' => $txt_position) = $options;

    if($bgcolor === null) {
      $bgcolor = 'transparent';
    }

    if($url !== null) {
      if(preg_match('/localhost/i', $url)) {
        $target = '_self';
      }
      else {
        $target = '_blank';
      }
      if($bgcolor === 'white' || $bgcolor === 'transparent') {
        $text .= '<div class="icon_block_footer"><div><span class="like-a">Read more</span></div></div>';
      }
    }

    $needle = array(
      0 => '/%text%/i', // $text
      1 => '/%url%/i', // $url
      2 => '/%bgcolor%/i', // $bgcolor
      3 => '/%icon%/i', // $icon
      4 => '/%target%/i', // $target
      5 => '/%txt_position%/i', // $txt_position
      6 => '/%title%/i', // $title
    );

    $bgcolor = preg_replace('/^bg-/i', '', $bgcolor);
    $icon = preg_replace('/^icon\s/i', '', $icon);
    $icon = preg_replace('/^icon-/i', '', $icon);
    $txt_position = preg_replace('/^icon_block--txt-align-/i', '', $txt_position);

    if($title) {
      $title = "<h3>$title</h3>";
    }

    $replacements = array(
      "$1$text$3",
      "$1$url$3",
      "$1bg-$bgcolor$3",
      "$1icon icon-$icon$3",
      "$1$target$3",
      "$1icon_block--txt-align-$txt_position$3",
      "$1$title$3"
    );
    if(preg_match('/^_icon-/i', $icon)) {
      $replacements[3] = "$1icon $icon$3";
    }

    if($url === null) {
      // If no url provided, convert <a> to <div>
      $needle[] = '/<a/i';
      $needle[] = '/<\/a/i';
      $needle[] = '/href=.*target=".*%"/i';
      $replacements[] = "$1<div$3";
      $replacements[] = "$1</div$3";
      $replacements[] = "";
    }
    return preg_replace($needle, $replacements, file_get_contents($path));
  }

  public function renderEntityListingBlock($path, $col = NULL) {
    // TODO
    if($col === NULL) {
      $col = 1;
    }
    $needle = array(
      '/(entity\-listing\-)(\d+)(\s|\")/i', // $col
    );
    return preg_replace($needle, ["$1\\$col$3"], file_get_contents($path));
  }

  public function renderVideoBlock($video_url = 'https://www.youtube.com/embed/n1aYQgqUDBs', $video_ratio = null, $tpl = null) {
    $path = '../layout_blocks/video.element.html';
    if($tpl === null) {
      $tpl = file_get_contents($path);
    }
    if($video_ratio === null) {
      $video_ratio = '16:9';
    }
    if(preg_match('/\?/', $video_url)) {
      $video_url .= '&rel=0';
    }
    else {
      $video_url .= '?rel=0';
    }
    switch($video_ratio) {
      case '4:3':
        $video_ratio_class = 'video_ratio_4_3';
      break;
      case '16:9':
        $video_ratio_class = 'video_ratio_16_9';
      break;
    }
    $needle = array(
      '/(src=\")(.+)(\")/i', // $video_url
      '/%video_ratio_class%/i', // $video_height
    );
    return preg_replace($needle, ["$1$video_url$3", $video_ratio_class], $tpl);
  }

  public function renderVideosElement($contents = NULL, $options = NULL){
    $path = '../layout_blocks/videos.element.html';
    list('videos' => $videos) = $contents;
    list(
      'videosElement/numOfItemsShown' => $numOfItemsShown,
      'videosElement/ratio' => $ratio
    ) = $options;
    if($ratio === null) {
      $ratio = '16:9';
    }
    if(count($videos) <= 1) {
      $numOfItemsShown = 0;
      return $this->renderVideoBlock($videos[0]['url'], $videos[0]['ratio'] ? $videos[0]['ratio'] : $ratio);
    }
    else {
      $tpl = $this->getTpl($path, 'videos_tpl');
      $videosOutputHtml = '';
      foreach($videos AS $key => $video) {
        $videosOutputHtml .= $this->renderVideoBlock($video['url'], $video['ratio'] ? $video['ratio'] : $ratio, $tpl);
      }
      $needle = array(
        '/%numOfItemsShown%/'
      );
      return preg_replace($needle, [$numOfItemsShown], $this->attachScripts($this->writeWithTpl($path, 'videos_tpl', $videosOutputHtml)));
    }
  }

  public function renderCardBlock($path, $card_position = 'right') {
    // TODO
    $needle = array(
      '/(card\-)(\w+)(\s|\")/i', // $card_position
    );
    return preg_replace($needle, ["$1$card_position$3"], file_get_contents($path));
  }

  public function renderCardVideoBlock($path, $card_position = 'right', $video_height = 420) {
    // TODO
    $needle = array(
      '/(card\-)(\w+)(\s|\")/i', // $card_position
      '/(height=")(.+)(\"\ssrc)/i', // $video_height
    );
    return preg_replace($needle, ["$1$card_position$3", "$1 $video_height$3"], file_get_contents($path));
  }

  public function renderGlossaryBlock($data) {
    $data = json_encode(
      $data
    ); // Sorting should be done before passing to glossary tpl.
    $path = '../layout_blocks/glossary.block.html';
    $needle = array(
      '/%data%/i', // $data
    );
    $replacements = array(
      "$1$data$3"
    );

    return preg_replace($needle, $replacements, file_get_contents($path));
  }

  public function renderIconText($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/icon_text.element.html';
    $tpl = file_get_contents($path);
    list(
      'icon' => $icon,
      'label' => $label,
      'url' => $url
    ) = $contents;

    if(!$url) {
      $tpl = str_replace('<a href="%url%">', '', $tpl);
      $tpl = str_replace('</a>', '', $tpl);
    }

    $needle = [
      '/%icon%/i',
      '/%label%/i',
      '/%url%/i',
    ];
    $replacements = [
      $icon,
      $label,
      $url,
    ];
    return preg_replace($needle, $replacements, $tpl);
  }

  public function renderWorkspaceInfoCardElement($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/workspace_infocard.element.html';
    list(
      'projectLogoUrl' => $projectLogoUrl,
      'projectVisibility' => $projectVisibility,
      'projectMemberCount' => $projectMemberCount,
      'projectHomeUrl' => $projectHomeUrl,
      'projectContactEmail' => $projectContactEmail,
    ) = $contents;
    $tpl = file_get_contents($path);

    $text = '';
    if($projectVisibility) {
      $text .= $this->renderIconText(['icon' => 'icon-lock', 'label' => "<span>$projectVisibility</span>"]);
    }
    if($projectMemberCount) {
      $text .= $this->renderIconText(['icon' => 'icon-group', 'label' => "<span><b>$projectMemberCount members</b></span>"]);
    }
    if($projectHomeUrl) {
      $text .= $this->renderIconText(['icon' => 'icon-house', 'label' => 'Homepage', 'url' => $projectHomeUrl]);
    }
    if($projectContactEmail) {
      $text .= $this->renderIconText(['icon' => 'icon-avatar', 'label' => $projectContactEmail, 'url' => $projectContactEmail]);
    }

    $needle = [
      '/%logo%/i',
      '/%text%/i',
    ];
    $replacements = [
      $projectLogoUrl,
      $text,
    ];
    return preg_replace($needle, $replacements, $tpl);
  }

  /**
   * Render accordions.
   *
   * @param [type] $contents
   * @param [type] $options
   * @return void
   */
  public function renderAccordionsElement($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/accordion.element.html';
    $singleAccordionTpl = $this->getTpl($path, 'accordion_tpl');
    $accordions = $contents['accordions'];
    $accordionsHtml = '';
    if($contents['text']) {
      /**
       * Generator for build will collect sub accordions and pass via $contents['text']
       */
      $accordionsHtml = $contents['text'];
    }
    else {
      foreach($accordions AS $accordion) {
        $accordionsHtml .= $this->renderAccordion(['AccordionTitle' => $accordion['title'], 'AccordionBody' => $accordion['body']]);
      }
    }
    $accordionsHtml = $this->writeWithTpl($path, 'accordion_tpl', $accordionsHtml);
    $accordionsHtml = $this->attachScripts($accordionsHtml);
    return $accordionsHtml;
  }

  /**
   * Render a single accordion.
   *
   * @param [type] $contents
   * @param [type] $options
   * @return void
   */
  protected function renderAccordion($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/accordion.element.html';
    $singleAccordionTpl = $this->getTpl($path, 'accordion_tpl');
    $needle = [
      '/%title%/i',
      '/%body%/i',
    ];
    $replacements = [
      $contents['AccordionTitle'],
      $contents['AccordionBody'],
    ];
    return preg_replace($needle, $replacements, $singleAccordionTpl);
  }

  public function renderSliderElement($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/slider.element.html';
    list('numOfItemsShown' => $numOfItemsShown) = $options;
    $slides = $contents['slides'];
    if(!$numOfItemsShown) {
      $numOfItemsShown = 1;
    }
    $slidesHtml = '';
    if($contents['text']) {
      /**
       * Generator for build will collect sub accordions and pass via $contents['text']
       */
      $slidesHtml = $contents['text'];
    }
    else {
      foreach($slides AS $slide) {
        $slidesHtml .= $this->renderSlide(['text' => $slide['text']]);
      }
    }

    $sliderHtml = $this->writeWithTpl($path, 'slide_tpl', $slidesHtml);
    $needle = [
      '/%numOfItemsShown%/i',
    ];
    $replacements = [
      $numOfItemsShown,
    ];
    $sliderHtml = $this->attachScripts($sliderHtml);
    return preg_replace($needle, $replacements, $sliderHtml);
  }

  protected function renderSlide($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/slider.element.html';
    $singleSlideTpl = $this->getTpl($path, 'slide_tpl');
    $needle = [
      '/%slide%/i',
    ];
    $replacements = [
      $contents['text'],
    ];
    return preg_replace($needle, $replacements, $singleSlideTpl);
  }

  public function renderAppletElement($contents = NULL) {
    $path = '../layout_blocks/applet.element.html';
    $needle = [
      '/%html%/i'
    ];
    $replacements = [
      $contents['text']
    ];
    return preg_replace($needle, $replacements, file_get_contents($path));
  }

  public function renderStyleElement($dataObj) {
    $valuesMapping = [
      '#111111' => $dataObj->primary_color, // primary
      '#111112' => $dataObj->primary_color_contrast . '!important', // primary contrast
      '#111113' => $dataObj->primary_color_hover, // primary-hover
      '#111114' => $dataObj->primary_color_bg, // primary-bg
      '.theme--templateuse ' => '',
      '#222221' => $dataObj->secondary_color . '!important',
      '#333331' => $dataObj->tertiary_color . '!important',
    ];
    $dataArray = (Array) $dataObj;
    foreach($dataArray AS $key => $value) {
      if(!preg_match('/^(primary|secondary|tertiary)_/i', $key)) {
        $valuesMapping['"#' . $key . '"'] = $value;
      }
    }
    $needle = array_keys($valuesMapping);
    $replacements = array_values($valuesMapping);

    // Adding '!important'
    // foreach($replacements AS &$replace){
    //   if(!empty($replace)) {
    //     $replace .= '!important';
    //   }
    // }

    return str_replace($needle, $replacements, $this->defaultCssTemplate);
  }

  public function renderHtml($buildSnippets) {
    //RenderHTML, collects $this->outputJs, $this->outputCss, $this->renderStyleElement
    if($buildSnippets->environment) {
      $this->environment = $buildSnippets->environment;
    }
    $html = '';
    $components = implode('', $this->renderComponents($buildSnippets->children));
    $html .= $this->outputCss();
    $html .= $this->renderStyleElement($buildSnippets->projectOptions);
    $html .= $components;
    $html .= $this->outputJs();
    $html .= $this->renderBuildSnippetsEncodedCodes($buildSnippets);

    return '<div class="container">' . preg_replace(['/\n|\r\n/', '/%br%/'], ['', "\n"], $html) . '</div>';
  }

  private function renderBuildSnippetsEncodedCodes($buildSnippets) {
    return "<!--%br%
Copy the code and paste into Kahootz's Applet.%br%
----------------------------------------------%br%
%datasrc:" . $this->encodeBuildSnippets($buildSnippets) . '%-->';
  }

  public function outputJs() {
    $output = '';
    $timestamp = time();
    foreach($this->Js AS $path) {
      $script = file_get_contents($path);
      $output .= "<script>$script</script>";
    }
    return $output;
  }

  public function outputCss() {
    $output = '';
    $timestamp = time();
    foreach($this->Css AS $path) {
      $styles = file_get_contents($path);
      $styles = str_replace('"../fonts/', '"' . $this->environmentConfig[$this->environment]['rootUrl'] . 'css/fonts/', $styles);
      $output .= "<style>$styles</style>";
    }
    return $output;
  }

  public function renderRowBlock($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/row.block.html';
    $options = $this->extendsOptions($this->options, $options);
    list('text' => $text) = $contents;
    list(
      'columnLayout' => $columnLayout) = $options;

    $classes = [
      '1/1' => ' ',
      '1/2' => 'kd_columns-2',
      '1/3+2/3' => 'kd_columns-2 kd_columns_partition-1-2',
      '2/3+1/3' => 'kd_columns-2 kd_columns_partition-2-1',
      '1/3' => 'kd_columns-3',
      '1/4' => 'kd_columns-4',
      '1/5' => 'kd_columns-5',
    ];

    if(empty($classes[$columnLayout])) {
      die('Missing class for row: ' . $columnLayout);
    }

    $needle = array(
      '/%kd_cols_layout%/i', // $columnLayout
    );
    $replacements = array(
      "$1$classes[$columnLayout]$3",
    );

    $needle[] = '/%content%/i'; // $text
    $replacements[] = "$1$text$3";

    return preg_replace($needle, $replacements, file_get_contents($path));
  }

  public function renderColBlock($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/col.block.html';
    list('text' => $text) = $contents;
    // Special block, it doesn't have any options.
    $needle = array(
      '/%content%/i', // $text
    );
    $replacements = array(
      "$1$text$3",
    );
    return preg_replace($needle, $replacements, file_get_contents($path));
  }

  public function renderHrElement($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/hr.element.html';
    return file_get_contents($path);
  }

  public function renderImageElement($contents = NULL, $options = NULL) {
    $path = '../layout_blocks/image.element.html';
    list('ImageSrc' => $src, 'ImageUrl' => $url, 'ImageTitle' => $title, 'ImageAlt' => $alt) = $contents;
    $target = '_self';
    if($url !== null) {
      if(preg_match('/localhost/i', $url)) {
        $target = '_self';
      }
      else {
        $target = '_blank';
      }
    }
    $needle = array(
      '/%src%/i', // $src
      '/%alt%/i', // $alt
      '/%title%/i', // $title
      '/%href%/i', // $url
      '/%target%/i', // $target
    );
    $replacements = array(
      "$1$src$3",
      "$1$alt$3",
      "$1$title$3",
      "$1$url$3",
      "$1$target$3",
    );
    if($url === null) {
      // If no url provided, convert <a> to <div>
      $needle[] = '/<a/i';
      $needle[] = '/<\/a/i';
      $needle[] = '/href=.*target=".*%"/i';
      $replacements[] = "$1<div$3";
      $replacements[] = "$1</div$3";
      $replacements[] = "";
    }
    return preg_replace($needle, $replacements, file_get_contents($path));
  }

  public function renderComponents($buildSnippets) {
    // Render the whole html by reading passed in buildSnippets which was json.
    // gzencode() to compress buildSnippets and include it in html.
    $componentsHtmlArray = [];
    foreach($buildSnippets AS $component) {
      if(!empty($component->children)) {
        $childrenHtmlArray = $this->renderComponents($component->children);
        if($component->component === 'SliderElement') {
          foreach($childrenHtmlArray AS &$childHtml) {
            $childHtml = $this->renderSlide(['text' => $childHtml]);
          }
        }
        $component->contents = (object)['Text' => implode('', $childrenHtmlArray)]; // replace content with children html
      }

      $methods = [
        'RowBlock' => 'renderRowBlock',
        'ColBlock' => 'renderColBlock',
        'ButtonElement' => 'renderButtonElement',
        'HrElement' => 'renderHrElement',
        'ImageElement' => 'renderImageElement',
        'TextElement' => 'renderTxtElement',
        'IconBlockElement' => 'renderIconBlockElement',
        'VideosElement' => 'renderVideosElement',
        'AppletElement' => 'renderAppletElement',
        'AccordionsElement' => 'renderAccordionsElement',
        'SliderElement' => 'renderSliderElement',
        'AccordionFieldCollection' => 'renderAccordion',
        'TextFieldCollection' => 'renderTxtElement',
        'ImageFieldCollection' => 'renderImageElement',
        'WorkspaceInfoCardElement' => 'renderWorkspaceInfoCardElement',
      ];
      if(method_exists($this, $methods[$component->component])) {
        $componentsHtmlArray[] =
          $this->{$methods[$component->component]}(
            $this->convertContents($component->contents),
            $this->convertOptions($component->options, $component->component)
          );
      } else {
        die('Missing method for: ' . $component->component);
      }

    }
    return $componentsHtmlArray;
  }

  private function convertContents($contents) {
    $return = ['text' => null, 'url' => null];
    if(!empty($contents->BlockText)) {
      $return['text'] = $contents->BlockText;
      $return['url'] = $contents->BlockUrl;
      $return['title'] = $contents->BlockTitle;
    }
    if(!empty($contents->ButtonText)) {
      $return['text'] = $contents->ButtonText;
      $return['url'] = $contents->ButtonUrl;
    }
    if(!empty($contents->Text)) {
      $return['text'] = $contents->Text;
      $return['url'] = $contents->Url;
    }
    if(!empty($contents->TextCollapsed)) {
      $return['collapsed_text'] = $contents->TextCollapsed;
    }
    if(!empty($contents->ImageSrc)) {
      $return['ImageSrc'] = $contents->ImageSrc;
      $return['ImageUrl'] = $contents->ImageUrl;
      $return['ImageTitle'] = $contents->ImageTitle;
      $return['ImageAlt'] = $contents->ImageAlt;
    }
    if(!empty($contents->videos)) {
      $return['videos'] = [];
      foreach($contents->videos AS $video_url) {
        $return['videos'][] = ['url' => $video_url];
      }
    }
    if(!empty($contents->AccordionTitle)) {
      $return['AccordionTitle'] = $contents->AccordionTitle;
      $return['AccordionBody'] = $contents->AccordionBody;
    }
    if(!empty($contents->projectLogoUrl)) {
      $return = (Array) $contents;
    }
    return $return;
  }

  private function convertOptions($options, $component) {
    switch($component){
      case 'RowBlock':
        return [
          'columnLayout' => $options->columnLayout->value
        ];
      break;
      case 'ColBlock':
      break;
      case 'IconBlockElement':
        return [
          'iconBlockElement/bgcolor' => $options->_bgColor,
          'iconBlockElement/icon' => $options->_icon,
          'iconBlockElement/txt_position' => $options->iconblock_txtPosition
        ];
      break;
      case 'ButtonElement':
        return [
          'buttonElement/color' => $options->_bgColor,
          'buttonElement/txt_position' => $options->btn_txtPosition,
          'buttonElement/icon' => $options->_icon,
          'buttonElement/icon_button_size' => $options->_iconButtonSize
        ];
      break;
      case 'VideosElement':
        return [
          'videosElement/numOfItemsShown' => $options->numToShow,
          'videosElement/height' => $options->height
        ];
      break;
      case 'SliderElement':
        return [
          'numOfItemsShown' => $options->numToShow,
        ];
      break;
    }
  }

  public function addJs($path) {
    $this->Js[$path] = '../js/' . $path;
  }

  public function addCss($path) {
    $this->Css[$path] = '../css/' . $path;
  }

  protected function getTpl($path, $tplName) {
    $tpl = file_get_contents($path);
    $matches = null;
    if(preg_match('/<!\-\-\s###' . $tplName . '_START###\s\-\->(.*)<!\-\-\s###' . $tplName . '_END###\s\-\->/s', $tpl, $matches)) {
      return $matches[1];
    }
    else {
      return null;
    }
  }

  protected function writeWithTpl($path, $tplName, $value) {
    $tpl = file_get_contents($path);
    return preg_replace('/<!\-\-\s###' . $tplName . '_START###\s\-\->.*<!\-\-\s###' . $tplName . '_END###\s\-\->/s', $value, $tpl);
  }
}