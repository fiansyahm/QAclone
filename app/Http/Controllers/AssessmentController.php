<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleUser;
use App\Models\ArticleUserQuestionaire;
use App\Models\Project;
use App\Models\Questionaire;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AssessmentController extends Controller
{
    public function index()
    {
        $this->authorize('reviewer');
        $questionaires = Questionaire::all();
        $projects = Project::select('id','project_name')->whereHas('project_user', function($query) {
            $query->where('user_id', auth()->user()->id)->where('user_role', 'reviewer');
        })->get();
        return view('dashboard.reviewer.assessment', compact('questionaires', 'projects'));
    }
    
    public function assessmentTable(Request $request)
    {
        $this->authorize('reviewer');
        $articles = [];
        if ($request->has('project_id') && $request->project_id != 'all') {
            $data = ArticleUser::with(['article' => function($query) use ($request) {
                $query->with('project')->whereHas('project', function($query) use ($request) {
                    $query->where('id', $request->project_id);
                });
            }])->where('user_id', auth()->user()->id)->where('is_assessed', false)->get();
    
            foreach ($data as $key => $value) {
                if ($value->article != null) {
                    $articles[] = $value;
                }
            }
        }
        else {
            $articles = ArticleUser::with(['article' => function($query) {
                $query->with('project');
            }])->where('user_id', auth()->user()->id)->where('is_assessed', false)->get()->sortBy('article.project.project_name');
        }

        return DataTables::of($articles)
            ->addColumn('no', function(ArticleUser $article){
                return $article->article->id.' - '.$article->article->no;
            })
            ->addColumn('title', function(ArticleUser $article){
                return $article->article->title;
            })
            ->addColumn('project_name', function(ArticleUser $article) {
                return $article->article->project->project_name;
            })
            ->addColumn('year', function(ArticleUser $article) {
                return $article->article->year;
            })
            ->addColumn('publication', function(ArticleUser $article){
                return $article->article->publication;
            })
            ->addColumn('authors', function(ArticleUser $article){
                return $article->article->authors;
            })
            ->addColumn('action', function(ArticleUser $article){
                return '<button class="btn btn-primary btn-sm" id="btn_assessment" data-bs-toggle="modal" data-bs-target="#exampleModal" data-article_id="'.$article->article->id.'" data-title="'.$article->article->title.'" data-link="'.$article->article->link.'" data-file="'.$article->article->file.'" data-no="'.$article->article->no.'"><ion-icon name="pencil"></ion-icon> Assess</button>';
            })->rawColumns(['action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $this->authorize('reviewer');
        $answer = $request->toArray();
        $article_user = ArticleUser::where('article_id', $answer['article_id'])->where('user_id', auth()->user()->id)->first();

        foreach($answer['questionaire_id'] as $key => $value) {
            $questionaire_id = $value;
            $questionaire_answer = intval($answer['QA'.$value]);
            ArticleUserQuestionaire::Create([
                'article_user_id' => $article_user->id,
                'questionaire_id' => $questionaire_id,
                'score' => $questionaire_answer,
            ]);
        }
        $article_user->update([
            'is_assessed' => true,
        ]);

        return response()->json([
            'message' => 'Assessment has been submitted',
        ], 200);
    }

    public function assessedIndex()
    {
        $this->authorize('reviewer');
        $questionaires = Questionaire::with(['article_user_questionaire' => function($query){
            $query->whereHas('articleUser', function($query) {
                $query->where('user_id', auth()->user()->id);
            });
        }])->get();
        $projects = Project::select('id','project_name')->whereHas('project_user', function($query) {
            $query->where('user_id', auth()->user()->id)->where('user_role', 'reviewer');
        })->get();
        return view('dashboard.reviewer.assessed', compact('questionaires', 'projects'));
    }

    public function assessedTable(Request $request)
    {
        $this->authorize('reviewer');
        $articles = [];
        if ($request->has('project_id') && $request->project_id != 'all') {
            $data = ArticleUser::with(['article' => function($query) use ($request) {
                $query->with('project')->whereHas('project', function($query) use ($request) {
                    $query->where('id', $request->project_id);
                });
            }])->where('user_id', auth()->user()->id)->where('is_assessed', true)->get();
    
            foreach ($data as $key => $value) {
                if ($value->article != null) {
                    $articles[] = $value;
                }
            }
        }
        else {
            $articles = ArticleUser::with(['article' => function($query) {
                $query->with('project');
            }])->where('user_id', auth()->user()->id)->where('is_assessed', true)->get()->sortBy('article.project.project_name');
        }

        return DataTables::of($articles)
            ->addColumn('no', function(ArticleUser $article){
                return $article->article->id.' - '.$article->article->no;
            })
            ->addColumn('title', function(ArticleUser $article){
                return $article->article->title;
            })
            ->addColumn('project_name', function(ArticleUser $article) {
                return $article->article->project->project_name;
            })
            ->addColumn('year', function(ArticleUser $article) {
                return $article->article->year;
            })
            ->addColumn('publication', function(ArticleUser $article){
                return $article->article->publication;
            })
            ->addColumn('authors', function(ArticleUser $article){
                return $article->article->authors;
            })
            ->addColumn('action', function(ArticleUser $article){
                $btn = '<button type="button" class="btn btn-warning text-white btn-sm me-2 aksi scoreArticle" id="scoreArticle" data-bs-toggle="modal" data-bs-target="#modalScore" data-id="' . $article->article->id . '" data-title="' . $article->article->title . '" data-no="'.$article->article->no.'"><ion-icon name="stats-chart-outline"></ion-icon> Result</button>';
                $btn .= '<button class="btn btn-primary btn-sm" id="btn_edit_assessment" data-bs-toggle="modal" data-bs-target="#exampleModal" data-article_id="'.$article->article->id.'" data-title="'.$article->article->title.'" data-link="'.$article->article->link.'" data-file="'.$article->article->file.'" data-no="'.$article->article->no.'" data-project_id="'.$article->article->project_id.'"><ion-icon name="create-outline"></ion-icon> Edit</button>';
                return $btn;
            })->rawColumns(['action'])
            ->toJson();
    }

    public function scoreReviewer(Request $request)
    {
        $this->authorize('reviewer');
        $score = Questionaire::with(['article_user_questionaire' => function($query) use ($request){
            $query->with(['articleUser' => function($query) use ($request){
                $query->with('user')->where('article_id', $request->article_id)->where('user_id', auth()->user()->id)->first();
            }]);
        }])->get();

        return $score;
    }

    public function editScore(Request $request)
    {
        $questionaires = Questionaire::with(['article_user_questionaire' => function($query) use ($request){
            $query->whereHas('articleUser', function($query) use ($request){
                $query->where('user_id', auth()->user()->id)->where('article_id', $request->article_id);
            });
        }])->get();

        return $questionaires;
    }

    public function updateScore(Request $request)
    {
        $this->authorize('reviewer');
        $answer = $request->toArray();
        $article_user = ArticleUser::where('article_id', $answer['article_id'])->where('user_id', auth()->user()->id)->first();

        foreach($answer['questionaire_id'] as $key => $value) {
            $questionaire_answer = intval($answer['QA'.$value]);
            ArticleUserQuestionaire::where('article_user_id', $article_user->id)->where('questionaire_id', $value)->update([
                'score' => $questionaire_answer,
            ]);
        }

        return response()->json([
            'message' => 'Assessment has been updated',
        ], 200);
    }

    public function findDetailArticle(Request $request)
    {
        $this->authorize('reviewer');
        $article = Article::findOrFail($request->article_id);
        $authors = preg_split('/[,;|]/', $article->authors, -1, PREG_SPLIT_NO_EMPTY);

        $citingIds = array_values(preg_split('/\W+/', $article->citing));
        $citingNewIds = array_values(preg_split('/\W+/', $article->citing_new));

        $mergedCitingIds = array_merge($citingIds, $citingNewIds);

        $citing = Article::select('id', 'title')
            ->whereIn('no', $mergedCitingIds)
            ->where('project_id', $article->project_id)
            ->get();

        $citing->map(function ($item) {
            $item->encrypted_id = encrypt($item->id);
            return $item;
        });

        return response()->json([
            'article' => $article,
            'authors' => $authors,
            'citing' => $citing,
        ], 200);
    }

    public function show($id)
    {
        $this->authorize('reviewer');
        $decryptedId = decrypt($id);
        $article = Article::findOrFail($decryptedId);

        $citingIds = array_values(preg_split('/\W+/', $article->citing));
        $citingNewIds = array_values(preg_split('/\W+/', $article->citing_new));
        
        $mergedCitingIds = array_merge($citingIds, $citingNewIds);

        $citing = Article::select('no', 'id', 'title')
            ->whereIn('no', $mergedCitingIds)
            ->where('project_id', $article->project_id)
            ->get();

        $citing->map(function ($item) {
            $item->encrypted_id = encrypt($item->id);
            return $item;
        });

        return view('dashboard.reviewer.show', compact('article', 'citing'));
    }
}
