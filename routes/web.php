<?php

use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\mediaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\UserPermissions;
use App\Http\Controllers\GerenciarGrupo;
use App\Http\Controllers\GerenciarUsuarios;
use App\Http\Controllers\FamiliaController;
use App\Http\Controllers\BairroController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\EtapaController;
use App\Http\Controllers\EscolaridadeController;
use App\Http\Controllers\EstadocivilController;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\RelatoriosController;
use App\Http\Controllers\MapasController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

Route::prefix('users')->group(function(){
    Route::get('/',[UserController::class,'index'])->name('users.index');

    Route::get('/ajax',[UserController::class,'paginacaoAjax'])->name('users.ajax');
    Route::get('/lista.ajax',function(){
        return view('users.index_ajax');
    });

    Route::get('/create',[UserController::class,'create'])->name('users.create');
    Route::post('/',[UserController::class,'store'])->name('users.store');
    Route::get('/{id}/show',[UserController::class,'show'])->where('id', '[0-9]+')->name('users.show');
    Route::get('/{id}/edit',[UserController::class,'edit'])->where('id', '[0-9]+')->name('users.edit');
    Route::put('/{id}',[UserController::class,'update'])->where('id', '[0-9]+')->name('users.update');
    Route::delete('/{id}',[UserController::class,'destroy'])->where('id', '[0-9]+')->name('users.destroy');
});
/*
Route::prefix('permissions')->group(function(){
    Route::get('/',[UserPermissions::class,'index'])->name('permissions.index');
    Route::get('/create',[UserPermissions::class,'create'])->name('permissions.create');
    Route::post('/',[UserPermissions::class,'store'])->name('permissions.store');
    Route::get('/{id}/show',[UserPermissions::class,'show'])->where('id', '[0-9]+')->name('permissions.show');
    Route::get('/{id}/edit',[UserPermissions::class,'edit'])->where('id', '[0-9]+')->name('permissions.edit');
    Route::put('/{id}',[UserPermissions::class,'update'])->where('id', '[0-9]+')->name('permissions.update');
    Route::delete('/{id}',[UserPermissions::class,'destroy'])->where('id', '[0-9]+')->name('permissions.destroy');
});*/
Route::prefix('familias')->group(function(){
    Route::get('/',[FamiliaController::class,'index'])->name('familias.index');
    Route::get('/create',[FamiliaController::class,'create'])->name('familias.create');
    Route::post('/',[FamiliaController::class,'store'])->name('familias.store');
    Route::get('/{id}/show',[FamiliaController::class,'show'])->name('familias.show');
    Route::get('/{id}/edit',[FamiliaController::class,'edit'])->name('familias.edit');
    Route::put('/{id}',[FamiliaController::class,'update'])->where('id', '[0-9]+')->name('familias.update');
    Route::delete('/{id}',[FamiliaController::class,'destroy'])->where('id', '[0-9]+')->name('familias.destroy');
    Route::get('export/all', [FamiliaController::class, 'exportAll'])->name('familias.export_all');
    Route::get('export/filter', [FamiliaController::class, 'exportFilter'])->name('familias.export_filter');
    Route::get('campos', [FamiliaController::class, 'camposJson'])->name('familias.campos');
    Route::post('ajax', [FamiliaController::class, 'ajaxPost'])->name('familias.ajax');
});
Route::prefix('bairros')->group(function(){
    Route::get('/',[BairroController::class,'index'])->name('bairros.index');
    Route::get('/create',[BairroController::class,'create'])->name('bairros.create');
    Route::post('/',[BairroController::class,'store'])->name('bairros.store');
    Route::get('/{id}/show',[BairroController::class,'show'])->name('bairros.show');
    Route::get('/{id}/edit',[BairroController::class,'edit'])->name('bairros.edit');
    Route::put('/{id}',[BairroController::class,'update'])->where('id', '[0-9]+')->name('bairros.update');
    Route::delete('/{id}',[BairroController::class,'destroy'])->where('id', '[0-9]+')->name('bairros.destroy');
    Route::get('export/all', [BairroController::class, 'exportAll'])->name('bairros.export_all');
    Route::get('export/filter', [BairroController::class, 'exportFilter'])->name('bairros.export_filter');
});

