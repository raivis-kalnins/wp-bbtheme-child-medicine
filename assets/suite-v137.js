(function(W,D){
  'use strict';
  var cfg=W.wpbbMedicineV137||{};
  var galleryUrls=Array.isArray(cfg.galleryUrls)?cfg.galleryUrls.filter(Boolean):[];
  function q(s,r){try{return (r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return [];}}
  function kids(n){return n?Array.prototype.slice.call(n.children||[]):[];}
  function isMedicine(){return !!(D.body&&(D.body.classList.contains('wpbb-medicine-v137')||D.body.classList.contains('wpbb-v136-theme-medicine')));}
  function visible(n){if(!n)return false;var s=W.getComputedStyle?W.getComputedStyle(n):null;return !s||s.display!=='none';}

  function markGrid(host,cells){
    if(!host)return;
    cells=(cells||kids(host)).filter(function(c){return visible(c)&&!c.classList.contains('wpbb-v136-hidden');});
    var n=cells.length;if(n<2||n>12)return;
    var cols=n>=4?4:(n===3?3:2);
    host.classList.remove('wpbb-v137-cols-2','wpbb-v137-cols-3','wpbb-v137-cols-4');
    host.classList.add('wpbb-v137-grid','wpbb-v137-cols-'+cols);
    cells.forEach(function(c){c.classList.add('wpbb-v137-grid-cell');});
  }

  function repairGrids(){
    qa('#wp-theme-main .wpbb-v62-card-grid').forEach(function(g){
      if(g.closest('.wpbb-v136-process-section'))return;
      markGrid(g,kids(g));
    });
    qa('#wp-theme-main .medicine-speciality-grid,#wp-theme-main .medicine-doctor-grid').forEach(function(g){markGrid(g,kids(g));});
    qa('#wp-theme-main .medicine-pharmacy-catalogue').forEach(function(scope){
      var cards=qa('.wpbb-catalogue-card',scope);if(cards.length<2)return;
      var host=cards[0].parentElement;
      while(host&&host!==scope){
        var direct=kids(host).filter(function(c){return cards.some(function(card){return c===card||c.contains(card);});});
        if(direct.length>=2){markGrid(host,direct);return;}
        host=host.parentElement;
      }
    });
  }

  function repairTrust(){
    qa('#wp-theme-main .medicine-trust-band').forEach(function(section){
      var cards=qa('.medicine-trust-card',section);if(cards.length<2)return;
      var cardHost=cards[0].parentElement;
      while(cardHost&&cardHost!==section){
        var direct=kids(cardHost).filter(function(c){return cards.some(function(card){return c===card||c.contains(card);});});
        if(direct.length>=2){markGrid(cardHost,direct);break;}
        cardHost=cardHost.parentElement;
      }
      var outer=qa('.row,.wpbb-row',section).filter(function(r){return r.contains(cards[0])&&!!q('h2,h3',r);})[0];
      if(!outer){
        var cardColumn=cards[0].closest('[class*=\"col-\"],.wpbb-column');
        outer=cardColumn&&cardColumn.parentElement;
      }
      if(outer){
        outer.classList.add('wpbb-v137-trust-layout');
        var cs=kids(outer);
        if(cs[0])cs[0].classList.add('wpbb-v137-trust-intro');
        if(cs[1])cs[1].classList.add('wpbb-v137-trust-cards');
      }
    });
  }

  function setSource(img,url,eager){
    if(!img||!url)return;
    if(img.getAttribute('src')!==url)img.setAttribute('src',url);
    img.removeAttribute('srcset');img.removeAttribute('sizes');img.removeAttribute('width');img.removeAttribute('height');
    img.loading=eager?'eager':'lazy';img.decoding='async';
  }

  function repairMedia(){
    var about=q('#wp-theme-main .wp-theme-about-section img');
    if(about&&cfg.aboutUrl)setSource(about,cfg.aboutUrl,false);

    if(galleryUrls.length){
      qa('#wp-theme-main .wp-theme-gallery-section').forEach(function(section){
        var slides=qa('.swiper-slide,.wpbb-swiper-slide',section);
        slides.forEach(function(slide,i){
          var raw=slide.getAttribute('data-swiper-slide-index'),idx=raw===null?i:parseInt(raw,10);
          if(!Number.isFinite(idx))idx=i;
          var img=q('img',slide);if(img)setSource(img,galleryUrls[idx%galleryUrls.length],false);
        });
        var swiperNode=q('.swiper,.wpbb-swiper',section),sw=swiperNode&&swiperNode.swiper;
        if(sw&&sw.params){sw.params.spaceBetween=24;if(typeof sw.update==='function')sw.update();}
      });
    }

    qa('#wp-theme-main :is(.medicine-doctor-card__media img,.medicine-pharmacy-catalogue img,.wp-theme-sector-media-text__media img)').forEach(function(img){
      img.decoding='async';if(!img.hasAttribute('loading'))img.loading='lazy';
    });
  }

  function run(){if(!isMedicine())return;D.body.classList.add('wpbb-medicine-v137');repairGrids();repairTrust();repairMedia();}
  var pending=false;
  function schedule(){if(pending)return;pending=true;W.setTimeout(function(){pending=false;run();},60);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,350);W.setTimeout(run,1100);});
  if(W.MutationObserver)new MutationObserver(function(ms){if(ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))schedule();}).observe(D.documentElement,{childList:true,subtree:true});
})(window,document);
