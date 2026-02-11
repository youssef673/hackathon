function buildUrl(path) {
  const base = (window.APP_BASE_URL || '').replace(/\/$/, '');
  const suffix = String(path || '').replace(/^\//, '');
  return `${base}/${suffix}`;
}

async function postJSON(url, payload) {
  const res = await fetch(url, {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(payload),
  });
  return res.json();
}

document.querySelectorAll('.add-to-cart').forEach((btn) => {
  btn.addEventListener('click', async (e) => {
    e.preventDefault();
    const id = Number(btn.dataset.productId);
    const qtyInput = document.getElementById(`qty-${id}`);
    const quantity = qtyInput ? Number(qtyInput.value) : 1;
    const result = await postJSON(buildUrl('ajax/cart_add.php'), {product_id: id, quantity});
    alert(result.message || 'Operazione completata');
  });
});

document.querySelectorAll('.remove-from-cart').forEach((btn) => {
  btn.addEventListener('click', async (e) => {
    e.preventDefault();
    const id = Number(btn.dataset.productId);
    const result = await postJSON(buildUrl('ajax/cart_remove.php'), {product_id: id});
    const row = document.getElementById(`row-${id}`);
    if (row) row.remove();
    const feedback = document.getElementById('cart-feedback');
    if (feedback) feedback.innerHTML = `<p class="alert success">${result.message}</p>`;
  });
});
