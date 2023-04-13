laravel new your-project-name

<br>
php artisan make:migration create_posts_table --create=posts

<br>

Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->unsignedBigInteger('user_id');
    $table->timestamps();
});

<br>
php artisan migrate
<br>
php artisan make:model Post
<br>
class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
<br>
php artisan make:migration create_comments_table --create=comments
<br>
Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->text('content');
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('post_id');
    $table->unsignedBigInteger('parent_id')->nullable();
    $table->timestamps();
});
<br>
php artisan migrate

<br>
php artisan make:model Comment
<br>
class Comment extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
<br>
php artisan make:controller PostController --api
<br>
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json(['data' => $posts]);
    }

    public function store(Request $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->user_id = $request->user_id;
        $post->save();
        return response()->json(['data' => $post]);
    }

    public function show(Post $post)
    {
        return response()->json(['data' => $post]);
    }

    public function like(Post $post)
    {
        $post->likes++;
        $post->save();
        return response()->json(['data' => $post]);
    }

 public function addComment(Post $post, Request $request)
    {
        $comment = new Comment;
        $comment->content = $request->content;
        $comment->user_id = $request->user_id;
        $comment->post_id = $post->id;
        $comment->parent_id = $request->parent_id;
        $comment->save();
        return response()->json(['data' => $comment]);
    }
   
<br>
In the routes/api.php file, define the necessary routes for the PostController:

<br>
Route::get('posts', 'PostController@index');
Route::post('posts', 'PostController@store');
Route::get('posts/{post}', 'PostController@show');
Route::post('posts/{post}/like', 'PostController@like');
Route::post('posts/{post}/comments', 'PostController@addComment');



