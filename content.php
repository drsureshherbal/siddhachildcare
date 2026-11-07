<?php
require_once 'app/auth.php'; require_once 'app/helpers.php';
$id=(int)($_GET['subtopic']??0);
$s=db()->prepare('SELECT s.*,t.title_en topic_en,t.title_ta topic_ta,t.audience,t.description topic_description FROM subtopics s JOIN topics t ON t.id=s.topic_id WHERE s.id=? AND s.active=1 AND t.active=1');
$s->execute([$id]); $sub=$s->fetch();
if(!$sub){http_response_code(404);exit('Subtopic not found');}
require_audience_access($sub['audience']);
$s=db()->prepare('SELECT * FROM contents WHERE subtopic_id=? ORDER BY id');$s->execute([$id]);$items=$s->fetchAll();
$page_title=$sub['title_en'];include 'partials/header.php';
?>
<div class="crumb"><a href="index.php">Home</a> / <a href="topics.php?audience=<?=e($sub['audience'])?>">Topics</a> / <a href="subtopics.php?topic=<?=e($sub['topic_id'])?>"><?=e($sub['topic_en'])?></a> / <?=e($sub['title_en'])?></div>
<section class="content-hero <?=e($sub['audience'])?>-head"><div><span class="eyebrow"><?=e($sub['audience']==='professional'?'PROFESSIONAL REFERENCE':'PARENT & GENERAL PUBLIC')?></span><h1><?=e($sub['title_en'])?></h1><p class="tamil"><?=e($sub['title_ta'])?></p></div><?php if($sub['audience']==='professional'):?><span class="access-pill">🔒 Professional content</span><?php endif;?></section>
<?php foreach($items as $c):?><article class="card knowledge-card" id="content-<?=e($c['id'])?>"><div class="knowledge-meta"><span class="tag"><?=e($sub['topic_en'])?></span><?php if(user()):?><form method="post" action="api/save.php"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="content_id" value="<?=e($c['id'])?>"><button class="save-btn" type="submit">☆ Save</button></form><?php endif;?></div><h2><?=e($c['title_en'])?></h2><?php if($c['title_ta']):?><p class="tamil title-ta"><?=e($c['title_ta'])?></p><?php endif;?><div class="content-body"><?=nl2br(e($c['body_en']))?></div><?php if($c['body_ta']):?><details class="language-panel"><summary>தமிழ் தகவல்</summary><div class="content-body tamil"><?=nl2br(e($c['body_ta']))?></div></details><?php endif;?><?php if($c['references_text']):?><details class="reference-panel"><summary>References</summary><div class="content-body muted"><?=nl2br(e($c['references_text']))?></div></details><?php endif;?></article><?php endforeach;?>
<div class="card ai-widget"><div class="ai-widget-title"><div><span class="eyebrow">OPTIONAL AI</span><h2>🤖 ASK AI about this topic</h2><p class="muted">Searches the information stored in this website. AI is an optional layer and does not replace professional assessment.</p></div></div><form action="api/ask_ai.php" method="post"><input type="hidden" name="topic_id" value="<?=e($sub['topic_id'])?>"><div class="searchbar"><input name="q" placeholder="Ask a question about this topic…" required><button class="btn" type="submit">ASK AI</button></div></form></div>
<?php include 'partials/footer.php';?>
