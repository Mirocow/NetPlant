<?php

/*
 * Copyright (c) 2012, "Klaas Sangers"<klaas@webkernel.nl>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this 
 * list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, 
 * this list of conditions and the following disclaimer in the documentation 
 * and/or other materials provided with the distribution.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE 
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF 
 * THE POSSIBILITY OF SUCH DAMAGE.
 */
/**
 * Modifications by @bethrezen
 * - Added less support
 * - Fixed css minification when background-image is set using data
 * @todo Fix possible notices and warnings.
 */

/**
 * MinifyClientScript class file.
 */
Yii::import('ext.wbkrnl.CssCompressor');
Yii::import('ext.jsmin.JSMin');

/**
 * MinifyClientScript manages JavaScript and CSS stylesheets for views.
 */
class MinifyClientScript extends CClientScript {

	/**
	 * Path to the 'original' CSS
	 * @var string
	 */
	private $relativeCssPath;

	/**
	 * Path to the minify runtime files
	 * @var string
	 */
	private $minifyPath;
	
	/**
	 * The POS_* constants that this class will parse
	 * @var array
	 */
	private $minifyJsPositions;

	public $caching = true; //use cached less css file if available
    public $compress = false; //compress less css to 1 line



    protected function compileLess($less_input, $returnString = true)
    {
		//webroot of the application
        $basepath = Yii::getPathOfAlias('webroot')  . DIRECTORY_SEPARATOR;

        //store in runtime folder
        $cachePath = Yii::app()->runtimePath . DIRECTORY_SEPARATOR . 'minScript';
        if (!is_dir($cachePath)) {
            mkdir($cachePath, 0777, true);
        } else if (!is_writable($cachePath)) {
            throw new CException('LClientscript: ' . $cachePath . ' is not writable.');
        }

		//path to assets (default /assets)
		$assetspath = $cachePath . DIRECTORY_SEPARATOR;
        $assetName = md5($less_input);

        //reference where to store the temporary css file, before sending it to registerCSSFile
        $assetCssOrig = $assetspath . $assetName . '.css';

        $parsed = false;
		//only parse the file if caching is off and the file doesn't exist
		//Todo: Perhaps a check if the content is altered (date_modified) or file size changed
        //Todo: Use pathinfo to get the absolute url

        if(!$this->caching || !is_file($assetCssOrig)){
			//make the path absolute, but make sure basepath isn't in the url first.
            $sourcepath = $basepath . str_replace($basepath, '', $less_input);
            $sourcepath = str_replace('\\', '/', $sourcepath);
            $sourcepath = str_replace('//', '/', $sourcepath);

            if (is_file($sourcepath)) {
				//start parsing the less file and store it in assets folder
                file_put_contents($assetCssOrig, $this->parseLess($sourcepath));
            }
            else {
                throw new CException(__CLASS__.': Less stylesheet not found: '. $sourcepath);
            }
        }

        // return the parsed css string or file
        if ($returnString) {
            return $parsed;
        } else {
            return Yii::app()->getAssetManager()->publish($assetCssOrig);
        }
    }

