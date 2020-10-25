<?php

/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2006 Frederico Caldeira Knabben
 *
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 *
 * For further information visit:
 * 		http://www.fckeditor.net/
 *
 * "Support Open Source software. What about a donation today?"
 *
 * File Name: fckeditor.php
 * 	This is the integration file for PHP.
 *
 * 	It defines the FCKeditor class that can be used to create editor
 * 	instances in PHP pages on server side.
 *
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

class FCKeditor
{
    public $InstanceName;

    public $BasePath;

    public $Width;

    public $Height;

    public $ToolbarSet;

    public $Value;

    public $Config;

    // PHP 5 Constructor (by Marcus Bointon <coolbru@users.sourceforge.net>)

    public function __construct($instanceName)
    {
        $this->InstanceName = $instanceName;

        $this->BasePath = '/fckeditor/';

        $this->Width = '100%';

        $this->Height = '200';

        $this->ToolbarSet = 'Default';

        $this->Value = '';

        $this->Config = [];
    }

    // PHP 4 Contructor

    public function FCKeditor($instanceName)
    {
        $this->__construct($instanceName);
    }

    public function Create()
    {
        echo $this->CreateHtml();
    }

    public function CreateHtml()
    {
        $HtmlValue = htmlspecialchars($this->Value, ENT_QUOTES | ENT_HTML5);

        $Html = '<div>';

        if ($this->IsCompatible()) {
            if (isset($_GET['fcksource']) && 'true' == $_GET['fcksource']) {
                $File = 'fckeditor.original.html';
            } else {
                $File = 'fckeditor.html';
            }

            $Link = "{$this->BasePath}editor/{$File}?InstanceName={$this->InstanceName}";

            if ('' != $this->ToolbarSet) {
                $Link .= "&amp;Toolbar={$this->ToolbarSet}";
            }

            // Render the linked hidden field.

            $Html .= "<input type=\"hidden\" id=\"{$this->InstanceName}\" name=\"{$this->InstanceName}\" value=\"{$HtmlValue}\" style=\"display:none\">";

            // Render the configurations hidden field.

            $Html .= "<input type=\"hidden\" id=\"{$this->InstanceName}___Config\" value=\"" . $this->GetConfigFieldString() . '" style="display:none">';

            // Render the editor IFRAME.

            $Html .= "<iframe id=\"{$this->InstanceName}___Frame\" src=\"{$Link}\" width=\"{$this->Width}\" height=\"{$this->Height}\" frameborder=\"0\" scrolling=\"no\"></iframe>";
        } else {
            if (false === mb_strpos($this->Width, '%')) {
                $WidthCSS = $this->Width . 'px';
            } else {
                $WidthCSS = $this->Width;
            }

            if (false === mb_strpos($this->Height, '%')) {
                $HeightCSS = $this->Height . 'px';
            } else {
                $HeightCSS = $this->Height;
            }

            $Html .= "<textarea name=\"{$this->InstanceName}\" rows=\"4\" cols=\"40\" style=\"width: {$WidthCSS}; height: {$HeightCSS}\">{$HtmlValue}</textarea>";
        }

        $Html .= '</div>';

        return $Html;
    }

    public function IsCompatible()
    {
        global $HTTP_USER_AGENT;

        $sAgent = $HTTP_USER_AGENT ?? $_SERVER['HTTP_USER_AGENT'];

        if (false !== mb_strpos($sAgent, 'MSIE') && false === mb_strpos($sAgent, 'mac') && false === mb_strpos($sAgent, 'Opera')) {
            $iVersion = (float)mb_substr($sAgent, mb_strpos($sAgent, 'MSIE') + 5, 3);

            return ($iVersion >= 5.5);
        } elseif (false !== mb_strpos($sAgent, 'Gecko/')) {
            $iVersion = (int)mb_substr($sAgent, mb_strpos($sAgent, 'Gecko/') + 6, 8);

            return ($iVersion >= 20030210);
        }

        return false;
    }

    public function GetConfigFieldString()
    {
        $sParams = '';

        $bFirst = true;

        foreach ($this->Config as $sKey => $sValue) {
            if (false === $bFirst) {
                $sParams .= '&amp;';
            } else {
                $bFirst = false;
            }

            if (true === $sValue) {
                $sParams .= $this->EncodeConfig($sKey) . '=true';
            } elseif (false === $sValue) {
                $sParams .= $this->EncodeConfig($sKey) . '=false';
            } else {
                $sParams .= $this->EncodeConfig($sKey) . '=' . $this->EncodeConfig($sValue);
            }
        }

        return $sParams;
    }

    public function EncodeConfig($valueToEncode)
    {
        $chars = [
            '&' => '%26',
'=' => '%3D',
'"' => '%22',
        ];

        return strtr($valueToEncode, $chars);
    }
}