Route::prefix('escolaridades')->group(function(){
    Route::get('/',[EscolaridadeController::class,'index'])->name('escolaridades.index');
    Route::get('/create',[EscolaridadeController::class,'create'])->name('escolaridades.create');
    Route::post('/',[EscolaridadeController::class,'store'])->name('escolaridades.store');
    Route::get('/{id}/show',[EscolaridadeController::class,'show'])->name('escolaridades.show');
    Route::get('/{id}/edit',[EscolaridadeController::class,'edit'])->name('escolaridades.edit');
    Route::put('/{id}',[EscolaridadeController::class,'update'])->where('id', '[0-9]+')->name('escolaridades.update');
    Route::post('/{id}',[EscolaridadeController::class,'update'])->where('id', '[0-9]+')->name('escolaridades.update-ajax');
    Route::delete('/{id}',[EscolaridadeController::class,'destroy'])->where('id', '[0-9]+')->name('escolaridades.destroy');
});
Route::prefix('estado-civil')->group(function(){
    Route::get('/',[EstadocivilController::class,'index'])->name('estado-civil.index');
    Route::get('/create',[EstadocivilController::class,'create'])->name('estado-civil.create');
    Route::post('/',[EstadocivilController::class,'store'])->name('estado-civil.store');
    Route::get('/{id}/show',[EstadocivilController::class,'show'])->name('estado-civil.show');
    Route::get('/{id}/edit',[EstadocivilController::class,'edit'])->name('estado-civil.edit');
    Route::put('/{id}',[EstadocivilController::class,'update'])->where('id', '[0-9]+')->name('estado-civil.update');
    Route::post('/{id}',[EstadocivilController::class,'update'])->where('id', '[0-9]+')->name('estado-civil.update-ajax');
    Route::delete('/{id}',[EstadocivilController::class,'destroy'])->where('id', '[0-9]+')->name('estado-civil.destroy');
});
Route::prefix('etapas')->group(function(){
    Route::get('/',[EtapaController::class,'index'])->name('etapas.index');
    Route::get('/create',[EtapaController::class,'create'])->name('etapas.create');
    Route::post('/',[EtapaController::class,'store'])->name('etapas.store');
    Route::get('/{id}/show',[EtapaController::class,'show'])->name('etapas.show');
    Route::get('/{id}/edit',[EtapaController::class,'edit'])->name('etapas.edit');
    Route::put('/{id}',[EtapaController::class,'update'])->where('id', '[0-9]+')->name('etapas.update');
    Route::post('/{id}',[EtapaController::class,'update'])->where('id', '[0-9]+')->name('etapas.update-ajax');
    Route::delete('/{id}',[EtapaController::class,'destroy'])->where('id', '[0-9]+')->name('etapas.destroy');
});
Route::prefix('relatorios')->group(function(){
    Route::get('/',[RelatoriosController::class,'index'])->name('relatorios.index');
    Route::get('/social',[RelatoriosController::class,'realidadeSocial'])->name('relatorios.social');
    Route::get('/acessos',[EventController::class,'listAcessos'])->name('relatorios.acessos');
    Route::get('export/filter', [RelatoriosController::class, 'exportFilter'])->name('relatorios.export_filter');
    Route::get('export/all', [RelatoriosController::class, 'exportAll'])->name('relatorios.export_all');
    //Route::post('/',[RelatoriosController::class,'store'])->name('relatorios.store');
    //Route::get('/{id}/show',[RelatoriosController::class,'show'])->name('relatorios.show');
    //Route::get('/{id}/edit',[RelatoriosController::class,'edit'])->name('relatorios.edit');
    //Route::put('/{id}',[RelatoriosController::class,'update'])->where('id', '[0-9]+')->name('relatorios.update');
    //Route::post('/{id}',[RelatoriosController::class,'update'])->where('id', '[0-9]+')->name('relatorios.update-ajax');
    //Route::delete('/{id}',[RelatoriosController::class,'destroy'])->where('id', '[0-9]+')->name('relatorios.destroy');
});
Route::prefix('sistema')->group(function(){
    Route::get('/pefil',[UserController::class,'perfilShow'])->name('sistema.perfil');
    Route::get('/perfil/edit',[UserController::class,'perfilEdit'])->name('sistema.perfil.edit');
    Route::post('/perfil/store',[UserController::class,'perfilStore'])->name('sistema.perfil.store');
    Route::get('/config',[EtapaController::class,'config'])->name('sistema.config');
    Route::get('/midias',[mediaController::class,'midias'])->name('sistema.midias');
    Route::post('/{id}',[EtapaController::class,'update'])->where('id', '[0-9]+')->name('sistema.update-ajax');
});
Route::prefix('mapas')->group(function(){
    Route::get('/quadras/{id}',[MapasController::class,'quadras'])->name('mapas.quadras');
});
Route::prefix('uploads')->group(function(){
    Route::get('/',[uploadController::class,'index'])->name('uploads.index');
    Route::get('/create',[UploadController::class,'create'])->name('uploads.create');
    Route::post('/',[UploadController::class,'store'])->name('uploads.store');
    Route::get('/{id}/show',[UploadController::class,'show'])->name('uploads.show');
    Route::get('/{id}/edit',[UploadController::class,'edit'])->name('uploads.edit');
    Route::put('/{id}',[UploadController::class,'update'])->where('id', '[0-9]+')->name('uploads.update');
    Route::post('/{id}',[UploadController::class,'update'])->where('id', '[0-9]+')->name('uploads.update-ajax');
    Route::post('/{id}',[UploadController::class,'destroy'])->where('id', '[0-9]+')->name('uploads.destroy');
    Route::get('export/all', [UploadController::class, 'exportAll'])->name('uploads.export_all');
    Route::get('export/filter', [UploadController::class, 'exportFilter'])->name('uploads.export_filter');
});
Route::fallback(function () {
    return view('erro404');
});
Route::get('menu/{id}', [App\Http\Controllers\HomeController::class, 'menu'])->name('menu');
Route::prefix('teste')->group(function(){
    Route::get('/',[App\Http\Controllers\TesteController::class,'index'])->name('teste');
    Route::get('/ajax',[App\Http\Controllers\TesteController::class,'ajax'])->name('teste.ajax');
});
// Route::prefix('arquivamento')->group(function(){
//     Route::get('/index',[App\Http\Controllers\admin\PostController::class,'index'])->name('arquivamento.index');
//     Route::get('/text',[App\Http\Controllers\admin\PostController::class,'index'])->name('arquivamento-text');
//     Route::get('/video',[App\Http\Controllers\admin\PostController::class,'index'])->name('arquivamento-video');
// });
    Route::get('/arquivamento-text',[App\Http\Controllers\admin\PostController::class,'index'])->name('arquivamento-text');

