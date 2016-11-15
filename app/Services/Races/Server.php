<?php

namespace App\Services\Races;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Server
{
    protected $script;
    protected $configPath;
    protected $entryList = 'entry_list.ini';
    protected $serverConfig = 'server_cfg.ini';
    protected $startCacheKey = 'ac-server-started-by';
    protected $entryListCacheKey = 'ac-server-entry-list-';
    protected $serverConfigCacheKey = 'ac-server-config-';

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
        \Cache::forever($this->startCacheKey, $userName);
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
        \Cache::forget($this->startCacheKey);
        // Log the action
        \Log::info('Assetto Corsa Server: stopped', [
            'user' => \Auth::user()->driver->name,
        ]);
    }

    public function status()
    {
        exec($this->script.' status', $out);
        return $out[0].(\Cache::get($this->startCacheKey) ? ' ('.\Cache::get($this->startCacheKey).')' : '');
    }

    public function updateEntryList($contents)
    {
        return $this->updateFile(
            $contents,
            $this->entryList,
            $this->entryListCacheKey,
            $this->getCurrentEntryList()
        );
    }

    public function updateServerConfig($contents)
    {
        return $this->updateFile(
            $contents,
            $this->serverConfig,
            $this->serverConfigCacheKey,
            $this->getCurrentConfigFile()
        );
    }

    protected function updateFile($contents, $name, $cacheKey, $currentFile)
    {
        if ($contents != $currentFile) {
#            dd($name, $cacheKey);
            $localPath = storage_path('uploads/ac-server/');
            $localName = time().'-'.$name;

            // Set the contents of the file
            \File::put($localPath.$localName, $contents);
            // Then copy the file to the server config
            \File::copy($localPath.$localName, $this->configPath.$name);
            // Log the action
            \Log::info('Assetto Corsa Server: file uploaded', [
                'file' => $localName,
                'user' => \Auth::user()->driver->name,
            ]);

            // Cache the update details
            \Cache::forever($cacheKey . '-user', \Auth::user()->driver->name);
            \Cache::forever($cacheKey . '-update', Carbon::now());

            return true;
        } else {
            return false;
        }
    }

    public function getCurrentEntryList()
    {
        return file_get_contents($this->configPath.$this->entryList);
    }

    public function getCurrentConfigFile()
    {
        return file_get_contents($this->configPath.$this->serverConfig);
    }

    public function getEntryListLastUpdate()
    {
        return $this->getLastUpdate($this->entryListCacheKey);
    }

    public function getServerConfigLastUpdate()
    {
        return $this->getLastUpdate($this->serverConfigCacheKey);
    }

    protected function getLastUpdate($cacheKey)
    {
        $string = \Cache::get($cacheKey.'-user');
        if (\Cache::get($cacheKey.'-update')) {
            $string .= ', '.\Cache::get($cacheKey.'-update')->format('Y-m-d H:i:s');
        }
        return $string;
    }

}