<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\GcsHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;

/**
 * @group WsBlog
 *
 * @authenticated
 *
 * APIs for ws_blog
 */
class WsBlogController extends Controller
{
  public $model                   = 'Wasateam\Laravelapistone\Models\WsBlog';
  public $name                    = 'ws_blog';
  public $resource                = 'Wasateam\Laravelapistone\Resources\WsBlog';
  public $resource_for_collection = 'Wasateam\Laravelapistone\Resources\WsBlogCollection';
  public $input_fields            = [
    'title',
    'description',
    'publish_at',
    'publish_status',
    'read_count',
    'content',
    'tags',
  ];
  public $search_fields = [
    'title',
  ];
  public $belongs_to = [
    'cover_image',
  ];
  public $belongs_to_many = [
    'ws_blog_classes',
  ];
  public $filter_belongs_to_many = [
    'ws_blog_classes',
  ];
  public $order_fields = [
    'publish_at',
    'read_count',
  ];
  public $user_record_field = 'updated_admin_id';

  /**
   * Index
   * @queryParam search string No-example
   *
   */
  public function index(Request $request, $id = null)
  {
    return ModelHelper::ws_IndexHandler($this, $request, $id);
  }

  /**
   * Store
   *
   * @bodyParam title string Example: AAAAA
   * @bodyParam description string No-example
   * @bodyParam publish_at datetime No-example
   * @bodyParam publish_status int No-example
   * @bodyParam read_count int No-example
   * @bodyParam content string Example: <p>gasdfasdfasdf</p><p></p><p>asdvkjsadv</p><p></p><p>asdvkasdvlasdv哈哈好哈</p><p>ㄏ嗨ㄎㄢ</p><p></p><h2>ㄇk;aksdfasdfasdf</h2><p></p><p></p><ul><li><p>111</p></li><li><p>222</p></li><li><p>333</p></li></ul><p></p><p></p><p></p><blockquote><p>asdfasdfasdfasdfasdf</p></blockquote><p></p><p><a href="wasateam.com" rel="noopener noreferrer nofollow">advasdvasdv</a></p><p></p><p>asdfadsfasdf<img src="https://ws-showroom.s3.ap-northeast-1.amazonaws.com/pocket_image/1624495965Ql9WD/download.jpeg"></p><p></p><p></p><p><code>codecodecode</code></p><p></p><p>f</p><p>aaaffd</p><p></p><p>fsdfasdfasdfffffsd</p>
   * @bodyParam tags object No-example
   * @bodyParam cover_image id No-example
   */
  public function store(Request $request, $id = null)
  {
    return ModelHelper::ws_StoreHandler($this, $request, $id, function ($model) {
      if ($model->content) {
        $content              = $model->content;
        $search               = array('<p>', '</p>', '<br>', '<h1>', '</h1>', '<h2>', '</h2>', '<ol>', '</ol>', '<li>', '</li>', '<br>', '<strong>', '</strong>');
        $trim_content         = str_replace($search, '', $content);
        $trim_content         = preg_replace("/<img[^>]+\>/i", "", $trim_content);
        $model->rough_content = Str::limit($trim_content, 200);
        $model->save();
      }
    });
  }

  /**
   * Show
   *
   * @urlParam  blog required The ID of blog. Example: 1
   */
  public function show(Request $request, $id = null)
  {
    return ModelHelper::ws_ShowHandler($this, $request, $id);
  }

  /**
   * Update
   *
   * @urlParam  blog required The ID of blog. Example: 1
   * @bodyParam title string No-example
   * @bodyParam description string No-example
   * @bodyParam publish_at datetime No-example
   * @bodyParam publish_status int No-example
   * @bodyParam read_count int No-example
   * @bodyParam content string No-example
   * @bodyParam tags object No-example
   * @bodyParam cover_image id No-example
   */
  public function update(Request $request, $id)
  {
    return ModelHelper::ws_UpdateHandler($this, $request, $id, [], function ($model) {
      if ($model->content) {
        $content              = $model->content;
        $search               = array('<p>', '</p>', '<br>', '<h1>', '</h1>', '<h2>', '</h2>', '<ol>', '</ol>', '<li>', '</li>', '<br>');
        $trim_content         = str_replace($search, '', $content);
        $trim_content         = preg_replace("/<img[^>]+\>/i", "", $trim_content);
        $model->rough_content = Str::limit($trim_content, 200);
        $model->save();
      }
    });
  }

  /**
   * Delete
   *
   * @urlParam  blog required The ID of blog. Example: 2
   */
  public function destroy($id)
  {
    return ModelHelper::ws_DestroyHandler($this, $id);
  }

  /**
   * Pocket Image Get Upload Url
   *
   */
  public function image_get_upload_url(Request $request)
  {
    $name            = $request->name;
    $storage_service = config('stone.storage.service');
    if ($storage_service == 'gcs') {
      return GcsHelper::getUploadSignedUrlByNameAndPath($name, 'ws_blog');
    }
  }

  /**
   * Pocket Image Upload Complete
   *
   */
  public function image_upload_complete(Request $request)
  {
    $url = $request->url;
    GcsHelper::makeUrlPublic($url);
    return response()->json([
      'message' => 'ok.',
    ]);
  }

  /**
   * Read
   *
   */
  public function read($id)
  {
    $blog = $this->model::find($id);
    $blog->read_count++;
    $blog->save();
    return response()->json([
      'message' => 'ok.',
    ]);
  }
}
