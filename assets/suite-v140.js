/* Woo Events 3.8.11.40 - Automotive-parity scoped layout/runtime repair. */
(function(W,D){
  'use strict';
  var CFG=W.wpbbSuiteV140||{};
  var ROOT=D.documentElement;
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function txt(el){return String(el&&el.textContent||'').replace(/\s+/g,' ').trim();}
  function unique(arr){return arr.filter(function(x,i,a){return x&&a.indexOf(x)===i;});}
  function topLevel(arr){return unique(arr).filter(function(item){return !arr.some(function(other){return other!==item&&other.contains&&other.contains(item);});});}

  function measureGrid(){
    var candidates=[q('.wp-theme-header-main > .container'),q('.wp-theme-header-main .container'),q('.wp-theme-site-header .container'),q('.wp-theme-site-footer .container')].filter(Boolean);
    var best=null;
    candidates.some(function(el){var r=el.getBoundingClientRect();if(r.width>320&&r.width<=W.innerWidth+2){best=r;return true;}return false;});
    if(!best)return;
    ROOT.style.setProperty('--wpbb-v140-left',Math.max(16,Math.round(best.left))+'px');
    ROOT.style.setProperty('--wpbb-v140-right',Math.max(16,Math.round(W.innerWidth-best.right))+'px');
  }

  function removeMalformedBlockText(){
    var main=q('#wp-theme-main');if(!main||!D.createTreeWalker)return;
    var walker=D.createTreeWalker(main,NodeFilter.SHOW_TEXT),node,remove=[];
    while((node=walker.nextNode())){var value=String(node.nodeValue||'');if(/wp:wpbb\/icon-card|svgCode|wpbb-sector-proof-card/.test(value)&&(/<!--|<!-|wp:wpbb/.test(value)))remove.push(node);}
    remove.forEach(function(n){if(n.parentNode)n.parentNode.removeChild(n);});
  }

  function replacePlaceholderCards(){
    var main=q('#wp-theme-main'),cards=Array.isArray(CFG.proofCards)?CFG.proofCards:[];if(!main||!cards.length)return;
    var titles=qa('h1,h2,h3,h4,h5,h6,.wpbb-icon-card__title,.wp-theme-sector-card__title',main).filter(function(el){return txt(el)==='Card title';});
    var bodies=qa('p,.wpbb-icon-card__text,.wp-theme-sector-card__text',main).filter(function(el){return txt(el)==='Add a short description.';});
    titles.forEach(function(el,i){el.textContent=cards[i%cards.length].title;});
    bodies.forEach(function(el,i){el.textContent=cards[i%cards.length].text;});
  }

  function bestHost(scope,cards){
    cards=topLevel(cards||[]);if(!scope||cards.length<2)return null;
    var candidates=unique(cards.reduce(function(out,card){var node=card;for(var depth=0;node&&node!==scope&&depth<6;depth++,node=node.parentElement){if(node.matches&&node.matches('.row,.wpbb-row,.wpbb-v62-card-grid,.wpbb-sector-grid,.products,.wp-block-post-template,.wpbb-events-list'))out.push(node);}return out;},[]));
    var best=null;
    candidates.forEach(function(host){var direct=kids(host).filter(function(child){return cards.some(function(card){return child===card||child.contains(card);});});if(direct.length<2)return;if(!best||direct.length>best.items.length)best={host:host,items:direct};});
    if(best)return best;
    var parent=cards[0].parentElement;if(parent&&cards.every(function(card){return card.parentElement===parent;}))return{host:parent,items:cards.slice()};
    return null;
  }

  function clearOwnedAncestors(scope,host){
    if(!scope||!host)return;
    var node=host.parentElement;
    while(node&&node!==scope){node.classList.remove('wpbb-v140-grid','wpbb-v140-cols-2','wpbb-v140-cols-3','wpbb-v140-cols-4','wpbb-v140-product-grid');node=node.parentElement;}
  }

  function markGrid(host,items,cols,extra){
    if(!host||!items||items.length<2)return;
    host.classList.add('wpbb-v140-grid','wpbb-v140-cols-'+Math.max(2,Math.min(4,cols||items.length)));
    if(extra)host.classList.add(extra);
    items.forEach(function(item){item.classList.add('wpbb-v140-grid-cell');});
  }

  function markSectionCards(){
    var groups=[
      ['.wp-theme-services-section,.wp-theme-sector-services-section','.wp-theme-sector-card,.wpbb-icon-card',3],
      ['.wp-theme-industries-section,.wp-theme-sector-industries-section','.wp-theme-sector-card,.wpbb-icon-card',4],
      ['.wp-theme-case-studies-section','.wp-theme-case-card,.wpbb-icon-card,.wp-theme-sector-card',3],
      ['.wp-theme-process-section','.wp-theme-process-card,.wpbb-icon-card,.wp-theme-sector-card',3]
    ];
    groups.forEach(function(group){qa('#wp-theme-main '+group[0]).forEach(function(section){var cards=topLevel(qa(group[1],section));var pick=bestHost(section,cards);if(pick)markGrid(pick.host,pick.items,group[2]);});});
  }

  function markStats(){
    qa('#wp-theme-main .wp-theme-sector-proof,#wp-theme-main .wp-theme-home-stats').forEach(function(section){var cards=topLevel(qa('.wp-theme-sector-proof__item,.wpbb-fun-fact',section));var pick=bestHost(section,cards);if(pick)markGrid(pick.host,pick.items,4,'wpbb-v140-stats-grid');});
  }

  function proofTitles(){return(Array.isArray(CFG.proofCards)?CFG.proofCards:[]).map(function(card){return String(card&&card.title||'').trim();}).filter(Boolean);}
  function markProof(){
    var expected=proofTitles();
    qa('#wp-theme-main .wpbb-sector-proof-band').forEach(function(section){
      var cards=topLevel(qa('.wpbb-sector-proof-card,.wpbb-v140-sector-proof-card,.wpbb-icon-card,.wp-theme-sector-card',section).filter(function(card){var h=q('h1,h2,h3,h4,h5,h6,.wpbb-icon-card__title,.wp-theme-sector-card__title',card);return card.classList.contains('wpbb-v140-sector-proof-card')||card.classList.contains('wpbb-sector-proof-card')||(h&&expected.indexOf(txt(h))!==-1);}));
      var pick=bestHost(section,cards);if(pick)markGrid(pick.host,pick.items,3,'wpbb-v140-proof-grid');
    });
  }

  function markEvents(){
    qa('#wp-theme-main .wpbb-events-list').forEach(function(host){var cards=topLevel(qa('.wpbb-events-card',host));if(cards.length>=2)markGrid(host,cards,3,'wpbb-v140-events-grid');});
  }

  function productCards(section){return topLevel(qa('.wpbb-catalogue-card,.product.type-product,.type-product.product,.product-card,.iws-product-card',section));}
  function markProducts(){
    qa('#wp-theme-main .wp-theme-home-product-catalogue').forEach(function(section){
      var cards=productCards(section),pick=bestHost(section,cards);
      if(pick){clearOwnedAncestors(section,pick.host);markGrid(pick.host,pick.items,4,'wpbb-v140-product-grid');cards.forEach(function(card){card.classList.add('wpbb-v140-product-card');});}
      qa('h1,h2,h3,h4,h5,h6',section).forEach(function(h){if(txt(h))return;var box=h.parentElement;if(box&&!q('article,.card,.wpbb-catalogue-card,img,form,button,input,select',box))box.classList.add('wpbb-v140-hidden');else h.classList.add('wpbb-v140-hidden');});
    });
  }

  function markEditorial(){
    qa('#wp-theme-main .wp-theme-insights-section .wp-block-post-template,#wp-theme-main .wp-theme-blog-preview-section .wp-block-post-template').forEach(function(host){var items=kids(host).filter(function(item){return txt(item)||q('article,img,a',item);});if(items.length>=2)markGrid(host,items,3,'wpbb-v140-blog-grid');});
    qa('#wp-theme-main .wp-theme-gallery-section').forEach(function(section){if(q('.swiper,.wpbb-swiper--gallery',section))return;var cards=topLevel(qa('.wp-theme-gallery-card',section));var pick=bestHost(section,cards);if(pick)markGrid(pick.host,pick.items,4,'wpbb-v140-gallery-grid');});
  }

  function repairShop(){qa('#wp-theme-main.wp-theme-woo-legacy--catalog ul.products,#wp-theme-main.wp-theme-woo-legacy--catalog .products').forEach(function(g){if(qa(':scope > li.product',g).length>1)g.classList.add('wpbb-v140-shop-grid');});}
  function commonParent(a,b){if(!a||!b)return null;var p=a.parentElement,depth=0;while(p&&depth<7){if(p.contains(b))return p;p=p.parentElement;depth++;}return null;}
  function repairCart(){var main=q('#wp-theme-main.wp-theme-woo-legacy--cart');if(!main)return;var form=q('.woocommerce-cart-form',main),tot=q('.cart-collaterals',main);if(!form||!tot)return;var host=commonParent(form,tot);if(host)host.classList.add('wpbb-v140-cart-grid');}
  function removeCheckoutMarketingConsent(){
    qa('#wp-theme-main.wp-theme-woo-legacy--checkout form.checkout').forEach(function(form){
      qa('.wpbb-v128-newsletter-consent,.wp-newslatter-campaigns-consent,.wp-newsletter-campaigns-consent,[class*="newsletter"][class*="consent"],[class*="newslatter"][class*="consent"]',form).forEach(function(n){var box=n.closest('.form-row,.woocommerce-form-row,label,p')||n;if(box&&!q('#payment,#order_review',box))box.remove();});
    });
  }

  var endpointSlugs=['orders','downloads','edit-address','edit-account','payment-methods','lost-password','view-order','add-payment-method','delete-payment-method','set-default-payment-method','customer-logout'];
  function repairAccountLinks(){var main=q('#wp-theme-main.wp-theme-woo-legacy--account');if(!main)return;qa('a[href]',main).forEach(function(link){try{var url=new URL(link.href,W.location.href);if(url.origin!==W.location.origin)return;var parts=url.pathname.replace(/^\/+|\/+$/g,'').split('/').filter(Boolean);if(parts.length!==1||endpointSlugs.indexOf(parts[0])===-1)return;url.pathname='/my-account/'+parts[0]+'/';link.href=url.toString();}catch(e){}});}

  function menuTrigger(menu){var li=menu&&menu.parentElement;if(!li)return null;try{return li.querySelector(':scope > a, :scope > button, :scope > .wp-theme-nav-link')||li;}catch(e){return li.querySelector('a,button')||li;}}
  function desktop(){return W.matchMedia('(min-width: 992px)').matches;}
  function positionMega(menu){if(!menu)return;if(!desktop()){menu.style.removeProperty('top');return;}var trigger=menuTrigger(menu);if(!trigger||!trigger.getBoundingClientRect)return;var r=trigger.getBoundingClientRect(),li=menu.parentElement,lr=li&&li.getBoundingClientRect?li.getBoundingClientRect():r,bottom=Math.ceil(Math.max(r.bottom,lr.bottom)-6);if(bottom>0){menu.style.setProperty('top',bottom+'px','important');ROOT.style.setProperty('--wpbb-v140-mega-top',bottom+'px');}}
  function positionMegas(){qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(positionMega);}
  function bindMegas(){qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(function(menu){if(menu.dataset.wpbbV140Bound)return;menu.dataset.wpbbV140Bound='1';var li=menu.parentElement;if(li){li.addEventListener('pointerenter',function(){positionMega(menu);},{passive:true});li.addEventListener('focusin',function(){positionMega(menu);});}menu.addEventListener('pointerenter',function(){positionMega(menu);},{passive:true});});positionMegas();}

  function heroBlocks(){return qa('#wp-theme-main .wpbb-swiper--hero');}
  function swiperEl(block){return block&&(block.matches&&block.matches('.swiper')?block:q('.swiper',block));}
  function liveSwiper(block,el){return(el&&el.swiper)||(block&&block.swiper)||null;}
  function uniqueSlides(el){if(!el)return[];var slides=qa('.swiper-wrapper > .swiper-slide',el);if(!slides.length)slides=qa('.swiper-slide',el);var seen={},out=[];slides.forEach(function(slide,index){if(slide.classList.contains('swiper-slide-duplicate'))return;var raw=slide.getAttribute('data-swiper-slide-index'),key=(raw===null||raw==='')?'dom-'+index:String(raw);if(seen[key])return;seen[key]=1;out.push(slide);});return out.length?out:slides;}
  function activeIndex(sw,count){if(!count)return 0;var n=sw&&typeof sw.realIndex==='number'?sw.realIndex:(sw&&typeof sw.activeIndex==='number'?sw.activeIndex:0);n=parseInt(n,10);if(!isFinite(n)||n<0)n=0;return n%count;}
  function paintPager(pager,sw,count){var active=activeIndex(sw,count);qa('.wpbb-v140-hero-pagination__bullet',pager).forEach(function(button,index){var on=index===active;button.classList.toggle('is-active',on);if(on)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');});}
  function ensurePager(block){
    var el=swiperEl(block);if(!el)return;var count=uniqueSlides(el).length;if(count<2)return;
    var pager=q(':scope > .wpbb-v140-hero-pagination',el);if(!pager){pager=D.createElement('div');pager.className='wpbb-v140-hero-pagination';pager.setAttribute('role','group');pager.setAttribute('aria-label','Hero slides');el.appendChild(pager);}
    if(pager.children.length!==count){pager.innerHTML='';for(var i=0;i<count;i++){var b=D.createElement('button');b.type='button';b.className='wpbb-v140-hero-pagination__bullet';b.setAttribute('data-wpbb-v140-slide',String(i));b.setAttribute('aria-label','Go to hero slide '+(i+1)+' of '+count);pager.appendChild(b);}}
    if(!pager.dataset.wpbbV140PagerBound){pager.dataset.wpbbV140PagerBound='1';pager.addEventListener('click',function(event){var button=event.target&&event.target.closest?event.target.closest('[data-wpbb-v140-slide]'):null;if(!button)return;var index=parseInt(button.getAttribute('data-wpbb-v140-slide'),10)||0,sw=liveSwiper(block,swiperEl(block));if(sw){try{if(typeof sw.slideToLoop==='function')sw.slideToLoop(index);else if(typeof sw.slideTo==='function')sw.slideTo(index);}catch(e){}}paintPager(pager,sw,count);});}
    var sw=liveSwiper(block,el);if(sw&&pager._wpbbV140Swiper!==sw){pager._wpbbV140Swiper=sw;if(typeof sw.on==='function'){var update=function(){paintPager(pager,sw,count);};try{sw.on('slideChange',update);sw.on('realIndexChange',update);sw.on('transitionEnd',update);}catch(e){}}}paintPager(pager,sw,count);
  }

  function run(){
    if(D.body)D.body.classList.add('wpbb-v140');
    measureGrid();removeMalformedBlockText();replacePlaceholderCards();markSectionCards();markStats();markProof();markEvents();markProducts();markEditorial();repairShop();repairCart();removeCheckoutMarketingConsent();repairAccountLinks();bindMegas();heroBlocks().forEach(ensurePager);
  }
  var timer=0;function schedule(){clearTimeout(timer);timer=W.setTimeout(run,40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,300);W.setTimeout(run,1100);});
  W.addEventListener('resize',function(){clearTimeout(timer);timer=W.setTimeout(run,100);},{passive:true});
  W.addEventListener('scroll',function(){W.requestAnimationFrame(positionMegas);},{passive:true});
  if(W.MutationObserver){var queued=false,observer=new MutationObserver(function(ms){if(!ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))return;if(queued)return;queued=true;W.setTimeout(function(){queued=false;run();},55);});observer.observe(D.documentElement,{childList:true,subtree:true});W.setTimeout(function(){observer.disconnect();},6000);}
})(window,document);
