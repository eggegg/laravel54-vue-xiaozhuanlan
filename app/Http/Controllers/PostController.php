<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use App\Repos\Repository;
use App\API\ApiHelper;
use Illuminate\Http\Request;

class PostController extends Controller
{

    use ApiHelper;

    /**
     * @var Repository
     */
    protected $model;

    public function __construct(Post $post)
    {
        $this->model = new Repository( $post );

        // Protect all except reading
        $this->middleware('auth:api', ['except' => ['index', 'show'] ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->model->with(['channel']);

        // check for trending
        if ( $request->has('trending')) {
            $query->orderBy('views', 'desc');
        }

        // paginate the result
        $paginated = $query->latest()->paginate()->toArray();

        // check for categories
        if ($request->has('categories')) {
           $paginated['categories'] = Category::select('id', 'name')->get();
        }

        return $paginated;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // run the validation
        $this->beforeCreate($request);

        // validate the channel id belongs to user
        if( ! $request->user()->channels()->find($request->get('channel_id', 0)) ) {
            return $this->errorForbidden('You can only add post in your channel.');
        }

        return $request->user()->posts()
            ->create(
                $request->only($this->model->getModel()->fillable)
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $video =  $this->model->with(['channel', 'category'])->findOrFail($id);
        
        // check related video requested
        if( $request->has('related') ) {
            $video->related = $this->model->with(['channel'])->inRandomOrder()->limit(16)->get();
        }

        // update view count
        $video->increment('views');

        return $video;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->beforeUpdate($request);

        if (! $this->model->update($request->only($this->model->getModel()->fillable), $id) ) {
            return $this->errorBadRequest('Unable to update.');
        }

        return $this->model->find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // run before delete checks
        if (! $request->user()->posts()->find($id)) {
            return $this->errorNotFound('Post not found.');
        }

        return $this->model->delete($id) ? $this->noContent() : $this->errorBadRequest();
    }
}
