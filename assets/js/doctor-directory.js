document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-doctor-directory]').forEach(function (directory) {
    var form = directory.querySelector('[data-doctor-filter]');
    var results = directory.querySelector('[data-doctor-results]');
    if (!form || !results || !window.WPBBMedicineDirectory) return;
    var controller;
    function submit() {
      if (controller) controller.abort();
      controller = new AbortController();
      var data = new FormData(form);
      data.append('action', 'wpbb_medicine_doctors');
      data.append('nonce', WPBBMedicineDirectory.nonce);
      directory.classList.add('is-loading');
      fetch(WPBBMedicineDirectory.ajaxUrl, {method:'POST',body:data,credentials:'same-origin',signal:controller.signal})
        .then(function(r){return r.json();})
        .then(function(json){if(json.success&&json.data&&json.data.html)results.innerHTML=json.data.html;})
        .catch(function(error){if(error.name!=='AbortError')results.innerHTML='<div class="alert alert-warning">Unable to load doctors right now.</div>';})
        .finally(function(){directory.classList.remove('is-loading');});
    }
    form.addEventListener('submit', function(e){e.preventDefault();submit();});
    form.querySelectorAll('select').forEach(function(el){el.addEventListener('change',submit);});
    var search=form.querySelector('input[type="search"]'),timer;
    if(search)search.addEventListener('input',function(){clearTimeout(timer);timer=setTimeout(submit,350);});
  });
});
