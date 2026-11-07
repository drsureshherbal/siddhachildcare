<?php
require_once 'app/auth.php'; require_once 'app/helpers.php';
$aud=$_GET['audience']??'public';
if(!in_array($aud,['public','professional'],true)) $aud='public';
require_audience_access($aud);
$page_title=ucfirst($aud).' Topics';
include 'partials/header.php';
$s=db()->prepare('SELECT * FROM topics WHERE audience=? AND active=1 ORDER BY sort_order,id');
$s->execute([$aud]); $topics=$s->fetchAll();
?>
<div class="crumb"><a href="index.php">Home</a> / Topics</div>
<section class="section-head <?=e($aud)?>-head">
  <div><span class="eyebrow"><?=e($aud==='public'?'PARENT & GENERAL PUBLIC':'PROFESSIONAL ONLY')?></span><h1><?=e($aud==='public'?'Parent / General Public Knowledge':'Professional Knowledge')?></h1><p><?=e($aud==='public'?'Practical pediatric information for parents and the general public.':'Expanded pediatric reference content for authenticated professionals, including scientific and clinical reference material.')?></p></div>
  <?php if($aud==='professional'): ?><span class="access-pill">🔒 Professional access</span><?php endif; ?>
</section>
<div class="grid topic-grid">
<?php foreach($topics as $t): ?>
  <div class="card topic-card">
    <div class="topic-icon"><?= $aud==='professional' ? '🩺' : '🌿' ?></div>
    <span class="tag"><?=e($aud==='professional'?'Professional':'Public')?></span>
    <h3><?=e($t['title_en'])?></h3>
    <?php if($t['title_ta']): ?><p class="tamil"><?=e($t['title_ta'])?></p><?php endif; ?>
    <p class="muted"><?=e($t['description'])?></p>
    <a class="btn" href="subtopics.php?topic=<?=e($t['id'])?>">Explore topic <span>→</span></a>
  </div>
<?php endforeach; ?>
</div>
<?php include 'partials/footer.php'; ?>
