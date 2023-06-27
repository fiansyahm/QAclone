<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectAdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AssignReviewerController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\DataProcessingController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SummaryController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index']);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->middleware('auth');

//Super Admin
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
//User Management
Route::get('/dashboard/user', [UserController::class, 'index'])->middleware('auth');
Route::get('/userTable', [UserController::class, 'userTable'])->middleware('auth');
Route::post('/addUser', [UserController::class, 'create'])->middleware('auth');
Route::put('/updateUser', [UserController::class, 'update'])->middleware('auth');
Route::delete('/deleteUser', [UserController::class, 'delete'])->middleware('auth');
//Project Management
Route::get('/dashboard/project', [ProjectController::class, 'index'])->middleware('auth');
Route::get('/projectTable', [ProjectController::class, 'projectTable'])->middleware('auth');
Route::post('/addProject', [ProjectController::class, 'store'])->middleware('auth');
Route::put('/updateProject', [ProjectController::class, 'update'])->middleware('auth');
Route::delete('/deleteProject', [ProjectController::class, 'delete'])->middleware('auth');
Route::get('/findReviewer', [ProjectController::class, 'findReviewer'])->middleware('auth');
Route::get('/findEditReviewer', [ProjectController::class, 'findEditReviewer'])->middleware('auth');
//ajax
Route::get('/findProjectUser', [ProjectController::class, 'findProjectUser'])->middleware('auth');

//Admin
//project
Route::get('/dashboard/admin/project', [ProjectAdminController::class, 'index'])->middleware('auth');
Route::get('/dashboard/admin/project/{id}', [ProjectAdminController::class, 'show'])->middleware('auth')->name('project.show');
//article
Route::get('/articleTable/{id}', [ArticleController::class, 'articleTable'])->middleware('auth')->name('article.table');
Route::get('/dashboard/admin/article/create', [ArticleController::class, 'create'])->middleware('auth')->name('article.create');
Route::post('/dashboard/admin/article/store', [ArticleController::class, 'store'])->middleware('auth')->name('article.store');
Route::delete('/deleteArticle', [ArticleController::class, 'delete'])->middleware('auth');
Route::get('/dashboard/admin/article/{id}/edit', [ArticleController::class, 'edit'])->middleware('auth')->name('article.edit');
Route::patch('/dashboard/admin/article/update', [ArticleController::class, 'update'])->middleware('auth')->name('article.update');
Route::post('/article/import', [ArticleController::class, 'storeExcel'])->middleware('auth')->name('article.import');
Route::get('/article/download', [ArticleController::class, 'downloadExcel'])->middleware('auth')->name('article.download');
Route::get('/assignmentTable/{id}', [ArticleController::class, 'assignmentTable'])->middleware('auth')->name('assignment.table');
Route::get('/articleScore', [ArticleController::class, 'articleScore'])->middleware('auth')->name('article.score');
Route::get('/articleShow', [ArticleController::class, 'articleShow'])->middleware('auth')->name('article.show');
Route::put('/addArticleFile', [ArticleController::class, 'addArticleFile'])->middleware('auth')->name('article.addFile');
Route::get('/findArticleScore', [ArticleController::class, 'findArticleScore'])->middleware('auth')->name('find.articleScore');
Route::get('/findArticleDB', [ArticleController::class, 'findArticleDB'])->middleware('auth')->name('find.articleDB');
Route::get('/exportResult', [ExportController::class, 'export'])->middleware('auth')->name('export.result');
//Assign Article
Route::get('/dashboard/admin/assign', [AssignReviewerController::class, 'index'])->middleware('auth');
Route::get('/assignedTable', [AssignReviewerController::class, 'articleAssignTable'])->middleware('auth')->name('assigned.table');
Route::get('/notAssignedTable', [AssignReviewerController::class, 'articleNotAssignTable'])->middleware('auth')->name('notAssigned.table');
Route::post('/dashboard/admin/assign/store', [AssignReviewerController::class, 'assignArticle'])->middleware('auth')->name('assign.store');
Route::post('/dashboard/admin/assign/delete', [AssignReviewerController::class, 'deleteAssignArticle'])->middleware('auth')->name('assign.remove');
Route::get('/dashboard/admin/articleStatus', [ProjectAdminController::class, 'articleStatus'])->middleware('auth')->name('article.status');
Route::get('/findStatus', [ProjectAdminController::class, 'findStatus'])->middleware('auth')->name('find.status');
Route::get('/findUserArticle', [ProjectAdminController::class, 'findUserArticle'])->middleware('auth')->name('find.userArticle');