//Route::post('/upload',[App\Http\Controllers\UploadFile::class,'upload'])->name('teste.upload');

Route::resource('beneficiarios','\App\Http\Controllers\BeneficiariosController',['parameters' => [
'beneficiarios' => 'id'
]]);
Route::resource('lotes','\App\Http\Controllers\LotesController',['parameters' => [
'lotes' => 'id'
]]);
Route::resource('biblioteca','\App\Http\Controllers\admin\mediaController',['parameters' => [
'biblioteca' => 'id'
]]);
Route::get('/lotes/lista-ocupantes/{lotes}',[LotesController::class,'listagemOcupantes'])->name('lotes.ocupantes');
Route::get('/lotes/ficha-ocupantes/{lote}/{familia}',[LotesController::class,'FichaOcupantes'])->name('lotes.ficha_ocupantes');
Route::resource('quadras','\App\Http\Controllers\QuadrasController',['parameters' => [
'quadras' => 'id'
]]);
Route::resource('documentos','\App\Http\Controllers\DocumentosController',['parameters' => [
    'documentos' => 'id'
]]);
Route::resource('qoptions','\App\Http\Controllers\admin\QoptionsController',['parameters' => [
    'qoptions' => 'id'
]]);
Route::resource('tags','\App\Http\Controllers\admin\TagsController',['parameters' => [
    'tags' => 'id'
]]);
Route::resource('permissions','\App\Http\Controllers\admin\UserPermissions',['parameters' => [
    'permissions' => 'id'
]]);
Route::resource('decretos','\App\Http\Controllers\admin\PostController',['parameters' => [
    'decretos' => 'id'
]]);
Route::resource('arquivamento-text','\App\Http\Controllers\admin\PostController',['parameters' => [
    'arquivamento-text' => 'id'
]]);
Route::resource('arquivamento-video','\App\Http\Controllers\admin\PostController',['parameters' => [
    'arquivamento-video' => 'id'
]]);
Route::resource('processos','\App\Http\Controllers\admin\PostController',['parameters' => [
    'processos' => 'id'
]]);
Route::resource('processos-campo','\App\Http\Controllers\admin\PostController',['parameters' => [
    'processos-campo' => 'id'
]]);
Route::resource('processos-prefeitura','\App\Http\Controllers\admin\PostController',['parameters' => [
    'processos-prefeitura' => 'id'
]]);
Route::resource('processos-cartorio','\App\Http\Controllers\admin\PostController',['parameters' => [
    'processos-cartorio' => 'id'
]]);


Auth::routes();

Route::get('/',function(){
  return redirect()->route('login');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/transparencia', [App\Http\Controllers\HomeController::class, 'transparencia'])->name('transparencia');

Route::get('envio-mails',function(){
    $user = new stdClass();
    $user->name = 'Fernando Queta';
    $user->email = 'ger.maisaqui3@gmail.com';
    // return new \App\Mail\dataBrasil($user);
    $enviar = Mail::send(new \App\Mail\dataBrasil($user));
    return $enviar;
});
Route::get('/suspenso',[UserController::class,'suspenso'])->name('cobranca.suspenso');
Route::prefix('cobranca')->group(function(){
    Route::get('/fechar',[UserController::class,'pararAlertaFaturaVencida'])->name('alerta.cobranca.fechar');
});
Route::resource('media','\App\Http\Controllers\admin\mediaController',['parameters' => [
    'media' => 'id'
]]);
Route::prefix('media')->group(function(){
    Route::post('/store-parent',['\App\Http\Controllers\admin\mediaController','storeParent'])->name('store.parent.media');
    Route::post('/trash',['\App\Http\Controllers\admin\mediaController','trash'])->name('trash.media');
    // Route::get('/ajax',[App\Http\Controllers\TesteController::class,'ajax'])->name('teste.ajax');
});
//inicio Rotas de verificação
Route::get('/email/verify', function () {
    return view('auth.verify');
    // return view('site.index');
})->middleware('auth')->name('verification.notice');
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message-very', 'enviado');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
