<?php
require_login(); require_once 'app/helpers.php';
$page_title='Saved'; include 'partials/header.php';
$visibility=" AND (t.audience='public'";
if(in_array(user()['role'],['professional','admin'],true)) $visibility.=" OR t.audience='professional'";
$visibility.=')';
$s=db()->prepare("SELECT c.*,s.id subtopic_id,t.title_en topic,t.audience FROM saved x JOIN contents c ON c.id=x.content_id JOIN subtopics s ON s.id=c.subtopic_id JOIN topics t ON t.id=s.topic_id WHERE x.user_id=? AND t.active=1 AND s.active=1 $visibility ORDER BY x.created_at DESC");
$s->execute([user()['id']]); $rows=$s->fetchAll();
?>
<section class="search-hero"><span class="eyebrow">YOUR LIBRARY</span><h1>Saved knowledge</h1><p>Quick access to pediatric information you have bookmarked.</p></section>
<div class="grid"><?php foreach($rows as $r):?><div class="card result-card"><span class="tag"><?=e($r['audience']==='professional'?'🔒 Professional':'Public')?></span><h3><?=e($r['title_en'])?></h3><p class="muted"><?=e($r['topic'])?></p><p><?=e(excerpt($r['body_en']))?></p><a class="text-link" href="content.php?subtopic=<?=e($r['subtopic_id'])?>#content-<?=e($r['id'])?>">Open →</a></div><?php endforeach;?></div>
<?php if(!$rows):?><div class="card"><p class="muted">No saved knowledge yet.</p></div><?php endif;?>
<?php include 'partials/footer.php';?>
