(function(){
'use strict';
const app=document.querySelector('.hsb-ds-app');if(!app)return;
const fields=Array.from(app.querySelectorAll('.hsb-ds-field'));
const tabs=Array.from(app.querySelectorAll('[data-hsb-tab]'));
const panels=Array.from(app.querySelectorAll('[data-hsb-panel]'));
const previews=Array.from(app.querySelectorAll('.hsb-ds-preview'));
const notice=app.querySelector('.hsb-ds-unsaved');
const realFrames=Array.from(app.querySelectorAll('[data-hsb-frame]'));
function previewValues(){
 const out={};
 fields.forEach(f=>{
  const token=f.dataset.token;
  const mode=f.querySelector('.hsb-ds-mode').value;
  if(mode==='custom')out[token]=f.querySelector('.hsb-ds-color').value;
  else if(mode==='primary')out[token]='var(--color-primary)';
  else if(mode==='secondary')out[token]='var(--color-secondary)';
 });
 return out;
}
function syncFrame(frame){
 try{if(frame.contentWindow)frame.contentWindow.postMessage({type:'hsb-ds-preview-colors',values:previewValues()},window.location.origin);}catch(e){}
}
function loadFrame(frame){
 if(!frame.getAttribute('src'))frame.setAttribute('src',frame.dataset.src);
 else syncFrame(frame);
}
realFrames.forEach(frame=>frame.addEventListener('load',()=>syncFrame(frame)));
window.addEventListener('message',event=>{
 if(event.origin!==window.location.origin || !event.data)return;
 realFrames.forEach(frame=>{
   if(frame.contentWindow!==event.source)return;
   if(event.data.type==='hsb-ds-preview-ready')syncFrame(frame);
   if(event.data.type==='hsb-ds-preview-height'){
     const h=Number(event.data.height);
     if(Number.isFinite(h) && h>100)frame.style.height=Math.min(2900,Math.max(180,h))+'px';
   }
 });
});
app.querySelectorAll('.hsb-ds-reload-preview').forEach(btn=>btn.addEventListener('click',()=>{
 const frame=btn.closest('.hsb-ds-preview').querySelector('[data-hsb-frame]');
 if(frame)frame.src=frame.dataset.src;
}));
const byToken=Object.fromEntries(fields.map(f=>[f.dataset.token,f]));
const hexPattern=/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/;
function normalizeHex(text){
 const s=String(text||'').trim();if(!hexPattern.test(s))return null;
 if(s.length===4)return '#'+Array.from(s.slice(1)).map(x=>x+x).join('').toLowerCase();
 return s.toLowerCase();
}
function value(token,seen){
 const f=byToken[token];if(!f)return null;
 seen=seen||new Set();if(seen.has(token))return f.dataset.default;seen.add(token);
 const mode=f.querySelector('.hsb-ds-mode').value;
 if(mode==='custom')return f.querySelector('.hsb-ds-color').value;
 if(mode==='primary')return value('color-primary',seen);
 if(mode==='secondary')return value('color-secondary',seen);
 const inherited={
  'hsb-header-top-bg':'color-primary-hover',
  'hsb-header-icon-bg':'color-secondary',
  'hsb-header-nav-active':'color-secondary',
  'hsb-footer-contact-heading-bg':'color-secondary',
  'hsb-shop-card-part-emphasis':'color-secondary',
  'hsb-shop-card-family-badge-base':'color-secondary'
 };
 if(inherited[token])return value(inherited[token],seen);
 return f.dataset.default;
}
function refresh(){
 previews.forEach(p=>{
  fields.forEach(f=>{const token=f.dataset.token;const val=value(token);if(val)p.style.setProperty('--'+token,val)});
 });
 realFrames.forEach(syncFrame);
 fields.forEach(f=>{
  const mode=f.querySelector('.hsb-ds-mode').value;
  f.classList.toggle('is-custom',mode==='custom');
 });
}
function dirty(msg){notice.textContent=msg||'تغییرات ذخیره‌نشده دارید';refresh()}
function setHex(f,hex){
 f.querySelector('.hsb-ds-color').value=hex;
 f.querySelector('.hsb-ds-hex').value=hex;
 f.querySelector('.hsb-ds-hex').setCustomValidity('');
 f.querySelector('.hsb-ds-hex').classList.remove('is-invalid');
}
fields.forEach(f=>{
 const mode=f.querySelector('.hsb-ds-mode');
 const picker=f.querySelector('.hsb-ds-color');
 const hex=f.querySelector('.hsb-ds-hex');
 mode.addEventListener('change',()=>{
  if(mode.value==='custom'){
   const normalized=normalizeHex(hex.value);
   if(normalized)setHex(f,normalized);
  }
  dirty();
 });
 // Color picker remains usable even when mode is default/inherited.
 picker.addEventListener('input',()=>{
  mode.value='custom';setHex(f,picker.value);dirty();
 });
 picker.addEventListener('change',()=>{
  mode.value='custom';setHex(f,picker.value);dirty();
 });
 hex.addEventListener('input',()=>{
  const normalized=normalizeHex(hex.value);
  hex.classList.toggle('is-invalid',!normalized);
  hex.setCustomValidity(normalized?'':'کد رنگ باید به شکل #RRGGBB یا #RGB باشد.');
  if(normalized){picker.value=normalized;mode.value='custom';dirty();}
 });
 hex.addEventListener('blur',()=>{
  const normalized=normalizeHex(hex.value);
  if(normalized){setHex(f,normalized);} else {
   hex.setCustomValidity('کد رنگ باید به شکل #RRGGBB یا #RGB باشد.');
  }
 });
});
tabs.forEach(btn=>btn.addEventListener('click',()=>{
 const target=btn.dataset.hsbTab;
 tabs.forEach(b=>{const active=b===btn;b.classList.toggle('is-active',active);b.setAttribute('aria-selected',active?'true':'false')});
 panels.forEach(p=>p.classList.toggle('is-active',p.dataset.hsbPanel===target));
 const panel=panels.find(p=>p.dataset.hsbPanel===target);
 if(panel){const frame=panel.querySelector('[data-hsb-frame]');if(frame)loadFrame(frame);}
}));
app.querySelectorAll('[data-reset-group]').forEach(btn=>btn.addEventListener('click',()=>{
 const slug=btn.dataset.resetGroup;
 const panel=panels.find(p=>p.dataset.hsbPanel===slug);
 if(!panel)return;
 if(!window.confirm('فقط رنگ‌های این بخش به مقادیر اصلی UI Kit برگردند؟ تغییر تا زمان ذخیره در سایت اعمال نمی‌شود.'))return;
 panel.querySelectorAll('.hsb-ds-field').forEach(f=>{
  f.querySelector('.hsb-ds-mode').value='default';
  setHex(f,normalizeHex(f.dataset.default)||'#ffffff');
 });
 dirty('رنگ‌های این بخش بازنشانی شد؛ برای اعمال در سایت ذخیره کنید.');
}));
const reset=app.querySelector('#hsb-ds-reset');
if(reset)reset.addEventListener('click',()=>{
 if(!window.confirm('همه تنظیمات ذخیره‌شده در تمام بخش‌ها حذف شوند و رنگ‌های اصلی CSS برگردند؟'))return;
 app.querySelector('#hsb-ds-action').value='reset';
 app.querySelector('#hsb-ds-form').submit();
});
const form=app.querySelector('#hsb-ds-form');
form.addEventListener('submit',event=>{
 const invalid=fields.find(f=>!normalizeHex(f.querySelector('.hsb-ds-hex').value));
 if(invalid){
  event.preventDefault();
  const panel=invalid.closest('[data-hsb-panel]');
  const tab=tabs.find(b=>b.dataset.hsbTab===panel.dataset.hsbPanel);
  if(tab)tab.click();
  invalid.querySelector('.hsb-ds-hex').focus();
  notice.textContent='کد HEX نامعتبر است؛ مقدار را اصلاح کنید.';
  return;
 }
 // Persist swatch values, not the manually entered text field.
 fields.forEach(f=>setHex(f,normalizeHex(f.querySelector('.hsb-ds-hex').value)));
});
refresh();
const initial=panels.find(p=>p.classList.contains('is-active'));
if(initial){const frame=initial.querySelector('[data-hsb-frame]');if(frame)loadFrame(frame);}
})();
