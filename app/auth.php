<?php
require_once __DIR__ . '/db.php';

function csrf_token(){
    if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(){
    if(!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Invalid request token.'); }
}
function user(){
    static $u=null;
    if($u!==null) return $u;
    if(empty($_SESSION['user_id'])) return null;
    $s=db()->prepare('SELECT * FROM users WHERE id=?');
    $s->execute([$_SESSION['user_id']]);
    return $u=$s->fetch() ?: null;
}
function require_login(){
    if(!user()){ header('Location: login.php'); exit; }
}
function require_role($role){
    require_login();
    if(user()['role']!==$role && user()['role']!=='admin'){ http_response_code(403); exit('Access denied.'); }
}
function login_user($id){ session_regenerate_id(true); $_SESSION['user_id']=$id; }
function logout_user(){
    $_SESSION=[];
    if(ini_get('session.use_cookies')){
        $p=session_get_cookie_params();
        setcookie(session_name(),'',['expires'=>time()-42000,'path'=>$p['path'],'domain'=>$p['domain'],'secure'=>$p['secure'],'httponly'=>$p['httponly'],'samesite'=>'Lax']);
    }
    session_destroy();
}

/*
 * Content access policy:
 * - public content: available to everyone, including visitors
 * - professional content: available only to authenticated professionals/admins
 * - parent accounts have the same access as public visitors
 */
function can_view_audience($audience){
    if($audience !== 'professional') return true;
    $u=user();
    return $u && in_array($u['role'], ['professional','admin'], true);
}
function require_audience_access($audience){
    if(can_view_audience($audience)) return;
    if($audience === 'professional' && !user()){
        header('Location: login.php?required=professional');
        exit;
    }
    http_response_code(403);
    exit('Professional access is required to view this content.');
}
function current_access_label(){
    $u=user();
    if(!$u) return 'Public access';
    if($u['role']==='admin') return 'Administrator';
    if($u['role']==='professional') return 'Professional access';
    return 'Parent access';
}
