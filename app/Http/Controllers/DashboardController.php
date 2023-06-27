<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleUser;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->is_superAdmin) {
            $total_user = count(User::where('is_superAdmin', false)->get());
            $total_project = count(Project::all());
            $total_article_assessed = count(ArticleUser::where('is_assessed', true)->get());
            $total_article_not_assessed = count(ArticleUser::where('is_assessed', false)->get());
            $project_name = Project::pluck('project_name');
            $total_article = count(Article::all());

            $data_project_assessed = Project::with(['article' => function($query){
                $query->select('project_id', 'id')->whereHas('article_user', function($query){
                    $query->where('is_assessed', true);
                })->withCount(['article_user' => function($query){
                    $query->where('is_assessed', true);
                }]);
            }])->get();
            $project_assessed = $data_project_assessed->map(function($project) {
                $articleUserCountSum = $project->article->sum('article_user_count');
                return $articleUserCountSum;
            });

            $data_project_not_assessed = Project::with(['article' => function($query){
                $query->select('project_id', 'id')->whereHas('article_user', function($query){
                    $query->where('is_assessed', false);
                })->withCount(['article_user' => function($query){
                    $query->where('is_assessed', false);
                }]);
            }])->get();
            $project_not_assessed = $data_project_not_assessed->map(function($project) {
                $articleUserCountSum = $project->article->sum('article_user_count');
                return $articleUserCountSum;
            });

            return view('dashboard.superAdmin.index', [
                'total_user' => $total_user,
                'total_project' => $total_project,
                'article_assessed' => $total_article_assessed,
                'article_not_assessed' => $total_article_not_assessed,
                'project_name' => $project_name,
                'project_assessed' => $project_assessed,
                'project_not_assessed' => $project_not_assessed,
                'total_article' => $total_article,
                'article_not_assign' => $total_article - $total_article_assessed - $total_article_not_assessed
            ]);
        }
        elseif (auth()->user()->is_admin) {
            // card data
            $project_admin = ProjectUser::where('user_id', auth()->user()->id)->where('user_role', 'admin')->count();
            $project_assign = ProjectUser::where('user_id', auth()->user()->id)->count();
            $total_article = Article::whereHas('project', function($query){
                $query->whereHas('project_user', function($query){
                    $query->where('user_id', auth()->user()->id)->where('user_role', 'admin');
                });
            })->count();
            $assign_article = Article::whereHas('project', function($query){
                $query->whereHas('project_user', function($query){
                    $query->where('user_id', auth()->user()->id)->where('user_role', 'admin');
                });
            })->whereHas('article_user')->count();
            $project_name = Project::whereHas('project_user', function($query){
                $query->where('user_id', auth()->user()->id);
            })->pluck('project_name');

            // chart data
            $data_project_assessed = Project::whereHas('project_user', function($query){
                $query->where('user_id', auth()->user()->id)->where('user_role', 'admin');
            })->with(['article' => function($query){
                $query->select('project_id', 'id')->whereHas('article_user', function($query){
                    $query->where('is_assessed', true);
                })->withCount(['article_user' => function($query){
                    $query->where('is_assessed', true);
                }]);
            }])->get();
            $project_assessed = $data_project_assessed->map(function($project) {
                $articleUserCountSum = $project->article->sum('article_user_count');
                return $articleUserCountSum;
            });

            $data_project_not_assessed = Project::whereHas('project_user', function($query){
                $query->where('user_id', auth()->user()->id)->where('user_role', 'admin');
            })->with(['article' => function($query){
                $query->select('project_id', 'id')->whereHas('article_user', function($query){
                    $query->where('is_assessed', false);
                })->withCount(['article_user' => function($query){
                    $query->where('is_assessed', false);
                }]);
            }])->get();
            $project_not_assessed = $data_project_not_assessed->map(function($project) {
                $articleUserCountSum = $project->article->sum('article_user_count');
                return $articleUserCountSum;
            });

            $total_article_assessed = Article::whereHas('project', function($query){
                $query->whereHas('project_user', function($query){
                    $query->where('user_id', auth()->user()->id)->where('user_role', 'admin');
                });
            })->whereHas('article_user', function($query){
                $query->where('is_assessed', true);
            })->count();
            $total_article_not_assessed = Article::whereHas('project', function($query){
                $query->whereHas('project_user', function($query){
                    $query->where('user_id', auth()->user()->id)->where('user_role', 'admin');
                });
            })->whereHas('article_user', function($query){
                $query->where('is_assessed', false);
            })->count();

            return view('dashboard.admin.index', [
                'project_admin' => $project_admin,
                'project_assign' => $project_assign,
                'total_article' => $total_article,
                'assign_article' => $assign_article,
                'project_name' => $project_name,
                'project_assessed' => $project_assessed,
                'project_not_assessed' => $project_not_assessed,
                'article_not_assign' => $total_article - $assign_article,
                'article_assessed' => $total_article_assessed,
                'article_not_assessed' => $total_article_not_assessed
            ]);
        }
        else {
            // card data
            $project_assign = ProjectUser::where('user_id', auth()->user()->id)->where('user_role', 'reviewer')->count();
            $article_assign = ArticleUser::where('user_id', auth()->user()->id)->count();
            $article_assessed = ArticleUser::where('user_id', auth()->user()->id)->where('is_assessed', true)->count();
            $article_not_assessed = ArticleUser::where('user_id', auth()->user()->id)->where('is_assessed', false)->count();

            // chart data
            $project_name = Project::whereHas('project_user', function($query){
                $query->where('user_id', auth()->user()->id)->where('user_role', 'reviewer');
            })->pluck('project_name');

            $data_project_assessed = Project::whereHas('project_user', function($query){
                $query->where('user_id', auth()->user()->id)->where('user_role', 'reviewer');
            })->with(['article' => function($query){
                $query->select('project_id', 'id')->whereHas('article_user', function($query){
                    $query->where('user_id', auth()->user()->id)->where('is_assessed', true);
                })->withCount(['article_user' => function($query){
                    $query->where('user_id', auth()->user()->id)->where('is_assessed', true);
                }]);
            }])->get();
            $project_assessed = $data_project_assessed->map(function($project) {
                $articleUserCountSum = $project->article->sum('article_user_count');
                return $articleUserCountSum;
            });

            $data_project_not_assessed = Project::whereHas('project_user', function($query){
                $query->where('user_id', auth()->user()->id)->where('user_role', 'reviewer');
            })->with(['article' => function($query){
                $query->select('project_id', 'id')->whereHas('article_user', function($query){
                    $query->where('user_id', auth()->user()->id)->where('is_assessed', false);
                })->withCount(['article_user' => function($query){
                    $query->where('user_id', auth()->user()->id)->where('is_assessed', false);
                }]);
            }])->get();
            $project_not_assessed = $data_project_not_assessed->map(function($project) {
                $articleUserCountSum = $project->article->sum('article_user_count');
                return $articleUserCountSum;
            });


            return view('dashboard.reviewer.index',
                [
                    'project_assign' => $project_assign,
                    'article_assign' => $article_assign,
                    'article_assessed' => $article_assessed,
                    'article_not_assessed' => $article_not_assessed,
                    'project_name' => $project_name,
                    'project_assessed' => $project_assessed,
                    'project_not_assessed' => $project_not_assessed,
                ]
            );
        }
    }
}
