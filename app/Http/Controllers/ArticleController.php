<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Yajra\DataTables\DataTables;
use App\Imports\ArticleImport;
use App\Models\ArticleUser;
use App\Models\ArticleUserQuestionaire;
use App\Models\Questionaire;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function articleTable(Request $request,$id)
    {
        $this->authorize('admin');
        $articles = Article::select('id', 'no', 'title', 'year', 'publication', 'authors', 'file', 'link_articles', 'project_id')->where('project_id', $id);
        if ($request->has('edatabase')) {
            $articles = $articles->where('edatabase', $request->edatabase);
        }
        return DataTables::of($articles)
            ->addColumn('no', function (Article $article) {
                return $article->id . ' - ' . $article->no;
            })
            ->addColumn('title', function (Article $article) {
                return '<span style="white-space:normal">'.$article->title.'</span>';
            })
            ->addColumn('year', function (Article $article) {
                return $article->year;
            })
            ->addColumn('publication', function (Article $article) {
                return '<span style="white-space:normal">'.$article->publication.'</span>';
            })
            ->addColumn('authors', function (Article $article) {
                return '<span style="white-space:normal">'.$article->authors.'</span>';
            })
            ->addColumn('article_file', function(Article $article){
                if($article->file == null && $article->link_articles == null)
                {
                    $content = 'No Preview Available<br><br><button type="button" id="addFileBtn" class="btn btn-sm alert-success" data-bs-toggle="modal" data-bs-target="#addFileModal" data-no="'.$article->no.'" data-title="'. $article->title .'" data-id="' . $article->id . '" data-no="'.$article->no.'"><ion-icon name="document-attach"></ion-icon> Add File</button>';
                }
                if ($article->file == null && $article->link_articles != null) {
                    $content = '<a href="'.$article->link_articles.'" target="_blank" class="btn btn-sm alert-primary"><ion-icon name="link"></ion-icon> Go To Link</a>';
                }
                if ($article->file != null && $article->link_articles == null) {
                    $content = '<button type="button" id="filePreview" data-bs-toggle="modal" data-bs-target="#fileModal" data-no="'.$article->no.'" data-title="'. $article->title .'" data-file="' . URL::asset('/storage/article/'.$article->file) . '" class="btn btn-sm alert-primary"><ion-icon name="attach"></ion-icon> Preview File</button>';
                }
                return $content;
            })
            ->addColumn('action', function (Article $article) use ($id) {
                $btn = '<button type="button" style="width:100%;" class="btn btn-warning text-white btn-sm aksi scoreArticle" id="scoreArticle" data-bs-toggle="modal" data-bs-target="#modalScore" data-no="'.$article->no.'" data-id="' . $article->id . '" data-title="' . $article->title . '"><ion-icon name="stats-chart-outline"></ion-icon> Score</button><br>';
                $btn .= '<a href="/dashboard/admin/article/' . encrypt($article->id) . '/edit?pid=' . encrypt($id) . '"><button type="button" style="width:100%;" class="btn btn-primary btn-sm aksi mt-2 mb-2"><ion-icon name="create-outline"></ion-icon> Edit</button></a><br>';
                $btn .= '<button type="button" style="width:100%;" class="btn btn-danger btn-sm aksi deleteArticle" data-id="' . $article->id . '" data-project_id="'.$article->project_id.'" data-no="'.$article->no.'"><ion-icon name="trash-outline"></ion-icon> Delete</button>';
                return $btn;
            })
            ->filterColumn('no', function ($query, $keyword) {
                $sql = "CONCAT(articles.id,' - ',articles.no)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('title', function ($query, $keyword) {
                $sql = "articles.title  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('publication', function ($query, $keyword) {
                $sql = "articles.publication  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('authors', function ($query, $keyword) {
                $sql = "articles.authors  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->rawColumns(['no','title', 'publication', 'authors', 'action', 'article_file'])
            ->toJson();
    }

    public function assignmentTable($id)
    {
        $this->authorize('admin');
        $users = User::with(['article_user' => function ($query) use ($id) {
                    $query->whereHas('article', function ($query) use ($id) {
                        $query->where('project_id', $id);
                    });
                }])->where('id', '!=', auth()->user()->id)->where('is_superadmin', false)->whereHas('project_user', function ($query) use ($id) {
                    $query->where('project_id', $id)->where('user_role', 'reviewer');
                })->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('name', function (User $user) {
                return $user->name;
            })
            ->addColumn('article', function (User $user) {
                if(count($user->article_user) == 0 || $user->article_user[0]->article == null){
                    return '<span class="badge alert-danger text">No Article Assigned</span>';
                } else {
                    return '<span class="badge alert-primary">'.count($user->article_user).' Article(s) Assigned</span>';
                }
            })
            ->addColumn('action', function (User $user) use ($id) {
                if (count($user->article_user) == 0 || $user->article_user[0]->article == null)
                {
                    $btn = '<a href="/dashboard/admin/assign?pid=' . encrypt($id) . '&uid=' . encrypt($user->id) . '">
                                <button type="button" class="btn btn-sm btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                                </svg> Assign</button>
                            </a>';
                    $btn .= '<button disabled type="button" id="showArticle" class="btn btn-sm btn-primary">
                                <ion-icon name="eye-sharp"></ion-icon> Show
                            </button>';  
                }
                else {
                    $btn = '<a href="/dashboard/admin/assign?pid=' . encrypt($id) . '&uid=' . encrypt($user->id) . '">
                                <button type="button" class="btn btn-sm btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                                </svg> Assign</button>
                            </a>';
                    $btn .= '<a href="javascript:;"
                                <button type="button" id="showArticle" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#articleModal" data-name="'.$user->name.'" data-id="'.$user->id.'" data-project="'.$id.'">
                                <ion-icon name="eye-sharp"></ion-icon> Show</button>
                            </a>';  
                }
                return $btn;
            })
            // ->rawColumns(['id_no', 'title', 'assessed', 'action'])
            ->rawColumns(['action', 'article'])
            ->toJson();
    }

    public function create()
    {
        $this->authorize('admin');
        $http = new Client();
        $response = $http->get('https://restcountries.com/v3.1/all?fields=name,cca2');
        $countries = json_decode($response->getBody(), true);
        return view('dashboard.admin.article.create', [
            'project_id' => decrypt(request()->id),
            'countries' => $countries,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'kode_artikel' => ['required', Rule::unique('articles', 'no')->where(function($query) use ($request){
                return $query->where('project_id', $request->project_id);
            })],
            'file' => 'mimes:pdf|nullable',
            'link' => 'url|nullable',
            'title' => 'required',
            'publication' => 'required',
            'year' => 'required',
            'authors' => 'required',
            'language' => 'required',
            'article_type' => 'required',
            'publisher' => 'required',
            'cited' => 'required',
            'cited_gs' => 'required',
            'cited_other' => 'required',
            'keyword' => 'required',
            'edatabase' => 'required'
        ]);

        if ($request->link != null && $request->file('file') != null) {
            return redirect()->back()->with('error', 'Please choose one of the file type');
        }

        if ($request->file('file') != null) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName().'_'.time().'.'.$file->getClientOriginalExtension();
            Storage::putFileAs('public/article', $file, $file_name);
        }
        //save data to database
        $article = Article::create([
            'no' => $request->kode_artikel,
            'file' => $file_name ?? null,
            'link_articles' => $request->link ?? null,
            'title' => $request->title,
            'publication' => $request->publication,
            'index' => $request->index ?? null,
            'quartile' => $request->quartile ?? null,
            'year' => $request->year,
            'authors' => $request->authors,
            'abstracts' => $request->abstract ?? null,
            'keywords' => $request->keywords ?? null,
            'language' => $request->language,
            'type' => $request->article_type,
            'publisher' => $request->publisher ?? null,
            'references_ori' => $request->references_ori ?? null,
            'references_filter' => $request->references_filter ?? null,
            'cited' => $request->cited,
            'cited_gs' => $request->cited_gs,
            'cited_other' => $request->cited_other,
            'citing' => $request->cited_other,
            'keyword' => $request->keyword,
            'edatabase' => $request->edatabase,
            'edatabase_2' => $request->edatabase2 ?? null,
            'nation_first_author' => $request->nation_first_author ?? null,
            'project_id' => $request->project_id,
        ]);
        return redirect()->route('project.show',  encrypt($request->project_id))->with('success', 'Article successfully added!');
    }

    public function edit($id)
    {
        $this->authorize('admin');
        $article = Article::find(decrypt($id));
        $http = new Client();
        $response = $http->get('https://restcountries.com/v3.1/all?fields=name,cca2');
        $countries = json_decode($response->getBody(), true);
        return view('dashboard.admin.article.edit', [
            'article' => $article,
            'project_id' => decrypt(request()->pid),
            'countries' => $countries,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'no' => ['required', Rule::unique('articles', 'no')->where(function($query) use ($request){
                return $query->where('project_id', $request->project_id);
            })],
            'file' => 'mimes:pdf|nullable',
            'link' => 'url|nullable',
            'title' => 'required',
            'publication' => 'required',
            'year' => 'required',
            'authors' => 'required',
            'language' => 'required',
            'article_type' => 'required',
            'publisher' => 'required',
            'cited' => 'required',
            'cited_gs' => 'required',
            'cited_other' => 'required',
            'keyword' => 'required',
            'edatabase' => 'required',
        ]);

        $article = Article::find($request->article_id);
        if ($request->file('file') != null) {
            if ($article->file != null) {
                $file_path = storage_path('/app/public/article/' . $article->file);
                File::delete($file_path);
            }

            $file = $request->file('file');
            $file_name = $file->getClientOriginalName().'_'.time().'.'.$file->getClientOriginalExtension();
            Storage::putFileAs('public/article', $file, $file_name);
            $article->update([
                'file' => $file_name,
                'link_articles' => null
            ]);
        } elseif ($request->link != null) {
            if ($article->file != null) {
                $file_path = storage_path('/app/public/article/' . $article->file);
                File::delete($file_path);
            }
            $article->update([
                'link_articles' => $request->link,
                'file' => null
            ]);
        } elseif ($request->link != null && $request->file('file') != null) {
            return redirect()->back()->with('error', 'Please choose one of the file type');
        }

        $article->update([
            'no' => $request->kode_artikel,
            'title' => $request->title ?? $article->title,
            'publication' => $request->publication ?? $article->publication,
            'index' => $request->index ?? $article->index,
            'quartile' => $request->quartile ?? $article->quartile,
            'year' => $request->year ?? $article->year,
            'authors' => $request->authors ?? $article->authors,
            'abstracts' => $request->abstract ?? $article->abstracts,
            'keywords' => $request->keywords ?? $article->keywords,
            'language' => $request->language ?? $article->language,
            'type' => $request->article_type ?? $article->type,
            'publisher' => $request->publisher ?? $article->publisher,
            'references_ori' => $request->references_ori ?? $article->references_ori,
            'references_filter' => $request->references_filter ?? $article->references_filter,
            'cited' => $request->cited ?? $article->cited,
            'cited_gs' => $request->cited_gs ?? $article->cited_gs,
            'cited_other' => $request->cited_other ?? $article->cited_other,
            'citing_new' => $request->cited_other ?? $article->citing_new,
            'keyword' => $request->keyword ?? $article->keyword,
            'edatabase' => $request->edatabase ?? $article->edatabase,
            'edatabase_2' => $request->edatabase2 ?? $article->edatabase_2,
            'nation_first_author' => $request->nation_first_author ?? $article->nation_first_author,
        ]);
        return redirect()->route('project.show',  encrypt($article->project_id))->with('success', 'Article successfully updated!');
    }

    public function delete(Request $request)
    {
        $this->authorize('admin');
        $article = Article::find($request->id);
        $article_citing = Article::select('id', 'no', 'citing', 'citing_new', 'project_id')->where('project_id', $request->project_id)->where(function($query) use ($article) {
            $query->where('citing', 'like', '%'.$article->no.'%')->orWhere('citing_new', 'like', '%'.$article->no.'%');
        })->get();

        if (ArticleUser::where('article_id', $request->id)->where('is_assessed', true)->exists())
        {
            return json_encode(['error' => 'Article has been assessed, cannot be deleted!']);
        }
        if ($article->file != null) {
            $file_path = storage_path('/app/public/article/' . $article->file);
            File::delete($file_path);
        }

        ArticleUser::where('article_id', $request->id)->delete();
        $article->delete();

        foreach ($article_citing as $key => $value) {
            if (substr($value->citing, -strlen($article->no)) === $article->no) {
                $citing = $value->citing;
                $citing = str_replace($article->no, '', $citing);
                $value->update([
                    'citing' => $citing
                ]);
            }
            else {
                $citing = $value->citing_new;
                $citing = str_replace($article->no.';', '', $citing);
                $value->update([
                    'citing' => $citing
                ]);
            }
            if (substr($value->citing_new, -strlen($article->no)) === $article->no) {
                $citing_new = $value->citing_new;
                $citing_new = str_replace($article->no, '', $citing_new);
                $value->update([
                    'citing_new' => $citing_new
                ]);
            }
            else {
                $citing_new = $value->citing_new;
                $citing_new = str_replace($article->no.';', '', $citing_new);
                $value->update([
                    'citing_new' => $citing_new
                ]);
            }
        }

        return json_encode(['success' => 'Article successfully deleted!']);
    }

    public function storeExcel(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx'
        ]);
        $file = $request->file('excel_file');

        Excel::import(new ArticleImport($request->project_id), $file);

        return response()->json(['success' => 'Excel data imported successfully.']);
    }

    public function downloadExcel()
    {
        $this->authorize('admin');
        return response()->download(public_path('articles/TemplateSLR.xlsx'));
    }

    public function articleScore(Request $request)
    {
        $this->authorize('admin');
        $score = Questionaire::with(['article_user_questionaire' => function($query) use ($request){
            $query->with(['articleUser' => function($query) use ($request){
                $query->with('user')->where('article_id', $request->article_id);
            }]);
        }])->get();
        return $score;
    }

    public function articleShow(Request $request)
    {
        $this->authorize('admin');
        $article = ArticleUser::where('user_id', $request->user_id)->whereHas('article', function($query) use ($request){
            $query->where('project_id', $request->project_id);
        })->with('article')->get();

        return json_encode($article);
    }

    public function addArticleFile(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'file' => 'mimes:pdf|nullable',
            'link' => 'url|nullable',
        ]);

        $article = Article::find($request->article_id);
        if ($request->file('file') != null)
        {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName().'_'.time().'.'.$file->getClientOriginalExtension();
            Storage::putFileAs('public/article', $file, $file_name);
            $article->update([
                'file' => $file_name,
                'link_articles' => null
            ]);
        } else {
            $article->update([
                'link_articles' => $request->link,
                'file' => null
            ]);
        }

        return json_encode(['success' => 'Article file successfully added!']);
    }

    public function findArticleScore(Request $request)
    {
        $this->authorize('admin');
        $pos_answer_question = [];
        $net_answer_question = [];
        $neg_answer_question = [];

        $data = Questionaire::with(['article_user_questionaire' => function($query) use ($request){
            $query->whereHas('articleUser', function($query) use ($request){
                $query->where('article_id', $request->article_id);
            })->groupBy('score', 'questionaire_id')->selectRaw('count(*) as total, score, questionaire_id');
        }])->get();

        foreach ($data as $key => $value) {
            foreach ($value->article_user_questionaire as $value2) {
                if ($value2->score == 1) {
                    $pos_answer_question[$key] = $value2->total;
                    break;
                }
                else {
                    $pos_answer_question[$key] = 0;
                }
            }
        }

        foreach ($data as $key => $value) {
            foreach ($value->article_user_questionaire as $value2) {
                if ($value2->score == 0) {
                    $net_answer_question[$key] = $value2->total;
                    break;
                }
                else {
                    $net_answer_question[$key] = 0;
                }
            }
        }

        foreach ($data as $key => $value) {
            foreach ($value->article_user_questionaire as $value2) {
                if ($value2->score == -1) {
                    $neg_answer_question[$key] = $value2->total;
                    break;
                }
                else {
                    $neg_answer_question[$key] = 0;
                }
            }
        }

        return json_encode([
            'question_name' => Questionaire::select('name')->pluck('name')->toArray(),
            'pos_answer_question' => $pos_answer_question,
            'net_answer_question' => $net_answer_question,
            'neg_answer_question' => $neg_answer_question,
        ]);
    }

    public function findArticleDB(Request $request)
    {
        $this->authorize('admin');
        $article_db = Article::select('id', 'no', 'title', 'year', 'publication', 'authors', 'file', 'link_articles', 'project_id')->where('project_id', decrypt($request->project_id))->where('edatabase', $request->edatabase)->get();
        return json_encode($article_db);
    }
}
