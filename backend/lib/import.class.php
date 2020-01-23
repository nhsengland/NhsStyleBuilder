<?php
require_once __DIR__ . '/baseHtmlHandler.class.php';
class importHandler extends baseHtmlHandler{
  public function parseHtml($html) {
    $search = preg_match('/tasrc\:(.+)\%\-\-\>/', $html, $matches);
    $buildingScript = $this->decodeBuildSnippets($matches[1]);
    if(!$buildingScript->projectInfo) {
      $buildingScript->projectInfo = (Object) array('title' => '');
    }
    return json_encode($buildingScript);
  }
}