<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleUser;
use App\Models\ArticleUserQuestionaire;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AssignReviewerController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        return view('dashboard.admin.article.assign', [
            'project_id' => decrypt(request()->pid),
            'user_id' => decrypt(request()->uid),
        ]);
    }

    public function articleNotAssignTable(Request $request)
    {
        $this->authorize('admin');
        if ($request->has('count_reviewer')) {
            $data = Article::whereDoesntHave('article_user', function($query) use ($request){
                $query->where('user_id', $request->user_id);
            })->with(['article_user', 'project'])->withCount('article_user')->where('project_id', $request->project_id)->get();

            if ($request->count_reviewer == 'less') {
                $article = $data->filter(function($item){
                    return $item->article_user_count < $item->project->limit_reviewer;
                });
            }
            elseif ($request->count_reviewer == 'fits') {
                $article = $data->filter(function($item){
                    return $item->article_user_count == $item->project->limit_reviewer;
                });
            }
            elseif ($request->count_reviewer == 'more') {
                $article = $data->filter(function($item){
                    return $item->article_user_count > $item->project->limit_reviewer;
                });
            }
            else {
                $article = $data;
            }
        }
        else {
            $article = Article::whereDoesntHave('article_user', function($query) use ($request){
                $query->where('user_id', $request->user_id);
            })->with(['article_user', 'project'])->withCount('article_user')->where('project_id', $request->project_id)->get();
        }

        return DataTables::of($article)
            ->addColumn('action', function(Article $article){
                return '<input type="checkbox" name="article_id[]" class="cb_child" value="'.$article->id.'">';
            })->rawColumns(['action'])
            ->addColumn('no', function(Article $article){
                return $article->id.' - '.$article->no;
            })
            ->addColumn('title', function(Article $article){
                return $article->title;
            })
            ->addColumn('year', function(Article $article){
                return $article->year;
            })
            ->addColumn('publication', function(Article $article){
                return $article->publication;
            })
            ->addColumn('authors', function(Article $article){
                return $article->authors;
            })
            ->addColumn('reviewer', function(Article $article){
                $background_class = ($article->article_user_count < $article->project->limit_reviewer) ? 'alert-danger' : ($article->article_user_count == $article->project->limit_reviewer ? 'alert-success' : 'alert-warning');
                $count_text = "{$article->article_user_count}/{$article->project->limit_reviewer}";
                $count_html = "<span class=\"badge {$background_class}\">{$count_text}</span>";
                return $count_html;
            })->rawColumns(['reviewer'])
            ->toJson();
    }

    public function articleAssignTable(Request $request)
    {
        $this->authorize('admin');
        $data = ArticleUser::with(['article' => function($query) use ($request){
            $query->where('project_id', $request->project_id);
        }])->where('user_id', $request->user_id)->where('is_assessed', false)->get();
        $articles = [];
        foreach ($data as $key => $value) {
            if ($value->article != null) {
                $articles[$key] = $value->article;
            }
        }
        return DataTables::of($articles)
            ->addColumn('action', function(Article $article){
                return '<input type="checkbox" name="article_id[]" class="cb_child_assign" value="'.$article->id.'">';
            })->rawColumns(['action'])
            ->addColumn('no', function(Article $article){
                return $article->id.' - '.$article->no;
            })
            ->addColumn('title', function(Article $article){
                return $article->title;
            })
            ->addColumn('year', function(Article $article){
                return $article->year;
            })
            ->addColumn('publication', function(Article $article){
                return $article->publication;
            })
            ->addColumn('authors', function(Article $article){
                return $article->authors;
            })
            ->addColumn('action', function(Article $article){
                return '<input type="checkbox" name="article_id[]" value="'.$article->id.'">';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function assignArticle(Request $request)
    {
        $this->authorize('admin');

        DB::beginTransaction();
        try {
            foreach($request->article_id as $value) {
                ArticleUser::create([
                    'article_id' => $value,
                    'user_id' => $request->user_id
                ]);
            }
            $project_user = ProjectUser::where('project_id', $request->project_id)->where('user_id', $request->user_id)->where('user_role', 'reviewer')->first();
            if ($project_user == null) {
                ProjectUser::create([
                    'project_id' => $request->project_id,
                    'user_id' => $request->user_id,
                    'user_role' => 'reviewer'
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Article has been assigned'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Article has not been assigned'
            ]);
        }
        
        
    }

    public function deleteAssignArticle(Request $request)
    {
        $this->authorize('admin');
        foreach($request->article_id as $value) {
            $article_user = ArticleUser::where('article_id', $value)->where('user_id', $request->user_id);
            ArticleUserQuestionaire::where('article_user_id', $article_user->first()->id)->delete();
            $article_user->delete();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Article has been removed'
        ]);
    }
}
