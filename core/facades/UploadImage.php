<?php

namespace core\facades;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class UploadImage
{
    private ImageManager $manager;
    private Image $image;
    private array $upload;

    public function __construct()
    {
        $this->manager = new ImageManager(['driver' => 'gd']);
    }

    public function make(string $input_name = 'file')
    {
        ['name' => $name, 'tmp_name' => $tmp_name] = $_FILES[$input_name];

        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $this->upload['image'] = [
            'name' => $name,
            'extension' => $extension,
        ];

        $this->image = $this->manager->make($tmp_name);

        return $this;
    }

    public function resize(int $width = null, int $height = null, bool $constraint = false)
    {
        if (!$constraint) {
            $this->image->resize($width, $height);
        } else {
            $this->image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        return $this;
    }

    public function watermark(string $watermark_image, string $position = 'top-left', int $x = 0, int $y = 0, int $width = 100, int $height = 100, $opacity = 70)
    {
        $watermark = $this->manager->make(public_path() . '/assets/images/' . $watermark_image)->resize($width, $height)->opacity($opacity);
        $this->image->insert($watermark, $position, $x, $y);

        return $this;
    }

    public function crop(int $width, int $height, ?int $x = null, ?int $y = null)
    {
        $this->image->crop($width, $height, $x, $y);
    }

    public function fit(int $width, ?int $height = null, bool $constraint = false)
    {
        if (!$constraint) {
            $this->image->fit($width, $height);
        } else {
            $this->image->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            });
        }

        return $this;
    }

    public function quality(int $quality)
    {
        $this->upload['image']['quality'] = $quality;

        return $this;
    }

    public function execute()
    {
        $image_new_name = uniqid() . '.' . $this->upload['image']['extension'];
        $this->upload['image']['path'] = '/assets/images/' . $image_new_name;
        $this->image->save(public_path() . $this->upload['image']['path'], $this->upload['image']['quality'] ?? 60);
    }

    public function get_image_info()
    {
        return $this->upload['image'];
    }
}
