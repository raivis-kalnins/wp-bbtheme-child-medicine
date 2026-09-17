(function(W,D){
  'use strict';
  var cfg=W.wpbbMedicineV138||{};
  function q(s,r){try{return (r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return [];}}
  function kids(n){return n?Array.prototype.slice.call(n.children||[]):[];}
  function visible(n){if(!n)return false;var s=W.getComputedStyle?W.getComputedStyle(n):null;return !s||s.display!=='none';}
  function text(n){return String(n&&n.textContent||'').replace(/\s+/g,' ').trim();}
  function slugFromHref(href){
    try{var p=(new URL(href,W.location.href)).pathname.replace(/\/+$/,'').split('/').filter(Boolean);return p[p.length-1]||'';}catch(e){return '';}
  }
  function setSource(img,url,alt,eager){
    if(!img||!url)return;
    if(img.getAttribute('src')!==url)img.setAttribute('src',url);
    img.removeAttribute('srcset');img.removeAttribute('sizes');img.removeAttribute('width');img.removeAttribute('height');
    if(alt&&!img.getAttribute('alt'))img.setAttribute('alt',alt);
    img.loading=eager?'eager':'lazy';img.decoding='async';
    try{img.fetchPriority=eager?'high':'auto';}catch(e){}
  }
  function markGrid(host,cells,cols){
    if(!host)return;
    cells=(cells||kids(host)).filter(function(c){return visible(c)&&!c.classList.contains('wpbb-v136-hidden');});
    if(cells.length<2)return;
    cols=cols||Math.min(4,cells.length);
    host.classList.add('wpbb-m138-grid','wpbb-m138-cols-'+cols);
    cells.forEach(function(c){c.classList.add('wpbb-m138-cell');});
  }
  function commonHost(cards,stop){
    if(!cards||cards.length<2)return null;
    var host=cards[0].parentElement;
    while(host&&host!==stop&&host!==D.body){
      var direct=kids(host).filter(function(c){return cards.some(function(card){return c===card||c.contains(card);});});
      if(direct.length>=2)return {host:host,cells:direct};
      host=host.parentElement;
    }
    return null;
  }

  function repairHero(){
    var block=q('#wp-theme-main .wpbb-swiper--hero,#wp-theme-main .wp-theme-sector-hero .swiper,#wp-theme-main .wp-theme-hero .swiper');
    if(!block)return;
    var shell=block.closest('.wp-theme-sector-hero,.wp-theme-hero,section')||block.parentElement;
    if(shell)shell.classList.add('wpbb-m138-hero-shell');
    var heroContainer=block.closest('.container');if(heroContainer)heroContainer.classList.add('wpbb-m138-hero-container');
    block.classList.add('wpbb-m138-hero');
    qa('.wpbb-v97-hero-finder',shell||block).forEach(function(n){n.remove();});
    var wrapper=q('.swiper-wrapper,.wpbb-swiper-wrapper',block);
    if(wrapper){wrapper.style.removeProperty('transform');wrapper.classList.add('wpbb-m138-hero-wrapper');}
    var slides=qa('.swiper-slide,.wpbb-swiper-slide',block).filter(function(s){return !s.classList.contains('swiper-slide-duplicate');});
    slides.forEach(function(slide,i){
      slide.classList.toggle('wpbb-m138-hero-active',i===0);
      slide.classList.toggle('wpbb-m138-hero-hidden',i!==0);
    });
    var slide=slides[0];if(!slide)return;
    var content=q('.wpbb-swiper-slide__content,.wp-theme-hero__content,.wpbb-hero-content',slide);
    var media=q('.wpbb-swiper-slide__media,.wp-theme-hero__media,.wpbb-hero-media',slide);
    if(!media){media=D.createElement('div');media.className='wpbb-swiper-slide__media wpbb-m138-hero-media';slide.insertBefore(media,content||slide.firstChild);}
    media.classList.add('wpbb-m138-hero-media');
    var img=q('img',media);if(!img){img=D.createElement('img');media.appendChild(img);}
    setSource(img,cfg.heroUrl,'Medical specialist',true);
    if(media.style)media.style.removeProperty('background-image');
    qa('.wpbb-v136-hero-pagination,.swiper-pagination,.swiper-button-prev,.swiper-button-next',shell||block).forEach(function(n){n.classList.add('wpbb-m138-hidden');});
  }

  function repairGrids(){
    qa('#wp-theme-main .medicine-speciality-grid,#wp-theme-main .medicine-trust-grid').forEach(function(g){markGrid(g,kids(g),4);});
    qa('#wp-theme-main .medicine-doctor-grid').forEach(function(g){markGrid(g,kids(g),4);});
    qa('#wp-theme-main .wp-theme-sector-services,#wp-theme-main .wp-theme-sector-industries').forEach(function(g){markGrid(g,kids(g),4);});
    qa('#wp-theme-main .wp-theme-case-grid,#wp-theme-main .wp-theme-case-studies-grid').forEach(function(g){markGrid(g,kids(g),3);});
    qa('#wp-theme-main .wp-theme-insights-grid,#wp-theme-main .wp-theme-blog-grid').forEach(function(g){markGrid(g,kids(g),3);});

    qa('#wp-theme-main .medicine-pharmacy-catalogue').forEach(function(scope){
      var cards=qa('.wpbb-catalogue-card',scope);var hit=commonHost(cards,scope.parentElement);
      if(hit)markGrid(hit.host,hit.cells,3);
    });

    qa('#wp-theme-main .wp-theme-home-stats').forEach(function(scope){
      var cards=qa('.wp-theme-sector-proof__item,.wpbb-fun-fact',scope);var hit=commonHost(cards,scope.parentElement);
      if(hit){markGrid(hit.host,hit.cells,4);scope.classList.add('wpbb-m138-stats');}
    });
  }

  function repairTrust(){
    qa('#wp-theme-main .medicine-trust-band').forEach(function(section){
      var cards=qa('.medicine-trust-card',section);if(cards.length<2)return;
      var hit=commonHost(cards,section);if(hit)markGrid(hit.host,hit.cells,4);
      var cardCol=cards[0].closest('[class*="col-"],.wpbb-column');
      var outer=cardCol&&cardCol.parentElement;
      while(outer&&outer!==section){
        var children=kids(outer),hasCards=children.some(function(c){return c.contains(cards[0]);}),hasHeading=!!q('h2,h3',outer);
        if(hasCards&&hasHeading&&children.length>=2)break;
        outer=outer.parentElement;
      }
      if(outer&&outer!==section){
        outer.classList.add('wpbb-m138-trust-layout');
        kids(outer).forEach(function(c){c.classList.add('wpbb-m138-trust-column');});
      }
    });
  }

  function repairDoctors(){
    qa('#wp-theme-main .medicine-doctor-card').forEach(function(card,index){
      var link=q('h3 a,.medicine-doctor-card__media',card),slug=link?slugFromHref(link.href):'';
      var urls=Array.isArray(cfg.doctorUrls)?cfg.doctorUrls:[];var url=(cfg.doctorImages&&cfg.doctorImages[slug])||urls[index%Math.max(1,urls.length)]||'';if(!url)return;
      var media=q('.medicine-doctor-card__media',card);if(!media)return;
      var badge=q('.medicine-doctor-card__badge',media),alt=text(q('h3',card));
      kids(media).forEach(function(child){if(child!==badge)child.remove();});
      var img=D.createElement('img');setSource(img,url,alt,false);media.insertBefore(img,badge||null);
    });
  }

  function repairPharmacy(){
    qa('#wp-theme-main .medicine-pharmacy-catalogue .wpbb-catalogue-card').forEach(function(card,index){
      var a=q('a[href]',card),slug=a?slugFromHref(a.href):'';
      var urls=Array.isArray(cfg.pharmacyUrls)?cfg.pharmacyUrls:[];var url=(cfg.pharmacyImages&&cfg.pharmacyImages[slug])||urls[index%Math.max(1,urls.length)]||'';if(!url)return;
      var img=q('img.card-img-top,img',card);
      if(!img){img=D.createElement('img');img.className='card-img-top';card.insertBefore(img,card.firstChild);}
      img.classList.add('card-img-top');setSource(img,url,text(q('.card-title,h3',card)),false);
    });
  }

  function repairGallery(){
    var urls=Array.isArray(cfg.galleryUrls)?cfg.galleryUrls.filter(Boolean):[];if(!urls.length)return;
    qa('#wp-theme-main .wp-theme-gallery-section').forEach(function(section){
      var slides=qa('.swiper-slide,.wpbb-swiper-slide',section).filter(function(s){return !s.classList.contains('swiper-slide-duplicate');});
      slides.forEach(function(slide,i){
        if(i>=urls.length){slide.classList.add('wpbb-m138-gallery-extra');return;}
        var media=q('.wpbb-swiper-slide__media,.wp-theme-gallery-card__media',slide);
        if(!media){media=D.createElement('div');media.className='wpbb-swiper-slide__media';slide.insertBefore(media,slide.firstChild);}
        var img=q('img',media);if(!img){img=D.createElement('img');media.appendChild(img);}
        setSource(img,urls[i],text(q('h3,h4,.wpbb-swiper-slide__title',slide)),false);
        if(media.style)media.style.removeProperty('background-image');
      });
    });
  }

  function repairAbout(){
    var img=q('#wp-theme-main .wp-theme-about-section img,#wp-theme-main .wp-theme-sector-media-text__media img');
    if(img&&cfg.aboutUrl)setSource(img,cfg.aboutUrl,img.alt||'Clinical care team',false);
  }

  function repairBlog(){
    var urls=Array.isArray(cfg.blogUrls)?cfg.blogUrls.filter(Boolean):[];if(!urls.length)return;
    qa('#wp-theme-main .wp-theme-insights-section .wp-theme-blog-card').forEach(function(card,i){
      var url=urls[i%urls.length],fig=q('.wp-block-post-featured-image',card),img=fig&&q('img',fig);
      if(!fig){fig=D.createElement('figure');fig.className='wp-block-post-featured-image';card.insertBefore(fig,card.firstChild);}
      if(!img){img=D.createElement('img');fig.appendChild(img);}
      setSource(img,url,text(q('.wp-block-post-title,h3',card)),false);
    });
  }

  function run(){
    if(!D.body)return;D.body.classList.add('wpbb-medicine-v138');
    repairHero();repairGrids();repairTrust();repairDoctors();repairPharmacy();repairGallery();repairAbout();repairBlog();
  }
  var pending=false;
  function schedule(){if(pending)return;pending=true;W.setTimeout(function(){pending=false;run();},80);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,350);W.setTimeout(run,1200);});
  if(W.MutationObserver)new MutationObserver(function(ms){if(ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))schedule();}).observe(D.documentElement,{childList:true,subtree:true});
})(window,document);
