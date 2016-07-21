<?php

namespace App\Interfaces;

interface PlaylistsInterface
{
    /**
     * Get the view to show on the main playlists page
     *
     * @return string
     */
    public function getView();
}