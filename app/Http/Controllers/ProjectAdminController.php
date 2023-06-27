<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleUser;
use App\Models\ProjectUser;
use App\Models\Questionaire;
use Illuminate\Http\Request;

class ProjectAdminController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $projects = ProjectUser::with('project')->where('user_id', auth()->user()->id)->where('user_role', 'admin')->get();
        return view('dashboard.admin.project', compact('projects'));
    }

    public function show($id)
    {
        $this->authorize('admin');
        $project = ProjectUser::with('project')->where('user_id', auth()->user()->id)->where('project_id', decrypt($id))->first();
        $article_db = Article::select('edatabase')->where('project_id', decrypt($id))->distinct()->pluck('edatabase')->toArray();
        return view('dashboard.admin.article.index', [
            'project' => $project,
            'article_db' => $article_db,
        ]);
    }

    public function articleStatus()
    {
        $this->authorize('admin');
        $articles = Article::with(['project', 'article_user' => function($query){
            $query->with('user');
        }])->where('project_id', decrypt(request()->pid))->get();
        
        return view('dashboard.admin.status', [
            'articles' => $articles,
        ]);
    }

    public function findStatus(Request $request)
    {
        $this->authorize('admin');
        if ($request->status == 'all') {
            return Article::with(['project', 'article_user' => function($query){
                $query->with('user');
            }])->where('project_id', $request->project_id)->get();
        }
        elseif ($request->status == 'part_assessed') {
            return Article::with(['project', 'article_user' => function($query){
                    $query->with('user');
                }])->whereHas('article_user', function($query) {
                    $query->where('is_assessed', true);
                })->whereHas('article_user', function($query) {
                    $query->where('is_assessed', false);
                })->where('project_id', $request->project_id)->get();
        }
        elseif ($request->status == 'full_assessed') {
            return Article::with(['project', 'article_user' => function($query){
                $query->with('user')->where('is_assessed', true);
            }])->whereDoesntHave('article_user', function($query){
                $query->where('is_assessed', false);
            })->whereHas('article_user')->where('project_id', $request->project_id)->get();
        }
        elseif ($request->status == 'not_assessed') {
            return Article::with(['project', 'article_user' => function($query){
                $query->with('user')->where('is_assessed', false);
            }])->whereDoesntHave('article_user', function($query){
                $query->where('is_assessed', true);
            })->whereHas('article_user')->where('project_id', $request->project_id)->get();
        }
        else {
            return Article::doesntHave('article_user')->with('article_user')->where('project_id', $request->project_id)->get();
        }
    }

    public function findUserArticle(Request $request)
    {
        $this->authorize('admin');
        $user = ArticleUser::with('user')->whereHas('article', function($query) use ($request){
            $query->where('project_id', $request->project_id);
        })->where('article_id', $request->article_id)->get();

        return $user;
    }
}
