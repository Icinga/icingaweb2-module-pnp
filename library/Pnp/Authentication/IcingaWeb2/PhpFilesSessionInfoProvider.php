<?php

namespace Icinga\Module\Pnp\Authentication\IcingaWeb2;


class PhpFilesSessionInfoProvider implements SessionInfoProvider
{
    public function getInfoForSid($sid)
    {
        $path = session_save_path() ?: sys_get_temp_dir();
        $filename = "$path/sess_$sid";
        if (@is_readable($filename)) {
            return @file_get_contents($filename);
        } else {
            return null;
        }
    }
}
