/* ============================================================
   contact.js - Contact page: obfuscation, form submit, Leaflet map
   Depends on: translations.js, Leaflet (loaded before this file)
   ============================================================ */

/*  Obfuscated contact details 
   Stored as reversed char arrays - no plain address in source. */
(function () {
  const rev = s => s.split('').reverse().join('');

  const user   = rev('akwehcramjardnaskela');
  const domain = rev('moc.liamg');
  const email  = user + '@' + domain;

  const emLabel = document.getElementById('em-label');
  const link    = document.createElement('a');
  link.href        = 'mai' + 'lto:' + email;
  link.textContent = email;
  emLabel.appendChild(link);

  const ph = ['\u002B','4','4','7','4','7','4','1','4','2','9','9','9'].join('');
  document.getElementById('ph-label').textContent = ph;
})();


/* Contact form → admin/submit_inquiry.php  */
document.getElementById('contact-form').addEventListener('submit', async function (e) {
  e.preventDefault();

  const name    = document.getElementById('cf-name').value.trim();
  const email   = document.getElementById('cf-email').value.trim();
  const contact = document.getElementById('cf-contact').value.trim();
  const msg     = document.getElementById('cf-message').value.trim();
  const btn     = this.querySelector('.submit-btn');

  if (!name || !email || !msg) {
    showFormMsg('Please fill in your name, email and message.', false);
    return;
  }

  btn.disabled  = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending…';

  const body = new URLSearchParams({ name, email, contact, message: msg });

  try {
    const res  = await fetch('admin/submit_inquiry.php', { method: 'POST', body });
    const data = await res.json();

    if (data.ok) {
      showFormMsg("Message sent! I'll be in touch soon.", true);
      document.getElementById('contact-form').reset();
    } else {
      showFormMsg(data.errors ? data.errors.join(' ') : (data.error || 'Something went wrong.'), false);
    }
  } catch {
    showFormMsg('Network error. Please try again.', false);
  }

  btn.disabled  = false;
  btn.innerHTML = '<i class="fas fa-paper-plane"></i> <span data-translate="sendMessage">Send Message</span>';
});

function showFormMsg(text, success) {
  let el = document.getElementById('form-msg');
  if (!el) {
    el = document.createElement('p');
    el.id           = 'form-msg';
    el.style.cssText = 'margin:0.5rem 0 0;font-size:0.85rem;font-weight:600;text-align:center;';
    document.querySelector('.form-footer').appendChild(el);
  }
  el.textContent = text;
  el.style.color = success ? 'green' : 'red';
}


/*  Leaflet map  */
document.addEventListener('DOMContentLoaded', function () {
  const lat = 51.5647, lng = -0.1019; // Finsbury Park / N4

  const map = L.map('contact-map', {
    center: [lat, lng],
    zoom: 13,
    scrollWheelZoom: false
  });

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 19
  }).addTo(map);

  const markerHtml = `
    <div style="
      background:#DABBC2;
      border:2px solid #000;
      border-radius:50% 50% 50% 0;
      width:28px;height:28px;
      transform:rotate(-45deg);
      display:flex;align-items:center;justify-content:center;
    ">
      <i class="fas fa-home" style="transform:rotate(45deg);font-size:0.6rem;color:#000;"></i>
    </div>`;

  const icon = L.divIcon({
    className: '',
    html: markerHtml,
    iconSize:    [28, 28],
    iconAnchor:  [14, 28],
    popupAnchor: [0, -30]
  });


  const marker = L.marker([lat, lng], { icon }).addTo(map);
  marker.bindPopup(getPopupText()).openPopup();

  document.getElementById('language-toggle')?.addEventListener('change', () => {
    setTimeout(() => marker.setPopupContent(getPopupText()), 60);
  });
});
