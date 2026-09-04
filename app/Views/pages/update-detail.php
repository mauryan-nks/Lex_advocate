<?php
$builder = json_decode($update['builder_json'] ?? '', true);
if (!is_array($builder) || !$builder) {
    $builder = [['id'=>'legacy','type'=>'richtext','data'=>['eyebrow'=>$update['category'] ?? 'Legal Update','title'=>$update['title'],'content'=>$update['content'] ?? '','theme'=>'light'],'hidden'=>false]];
}
$renderSection = static function(array $s) use ($update): string {
    if (!empty($s['hidden'])) return '';
    $d=$s['data']??[]; $type=$s['type']??'richtext'; $theme=($d['theme']??'light')==='dark'?'dark':'light';
    $esc=static fn($v)=>esc((string)$v); $html='';
    switch($type){
        case 'hero':
            $image=$d['image']??''; $html='<section class="page-hero '.($theme==='dark'?'dark':'').'">'.'<div class="container page-hero-grid"><div class="reveal"><div class="eyebrow">'.$esc($d['eyebrow']??$update['category']).'</div><h1>'.$esc($d['title']??$update['title']).'</h1><p>'.$esc($d['text']??$update['excerpt']).'</p>'.(!empty($d['button_text'])?'<a class="btn btn-gold" href="'.site_url($d['button_url']??'appointment').'">'.$esc($d['button_text']).'</a>':'').'</div><div class="page-art glass-card reveal">'.($image?'<img class="practice-page-image" src="'.esc(base_url($image)).'" alt="Legal update illustration">':'').'</div></div></section>'; break;
        case 'html':
            $html='<section class="section '.$theme.'"><div class="container dynamic-builder-html">'.($d['content']??'').'</div></section>'; break;
        case 'richtext':
            $html='<section class="section '.$theme.'"><div class="container dynamic-builder-rich"><div class="eyebrow">'.$esc($d['eyebrow']??'').'</div>'.(!empty($d['title'])?'<h2>'.$esc($d['title']).'</h2>':'').'<div class="builder-rich-content">'.($d['content']??'').'</div></div></section>'; break;
        case 'process': case 'cards':
            $items=is_array($d['items']??null)?$d['items']:[]; $html='<section class="section '.$theme.'"><div class="container"><div class="section-head reveal"><div><div class="eyebrow">'.$esc($d['eyebrow']??'').'</div><h2>'.$esc($d['title']??'').'</h2></div></div><div class="detail-grid">'; foreach($items as $it){$html.='<div class="detail-block reveal"><span class="eyebrow">'.$esc($it['label']??'').'</span><h3>'.$esc($it['title']??'').'</h3><div>'.($it['text']??'').'</div></div>';} $html.='</div></div></section>'; break;
        case 'quote':
            $html='<section class="section '.$theme.'"><div class="container matter-quote glass-light builder-quote"><span class="smallcaps">'.$esc($d['label']??'LEX FACTUM').'</span><blockquote>'.$esc($d['quote']??'').'</blockquote><div>'.($d['text']??'').'</div></div></section>'; break;
        case 'image_text':
            $img=$d['image']??''; $side=($d['side']??'right')==='left'?'image-left':'image-right'; $html='<section class="section '.$theme.'"><div class="container feature-practice '.$side.'"><div class="feature-copy reveal"><div class="eyebrow">'.$esc($d['eyebrow']??'').'</div><h2>'.$esc($d['title']??'').'</h2><div>'.($d['content']??'').'</div></div><div class="feature-art glass-card reveal">'.($img?'<img class="practice-feature-image" src="'.esc(base_url($img)).'" alt="Legal update illustration">':'').'</div></div></section>'; break;
        case 'cta':
            $html='<section class="section '.$theme.'"><div class="container cta-builder"><div><div class="eyebrow">'.$esc($d['eyebrow']??'').'</div><h2>'.$esc($d['title']??'').'</h2><div>'.($d['content']??'').'</div></div>'.(!empty($d['button_text'])?'<a class="btn btn-gold" href="'.site_url($d['button_url']??'appointment').'">'.$esc($d['button_text']).'</a>':'').'</div></section>'; break;
    }
    return $html;
};
?>
<?= view('pages/_shell_head', ['title' => esc($update['title']).' — Lex Factum & Partners', 'description' => esc($update['excerpt'] ?: $update['title'])]) ?>
<style>.dynamic-builder-html{line-height:1.75}.dynamic-builder-rich h2{margin-bottom:18px}.builder-rich-content{line-height:1.85}.builder-rich-content p{margin:0 0 16px}.builder-rich-content h3{margin:25px 0 10px}.builder-rich-content ul,.builder-rich-content ol{padding-left:22px}.builder-quote{max-width:900px;margin:0 auto}.builder-quote blockquote{margin:18px 0;font:500 clamp(30px,4vw,52px)/1.08 Georgia,serif}.cta-builder{display:flex;align-items:center;justify-content:space-between;gap:30px}.cta-builder>div:first-child{max-width:760px}.dark .builder-rich-content,.dark .builder-rich-content p,.dark .detail-block div,.dark .cta-builder p{color:#c6c9c8}.dynamic-update-meta{margin-top:10px;opacity:.7}@media(max-width:800px){.cta-builder{display:block}.cta-builder .btn{margin-top:20px}}</style>
<main><?php foreach($builder as $section) echo $renderSection($section); ?><section class="section light"><div class="container"><article class="glass-light" style="padding:clamp(24px,4vw,56px);max-width:920px;margin:auto"><div class="dynamic-update-meta"><?= esc($update['category']) ?> · <?= esc(date('d M Y', strtotime($update['published_at'] ?: $update['created_at']))) ?></div><p style="margin-top:22px;font-size:.9rem;opacity:.7">This content is provided for general information only and does not constitute legal advice.</p><a class="btn" href="<?= site_url('updates') ?>">← Back to updates</a></article></div></section></main>
<?= view('pages/_shell_foot') ?>
