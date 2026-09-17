/* Medicine 3.8.11.42 - sector runtime on top of the exact Events v140 owner. */
(function(W,D){
  'use strict';
  var CFG=W.wpbbMedicineV142||{};
  function q(s,r){try{return(r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function unique(a){return (a||[]).filter(function(x,i){return x&&a.indexOf(x)===i;});}
  function top(a){a=unique(a||[]);return a.filter(function(x){return !a.some(function(y){return y!==x&&y.contains&&y.contains(x);});});}
  function setSrc(img,url,alt,eager){
    if(!img||!url)return;
    if(img.getAttribute('src')!==url)img.src=url;
    img.removeAttribute('srcset');img.removeAttribute('sizes');img.removeAttribute('width');img.removeAttribute('height');
    if(alt)img.alt=alt;
    img.loading=eager?'eager':'lazy';img.decoding='async';
    if(eager)img.setAttribute('fetchpriority','high');else img.removeAttribute('fetchpriority');
  }
  function host(scope,cards){
    cards=top(cards);if(cards.length<2)return null;
    var poss=[];
    cards.forEach(function(card){
      var n=card;
      for(var i=0;n&&n!==scope&&i<7;i++,n=n.parentElement){
        if(n.matches&&n.matches('.row,.wpbb-row,.wpbb-v62-card-grid,.wpbb-sector-grid,.wp-block-post-template,.wpbb-catalogue-grid,.medicine-doctor-grid,.medicine-speciality-grid,.medicine-trust-grid'))poss.push(n);
      }
    });
    var best=null;
    unique(poss).forEach(function(h){
      var d=kids(h).filter(function(c){return cards.some(function(card){return c===card||c.contains(card);});});
      if(d.length>=2&&(!best||d.length>best.items.length))best={host:h,items:d};
    });
    if(best)return best;
    var p=cards[0].parentElement;
    return p&&cards.every(function(c){return c.parentElement===p;})?{host:p,items:cards}:null;
  }
  function grid(scopeSel,cardSel,cols,name){
    qa('#wp-theme-main '+scopeSel).forEach(function(scope){
      var pick=host(scope,qa(cardSel,scope));if(!pick)return;
      pick.host.classList.add('wpbb-v142-grid','wpbb-v142-cols-'+cols);
      if(name)pick.host.classList.add(name);
      pick.items.forEach(function(i){i.classList.add('wpbb-v142-grid-cell');});
    });
  }
  function mark(){
    grid('.medicine-specialities','.medicine-speciality-card',4,'wpbb-v142-specialities');
    grid('.medicine-trust-band','.medicine-trust-card',4,'wpbb-v142-trust');
    grid('.medicine-doctor-directory','.medicine-doctor-card',4,'wpbb-v142-doctors');
    grid('.medicine-pharmacy-section','.wpbb-catalogue-card',3,'wpbb-v142-pharmacy');
    grid('.wp-theme-services-section,.wp-theme-sector-services-section','.wp-theme-sector-card,.wpbb-icon-card',4,'wpbb-v142-services');
    grid('.wp-theme-process-section','.wp-theme-process-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v142-process');
    grid('.wp-theme-case-studies-section','.wp-theme-case-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v142-cases');
    qa('#wp-theme-main .medicine-doctor-toolbar .row').forEach(function(r){r.classList.add('wpbb-v142-filter-row');});
  }
  function hero(){
    var block=q('#wp-theme-main .wpbb-swiper--hero');if(!block)return;
    var urls=CFG.hero||[];
    var slides=qa('.swiper-wrapper>.swiper-slide,.wpbb-swiper-slide--hero',block).filter(function(s){return !s.classList.contains('swiper-slide-duplicate');});
    top(slides).forEach(function(s,i){
      var img=q('.wpbb-swiper-slide__media img',s);
      setSrc(img,urls.length?urls[i%urls.length]:'',(q('.wpbb-swiper-slide__title',s)||{}).textContent||'Medical specialist care',i===0);
      if(img)img.style.objectPosition='center center';
    });
    if(q('.wpbb-v97-hero-finder',block))D.body.classList.add('wpbb-v142-has-hero-finder');
  }
  function cleanDoctorMedia(card){
    var media=q('.medicine-doctor-card__media',card);if(!media)return;
    qa(':scope > *',media).forEach(function(el){if(el.tagName!=='IMG'&&!el.classList.contains('medicine-doctor-card__badge'))el.style.setProperty('display','none','important');});
  }
  function media(){
    var about=q('#wp-theme-main .wp-theme-about-section img,#wp-theme-main .wp-theme-sector-media-text__media img');
    if(about)setSrc(about,CFG.about,'Clinical specialist consultation',false);
    qa('#wp-theme-main .medicine-doctor-card').forEach(function(card,i){
      cleanDoctorMedia(card);
      var title=q('h2,h3,h4',card);
      var urls=CFG.doctors||[];
      setSrc(q('.medicine-doctor-card__media > img,.medicine-doctor-card__media img',card),urls.length?urls[i%urls.length]:'',title?title.textContent:'Doctor',false);
    });
    qa('#wp-theme-main .medicine-pharmacy-section .wpbb-catalogue-card').forEach(function(card,i){
      var title=q('h2,h3,h4',card),urls=CFG.pharmacy||[];
      setSrc(q('img',card),urls.length?urls[i%urls.length]:'',title?title.textContent:'Pharmacy product',false);
    });
    var gallery=CFG.gallery||[];
    qa('#wp-theme-main .wp-theme-gallery-section img').forEach(function(img,i){setSrc(img,gallery.length?gallery[i%gallery.length]:'','Clinical care',false);});
    var blog=CFG.blog||[];
    qa('#wp-theme-main .wp-theme-blog-preview-section img,#wp-theme-main .wp-theme-insights-section img').forEach(function(img,i){setSrc(img,blog.length?blog[i%blog.length]:'','Health guidance',false);});
  }
  function run(){if(!D.body)return;D.body.classList.add('wpbb-v142-theme-medicine');mark();hero();media();}
  var timer;
  function schedule(){clearTimeout(timer);timer=W.setTimeout(run,60);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,300);W.setTimeout(run,1000);});
  if(W.MutationObserver){var obs=new MutationObserver(schedule);obs.observe(D.documentElement,{childList:true,subtree:true});W.setTimeout(function(){obs.disconnect();},7000);}
})(window,document);
