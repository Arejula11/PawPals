<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Post;

class FileController extends Controller
{   
    static $default = 'default.jpg';
    static $diskName = 'Images'; // declared in config/filesystems.php:60

    static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg', 'gif'],
        'post' => ['mp3', 'mp4', 'gif', 'png', 'jpg', 'jpeg'],
    ];

    private static function getDefaultExtension(String $type) {
        return reset(self::$systemTypes[$type]);
    }

    private static function isValidExtension(String $type, String $extension) {
        $allowedExtensions = self::$systemTypes[$type];

        // Note the toLowerCase() method, it is important to allow .JPG and .jpg extensions as well
        return in_array(strtolower($extension), $allowedExtensions);
    }

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }

    private static function getFileName(String $type, int $id, String $extension = null) {

        $fileName = null;
        switch($type) {
            case 'profile':
                $fileName = User::find($id)->profile_picture; // can be null as well
                break;
            case 'post':
                $post = Post::find($id);
                $fileName = $post ? $post->post_picture : null; // Can be null if the post or post picture does not exist
                break;
            default:
                return null;
        }

        return $fileName;
    }

    private static function delete(String $type, int $id) {
        $existingFileName = self::getFileName($type, $id);
        if ($existingFileName) {
            Storage::disk(self::$diskName)->delete($type . '/' . $existingFileName);

            switch($type) {
                case 'profile':
                    User::find($id)->profile_picture = null;
                    break;
               case 'post':
                    $post = Post::find($id);
                    if ($post) {
                        $post->post_picture = null;
                        $post->save();
                    }
                break;
            }
        }
    }

    function upload(Request $request) {

        // Validation: has file
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'Error: File not found');
        }

        // Validation: upload type
        if (!$this->isValidType($request->type)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload type');
        }

        // Validation: upload extension
        $file = $request->file('file');
        $type = $request->type;
        $extension = $file->extension();
        if (!$this->isValidExtension($type, $extension)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload extension');
        }

        // Prevent existing old files
        $this->delete($type, $request->id);

        // Generate unique filename
        $fileName = $file->hashName();

        // Validation: model
        $error = null;
        switch($request->type) {
            case 'profile':
                $user = User::findOrFail($request->id);
                if ($user) {
                    error_log('User ID: ' . $request->id);
                    $user->profile_picture = $fileName;
                    $user->save();
                } else {
                    $error = "unknown user";
                }
                break;

                case 'post':
                    $post = Post::findOrFail($request->id);
                    if ($post) {
                        $post->post_picture = "$type/$fileName"; // Save the relative path
                        $post->save();
                    } else {
                        $error = "Unknown post";
                    }
                    break;

            default:
                redirect()->back()->with('error', 'Error: Unsupported upload object');
        }

        if ($error) {
            redirect()->back()->with('error', `Error: {$error}`);
        }

        $file->storeAs($type, $fileName, self::$diskName);
        return redirect()->back()->with('success', 'Success: upload completed!');
    }

    static function get(String $type, int $userId) {

        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }

        // Validation: file exists
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset(self::$diskName . '/' . $type . '/' . $fileName);
        }

        // Not found: returns default asset
        return self::defaultAsset($type);
    }

    static function getAllPostUserImages(int $userId) {
        $posts = Post::where('user_id', $userId)->get();
        $images = [];
        foreach($posts as $post) {
            $images[] = asset($post->post_picture);
        }
        error_log(print_r($images, true));
        return $images;
    }
}
