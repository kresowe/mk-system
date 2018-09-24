<?php

class PageContent {
	private $tagHeader;
	private $content;

	public function getHeader(){
		return $this->tagHeader;
	}

	public function setHeader($text){
		$this->tagHeader = $text;
	}

	public function setContent($text) {
		$this->content = $text;
	}

	public function getContent() {
		if (file_exists("contents/" . $this->content . ".tpl")) {
			include("contents/" . $this->content . ".tpl");
		} 
		else {
			echo "<p>Strona nie została znaleziona. <a href=\"/mk-system/\">Przejdź na stronę główną Systemu.</a></p>";
		}
	}
}

?>