//Summary
Route::get('/dashboard/projectSummary', [SummaryController::class, 'projectSummary'])->middleware('auth')->name('project.summary');
Route::get('/findProjectSummary', [SummaryController::class, 'findProjectSummary'])->middleware('auth')->name('find.projectSummary');
Route::get('/findArticleType', [SummaryController::class, 'findArticleType'])->middleware('auth')->name('find.articleType');

//map
Route::get('/getMapData', [SummaryController::class, 'getMapData'])->middleware('auth')->name('get.mapData');

//Reviewer
Route::get('/dashboard/reviewer/assessment', [AssessmentController::class, 'index'])->middleware('auth');
Route::get('/assessmentTable', [AssessmentController::class, 'assessmentTable'])->middleware('auth')->name('assessment.table');
Route::post('/dashboard/reviewer/assessment/store', [AssessmentController::class, 'store'])->middleware('auth')->name('assessment.store');
Route::get('/dashboard/reviewer/assessed', [AssessmentController::class, 'assessedIndex'])->middleware('auth');
Route::get('/assessedTable', [AssessmentController::class, 'assessedTable'])->middleware('auth')->name('assessed.table');
Route::get('/reviewerScore', [AssessmentController::class, 'scoreReviewer'])->middleware('auth')->name('reviewer.score');
Route::get('/editScore', [AssessmentController::class, 'editScore'])->middleware('auth')->name('reviewer.editScore');
Route::post('/dashboard/reviewer/assessment/update', [AssessmentController::class, 'updateScore'])->middleware('auth')->name('reviewer.updateScore');
Route::get('/previewArticle', [AssessmentController::class, 'previewArticle'])->middleware('auth')->name('reviewer.previewArticle');
Route::get('/findDetailArticle', [AssessmentController::class, 'findDetailArticle'])->middleware('auth')->name('find.detailArticle');
Route::get('/dashboard/reviewer/article/detail/{id}', [AssessmentController::class, 'show'])->middleware('auth');
//////////////////////////////////////
// PENGOLAHAN DATA
/////////////////////////////////////
// sebelumnya bisa ikuti command dibawah
// cd ./python
// python run_app_flask.py
Route::get('/pengolahan-data', [DataProcessingController::class, 'pengolahan_data'])->middleware('auth');
Route::get('/gambar-graph', [DataProcessingController::class, 'gambar_graph'])->middleware('auth');
Route::get('/gambar-graph', [DataProcessingController::class, 'gambar_graph'])->middleware('auth');
Route::get('/my-image', [DataProcessingController::class, 'my_image'])->middleware('auth');
Route::get('/data/{id}/rank', [DataProcessingController::class, 'data_rank'])->middleware('auth');
Route::get('/data/{id}/graph', [DataProcessingController::class, 'data_graph'])->middleware('auth');
Route::get('/metadata/{id}', [DataProcessingController::class, 'meta_data'])->middleware('auth');
Route::get('/worldmap', [DataProcessingController::class, 'worldmap'])->middleware('auth');
Route::post('/proses-metadata/{id}', [DataProcessingController::class, 'proses_meta_data'])->middleware('auth');
Route::post('/proses-worldmap', [DataProcessingController::class, 'proses_worldmap'])->middleware('auth');
Route::post('/get-image-graph/{id}', [DataProcessingController::class, 'get_image_graph'])->middleware('auth');
