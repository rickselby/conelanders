<?php

namespace App\Services\AssettoCorsa;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Server
{
    protected $script;
    protected $configPath;

    public function __construct()
    {
        $this->script = env('AC_SERVER_SCRIPT');
        $this->configPath = env('AC_SERVER_CONFIG_PATH');
    }

    public function start()
    {
        exec($this->script.' start');
        \Log::info('Assetto Corsa Server: started', [
            'user' => \Auth::user()->driver->name,
        ]);
    }

    public function stop()
    {
        exec($this->script.' stop');
        \Log::info('Assetto Corsa Server: stopped', [
            'user' => \Auth::user()->driver->name,
        ]);
    }

    public function status()
    {
        exec($this->script.' status', $out);
        return $out[0];
    }

    public function updateEntryList(UploadedFile $file)
    {
        $this->uploadFile($file, 'entry_list.ini');
    }

    public function updateServerConfig(UploadedFile $file)
    {
        $this->uploadFile($file, 'server_cfg.ini');
    }

    protected function uploadFile(UploadedFile $file, $name)
    {
        $localPath = storage_path('uploads/ac-server/');
        $localName = time().'-'.$name;
        // Move the uploaded file to the local storage of uploaded files
        $file->move($localPath, $localName);
        // Then copy the file to the server config
        \File::copy($localPath.$localName, $this->configPath.$name);
        \Log::info('Assetto Corsa Server: file uploaded', [
            'file' => $localName,
            'user' => \Auth::user()->driver->name,
        ]);
    }

}