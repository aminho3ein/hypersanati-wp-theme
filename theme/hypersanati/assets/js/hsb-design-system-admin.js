(function(){'use strict';
const app=document.querySelector('.hsb-ds-app');if(!app)return;
const fields=Array.from(app.querySelectorAll('.hsb-ds-field'));
const tabs=Array.from(app.querySelectorAll('[data-hsb-tab]'));
const panels=Array.from(app.querySelectorAll('[data-hsb-panel]'));
const previews=Array.from(app.querySelectorAll('.hsb-ds-preview'));
const byToken={};fields.forEach(f=>byToken[f.dataset.token]=f);
function value(token,seen){
 const f=byToken[token];if(!f)return null;
 seen=seen||new Set();if(seen.has(token))return f.dataset.default;seen.add(token);
 const mode=f.querySelector('.hsb-ds-mode').value;
 if(mode==='custom')return f.querySelector('.hsb-ds-color').value;
 if(mode==='primary')return value('color-primary',seen);
 if(mode==='secondary')return value('color-secondary',seen);
 // Mirror the natural CSS fallback of key semantic aliases in lightweight previews.
 const inherited={'hsb-header-top-bg':'color-primary-hover','hsb-header-icon-bg':'color-secondary','hsb-header-nav-active':'color-secondary','hsb-footer-contact-heading-bg':'color-secondary','hsb-shop-card-part-emphasis':'color-secondary','hsb-shop-card-family-badge-base':'color-secondary'};
 if(inherited[token])return value(inherited[token],seen);
 return f.dataset.default;
}
function refresh(){
 previews.forEach(p=>{
   fields.forEach(f=>{const token=f.dataset.token; const val=value(token);if(val)p.style.setProperty('--'+token,val)});
 });
 fields.forEach(f=>{const mode=f.querySelector('.hsb-ds-mode').value;f.querySelector('.hsb-ds-color').disabled=mode!=='custom'});
}
function dirty(){app.querySelector('.hsb-ds-unsaved').textContent='تغییرات ذخیره‌نشده دارید';refresh()}
fields.forEach(f=>{f.querySelector('.hsb-ds-mode').addEventListener('change',dirty);f.querySelector('.hsb-ds-color').addEventListener('input',dirty)});
tabs.forEach(btn=>btn.addEventListener('click',()=>{const target=btn.dataset.hsbTab;tabs.forEach(b=>{const active=b===btn;b.classList.toggle('is-active',active);b.setAttribute('aria-selected',active?'true':'false')});panels.forEach(p=>p.classList.toggle('is-active',p.dataset.hsbPanel===target))}));
const reset=app.querySelector('#hsb-ds-reset');if(reset)reset.addEventListener('click',()=>{if(!window.confirm('همه تنظیمات رنگ ذخیره‌شده حذف شوند و مقادیر CSS اصلی برگردند؟'))return;const action=app.querySelector('#hsb-ds-action');action.value='reset';app.querySelector('#hsb-ds-form').submit()});
refresh();
})();
