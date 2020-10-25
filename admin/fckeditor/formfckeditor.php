<?php

// $Id: formfckeditor.php,V 1.0 phppp Exp $

//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, https://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

/**
 * Adapted FCKeditor
 *
 * @author        phppp, http://xoops.org.cn
 * @copyright     copyright (c) 2004 XOOPS.org
 */
class XoopsFormFckeditor extends XoopsFormTextArea
{
    public $language = _LANGCODE;

    public $filePath = '';

    public $uploadEnabled = false;

    public $allowdedExtensions = '';

    public $width;

    public $height;

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name    "name" attribute
     * @param string $value   Initial text
     * @param string $width   iframe width
     * @param string $height  iframe height
     * @param mixed $checkCompatible
     */
    public function __construct($caption, $name, $value = '', $width = '100%', $height = '300px', $checkCompatible = false)
    {
        if ($checkCompatible && !$this->isCompatible()) {
            $this = false;

            return false;
        }

        $this->XoopsFormTextArea($caption, $name, $value);

        $this->width = $width;

        $this->height = $height;
    }

    /**
     * get textarea width
     *
     * @return    string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * get textarea height
     *
     * @return    string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * get language
     *
     * @return    string
     */
    public function getLanguage()
    {
        return str_replace('_', '-', mb_strtolower($this->language));
    }

    /**
     * set language
     *
     * @param mixed $lang
     */
    public function setLanguage($lang = 'en')
    {
        $this->language = $lang;
    }

    /**
     * get allowed extensions for uploading
     *
     * @return    string
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * set  allowed extensions for uploading
     *
     * @param mixed $extensions
     */
    public function setAllowedExtensions($extensions = '')
    {
        $this->allowedExtensions = $extensions;
    }

    /**
     * set file path
     *
     * @param mixed $path
     */
    public function setFilePath($path = '')
    {
        $this->filePath = $path;
    }

    /**
     * enable upload
     *
     * @param mixed $extensions
     */
    public function enableUpload($extensions = '')
    {
        $this->uploadEnabled = true;

        $this->setAllowedExtensions($extensions);
    }

    /**
     * enable upload
     */
    public function getUploadStatus()
    {
        return $this->uploadEnabled;
    }

    /**
     * get file path
     */
    public function getFilePath()
    {
        $check_func = ($this->getUploadStatus()) ? 'is_writable' : 'is_readable';

        return $check_func($this->filePath) ? $this->filePath : false;
    }

    /**
     * prepare HTML for output
     *
     * @return    sting HTML
     */
    public function render()
    {
        global $myts;

        $ret = '';

        if (is_readable(XOOPS_ROOT_PATH . '/class/fckeditor/fckeditor.php')) {
            require_once XOOPS_ROOT_PATH . '/class/fckeditor/fckeditor.php';

            $oFCKeditor = new FCKeditor($this->getName());

            $oFCKeditor->SetVar('BasePath', '/class/fckeditor/');

            $oFCKeditor->SetVar('Width', $this->getWidth());

            $oFCKeditor->SetVar('Height', $this->getHeight());

            $value = $this->getValue();

            $value = str_replace('<BR>', '<br>', $myts->undoHtmlSpecialChars($value));

            $oFCKeditor->SetVar('Value', $value);

            $oFCKeditor->SetLanguage($this->getLanguage());

            //$oFCKeditor->SetVar('ToolbarSet', "Complex");

            ob_start();

            $oFCKeditor->Create();

            $ret = ob_get_contents();

            ob_end_clean();
        }

        return $ret;
    }

    /**
     * Check if compatible
     *
     * @return bool
     */
    public function isCompatible()
    {
        if (!is_readable(XOOPS_ROOT_PATH . '/class/fckeditor/fckeditor.php')) {
            return false;
        }

        require_once XOOPS_ROOT_PATH . '/class/fckeditor/fckeditor.php';

        return FCKeditor::IsCompatible();
    }
}
