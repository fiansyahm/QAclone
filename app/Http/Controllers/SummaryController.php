<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleUserQuestionaire;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Questionaire;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SummaryController extends Controller
{
    public function projectSummary()
    {
        $this->authorize('projectSummary');
        if (auth()->user()->is_superAdmin) {
            $projects = Project::select('id', 'project_name')->get();
        }
        else {
            $projects = Project::select('id', 'project_name')->whereHas('project_user', function($query){
                $query->where('user_id', auth()->user()->id)->where('user_role', 'admin');
            })->get();
        }
        return view('dashboard.summary.summary', compact('projects'));
    }
    
    public function findProjectSummary(Request $request)
    {
        $this->authorize('projectSummary');
        $articles = Article::with(['project', 'article_user' => function($query){
            $query->with('user');
        }])->where('project_id', $request->project_id)->whereHas('article_user', function($query){
            $query->where('is_assessed', false);
        })->get();
    
        // variable for chart
        $question_name = Questionaire::select('name')->pluck('name')->toArray();
        $user_name = User::select('name')->whereHas('project_user', function($query) use ($request){
            $query->where('project_id', $request->project_id)->where('user_role', 'reviewer');
        })->pluck('name')->toArray();
    
    
        $pos_answer_question = [];
        $net_answer_question = [];
        $neg_answer_question = [];
        $pos_answer_user = [];
        $net_answer_user = [];
        $neg_answer_user = [];
    
        // positive answer for question chart
        $data_pos_answer_question = Questionaire::with(['article_user_questionaire' => function($query) use ($request){
            $query->whereHas('articleUser', function($query) use ($request){
                $query->whereHas('article', function($query) use ($request){
                    $query->where('project_id', $request->project_id);
                });
            })->where('score', 1);
        }])->get();
        foreach ($data_pos_answer_question as $key => $value) {
            $pos_answer_question[$key] = $value->article_user_questionaire->count();
        }
    
        // netral answer for question chart
        $data_net_answer_question = Questionaire::with(['article_user_questionaire' => function($query) use ($request){
            $query->whereHas('articleUser', function($query) use ($request){
                $query->whereHas('article', function($query) use ($request){
                    $query->where('project_id', $request->project_id);
                });
            })->where('score', 0);
        }])->get();
        foreach ($data_net_answer_question as $key => $value) {
            $net_answer_question[$key] = $value->article_user_questionaire->count();
        }
    
        // negative answer for question chart
        $data_neg_answer_question = Questionaire::with(['article_user_questionaire' => function($query) use ($request){
            $query->whereHas('articleUser', function($query) use ($request){
                $query->whereHas('article', function($query) use ($request){
                    $query->where('project_id', $request->project_id);
                });
            })->where('score', -1);
        }])->get();
        foreach ($data_neg_answer_question as $key => $value) {
            $neg_answer_question[$key] = $value->article_user_questionaire->count();
        }
    
        $data_pos_answer_user = User::whereHas('project_user', function($query) use ($request){
            $query->where('project_id', $request->project_id)->where('user_role', 'reviewer');
        })->with(['article_user' => function($query) use ($request){
            $query->whereHas('questionaires', function($query){
                $query->where('score', 1);
            })->whereHas('article', function($query) use ($request){
                $query->where('project_id', $request->project_id);
            });
        }])->get();
        foreach ($data_pos_answer_user as $key => $value) {
            $pos_answer_user[$key] = $value->article_user->count();
        }
    
        $data_net_answer_user = User::whereHas('project_user', function($query) use ($request){
            $query->where('project_id', $request->project_id)->where('user_role', 'reviewer');
        })->with(['article_user' => function($query) use ($request){
            $query->whereHas('questionaires', function($query){
                $query->where('score', 0);
            })->whereHas('article', function($query) use ($request){
                $query->where('project_id', $request->project_id);
            });
        }])->get();
        foreach ($data_net_answer_user as $key => $value) {
            $net_answer_user[$key] = $value->article_user->count();
        }
    
        $data_neg_answer_user = User::whereHas('project_user', function($query) use ($request){
            $query->where('project_id', $request->project_id)->where('user_role', 'reviewer');
        })->with(['article_user' => function($query) use ($request){
            $query->whereHas('questionaires', function($query){
                $query->where('score', -1);
            })->whereHas('article', function($query) use ($request){
                $query->where('project_id', $request->project_id);
            });
        }])->get();
        foreach ($data_neg_answer_user as $key => $value) {
            $neg_answer_user[$key] = $value->article_user->count();
        }
        
        return compact('articles', 
                        'question_name', 
                        'user_name', 
                        'pos_answer_question', 
                        'net_answer_question',
                        'neg_answer_question',
                        'pos_answer_user',
                        'net_answer_user',
                        'neg_answer_user'
                        );
        return view('dashboard.summary.summary', compact('articles', 
                                                                'question_name', 
                                                                'user_name', 
                                                                'pos_answer_question', 
                                                                'net_answer_question',
                                                                'neg_answer_question',
                                                                'pos_answer_user',
                                                                'net_answer_user',
                                                                'neg_answer_user'
                                                                ));

    }

    public function findArticleType(Request $request)
    {
        $this->authorize('projectSummary');
        $articles = Article::select('type', 'year', DB::raw('count(*) as total'))
                            ->where('project_id', $request->project_id)
                            ->whereBetween('year', [$request->yearFrom, $request->yearTo])
                            ->groupBy('year', 'type')
                            ->get();
        $data_year = $articles->pluck('year')->unique();
        $year = [];
        foreach ($data_year as $value) {
            $year[] = $value;
        }
        return compact('articles', 'year');
    }

    public function getMapData()
    {
        try {
            $response = Http::get('https://restcountries.com/v2/all');
            $data = $response->json();
        } catch (\Exception $e) {
            // Handle the error when the API request fails
            // You can log the error, display a message, or perform any other desired action
        
            // Read the data from the local file instead
            $filePath = storage_path('app/public/world.json');
            $data = File::exists($filePath) ? json_decode(File::get($filePath), true) : [];
        }
        
        return $data;
    }
}
