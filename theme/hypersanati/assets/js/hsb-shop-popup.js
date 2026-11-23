(()=>{'use strict';
const root=document.getElementById('hsb-shop-guide');if(!root)return;
const panel=root.querySelector('[role="dialog"]');const openers=[...document.querySelectorAll('[data-hsb-guide-open]')];const closers=[...root.querySelectorAll('[data-hsb-guide-close]')];let previousFocus=null;
const focusable=()=>[...panel.querySelectorAll('button:not([disabled]),a[href],input:not([disabled]),[tabindex]:not([tabindex="-1"])')].filter(el=>el.getClientRects().length>0);
function open(){if(!root.hidden)return;previousFocus=document.activeElement;root.hidden=false;document.body.classList.add('hsb-shop-guide-open');(panel.querySelector('.hsb-shop-guide__close')||panel).focus();try{sessionStorage.setItem('hsb_shop_guide_seen_v1','1')}catch(e){}}
function close(){if(root.hidden)return;root.hidden=true;document.body.classList.remove('hsb-shop-guide-open');if(previousFocus&&previousFocus.isConnected)previousFocus.focus()}
openers.forEach(el=>el.addEventListener('click',open));closers.forEach(el=>el.addEventListener('click',close));
document.addEventListener('keydown',e=>{if(root.hidden)return;if(e.key==='Escape'){e.preventDefault();close();return}if(e.key!=='Tab')return;const elements=focusable();if(!elements.length){e.preventDefault();panel.focus();return}const first=elements[0],last=elements[elements.length-1];if(e.shiftKey&&document.activeElement===first){e.preventDefault();last.focus()}else if(!e.shiftKey&&document.activeElement===last){e.preventDefault();first.focus()}});
// Show once per browsing session, never repeatedly on every shop visit.
let seen=false;try{seen=sessionStorage.getItem('hsb_shop_guide_seen_v1')==='1'}catch(e){};
if(!seen){const start=()=>{if(document.visibilityState==='visible'&&root.hidden)open()};if(document.readyState==='complete')setTimeout(start,850);else window.addEventListener('load',()=>setTimeout(start,850),{once:true})}
})();
