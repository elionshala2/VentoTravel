(function () {
  'use strict';
  function b64ToBuf(s) { var b = atob(s), u = new Uint8Array(b.length); for (var i = 0; i < b.length; i++) u[i] = b.charCodeAt(i); return u.buffer; }
  function bufToB64(buf) { var s = '', u = new Uint8Array(buf); for (var i = 0; i < u.length; i++) s += String.fromCharCode(u[i]); return btoa(s); }
  function decode(o) {
    if (Array.isArray(o)) return o.map(decode);
    if (o && typeof o === 'object') { var r = {}; Object.keys(o).forEach(function (k) { r[k] = decode(o[k]); }); return r; }
    if (typeof o === 'string' && o.indexOf('=?BINARY?B?') === 0 && o.slice(-2) === '?=') return b64ToBuf(o.slice(11, -2));
    return o;
  }
  function post(action, data, csrf) {
    return fetch('passkey.php?a=' + action, { method: 'POST', credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf }, body: JSON.stringify(data || {}) })
      .then(function (r) { return r.json().catch(function () { return {}; }).then(function (j) { if (!r.ok || j.error) throw new Error(j.error || 'error'); return j; }); });
  }
  function msg(text) { var m = document.getElementById('authMsg'); if (m) { m.textContent = text; m.hidden = false; } }

  var supported = !!(window.PublicKeyCredential && navigator.credentials);

  var login = document.getElementById('passkeyLogin');
  if (login && supported) {
    login.hidden = false;
    login.addEventListener('click', function () {
      var csrf = login.dataset.csrf; login.disabled = true;
      post('login_options', {}, csrf)
        .then(function (o) { return navigator.credentials.get({ publicKey: decode(o).publicKey }); })
        .then(function (c) {
          return post('login_verify', { id: bufToB64(c.rawId), clientDataJSON: bufToB64(c.response.clientDataJSON),
            authenticatorData: bufToB64(c.response.authenticatorData), signature: bufToB64(c.response.signature) }, csrf);
        })
        .then(function (r) { window.location.href = r.redirect || 'index.php'; })
        .catch(function () { login.disabled = false; msg(login.dataset.err); });
    });
  }

  var add = document.getElementById('passkeyAdd');
  if (add) {
    if (!supported) { add.disabled = true; return; }
    add.addEventListener('click', function () {
      var csrf = add.dataset.csrf; add.disabled = true;
      post('reg_options', {}, csrf)
        .then(function (o) { return navigator.credentials.create({ publicKey: decode(o).publicKey }); })
        .then(function (c) {
          var ni = document.getElementById('passkeyName');
          var label = (ni && ni.value.trim()) || (navigator.userAgentData && navigator.userAgentData.platform) || navigator.platform || 'Passkey';
          return post('reg_verify', { clientDataJSON: bufToB64(c.response.clientDataJSON),
            attestationObject: bufToB64(c.response.attestationObject), name: label }, csrf);
        })
        .then(function () { window.location.href = 'account.php?pk=1'; })
        .catch(function () { add.disabled = false; msg(add.dataset.err); });
    });
  }
})();
