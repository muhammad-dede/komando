<?php

namespace App\Services;

use Spatie\MediaLibrary\UrlGenerator\LocalUrlGenerator;

class MediaUrlGenerator extends LocalUrlGenerator
{
    /**
     * Get the url for the profile of a media item.
     * @return string
     * @throws \Spatie\MediaLibrary\Exceptions\UrlCouldNotBeDetermined
     */
    public function getUrl()
    {
        $url = asset('storage/media/'.$this->getPathRelativeToRoot());

        return $this->makeCompatibleForNonUnixHosts($url);
    }
}
