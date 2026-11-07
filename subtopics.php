<?php
require_once 'app/auth.php'; require_once 'app/helpers.php';
$id=(int)($_GET['topic']??0);
$s=db()->prepare('SELECT * FROM topics WHERE id=? AND active=1'); $s->execute([$id]); $topic=$s->fetch();
if(!$topic){ http_response_code(404); exit('Topic not found'); }
require_audience_access($topic['audience']);
$s=db()->prepare('SELECT * FROM subtopics WHERE topic_id=? AND active=1 ORDER BY sort_order,id'); $s->execute([$id]); $subs=$s->fetchAll();
$page_title=$topic['title_en']; include 'partials/header.php';
?>
<div class="crumb"><a href="index.php">Home</a> / <a href="topics.php?audience=<?=e($topic['audience'])?>">Topics</a> / <?=e($topic['title_en'])?></div>
<section class="card topic-banner <?=e($topic['audience'])?>-head"><div><span class="eyebrow"><?=e($topic['audience']==='professional'?'PROFESSIONAL REFERENCE':'PUBLIC KNOWLEDGE')?></span><h1><?=e($topic['title_en'])?></h1><?php if($topic['title_ta']):?><p class="tamil"><?=e($topic['title_ta'])?></p><?php endif;?></div><?php if($topic['audience']==='professional'):?><span class="access-pill">🔒 Authenticated professionals</span><?php endif;?></section>
<h2 class="section-title">Explore subtopics</h2>
<div class="grid subtopic-grid">
<?php foreach($subs as $i=>$sub): ?><div class="card subtopic-card"><span class="number-badge"><?=str_pad((string)($i+1),2,'0',STR_PAD_LEFT)?></span><h3><?=e($sub['title_en'])?></h3><?php if($sub['title_ta']):?><p class="tamil"><?=e($sub['title_ta'])?></p><?php endif;?><a class="text-link" href="content.php?subtopic=<?=e($sub['id'])?>">Open subtopic →</a></div><?php endforeach; ?>
</div>
<?php include 'partials/footer.php'; ?>
