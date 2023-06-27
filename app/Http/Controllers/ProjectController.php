<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleUser;
use App\Models\ArticleUserQuestionaire;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    public function index()
    {
        $this->authorize('superadmin');
        $project = Project::with(['project_user' => function($query){
            $query->with('user')->where('user_role', 'admin');
        }])->get();
        // return $project[0]->project_user[0]->user->name;
        return view('dashboard.superAdmin.project', [
            'projects' => $project,
            'users' => User::where('id', '!=', auth()->user()->id)->get(),
        ]);
    }

    public function projectTable()
    {
        $this->authorize('superadmin');
        $project = Project::with(['project_user' => function($query){
            $query->with('user');
        }])->orderBy('id')->get();
        return DataTables::of($project)
            ->addIndexColumn()
            ->addColumn('project_name', function(Project $project){
                return $project->project_name;
            })
            ->addColumn('admin_project', function(Project $project){
                $name = '';
                foreach ($project->project_user as $pu) {
                    if ($pu->user_role == 'admin') {
                        $name .= '<span class="badge alert-success">'.$pu->user->name.'</span> '; 
                    }
                }
                return $name;
            })
            ->addColumn('reviewer', function(Project $project){
                $reviewer = '';
                foreach ($project->project_user as $pu) {
                    if ($pu->user_role == 'reviewer') {
                        $reviewer .= '<span class="badge alert-primary">'.$pu->user->name.'</span> ';
                    }
                }
                return $reviewer;
            })
            ->addColumn('action', function(Project $row){
                $btn = '<button type="button" class="btn btn-primary btn-sm aksi" data-toggle="modal" data-bs-target="#modalEdit" data-id="'.$row->id.'" data-project_name="'.$row->project_name.'" data-limit="'.$row->limit_reviewer.'" data-admin_project="'.$row->project_user[0]->user->id.'"><ion-icon name="create-outline"></ion-icon> Edit</button>';
                $btn .= '<button type="button" class="btn btn-danger btn-sm ms-2 aksi deleteProject" data-id="'.$row->id.'"><ion-icon name="trash-outline"></ion-icon> Delete</button>';
                return $btn;
            })
            ->rawColumns(['action', 'reviewer', 'admin_project'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $this->authorize('superadmin');
        $request->validate([
            'project_name' => 'required|unique:projects,project_name',
            'limit' => 'required',
            'admin_project' => 'required',
        ]);
        $project = Project::create([
            'project_name' => $request->project_name,
            'limit_reviewer' => $request->limit,
        ]);
        foreach ($request->admin_project as $admin) {
            ProjectUser::create([
                'project_id' => $project->id,
                'user_id' => $admin,
                'user_role' => 'admin',
            ]);
            User::where('id', $admin)->update([
                'is_admin' => true,
            ]);
        }
        foreach ($request->reviewer as $reviewer) {
            ProjectUser::create([
                'project_id' => $project->id,
                'user_id' => $reviewer,
                'user_role' => 'reviewer',
            ]);
        }
    }

    public function update(Request $request)
    {
        $this->authorize('superadmin');
        // return $request;
        $request->validate([
            'project_name' => 'required',
            'limit' => 'required',
        ]);

        $admin = ProjectUser::where('project_id', $request->project_id)->where('user_role', 'admin')->pluck('user_id')->toArray();

        foreach ($request->admin_project as $value) {
            if (!in_array($value, $admin)) {
                $article_user_admin = ArticleUser::with('user')->where('user_id', $value)->whereHas('article', function($query) use ($request){
                    $query->where('project_id', $request->project_id);
                })->first();

                if ($article_user_admin) {
                    if ($article_user_admin->is_assessed == true) {
                        $error = $article_user_admin->user->name.' cannot be made admin because it has already done an assessment';
                        return json_encode(['error' => $error]);
                    }
                }
            }
        }

        DB::beginTransaction();
        try {
            Project::where('id', $request->project_id)->update([
                'project_name' => $request->project_name,
                'limit_reviewer' => $request->limit,
            ]);
    
            if ($request->old_admin != $request->admin_project) {
                foreach ($request->admin_project as $key => $value) {
                    $article_user_admin = ArticleUser::with('user')->where('user_id', $value)->whereHas('article', function($query) use ($request){
                        $query->where('project_id', $request->project_id);
                    })->first();

                    ArticleUser::where('user_id', $value)->whereHas('article', function($query) use ($request){
                        $query->where('project_id', $request->project_id);
                    })->delete();
    
                    ProjectUser::where('project_id', $request->project_id)->where('user_id', $value)->update([
                        'user_role' => 'admin',
                    ]);
                    User::where('id', $value)->update([
                        'is_admin' => true,
                    ]);
    
                }
            }
            
            $project_user = ProjectUser::where('project_id', $request->project_id)->where('user_role', 'reviewer')->get();
            $reviewer_array = [];
            if($request->has('reviewer')){
                $reviewer_array = $request->reviewer;
            }
            foreach ($project_user as $pu) {
                if (!in_array($pu->user_id, $reviewer_array) && !in_array($pu->user_id, $request->admin_project)) {
                    ProjectUser::where('project_id', $request->project_id)->where('user_id', $pu->user_id)->delete();
                    $article_user = ArticleUser::whereHas('article', function($query) use ($request){
                        $query->where('project_id', $request->project_id);
                    })->where('user_id', $pu->user_id);
                    $article_user->delete();
    
                    ArticleUserQuestionaire::where('article_user_id', $article_user->pluck('id')->toArray())->delete();
                }
            }
            foreach ($reviewer_array as $reviewer) {
                if (!in_array($reviewer, $project_user->pluck('user_id')->toArray())) {
                    // update or create
                    ProjectUser::updateOrCreate([
                        'project_id' => $request->project_id,
                        'user_id' => $reviewer,
                    ],[
                        'user_role' => 'reviewer',
                    ]);
                }
            }
    
            $user_project_user = User::with(['project_user' => function($query){
                $query->where('user_role', 'admin');
            }])->get();
            foreach($user_project_user as $upu){
                if($upu->project_user->count() == 0 || $upu->project_user == null){
                    User::where('id', $upu->id)->update([
                        'is_admin' => false,
                    ]);
                }
            }
    
            $user = User::with(['project_user' => function($query) use ($request){
                $query->where('user_role', 'admin');
            }])->where('id', $request->old_admin)->first();
    
            if ($user->project_user->count() == 0) {
                User::where('id', $request->old_admin)->update([
                    'is_admin' => false,
                ]);
            }
            else {
                User::where('id', $request->old_admin)->update([
                    'is_admin' => true,
                ]);
            }
            DB::commit();
            return json_encode(['success' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return json_encode(['error' => $th->getMessage()]);
        }
        
    }

    public function delete(Request $request)
    {
        $this->authorize('superadmin');
        
        if (Article::whereHas('article_user', function($query){
            $query->where('is_assessed', true);
        })->where('project_id', $request->id)->exists()) {
            return json_encode(['error' => 'Cannot delete project because it has already been assessed']);
        }
        DB::beginTransaction();
        try {
            Project::where('id', $request->id)->delete();
            ProjectUser::where('project_id', $request->id)->delete();

            $users = User::with('project_user')->get();
            $articles = Article::where('project_id', $request->id)->get();
            
            foreach ($articles as $article) {
                $articleUser = ArticleUser::where('article_id', $article->id);
                foreach ($articleUser as $au) {
                    ArticleUserQuestionaire::where('article_user_id', $au->id)->delete();
                }
                $articleUser->delete();
            }
            Article::where('project_id', $request->id)->delete();

            $user_project_user = User::with(['project_user' => function($query){
                $query->where('user_role', 'admin');
            }])->get();
            foreach($user_project_user as $upu){
                if($upu->project_user->count() == 0 || $upu->project_user == null){
                    User::where('id', $upu->id)->update([
                        'is_admin' => false,
                    ]);
                }
            }
            foreach ($users as $user) {
                if ($user->project_user->count() == 0) {
                    $user->update([
                        'is_admin' => false,
                    ]);
                }
            }
            DB::commit();
            return json_encode(['success' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return json_encode(['error' => $th->getMessage()]);
        }
        

        
        
    }

    public function findProjectUser(Request $request)
    {
        $this->authorize('superadmin');
        $project = Project::with(['project_user' => function($query){
            $query->with('user')->orderBy('user_role');
        }])->where('id', $request->id)->first();
        return $project->project_user->toJson();
    }

    public function findReviewer(Request $request)
    {
        $this->authorize('superadmin');
        $array = json_decode($request->user_id);
        $user = User::whereNotIn('id', $array)->where('is_superadmin', '!=', true)->where('id', '!=', auth()->user()->id)->get();
        return $user->toJson();
    }

    public function findEditReviewer(Request $request)
    {
        $this->authorize('superadmin');
        $array = json_decode($request->user_id);
        $user = User::whereNotIn('id', $array)->where('is_superadmin', '!=', true)->where('id', '!=', auth()->user()->id)->with('project_user', function($query) use ($request){
            $query->where('project_id', $request->project_id);
        })->get();
        return $user->toJson();
    }
}
