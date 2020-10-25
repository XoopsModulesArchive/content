<?php

/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 *
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 *
 * For further information visit:
 * 		http://www.fckeditor.net/
 *
 * File Name: Thumbnail.php
 * 	Implements the Thumbnail command, to return
 * 	a thumbnail to the browser for the sent file,
 * 	if the file is an image an attempt is made to
 * 	generate a thumbnail, otherwise an appropriate
 * 	icon is returned.
 * 	Output is image data
 *
 * File Authors:
 * 		Grant French (grant@mcpuk.net)
 */
include 'helpers/iconlookup.php';

class Thumbnail
{
    public $fckphp_config;

    public $type;

    public $cwd;

    public $actual_cwd;

    public $filename;

    public function __construct($fckphp_config, $type, $cwd)
    {
        $this->fckphp_config = $fckphp_config;

        $this->type = $type;

        $this->raw_cwd = $cwd;

        $this->actual_cwd = str_replace('//', '/', ($fckphp_config['UserFilesPath'] . "/$type/" . $this->raw_cwd));

        $this->real_cwd = str_replace('//', '/', ($this->fckphp_config['basedir'] . '/' . $this->actual_cwd));

        $this->filename = str_replace(['..', '/'], '', $_GET['FileName']);
    }

    public function run()
    {
        //$mimeIcon=getMimeIcon($mime);

        $fullfile = $this->real_cwd . '/' . $this->filename;

        $thumbfile = $this->real_cwd . '/.thumb_' . $this->filename;

        $icon = false;

        if (file_exists($thumbfile)) {
            $icon = $thumbfile;
        } else {
            $mime = $this->getMIME($fullfile);

            $ext = mb_strtolower($this->getExtension($this->filename));

            if ($this->isImage($mime, $ext)) {
                //Try and find a thumbnail, else try to generate one

                //	else send generic picture icon.

                if ($this->isJPEG($mime, $ext)) {
                    $result = $this->resizeFromJPEG($fullfile);
                } elseif ($this->isGIF($mime, $ext)) {
                    $result = $this->resizeFromGIF($fullfile);
                } elseif ($this->isPNG($mime, $ext)) {
                    $result = $this->resizeFromPNG($fullfile);
                }

                if (false !== $result) {
                    if (function_exists('imagejpeg')) {
                        imagejpeg($result, $thumbfile, 70);

                        chmod($thumbfile, 0777);

                        $icon = $thumbfile;
                    } elseif (function_exists('imagepng')) {
                        imagepng($result, $thumbfile);

                        chmod($thumbfile, 0777);

                        $icon = $thumbfile;
                    } elseif (function_exists('imagegif')) {
                        imagegif($result, $thumbfile);

                        chmod($thumbfile, 0777);

                        $icon = $thumbfile;
                    } else {
                        $icon = iconLookup($mime, $ext);
                    }
                } else {
                    $icon = iconLookup($mime, $ext);
                }
            } else {
                $icon = iconLookup($mime, $ext);
            }
        }

        $iconMime = $this->image2MIME($icon);

        if (false === $iconMime) {
            $iconMime = 'image/jpeg';
        }

        header("Content-type: $iconMime", true);

        readfile($icon);
    }

    public function getMIME($file)
    {
        $mime = 'text/plain';

        //If mime magic is installed

        if (function_exists('mime_content_type')) {
            $mime = mime_content_type($file);
        } else {
            $mime = $this->image2MIME($file);
        }

        return mb_strtolower($mime);
    }

    public function image2MIME($file)
    {
        $fh = fopen($file, 'rb');

        if ($fh) {
            $start4 = fread($fh, 4);

            $start3 = mb_substr($start4, 0, 3);

            if ("\x89PNG" == $start4) {
                return 'image/png';
            } elseif ('GIF' == $start3) {
                return 'image/gif';
            } elseif ("\xFF\xD8\xFF" == $start3) {
                return 'image/jpeg';
            } elseif ('hsi1' == $start4) {
                return 'image/jpeg';
            }

            return false;
            unset($start3);

            unset($start4);

            fclose($fh);
        } else {
            return false;
        }
    }

    public function isImage($mime, $ext)
    {
        if (('image/gif' == $mime)
            || ('image/jpeg' == $mime) || ('image/jpg' == $mime) || ('image/pjpeg' == $mime) || ('image/png' == $mime) || ('jpg' == $ext) || ('jpeg' == $ext) || ('png' == $ext)
            || ('gif' == $ext)) {
            return true;
        }

        return false;
    }

    public function isJPEG($mime, $ext)
    {
        if (('image/jpeg' == $mime) || ('image/jpg' == $mime) || ('image/pjpeg' == $mime) || ('jpg' == $ext) || ('jpeg' == $ext)) {
            return true;
        }

        return false;
    }

    public function isGIF($mime, $ext)
    {
        if (('image/gif' == $mime) || ('gif' == $ext)) {
            return true;
        }

        return false;
    }

    public function isPNG($mime, $ext)
    {
        if (('image/png' == $mime) || ('png' == $ext)) {
            return true;
        }

        return false;
    }

    public function getExtension($filename)
    {
        //Get Extension

        $ext = '';

        $lastpos = mb_strrpos($this->filename, '.');

        if (false !== $lastpos) {
            $ext = mb_substr($this->filename, ($lastpos + 1));
        }

        return mb_strtolower($ext);
    }

    public function resizeFromJPEG($file)
    {
        if (function_exists('imagecreatefromjpeg')) {
            $img = @imagecreatefromjpeg($this->real_cwd . '/' . $this->filename);

            return (($img) ? $this->resizeImage($img) : false);
        }

        return false;
    }

    public function resizeFromGIF($file)
    {
        if (function_exists('imagecreatefromgif')) {
            $img = @imagecreatefromgif($this->real_cwd . '/' . $this->filename);

            return (($img) ? $this->resizeImage($img) : false);
        }

        return false;
    }

    public function resizeFromPNG($file)
    {
        if (function_exists('imagecreatefrompng')) {
            $img = @imagecreatefrompng($this->real_cwd . '/' . $this->filename);

            return (($img) ? $this->resizeImage($img) : false);
        }

        return false;
    }

    public function resizeImage($img)
    {
        //Get size for thumbnail

        $width = imagesx($img);

        $height = imagesy($img);

        if ($width > $height) {
            $n_height = $height * (96 / $width);

            $n_width = 96;
        } else {
            $n_width = $width * (96 / $height);

            $n_height = 96;
        }

        $x = 0;

        $y = 0;

        if ($n_width < 96) {
            $x = round((96 - $n_width) / 2);
        }

        if ($n_height < 96) {
            $y = round((96 - $n_height) / 2);
        }

        $thumb = imagecreatetruecolor(96, 96);

        #Background colour fix by:

        #Ben Lancaster (benlanc@ster.me.uk)

        $bgcolor = imagecolorallocate($thumb, 255, 255, 255);

        imagefill($thumb, 0, 0, $bgcolor);

        if (function_exists('imagecopyresampled')) {
            if (!($result = @imagecopyresampled($thumb, $img, $x, $y, 0, 0, $n_width, $n_height, $width, $height))) {
                $result = imagecopyresized($thumb, $img, $x, $y, 0, 0, $n_width, $n_height, $width, $height);
            }
        } else {
            $result = imagecopyresized($thumb, $img, $x, $y, 0, 0, $n_width, $n_height, $width, $height);
        }

        return ($result) ? $thumb : false;
    }
}