    protected function parseLess($input)
    {
        $source = file_get_contents($input);
        $sourcedir = dirname($input);

        try {
			//include lessc library
            require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'include'. DIRECTORY_SEPARATOR . 'lessc.inc.php');

			//setup the parser
            $lessc = new lessc();
			if($this->compress){
				$lessc->setFormatter("compressed");
			}
			$lessc->importDir = $sourcedir; // Set the correct context when using @import

			//parse the file
            $parsed = $lessc->parse($source);
        } catch (exception $e) {
			throw new CException(__CLASS__.': Failed to compile less file with message: '.$e->getMessage().'.');
        }
        return $parsed;
    }

	/**
	 * Prepare the clientScript
	 */
	public function init() {
		$this->minifyPath = Yii::getPathOfAlias("application.runtime.MinifyClientScript");
		$this->minifyJsPositions=array(
			self::POS_BEGIN,
			self::POS_END,
			self::POS_HEAD,
			self::POS_LOAD,
			self::POS_READY,
		);
		parent::init();
	}

	/**
	 * Callback method for preg_replace_callback in renderHead()
	 * @param array $matches
	 * @return array 
	 */
	protected function cssReplaceCallback(array $matches) {
		if (substr($matches[1], 0, 1) == "/")
			return $matches[0];
		elseif (strpos($matches[1], "http") === 0 || strpos($matches[1], "data:") === 0) // fix for data: url's in css // @bethrezen
			return $matches[0];
		else
			return "url($this->relativeCssPath/$matches[1])";
	}

	/**
	 * Minify the css for optimal client-side performance
	 */
	protected function minifyCss() {
		$cssArray = array();
		// remember the original pcre backtrace limit, we're going to put it back to default
		$pcreBacktrackLimitOriginal = ini_get('pcre.backtrack_limit');
		// determine the filenames
		$filenames = array();
		foreach ($this->cssFiles as $url => $media) if (!isset($filenames[empty($media) ? 'default' : $media]))
				$filenames[empty($media) ? 'default' : $media] = $url;
			else
				$filenames[empty($media) ? 'default' : $media] .= $url;
		foreach ($filenames as $media => $filename) $filenames[empty($media) ? 'default' : $media] = preg_replace("/[^a-zA-Z0-9]+/", "-", $media) . "-" . hash('sha256', $filename) . ".css";
		// check whether the files need to be regenerated
		$compileCss = array();
		foreach ($filenames as $media => $filename) $compileCss[empty($media) ? 'default' : $media] = (isset($compileCss[empty($media) ? 'default' : $media]) && $compileCss[empty($media) ? 'default' : $media]) || YII_DEBUG || !file_exists($this->minifyPath . DIRECTORY_SEPARATOR . $filename);
		// append css files to the minified css store
		foreach ($this->cssFiles as $url => $media) {
			// skip if it's an external css
			if (strpos($url, "http") === 0 || strpos($url, "//") === 0)
				continue;
			// make sure $media is defined
			if (empty($media))
				$media = "default";
			// check if the file can be read
			if (strpos($url, Yii::app()->homeUrl) === false)
				continue;
			if (!is_readable(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . substr($url, strlen(Yii::app()->homeUrl))))
				continue; // NOTE KS - throw new CException("CSS file '" . Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . substr($url, strlen(Yii::app()->homeUrl)) . "' is not readable."); ?
			else
				unset($this->cssFiles[$url]);
			if (isset($compileCss[$media]) && !$compileCss[$media])
				continue;
			// initialize the css per media if necessary
			if (!isset($cssArray[$media]))
				$cssArray[$media] = "";
			$this->relativeCssPath = pathinfo($url, PATHINFO_DIRNAME);
			$css = trim(file_get_contents(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . substr($url, strlen(Yii::app()->homeUrl))));
			// don't parse this file if it's empty
			if (empty($css))
				continue;
			$cssLength = strlen($css);
			// when the css is bigger than the original pcre backtrace limit increase the limit
			if ($pcreBacktrackLimitOriginal < $cssLength)
				ini_set('pcre.backtrack_limit', $cssLength);
			// replace url()'s in the css
			if (preg_match("#url\s*\(\s*['\"]?([^'\"\)]+)['\"]?\)#", $css) > 0)
				$css = preg_replace_callback("#url\s*\(\s*['\"]?([^'\"\)]+)['\"]?\)#", array($this, "cssReplaceCallback"), $css);
			// decrease the pcre backtrace limit if required
			if ($pcreBacktrackLimitOriginal < $cssLength)
				ini_set('pcre.backtrack_limit', $pcreBacktrackLimitOriginal);
			// append the non-minified css
			$cssArray[$media].=$css;
		}
		foreach ($filenames as $media => $filename) {
			if ($compileCss[$media])
				file_put_contents("$this->minifyPath/$filenames[$media]", CssCompressor::deflate($cssArray[$media]), LOCK_EX);
			$this->registerLinkTag("stylesheet", "text/css", Yii::app()->assetManager->getPublishedUrl($this->minifyPath) . "/$filenames[$media]", $media == "default" ? null : $media);
		}
	}

	/**
	 * Minify the javascript files for optimal client-side performance
	 */
	protected function minifyScriptFileByPosition($position) {
		$jsCore = "";
		$js = "";
		$css = '';
		$coreScripts = array();
		// generate filename for core scripts
		if ($position===self::POS_HEAD)
			$filenameCore = "core-" . hash('sha256', serialize($this->coreScripts));
		$filename = '';
		// generate filename for other scripts
		if (isset($this->scriptFiles[$position]))
			$filename .= serialize($this->scriptFiles[$position]);
		$filename = hash('sha256', $filename);
		$publishedMinifyPath = Yii::app()->assetManager->getPublishedUrl($this->minifyPath);
		// determine if the JS still needs to be 'compiled'
		$compileCoreJs = $position===self::POS_HEAD && (YII_DEBUG || !file_exists($this->minifyPath . DIRECTORY_SEPARATOR . "$filenameCore.js"));
		$compileJs = YII_DEBUG || !file_exists($this->minifyPath . DIRECTORY_SEPARATOR . "$filename.js");
		if ($compileCoreJs)
			foreach ($this->coreScripts as $coreScript) foreach ($coreScript["js"] as $file) $coreScripts[] = $coreScript["baseUrl"] . DIRECTORY_SEPARATOR . $file;
		if (isset($this->scriptFiles[$position])) {
			foreach ($this->scriptFiles[$position] as $key => $scriptFile) {
				if (strpos($scriptFile, "http") === 0 || strpos($scriptFile, "//") === 0)
					continue;
				unset($this->scriptFiles[$position][$key]);
				if (!$compileJs && !$compileCoreJs)
					continue;
				// check if the file can be read
				if (!is_readable(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . substr($scriptFile, strlen(Yii::app()->homeUrl))))
					continue; // NOTE KS - throw new CException("CSS file '" . Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . substr($scriptFile, strlen(Yii::app()->homeUrl)) . "' is not readable."); ?
				// don't parse this file if it's empty
				$jsContent = trim(file_get_contents(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . substr($scriptFile, strlen(Yii::app()->homeUrl))));
				if (empty($jsContent))
					continue;
				// only 'compile' non-core JavaScript
				if ($compileCoreJs && in_array($scriptFile, $coreScripts))
					$jsCore.= "$jsContent\n";
				elseif ($compileJs)
					$js.=JSMin::minify($jsContent) . "\n";
			}
		}
		if ($compileCoreJs)
			file_put_contents($this->minifyPath . DIRECTORY_SEPARATOR . "$filenameCore.js", $jsCore, LOCK_EX);
		if ($compileJs)
			file_put_contents($this->minifyPath . DIRECTORY_SEPARATOR . "$filename.js", $js, LOCK_EX);
		if ($position===self::POS_HEAD)
			$this->registerScriptFile($publishedMinifyPath . "/$filenameCore.js", $position);
		$this->registerScriptFile($publishedMinifyPath . "/$filename.js", $position);
	}

	/**
	 * Inserts the scripts in the head section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderHead(&$output) {
		if (!is_dir($this->minifyPath))
			mkdir($this->minifyPath, 0775, true);
		$this->minifyCss();
		// if ($this->enableJavaScript)
		// 	foreach ($this->minifyJsPositions as $position)
		// 		$this->minifyScriptFileByPosition($position);
		// else
		// 	$this->scriptFiles = $this->scripts = null;
		// force rewriting of assets when we're debugging, otherwise be lazy
		Yii::app()->assetManager->publish($this->minifyPath);
		parent::renderHead($output);
	}

	/**
	 *
	 * @param string $id
	 * @param string $css
	 * @param string $media 
	 */
	public function registerCss($id, $css, $media = '') {
		return parent::registerCss($id, CssCompressor::deflate($css), $media);
	}

	/**
	 *
	 * @param string $id
	 * @param string $script
	 * @param int $position 
	 */
	public function registerScript($id, $script, $position = self::POS_READY) {
		return parent::registerScript($id, JSMin::minify($script), $position);
	}

	/**
	 * @param string $path
	 * @param string $media
	 * @param string $defaultAssetUrl
	 */
	public function registerCssFile($path, $media = '') {

		//if the file extension is .less, use the lessparser.
        $file_extension = end(explode('.',$path));
        if($file_extension == 'less'){
            $path = $this->compileLess($path, false);
        //    return parent::registerCssFile($path, $media);
        }

		$parsedPath = $this->parseAssetsPath($path);
		if ($parsedPath)
			return parent::registerCssFile($this->getFileUrl($parsedPath['assetsUrl'], $parsedPath['path']), (($media === null) ? '' : $media));
		else
			return parent::registerCssFile($path, $media);
	}

	/**
	 * @param string $path
	 * @param int $position
	 * @param string $defaultAssetUrl
	 */
	public function registerScriptFile($path, $position = 0) {
		$parsedPath = $this->parseAssetsPath($path);
		
		if ($parsedPath)
			return parent::registerScriptFile($this->getFileUrl($parsedPath['assetsUrl'], $parsedPath['path']), $position);
		else
			return parent::registerScriptFile($path, $position);
	}

	/**
	 * @param string $path
	 * @return array 
	 */
	private function parseAssetsPath($path) {
		if (Yii::app()->theme !== null && strpos($path, Yii::app()->theme->baseUrl) !== false) // it's in the themes
			$baseUrl = Yii::app()->theme->baseUrl;
		elseif (strpos($path, Yii::app()->assetManager->baseUrl) !== false) // it's in the assets
			$baseUrl = Yii::app()->assetManager->baseUrl;
		else // could not be parsed
			return false;
		$truncatedPath = substr($path, strlen($baseUrl) + 1);
		$splitted = explode("/", $truncatedPath);
		return array(
			'assetsUrl' => $baseUrl . '/' . $splitted[0],
			'path' => substr($truncatedPath, strlen($splitted[0])),
		);
	}

	/**
	 * Check if the theme folder contains the same file,
	 * if so load that file instead of the default file
	 * @param string $defaultAssetUrl
	 * @param string $path
	 * @return string
	 */
	private function getFileUrl($defaultAssetUrl, $path) {
		if (Yii::app()->theme !== null &&
			file_exists(Yii::app()->theme->getBasePath() . DIRECTORY_SEPARATOR . 'assets' . $path)) {
			$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::app()->theme->getBasePath() . DIRECTORY_SEPARATOR . 'assets');
			return $assetsUrl . $path;
		}
		return $defaultAssetUrl . $path;
	}

}
