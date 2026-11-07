<?php
require_once 'app/auth.php';require_once 'app/helpers.php';
$q=trim($_GET['q']??'');$results=[];
if($q!==''){
 $like='%'.$q.'%';
 $visibility=" AND (t.audience='public'";
 $params=[$like,$like,$like,$like,$like,$like];
 $u=user(); if($u && in_array($u['role'],['professional','admin'],true)){ $visibility.=" OR t.audience='professional'"; }
 $visibility.=')';
 $s=db()->prepare("SELECT c.*,s.title_en subtopic,t.title_en topic,t.audience FROM contents c JOIN subtopics s ON s.id=c.subtopic_id JOIN topics t ON t.id=s.topic_id WHERE t.active=1 AND s.active=1 $visibility AND (c.title_en LIKE ? OR c.title_ta LIKE ? OR c.body_en LIKE ? OR c.body_ta LIKE ? OR t.title_en LIKE ? OR s.title_en LIKE ?) ORDER BY CASE WHEN t.audience='public' THEN 0 ELSE 1 END,c.updated_at DESC LIMIT 50");
 $s->execute($params);$results=$s->fetchAll();
}
$page_title='Search';include 'partials/header.php';
?>
<section class="search-hero"><span class="eyebrow">KNOWLEDGE SEARCH</span><h1>Find pediatric information</h1><p>Search across the knowledge available to your access level.</p><form class="searchbar"><input name="q" value="<?=e($q)?>" placeholder="Search breastfeeding, milestones, nutrition…"><button class="btn">Search</button></form></section>
<?php if($q):?><div class="results-summary"><strong><?=count($results)?></strong> result(s) for “<?=e($q)?>” <span class="access-pill"><?=e(current_access_label())?></span></div><div class="grid"><?php foreach($results as $r):?><div class="card result-card"><div class="knowledge-meta"><span class="tag"><?=e($r['audience']==='professional'?'🔒 Professional':'Public')?></span></div><h3><?=e($r['title_en'])?></h3><p class="muted"><?=e($r['topic'])?> → <?=e($r['subtopic'])?></p><p><?=e(excerpt($r['body_en']))?></p><a class="text-link" href="content.php?subtopic=<?=e($r['subtopic_id'])?>#content-<?=e($r['id'])?>">Open result →</a></div><?php endforeach;?></div><?php endif;?>
<?php include 'partials/footer.php';?>
