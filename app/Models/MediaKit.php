<?php

namespace App\Models;

use App\Enum\MediaKitJenis;
use App\Enum\MediaKitStatus;
use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\Media;

class MediaKit extends Model implements HasMedia
{
    use Auditable;
    use HasMediaTrait;

    protected $table = 'media_kit';
    protected $guarded = [];

    public function thumbnail()
    {
        return $this->getFirstMediaUrl();
    }

    public function getImages()
    {
        return $this->getMedia()->filter(function (Media $item) {
            return $item->getTypeFromExtensionAttribute() === Media::TYPE_IMAGE;
        });
    }

    public function getImagesUrl()
    {
        return $this->getImages()->map(function ($item) {
            return $item->getUrl();
        });
    }

    public function getVideos()
    {
        return $this->getMedia()->filter(function (Media $item) {
            return $item->getTypeFromMimeAttribute() === Media::TYPE_OTHER;
        });
    }

    public static function getBackgroundImageUrl()
    {
        $default = collect([asset('assets/images/bg_new_normal.png')]);

        $media = static::query()->whereJenis(MediaKitJenis::BACKGROUND)->whereStatus(MediaKitStatus::ACTIVE)->first();

        if (!$media) {
            return $default;
        }

        $images = $media->getImages();
        if ($images->isEmpty()) {
            return $default;
        }

        return $images->map(function ($item) {
            return $item->getUrl();
        });
    }

    public static function getBannerUrl()
    {
        $default = collect([
            asset('assets/images/big/banner_hln_75.png'),
            asset('assets/images/big/new_normal_1.png'),
            asset('assets/images/big/new_normal_2.png'),
            asset('assets/images/big/new_normal_3.png'),
            asset('assets/images/big/new_normal_4.png'),
            asset('assets/images/big/new_normal_5.png'),
            asset('assets/images/big/pln_terbaik.png'),
        ]);

        $media = static::query()->whereJenis(MediaKitJenis::BANNER)->whereStatus(MediaKitStatus::ACTIVE)->orderBy('id', 'desc')->get();

        if (!$media) {
            return $default;
        }

        if (empty($media)) {
            return $default;
        }

        $data = [];
        foreach ($media as $mediaKit) {
            $images = $mediaKit->getImages();
            if (!$images->isEmpty()) {
                $data[] = [
                    'judul' => $mediaKit->judul,
                    'url' => $images->first()->getUrl(),
                    'link' => $mediaKit->link
                ];
            }
        }

        return $data;
    }

    public static function getVideoGallery($status, $all = false)
    {
        $default = collect();

        $videoGallery = static::query()
            ->whereJenis(MediaKitJenis::VIDEO)
            ->whereStatus($status);

        if (! $all) {
            $videoGallery = $videoGallery
                ->limit(2)
                ->orderBy('created_at', 'DESC');
        }

        if ($videoGallery->get()->isEmpty()) {
            return $default;
        }

        $data = [];
        foreach ($videoGallery->get() as $mediaKit) {
            $videos = $mediaKit->getVideos();
            if (!$videos->isEmpty()) {
                $data[] = [
                    'judul' => $mediaKit->judul,
                    'url' => $videos->first()->getUrl(),
                ];
            }
        }

        return $data;
    }
}
