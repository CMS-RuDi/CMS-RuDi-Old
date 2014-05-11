<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.3                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2013                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function files(){

    $inDB = cmsDatabase::getInstance();

    global $_LANG;

    $do = cmsCore::getInstance()->do;

//========================================================================================================================//
//========================================================================================================================//
    // Скачивание
    if ($do=='view'){

        $fileurl = cmsCore::request('fileurl', 'str', '');
        if (!$fileurl) { cmsCore::error404(); }

        $fileurl = (mb_strpos($fileurl, '-') === 0) ? htmlspecialchars_decode(base64_decode(ltrim($fileurl, '-'))) : $fileurl;

        if(mb_strstr($fileurl, '..')){ cmsCore::halt(); }

        if (mb_strstr($fileurl, 'http:/')){
            if (!mb_strstr($fileurl, 'http://')){ $fileurl = str_replace('http:/', 'http://', $fileurl); }
        }

        $downloads = cmsCore::fileDownloadCount($fileurl);

        if ($downloads == 0){
            $sql = "INSERT INTO cms_downloads (fileurl, hits) VALUES ('$fileurl', '1')";
            $inDB->query($sql);
        } else {
            $sql = "UPDATE cms_downloads SET hits = hits + 1 WHERE fileurl = '$fileurl'";
            $inDB->query($sql);
        }

        if (mb_strstr($fileurl, 'http:/')){
            cmsCore::redirect($fileurl);
        }

        if (file_exists(PATH.$fileurl)){

            header('Content-Disposition: attachment; filename='.basename($fileurl) . "\n");
            header('Content-Type: application/x-force-download; name="'.$fileurl.'"' . "\n");
            header('Location:'.$fileurl);
            cmsCore::halt();

        } else {
            cmsCore::halt($_LANG['FILE_NOT_FOUND']);
        }

    }

//========================================================================================================================//
//========================================================================================================================//

    if ($do=='redirect'){

    	$url = str_replace('--q--', '?', cmsCore::request('url', 'str', ''));
        if (!$url) { cmsCore::error404(); }

        $url = (mb_strpos($url, '-') === 0) ? htmlspecialchars_decode(base64_decode(ltrim($url, '-'))) : $url;

        if(mb_strstr($url, '..')){ cmsCore::halt(); }

        if (mb_strstr($url, 'http:/')){
            if (!mb_strstr($url, 'http://')){ $url = str_replace('http:/', 'http://', $url); }
        }
        if (mb_strstr($url, 'https:/')){
            if (!mb_strstr($url, 'https://')){ $url = str_replace('https:/', 'https://', $url); }
        }
        // кириллические домены
        $url_host = parse_url($url, PHP_URL_HOST);
        if (preg_match('/^[а-яё]+/iu', $url_host)){

            cmsCore::loadClass('idna_convert');

            $IDN = new idna_convert();

            $host = $IDN->encode($url_host);

            $url = str_ireplace($url_host, $host, $url);

        }
        cmsCore::redirect($url);

    }

//========================================================================================================================//

}
?>
