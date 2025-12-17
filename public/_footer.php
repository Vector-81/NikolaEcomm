</main>
<footer class="text-center text-sm text-gray-500 py-8">
  <div>Nikola Nikolić IV2 - Web programiranje</div>
</footer>

<!-- Toast container -->
<div id="toast" class="fixed right-4 bottom-4 bg-gray-800 text-white px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-300" style="pointer-events:none;"></div>

<script>
// Intercept forms that post to cart.php and submit via fetch, then show toast
document.addEventListener('submit', async function(e){
  const form = e.target;
  if(!(form instanceof HTMLFormElement)) return;
  const action = (form.getAttribute('action')||'').split('/').pop();
  if(action !== 'cart.php') return;
  e.preventDefault();
  const formData = new FormData(form);
  try{
    const url = form.getAttribute('action') || form.action;
    const method = (form.getAttribute('method') || form.method || 'POST').toUpperCase();
    const res = await fetch(url, { method: method, body: formData, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    let data = null;
    try { data = await res.json(); } catch(e){ data = null; }
    const title = data && data.item_title ? data.item_title : 'Item';
    const count = data && typeof data.cart_count !== 'undefined' ? data.cart_count : null;
    if(count !== null){
      const el = document.getElementById('cart-count'); if(el) el.textContent = count;
    }
    showToast(title + ' added to cart', 3000, true);
  } catch(err){
    showToast('Failed to add to cart', 3000, false);
  }
});

function showToast(msg, timeout=3000, success=true){
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.pointerEvents = 'auto';
  t.classList.remove('opacity-0');
  t.classList.add('opacity-100');
  // green background on success, red on error
  t.style.backgroundColor = success ? '#16a34a' : '#dc2626';
  setTimeout(()=>{ t.classList.remove('opacity-100'); t.classList.add('opacity-0'); t.style.pointerEvents='none'; }, timeout);
}
</script>

</body>
</html>