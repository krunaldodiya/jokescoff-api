<?php namespace App\Repositories;

use App\PostCategory;
use App\PostLocation;
use Illuminate\Support\Facades\Session;
use App\Post;
use App\Wallet;
use App\WalletHistory;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\File;

use App\Interfaces\PostsInterface as PostsContract;

class PostsRepository implements PostsContract
{
    private $event;

    /**
     * @param Dispatcher $event
     */
    public function __construct(Dispatcher $event)
    {
        $this->event = $event;
    }

    /**
     * @param $data
     * @param $user
     * @return mixed
     */
    public function postManager($data, $user)
    {
        $post = Post::firstOrNew(['id' => $data->post_id]);
        $post->user_id = $user->id;
        $post->mobile = $data->mobile;
        $post->hide_mobile = ($data->hide_mobile == "on") ? 1 : 0;
        $post->price = $data->price;
        $post->negotiable_price = ($data->negotiable_price == "on") ? 1 : 0;
        $post->ad_type = $data->ad_type;
        $post->sponsored = false;
        $post->title = $data->title;
        $post->description = $data->description;
        $post->keywords = '';
        $post->status = isset($data->status) ? $data->status : 1;
        $post->save();

        // post category
        $this->updateCategory($post, $data->category_id);

        // post location
        $this->updateLocation($post, $data);

        // user wallet
        $this->updateWallet('created_classified', $user->email, 'created a classified post.');

        return $post;
    }

    /**
     * @param $post
     * @param $category_id
     */
    public function updateCategory($post, $category_id)
    {
        $categoryInfo = getSelectedCategory($category_id);

        $category = PostCategory::firstOrNew(['post_id' => $post->id]);

        $category->parent_category_id = $categoryInfo['parent_category_id'];
        $category->parent_category_name = $categoryInfo['parent_category_name'];
        $category->category_id = $categoryInfo['category_id'];
        $category->category_name = $categoryInfo['category_name'];
        $category->has_price = $categoryInfo['has_price'];

        $category->save();

        Session::forget('selected_category');
    }

    /**
     * @param $data
     * @param $post
     */
    public function updateLocation($post, $data)
    {
        $location = PostLocation::firstOrNew(['post_id' => $post->id]);

        $location->area = $data->area;
        $location->city = $data->city;
        $location->state = $data->state;
        $location->formatted_address = getFormattedLocation($data->area, $data->city);
        $location->country = session('location.country.name');
        $location->iso2code = session('location.country.iso2code');
        $location->phone_code = session('location.country.phone_code');
        $location->currency_code = session('location.country.currency_code');

        $location->save();
    }

    /**
     * @param $activity
     * @param $username
     * @param string $description
     */
    public function updateWallet($activity, $username, $description = '')
    {
        $file = json_decode(File::get(public_path('data/wallet_points.json')));

        $wallet = $file->{$activity};
        $affected_points = $wallet->points;
        $affected_type = $wallet->type;

        $user_wallet = Wallet::firstOrNew(['username' => $username]);
        $user_wallet->points = $user_wallet->points + $affected_points;
        $user_wallet->save();

        WalletHistory::create([
            "username" => $username,
            "points" => $affected_points,
            "type" => $affected_type,
            "activity" => $activity,
            "description" => $description
        ]);
    }
}