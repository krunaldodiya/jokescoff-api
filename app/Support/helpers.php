<?php

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

function filenameSlug($file)
{
    $filename = explode(".", $file->getClientOriginalName());

    return str_slug($filename[0]) . '.' . $file->getClientOriginalExtension();
}

function getSelectedCategory($category_id)
{
    if ( ! $category_id) return [];

    $selected_category = Category::with(['parent'])->whereId($category_id)->first();

    return [
        'parent_category_id' => $selected_category->parent->id,
        'parent_category_name' => $selected_category->parent->name,
        'category_id' => $selected_category->id,
        'category_name' => $selected_category->name,
        'has_price' => $selected_category->has_price
    ];
}

function fileUpload($file, $file_path, $sizes)
{
    $file_name = str_random(8) . '-' . filenameSlug($file);

    // saving original image
    $image = Image::make($file->getRealPath());
    $upload = Storage::disk(env('STORAGE_DISK'))->put("$file_path/$file_name", $image->stream()->__toString(), 'public');

    // resize different file size
    foreach ($sizes as $size) {
        $resize = $image
            ->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            });

        Storage::disk(env('STORAGE_DISK'))->put("$file_path/{$size}-{$size}-$file_name", $resize->stream()->__toString(), 'public');
    }

    return ($upload) ? $file_name : false;
}

function isSelected($value1, $value2)
{
    return ($value1 == $value2) ? "selected" : "";
}

function getMessage($data = 'description')
{
    $message = [];
    foreach (session('message') as $key => $value) {
        $message['type'] = $key;
        $message['description'] = $value;
    }

    return $message[$data];
}

function get_fields_type($fields)
{
    if (filter_var($fields, FILTER_VALIDATE_EMAIL)) return 'email';

    if (is_numeric($fields)) return 'mobile';

    return 'username';
}