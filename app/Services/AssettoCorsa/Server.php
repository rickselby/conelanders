<?php

namespace App\Services\AssettoCorsa;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Server
{
    protected $script;
    protected $configPath;
    protected $entryList = 'entry_list.ini';
    protected $serverConfig = 'server_cfg.ini';
    protected $cacheKey = 'ac-server-started-by';

    public function __construct()
    {
        $this->script = env('AC_SERVER_SCRIPT');
        $this->configPath = env('AC_SERVER_CONFIG_PATH');
    }

    public function start()
    {
        $userName = \Auth::user()->driver->name;
        // Start the server
        exec($this->script.' start');
        // Cache the user that started the server
        \Cache::forever($this->cacheKey, $userName);
        // Log the action
        \Log::info('Assetto Corsa Server: started', [
            'user' => $userName,
        ]);
    }

    public function stop()
    {
        // Stop the server
        exec($this->script.' stop');
        // Clear the cache (not vital)
        \Cache::forget($this->cacheKey);
        // Log the action
        \Log::info('Assetto Corsa Server: stopped', [
            'user' => \Auth::user()->driver->name,
        ]);
    }

    public function status()
    {
        exec($this->script.' status', $out);
        return $out[0].(\Cache::get($this->cacheKey) ? ' ('.\Cache::get($this->cacheKey).')' : '');
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