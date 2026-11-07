<?php
require_once __DIR__.'/app/db.php';
require_once __DIR__.'/app/helpers.php';
$pdo=db();
$pdo->exec("CREATE TABLE IF NOT EXISTS users(id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, email TEXT UNIQUE NOT NULL, password TEXT NOT NULL, role TEXT NOT NULL CHECK(role IN ('parent','professional','admin')), created_at TEXT DEFAULT CURRENT_TIMESTAMP)");
$pdo->exec("CREATE TABLE IF NOT EXISTS topics(id INTEGER PRIMARY KEY AUTOINCREMENT, audience TEXT NOT NULL, title_en TEXT NOT NULL, title_ta TEXT DEFAULT '', description TEXT DEFAULT '', sort_order INTEGER DEFAULT 0, active INTEGER DEFAULT 1)");
$pdo->exec("CREATE TABLE IF NOT EXISTS subtopics(id INTEGER PRIMARY KEY AUTOINCREMENT, topic_id INTEGER NOT NULL, title_en TEXT NOT NULL, title_ta TEXT DEFAULT '', sort_order INTEGER DEFAULT 0, active INTEGER DEFAULT 1, FOREIGN KEY(topic_id) REFERENCES topics(id) ON DELETE CASCADE)");
$pdo->exec("CREATE TABLE IF NOT EXISTS contents(id INTEGER PRIMARY KEY AUTOINCREMENT, subtopic_id INTEGER NOT NULL, title_en TEXT NOT NULL, title_ta TEXT DEFAULT '', body_en TEXT NOT NULL, body_ta TEXT DEFAULT '', image_path TEXT DEFAULT '', references_text TEXT DEFAULT '', updated_at TEXT DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(subtopic_id) REFERENCES subtopics(id) ON DELETE CASCADE)");
$pdo->exec("CREATE TABLE IF NOT EXISTS saved(id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER NOT NULL, content_id INTEGER NOT NULL, created_at TEXT DEFAULT CURRENT_TIMESTAMP, UNIQUE(user_id,content_id), FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY(content_id) REFERENCES contents(id) ON DELETE CASCADE)");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_topics_audience ON topics(audience)"); $pdo->exec("CREATE INDEX IF NOT EXISTS idx_subtopics_topic ON subtopics(topic_id)");
if(!$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn()){
 $s=$pdo->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)');
 $s->execute(['System Administrator','admin@example.com',password_hash('Admin@12345',PASSWORD_DEFAULT),'admin']);
 $s->execute(['Parent Demo','parent@example.com',password_hash('Parent@12345',PASSWORD_DEFAULT),'parent']);
 $s->execute(['Professional Demo','professional@example.com',password_hash('Professional@12345',PASSWORD_DEFAULT),'professional']);
}
if(!$pdo->query('SELECT COUNT(*) FROM topics')->fetchColumn()){
 $topics=[
 ['public','Breastfeeding','தாய்ப்பால் ஊட்டுதல்','Breastfeeding knowledge for child care.',1],
 ['public','Siddha Immune Boosters','சித்த நோய் எதிர்ப்பு ஆதரவு','General educational information.',2],
 ['public','Immunization','தடுப்பூசி','Immunization information and schedules.',3],
 ['public','Siddha Valarchi Paruvangal & Milestones','சித்த வளர்ச்சி பருவங்கள் மற்றும் வளர்ச்சி நிலைகள்','Growth and developmental milestones.',4],
 ['public','Nutritive Care','ஊட்டச்சத்து பராமரிப்பு','Nutrition and healthy child care.',5],
 ['public','Government Child Health Schemes','அரசு குழந்தை நலத் திட்டங்கள்','Government child health schemes.',6],
 ['professional','Immunization Schedule','தடுப்பூசி அட்டவணை','Professional reference information.',1],
 ['professional','Milestones','வளர்ச்சி நிலைகள்','Professional developmental reference.',2],
 ['professional','Immune Boosters','நோய் எதிர்ப்பு ஆதரவு','Professional reference information.',3],
 ['professional','Valarchi Paruvangal','வளர்ச்சி பருவங்கள்','Professional reference information.',4],
 ['professional','Balavagadam Therapeutic Index','பாலவாகடம் சிகிச்சை குறியீடு','Professional therapeutic index.',5],
 ['professional','Anthropometric Assessments','உடல் அளவியல் மதிப்பீடுகள்','Anthropometric assessment references.',6],
 ['professional','Pediatric Therapeutic Index','குழந்தை சிகிச்சை குறியீடு','Professional therapeutic index.',7],
 ['professional','Diet for Disease','நோய்க்கேற்ற உணவு','Disease-specific diet references.',8],
 ['professional','Reflex','பிரதிசெயல்கள்','Reflex examination references.',9],
 ['professional','Examinations','பரிசோதனைகள்','Pediatric examination references.',10],
 ['professional','Scales & Scores','அளவுகோல்கள் மற்றும் மதிப்பெண்கள்','Clinical scales and scores.',11],
 ['professional','Government Health Sources','அரசு சுகாதார ஆதாரங்கள்','Government health sources.',12]
 ];
 $st=$pdo->prepare('INSERT INTO topics(audience,title_en,title_ta,description,sort_order) VALUES(?,?,?,?,?)');
 foreach($topics as $t)$st->execute($t);
 $sub=$pdo->prepare('INSERT INTO subtopics(topic_id,title_en,title_ta,sort_order) VALUES(?,?,?,?)'); $con=$pdo->prepare('INSERT INTO contents(subtopic_id,title_en,title_ta,body_en,body_ta,references_text) VALUES(?,?,?,?,?,?)');
 foreach($pdo->query('SELECT * FROM topics ORDER BY id') as $t){$sub->execute([$t['id'],'Overview','கண்ணோட்டம்',1]);$sid=$pdo->lastInsertId();$con->execute([$sid,$t['title_en'],$t['title_ta'],'This is a structured knowledge page for pediatric knowledge dissemination. Replace this starter content with reviewed institutional material.\n\nThis application is an educational knowledge management system and does not replace clinical assessment or professional judgment.',$t['title_ta'].' பற்றிய கட்டமைக்கப்பட்ட அறிவுப் பக்கம். நிறுவனத்தால் மதிப்பாய்வு செய்யப்பட்ட உள்ளடக்கத்தை இங்கு சேர்க்கலாம்.','Institutional references can be entered by an administrator.']);}
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e(APP_NAME)?> Installer</title><link rel="stylesheet" href="assets/style.css"></head><body><main class="container narrow"><div class="card"><h1>Installation complete</h1><p>Database and starter pediatric knowledge structure have been created.</p><h3>Demo accounts</h3><p><b>Admin:</b> admin@example.com / Admin@12345</p><p><b>Parent:</b> parent@example.com / Parent@12345</p><p><b>Professional:</b> professional@example.com / Professional@12345</p><p class="warning">Delete <code>install.php</code> now, then open the application.</p><a class="btn" href="login.php">Go to Login</a></div></main></body></html>
