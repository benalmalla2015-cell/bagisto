{{--
    3inab AI Assistant — Bagisto admin FAB widget.
    Injected via view_render_event('bagisto.admin.layout.vue-app-mount.before').
    Stateless: history lives in sessionStorage, calls go to panel_app HTTP bridge.
--}}
@php
    $bridgeToken = env('AI_BRIDGE_TOKEN', '');
    $bridgeUrl   = rtrim(env('AI_BRIDGE_URL', 'https://3inab.net/panel/api/ai/admin'), '/');
    // Only expose the bridge when an admin is actually signed in — prevents
    // leaking the shared secret on the public /admin/login page.
    $adminAuthed = auth()->guard('admin')->check();
@endphp

@if ($bridgeToken !== '' && $adminAuthed)
<div id="aiBridgeRoot" data-token="{{ $bridgeToken }}" data-url="{{ $bridgeUrl }}"></div>

<style>
    #aiBridgeFab {
        position: fixed; bottom: 22px; inset-inline-start: 22px;
        width: 56px; height: 56px; border-radius: 50%;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff; border: none; cursor: pointer; z-index: 9998;
        box-shadow: 0 10px 20px rgba(139, 92, 246, 0.35);
        display: flex; align-items: center; justify-content: center;
        transition: transform .2s ease, box-shadow .2s ease;
        animation: aiBridgePulse 2.4s ease-out infinite;
    }
    #aiBridgeFab:hover { transform: scale(1.06); }
    @keyframes aiBridgePulse {
        0%,100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.35); }
        50%     { box-shadow: 0 0 0 14px rgba(139, 92, 246, 0); }
    }
    #aiBridgeDrawer {
        position: fixed; bottom: 92px; inset-inline-start: 22px;
        width: 420px; max-width: 94vw; height: 620px; max-height: 85vh;
        background: #fff; border-radius: 18px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35);
        display: none; flex-direction: column; overflow: hidden; z-index: 9999;
        border: 1px solid #e5e7eb; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }
    #aiBridgeDrawer.open { display: flex; }
    .aiBridgeHeader {
        background: linear-gradient(100deg, #6d28d9, #8b5cf6);
        color: #fff; padding: 12px 16px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .aiBridgeHeader h3 { margin: 0; font-size: 14px; font-weight: 700; }
    .aiBridgeHeader p  { margin: 2px 0 0; font-size: 11px; opacity: .9; }
    .aiBridgeHeader button { background: rgba(255,255,255,.15); border: none; color: #fff; padding: 4px 8px; border-radius: 6px; cursor: pointer; font-size: 12px; }
    #aiBridgeMsgs { flex: 1; overflow-y: auto; padding: 14px; background: #f8fafc; font-size: 13.5px; line-height: 1.7; }
    .aiBridgeMsg { margin-bottom: 10px; display: flex; }
    .aiBridgeMsg.user { justify-content: flex-end; }
    .aiBridgeMsg .bubble { max-width: 85%; padding: 9px 13px; border-radius: 14px; white-space: pre-wrap; word-wrap: break-word; }
    .aiBridgeMsg.user .bubble { background: #6d28d9; color: #fff; border-bottom-right-radius: 4px; }
    .aiBridgeMsg.assistant .bubble { background: #fff; color: #111; border: 1px solid #e5e7eb; border-bottom-left-radius: 4px; }
    .aiBridgeMsg.error .bubble { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .aiBridgeMsg .meta { font-size: 10px; opacity: .6; margin-top: 3px; }
    #aiBridgeQuick { padding: 6px 10px; border-top: 1px solid #eee; background: #fafafa; display: flex; flex-wrap: wrap; gap: 6px; }
    #aiBridgeQuick button { background: #fff; border: 1px solid #ddd6fe; color: #6d28d9; padding: 4px 10px; border-radius: 9999px; font-size: 11px; cursor: pointer; }
    #aiBridgeQuick button:hover { background: #f5f3ff; }
    #aiBridgeForm { display: flex; gap: 8px; padding: 10px; border-top: 1px solid #eee; background: #fff; }
    #aiBridgeForm textarea { flex: 1; resize: none; padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 10px; font-family: inherit; font-size: 13px; }
    #aiBridgeForm button { background: #6d28d9; color: #fff; border: none; padding: 8px 16px; border-radius: 10px; font-weight: 600; cursor: pointer; }
    #aiBridgeForm button:disabled { opacity: .5; cursor: not-allowed; }
    #aiBridgeLoading { text-align: center; padding: 6px; color: #6b7280; font-size: 12px; }
    [dir="rtl"] #aiBridgeFab, [dir="rtl"] #aiBridgeDrawer { inset-inline-start: 22px; }
</style>

<button id="aiBridgeFab" title="أستاذ عنب — مساعد الإدارة">
    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
    </svg>
</button>

<div id="aiBridgeDrawer" role="dialog" aria-modal="true">
    <div class="aiBridgeHeader">
        <div>
            <h3>أستاذ عنب — وضع الإدارة</h3>
            <p>رؤية كاملة لكل قنوات المتجر</p>
        </div>
        <button id="aiBridgeClear" title="مسح المحادثة">مسح</button>
    </div>
    <div id="aiBridgeMsgs"></div>
    <div id="aiBridgeQuick">
        <button data-q="كم عدد الطلبات خلال آخر 30 يوماً وما مجموعها؟">الطلبات الأخيرة</button>
        <button data-q="ما أكثر 5 منتجات مبيعاً هذا الشهر؟">الأكثر مبيعاً</button>
        <button data-q="ما المنتجات التي مخزونها أقل من 5؟">تنبيهات المخزون</button>
    </div>
    <form id="aiBridgeForm" autocomplete="off">
        <textarea id="aiBridgeInput" rows="2" placeholder="اكتب سؤالك… (Enter للإرسال)"></textarea>
        <button type="submit" id="aiBridgeSend">إرسال</button>
    </form>
</div>

<script>
(function() {
    var root    = document.getElementById('aiBridgeRoot');
    if (! root) return;
    var token   = root.dataset.token;
    var baseUrl = root.dataset.url;
    if (! token) return;

    var fab     = document.getElementById('aiBridgeFab');
    var drawer  = document.getElementById('aiBridgeDrawer');
    var msgs    = document.getElementById('aiBridgeMsgs');
    var input   = document.getElementById('aiBridgeInput');
    var form    = document.getElementById('aiBridgeForm');
    var sendBtn = document.getElementById('aiBridgeSend');
    var clearBtn = document.getElementById('aiBridgeClear');
    var quick   = document.getElementById('aiBridgeQuick');

    var STORAGE_KEY = 'aiBridge.history.v1';
    var history = [];
    try { history = JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '[]'); } catch (e) {}

    function save() { try { sessionStorage.setItem(STORAGE_KEY, JSON.stringify(history)); } catch (e) {} }

    function addMsg(role, content, isError) {
        var d = document.createElement('div');
        d.className = 'aiBridgeMsg ' + role + (isError ? ' error' : '');
        var b = document.createElement('div');
        b.className = 'bubble';
        b.textContent = content;
        d.appendChild(b);
        msgs.appendChild(d);
        msgs.scrollTop = msgs.scrollHeight;
    }

    function renderHistory() {
        msgs.innerHTML = '';
        if (history.length === 0) {
            addMsg('assistant', '👋 أهلاً أيها المدير! اسألني عن أي شيء في متجر عنب: الطلبات، المنتجات، المخزون، العملاء…');
            return;
        }
        history.forEach(function (m) { addMsg(m.role, m.content); });
    }
    renderHistory();

    fab.addEventListener('click', function () {
        drawer.classList.toggle('open');
    });

    clearBtn.addEventListener('click', function () {
        history = [];
        save();
        renderHistory();
    });

    quick.addEventListener('click', function (e) {
        if (e.target.tagName === 'BUTTON') {
            input.value = e.target.dataset.q || '';
            form.requestSubmit();
        }
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && ! e.shiftKey) {
            e.preventDefault();
            form.requestSubmit();
        }
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var q = (input.value || '').trim();
        if (! q) return;

        history.push({ role: 'user', content: q });
        save();
        addMsg('user', q);
        input.value = '';
        sendBtn.disabled = true;

        var loader = document.createElement('div');
        loader.id = 'aiBridgeLoading';
        loader.textContent = '⋯';
        msgs.appendChild(loader);
        msgs.scrollTop = msgs.scrollHeight;

        fetch(baseUrl + '/ask', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Admin-Bridge-Token': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ question: q, history: history.slice(-10, -1) })
        })
        .then(function (r) { return r.json().then(function (data) { return { status: r.status, data: data }; }); })
        .then(function (out) {
            loader.remove();
            if (out.status >= 400) {
                var err = (out.data && out.data.text) || ((out.data && out.data.error) || 'تعذّر الاتصال');
                addMsg('assistant', '⚠️ ' + err, true);
                return;
            }
            var reply = (out.data && out.data.text) || '(لا يوجد رد)';
            history.push({ role: 'assistant', content: reply });
            save();
            addMsg('assistant', reply);

            // UI directives: navigate
            (out.data.ui_directives || []).forEach(function (d) {
                if (d.ui_directive === 'navigate' && d.target_url) {
                    setTimeout(function () { window.location.assign(d.target_url); }, 1500);
                }
            });
        })
        .catch(function (err) {
            loader.remove();
            addMsg('assistant', '⚠️ خطأ شبكة: ' + err.message, true);
        })
        .finally(function () { sendBtn.disabled = false; });
    });
})();
</script>
@endif
