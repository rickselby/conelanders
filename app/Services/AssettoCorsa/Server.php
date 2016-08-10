<?php

namespace App\Services\AssettoCorsa;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Server
{
    protected $script;
    protected $configPath;
    protected $entryList = 'entry_list.ini';
    protected $serverConfig = 'server_cfg.ini';

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

    public function updateEntryList($contents)
    {
        $currentFile = $this->getCurrentEntryList();
        $this->updateFile($contents, $this->entryList);
        return ($contents != $currentFile);
    }

    public function updateServerConfig($contents)
    {
        $currentFile = $this->getCurrentConfigFile();
        $this->updateFile($contents, $this->serverConfig);
        return ($contents != $currentFile);
    }

    protected function updateFile($contents, $name)
    {
        $localPath = storage_path('uploads/ac-server/');
        $localName = time().'-'.$name;

        // Set the contents of the file
        \File::put($localPath.$localName, $contents);
        // Then copy the file to the server config
        \File::copy($localPath.$localName, $this->configPath.$name);
        \Log::info('Assetto Corsa Server: file uploaded', [
            'file' => $localName,
            'user' => \Auth::user()->driver->name,
        ]);
    }

    public function getCurrentEntryList()
    {
        return file_get_contents($this->configPath.$this->entryList);
    }

    public function getCurrentConfigFile()
    {
        return file_get_contents($this->configPath.$this->serverConfig);
    }

}