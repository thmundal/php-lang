<?php

define("CAP", 0x1);
define("CAPITALIZE", 0x1);

Class lang {
	private $language;
	private $default_language = "no";
	private $data = [];

	public function __construct($lang, $dir = "./lang") {
		$this->language = $lang;
		$err = false;

		// Load language file
		$this->data = json_decode(utf8_encode(file_get_contents($dir . "/" . $this->language .".lang")));

		switch (json_last_error()) {
		        case JSON_ERROR_NONE:
		        break;
		        case JSON_ERROR_DEPTH:
		            $err = ' - Maximum stack depth exceeded';
		        break;
		        case JSON_ERROR_STATE_MISMATCH:
		            $err = ' - Underflow or the modes mismatch';
		        break;
		        case JSON_ERROR_CTRL_CHAR:
		            $err = ' - Unexpected control character found';
		        break;
		        case JSON_ERROR_SYNTAX:
		            $err = ' - Syntax error, malformed JSON';
		        break;
		        case JSON_ERROR_UTF8:
		            $err = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
		        break;
		        default:
		            $err = ' - Unknown error';
		        break;
		    }

		if($err)
			throw new Exception($err);
	}

	public function get($type, $keyword, $flags) {
		$word = $this->data->{$type}->{$keyword};

		if($flags && (CAP || CAPITALIZE)) $word = ucfirst($word);

		if($word === NULL)
			throw new Exception("Could not find ".$keyword." in the language data.");

		return $word;
	}

	public function word($keyword, $flags = false) {
		$word = $this->get("words", $keyword, $flags);

		return $word;
	}
	public function phrase($keyword, $flags = false) {
		$word = $this->get("phrases", $keyword, $flags);

		return $word;
	}

	public function text($keyword, $flags = false) {
		$word = $this->get("text", $keyword, $flags);

		return $word;
	}
}

?>