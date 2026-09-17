/* Medicine 3.8.11.43 - deterministic media + section finish on Events v140. */
(function(W,D){
  'use strict';
  var CFG=W.wpbbMedicineV143||{};
  function q(s,r){try{return(r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function unique(a){return(a||[]).filter(function(x,i){return x&&a.indexOf(x)===i;});}
  function top(a){a=unique(a||[]);return a.filter(function(x){return !a.some(function(y){return y!==x&&y.contains&&y.contains(x);});});}

  function setAttrs(img,url,alt,eager){
    if(!img||!url)return img;
    img.src=url;
    img.removeAttribute('srcset');img.removeAttribute('sizes');img.removeAttribute('width');img.removeAttribute('height');
    if(alt)img.alt=alt;
    img.loading=eager?'eager':'lazy';img.decoding='async';
    if(eager)img.setAttribute('fetchpriority','high');else img.removeAttribute('fetchpriority');
    img.classList.add('wpbb-v143-media-img');
    return img;
  }

  /* Replace the whole responsive-picture ownership chain. This avoids a stale
     <source srcset> winning over the fallback <img>, which caused the blank cards. */
  function canonicalImage(container,url,alt,eager,extraClass){
    if(!container||!url)return null;
    if(container.tagName==='IMG'){
      if(extraClass)container.classList.add(extraClass);
      return setAttrs(container,url,alt,eager);
    }
    qa('source',container).forEach(function(n){n.remove();});
    qa('picture',container).forEach(function(p){
      var img=q('img',p);
      if(img){p.parentNode.insertBefore(img,p);}
      p.remove();
    });
    var img=q('img.wpbb-v143-media-img,img',container);
    if(!img){img=D.createElement('img');container.insertBefore(img,container.firstChild||null);}
    if(extraClass)img.classList.add(extraClass);
    return setAttrs(img,url,alt,eager);
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
      pick.host.classList.add('wpbb-v143-grid','wpbb-v143-cols-'+cols);
      if(name)pick.host.classList.add(name);
      pick.items.forEach(function(i){i.classList.add('wpbb-v143-grid-cell');});
    });
  }
  function mark(){
    grid('.medicine-specialities','.medicine-speciality-card',4,'wpbb-v143-specialities');
    grid('.medicine-trust-band','.medicine-trust-card',4,'wpbb-v143-trust');
    grid('.medicine-doctor-directory','.medicine-doctor-card',4,'wpbb-v143-doctors');
    grid('.medicine-pharmacy-section','.wpbb-catalogue-card',3,'wpbb-v143-pharmacy');
    grid('.wp-theme-services-section,.wp-theme-sector-services-section','.wp-theme-sector-card,.wpbb-icon-card',4,'wpbb-v143-services');
    grid('.wp-theme-process-section','.wp-theme-process-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v143-process');
    grid('.wp-theme-case-studies-section','.wp-theme-case-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v143-cases');
    qa('#wp-theme-main .medicine-doctor-toolbar .row').forEach(function(r){r.classList.add('wpbb-v143-filter-row');});
  }

  function hero(){
    var block=q('#wp-theme-main .wpbb-swiper--hero');if(!block)return;
    var urls=CFG.hero||[];
    var slides=qa('.swiper-wrapper>.swiper-slide,.wpbb-swiper-slide--hero',block).filter(function(s){return !s.classList.contains('swiper-slide-duplicate');});
    top(slides).forEach(function(s,i){
      var media=q('.wpbb-swiper-slide__media',s);
      var title=q('.wpbb-swiper-slide__title',s);
      canonicalImage(media,urls.length?urls[i%urls.length]:'',title?title.textContent:'Medical specialist care',i===0,'wpbb-v143-hero-image');
    });
    if(q('.wpbb-v97-hero-finder',block))D.body.classList.add('wpbb-v143-has-hero-finder');
  }

  function doctors(){
    var urls=CFG.doctors||[];
    qa('#wp-theme-main .medicine-doctor-card').forEach(function(card,i){
      var media=q('.medicine-doctor-card__media',card);if(!media)return;
      var badge=q('.medicine-doctor-card__badge',media);
      var img=q(':scope > img.wpbb-v143-media-img',media);
      /* Remove any legacy gallery/picture payload but preserve the canonical image and credential badge. */
      kids(media).forEach(function(el){if(el!==badge&&el!==img)el.remove();});
      var title=q('h2,h3,h4',card);
      if(!img){img=D.createElement('img');media.insertBefore(img,badge||null);}
      setAttrs(img,urls.length?urls[i%urls.length]:'',title?title.textContent:'Doctor',false);
    });
  }

  function pharmacy(){
    var urls=CFG.pharmacy||[];
    qa('#wp-theme-main .medicine-pharmacy-section .wpbb-catalogue-card').forEach(function(card,i){
      var title=q('h2,h3,h4,.card-title',card);
      var old=q('.card-img-top,picture,.wpbb-catalogue-card__media,img',card);
      var img;
      if(old && old.tagName==='PICTURE'){
        img=D.createElement('img');old.replaceWith(img);
      }else if(old && old.tagName==='IMG'){
        img=old;
      }else if(old){
        img=canonicalImage(old,urls.length?urls[i%urls.length]:'',title?title.textContent:'Pharmacy product',false,'card-img-top');
      }else{
        img=D.createElement('img');
        var body=q('.card-body',card);card.insertBefore(img,body||card.firstChild||null);
      }
      if(img){img.classList.add('card-img-top','wpbb-v143-product-image');setAttrs(img,urls.length?urls[i%urls.length]:'',title?title.textContent:'Pharmacy product',false);}
    });
  }

  function otherMedia(){
    var about=q('#wp-theme-main .wp-theme-about-section img,#wp-theme-main .wp-theme-sector-media-text__media img');
    if(about)setAttrs(about,CFG.about,'Clinical specialist consultation',false);
    var gallery=CFG.gallery||[];
    qa('#wp-theme-main .wp-theme-gallery-section img').forEach(function(img,i){setAttrs(img,gallery.length?gallery[i%gallery.length]:'','Clinical care',false);});
    var blog=CFG.blog||[];
    qa('#wp-theme-main .wp-theme-blog-preview-section img,#wp-theme-main .wp-theme-insights-section img').forEach(function(img,i){setAttrs(img,blog.length?blog[i%blog.length]:'','Health guidance',false);});
  }

  function run(){
    if(!D.body)return;
    D.body.classList.remove('wpbb-v142-theme-medicine');
    D.body.classList.add('wpbb-v143-theme-medicine');
    mark();hero();doctors();pharmacy();otherMedia();
  }
  var timer;
  function schedule(){clearTimeout(timer);timer=W.setTimeout(run,50);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,250);W.setTimeout(run,900);});
  if(W.MutationObserver){var obs=new MutationObserver(schedule);obs.observe(D.documentElement,{childList:true,subtree:true});W.setTimeout(function(){obs.disconnect();},6500);}
})(window,document);
