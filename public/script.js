const $=(s,p=document)=>p.querySelector(s), $$=(s,p=document)=>[...p.querySelectorAll(s)];
const root=document.documentElement;
let mx=innerWidth/2,my=innerHeight/2,rx=mx,ry=my;
function pointer(x,y){mx=x;my=y;root.style.setProperty('--mx',x+'px');root.style.setProperty('--my',y+'px')}
addEventListener('pointermove',e=>pointer(e.clientX,e.clientY),{passive:true});
(function(){const r=$('.cursor-ring'),d=$('.cursor-dot');function loop(){rx+=(mx-rx)*.16;ry+=(my-ry)*.16;if(r){r.style.left=rx+'px';r.style.top=ry+'px'}if(d){d.style.left=mx+'px';d.style.top=my+'px'}requestAnimationFrame(loop)}loop()})();
addEventListener('scroll',()=>{const max=document.documentElement.scrollHeight-innerHeight;const p=$('.progress');if(p)p.style.width=(max?scrollY/max*100:0)+'%';const n=$('.nav-shell');if(n)n.classList.toggle('scrolled',scrollY>12)},{passive:true});
const menu=$('.menu'),burger=$('.burger');
if(menu&&burger){burger.addEventListener('click',()=>{const open=menu.classList.toggle('open');burger.setAttribute('aria-expanded',String(open))});$$('.menu a').forEach(a=>a.addEventListener('click',()=>{menu.classList.remove('open');burger.setAttribute('aria-expanded','false')}))}
const page=(location.pathname.split('/').pop()||'index.html').toLowerCase();$$('.menu a').forEach(a=>{const h=(a.getAttribute('href')||'').toLowerCase();if(h===page||(!page&&h==='index.html'))a.classList.add('active')});
addEventListener('pointermove',e=>{$$('[data-depth]').forEach(el=>{const dep=+(el.dataset.depth||.5),x=(e.clientX/innerWidth-.5)*28*dep,y=(e.clientY/innerHeight-.5)*20*dep;el.style.marginLeft=x+'px';el.style.marginTop=y+'px'})},{passive:true});
$$('.tilt').forEach(c=>{c.addEventListener('pointermove',e=>{const r=c.getBoundingClientRect(),x=e.clientX/r.width-r.left/r.width,y=e.clientY/r.height-r.top/r.height;c.style.transform=`perspective(1200px) rotateX(${(y-.5)*-3}deg) rotateY(${(x-.5)*4}deg) translateY(-4px)`});c.addEventListener('pointerleave',()=>c.style.transform='')});
const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('in')}),{threshold:.08});$$('.reveal').forEach(x=>obs.observe(x));
const d=$('.disclaimer');
(function(){
  if(!d) return;
  const close=()=>{d.classList.remove('show');document.body.classList.remove('disclaimer-open');};
  $$('.disclaimer [data-disc]').forEach(btn=>btn.addEventListener('click',()=>{
    if(btn.dataset.disc==='yes') localStorage.setItem('lf-disclaimer','ok');
    close();
  }));
  document.addEventListener('keydown',e=>{if(e.key==='Escape') close();});
  d.addEventListener('click',e=>{if(e.target===d) close();});
  if(!localStorage.getItem('lf-disclaimer')){
    setTimeout(()=>{d.classList.add('show');document.body.classList.add('disclaimer-open');},650);
  }
})();

(function(){
  const opening=document.getElementById('opening');
  if(opening){setTimeout(()=>opening.classList.add('done'),950);setTimeout(()=>opening.remove(),1550);}
})();
