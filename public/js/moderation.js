/* ========================================
   ARQUIVO: public/js/moderation.js
   ========================================
   Versão com palavras customizadas EMBUTIDAS
   Não é mais necessário passar customWords no init()!
   CORRIGIDO: Falsos positivos removidos
   ======================================== */

(function (global) {
  if (global.Moderation) return; // já carregado

  const Moderation = (function(){
    let csrfToken = '';
    let endpoint = '/moderate';
    let debounceMs = 120;
    
    // PALAVRAS CUSTOMIZADAS PADRÃO - já embutidas no sistema
    let customWords = [
      "arrombada",
      "arrombadas",
      "arrombado",
      "babaca",
      "bacurinha",
      "baitola",
      "bichona",
      "bixa",
      "boceta",
      "boiola",
      "bolcinha",
      "bolsinha",
      "boquete",
      "boqueteira",
      "boqueteiro",
      "boquetera",
      "boquetero",
      "boquetes",
      "bosta",
      "brecheca",
      "bucefula",
      "buceta",
      "bucetao",
      "bucetas",
      "bucetasso",
      "bucetinha",
      "bucetinhas",
      "bucetonas",
      "cacete",
      "cachuleta",
      "cagalhao",
      "carai",
      "caraio",
      "caralha",
      "caralho",
      "caralhudo",
      "cassete",
      "cequelada",
      "cequelado",
      "chalerinha",
      "chatico",
      "chavasca",
      "checheca",
      "chereca",
      "chibio",
      "chimbica",
      "chupada",
      "chupador",
      "chupadora",
      "chupando",
      "chupeta",
      "chupetinha",
      "chupou",
      "porra",
      "crossdresser",
      "cuecao",
      "custozinha",
      "cuzao",
      "cuzinho",
      "cuzinhos",
      "dadeira",
      "encoxada",
      "enrabadas",
      "fornicada",
      "fudendo",
      "fudido",
      "furustreca",
      "gostozudas",
      "gozada",
      "gozadas",
      "greludas",
      "gulosinha",
      "katchanga",
      "bilau",
      "lesbofetiche",
      "lixa-pica",
      "mede-rola",
      "megasex",
      "mela-pentelho",
      "meleca",
      "melequinha",
      "menage",
      "menages",
      "merda",
      "merdao",
      "meretriz",
      "metendo",
      "mijada",
      "otario",
      "papa-duro",
      "pau",
      "pausudas",
      "pechereca",
      "peidao",
      "peido",
      "peidorreiro",
      "peitos",
      "peituda",
      "peitudas",
      "periquita",
      "pica",
      "piranhuda",
      "piriguetes",
      "piroca",
      "pirocao",
      "pirocas",
      "pirocudo",
      "pitbitoca",
      "pitchbicha",
      "pitchbitoca",
      "pithbicha",
      "pithbitoca",
      "pitibicha",
      "pitrica",
      "pixota",
      "prencheca",
      "prexeca",
      "priquita",
      "priquito",
      "punheta",
      "punheteiro",
      "pussy",
      "puta",
      "putaria",
      "putas",
      "putinha",
      "quenga",
      "rabuda",
      "rabudas",
      "rameira",
      "rapariga",
      "retardado",
      "saca-rola",
      "safada",
      "safadas",
      "safado",
      "safados",
      "sequelada",
      "sexboys",
      "sexgatas",
      "sirica",
      "siririca",
      "sotravesti",
      "suruba",
      "surubas",
      "taioba",
      "tarada",
      "tchaca",
      "tcheca",
      "tchonga",
      "tchuchuca",
      "tchutchuca",
      "tesuda",
      "tesudas",
      "tesudo",
      "tetinha",
      "tezao",
      "tezuda",
      "tezudo",
      "tgatas",
      "t-girls",
      "tobinha",
      "tomba-macho",
      "topsexy",
      "transa",
      "transando",
      "travecas",
      "traveco",
      "travecos",
      "trepada",
      "trepadas",
      "vacilao",
      "vadjaina",
      "vadia",
      "vagabunda",
      "vagabundo",
      "vaginismo",
      "vajoca",
      "veiaca",
      "veiaco",
      "viadinho",
      "viado",
      "xabasca",
      "xana",
      "xaninha",
      "xatico",
      "xavasca",
      "xebreca",
      "xereca",
      "xexeca",
      "xibio",
      "xoroca",
      "gay",
      "xota",
      "xotinha",
      "xoxota",
      "xoxotas",
      "xoxotinha",
      "xulipa",
      "xumbrega",
      "xupaxota",
      "xupeta",
      "xupetinha",
      // Variações com @
      "@rromb@d@",
      "@rromb@d@s",
      "@rromb@do",
      "b@b@c@",
      "b@curinh@",
      "b@itol@",
      "bichon@",
      "bix@",
      "bocet@",
      "boiol@",
      "bolcinh@",
      "bolsinh@",
      "boqueteir@",
      "boqueter@",
      "bost@",
      "brechec@",
      "buceful@",
      "bucet@",
      "bucet@o",
      "bucet@s",
      "bucet@sso",
      "bucetinh@",
      "bucetinh@s",
      "buceton@s",
      "c@cete",
      "c@chulet@",
      "c@g@lh@o",
      "c@r@i",
      "c@r@lh@",
      "c@r@lho",
      "c@r@lhudo",
      "c@ssete",
      "cequel@d@",
      "cequel@do",
      "ch@lerinh@",
      "ch@tico",
      "ch@v@sc@",
      "chechec@",
      "cherec@",
      "chimbic@",
      "chup@d@",
      "chup@dor",
      "chup@dor@",
      "chup@ndo",
      "chupet@",
      "chupetinh@",
      "cuec@o",
      "custozinh@",
      "cuz@o",
      "d@deir@",
      "progr@m@",
      "encox@d@",
      "enr@b@d@s",
      "put@",
      "fornic@d@",
      "furustrec@",
      "gostozud@s",
      "goz@d@",
      "goz@d@s",
      "grelud@s",
      "gulosinh@",
      "k@tch@ng@",
      "l@bios de feme@",
      "l@rgo do bil@u",
      "lix@-pic@",
      "mede-rol@",
      "meg@sex",
      "mel@-pentelho",
      "melec@",
      "melequinh@",
      "men@ge",
      "men@ges",
      "merd@",
      "merd@o",
      "mij@d@",
      "rol@",
      "ot@rio",
      "p@u",
      "p@p@-duro",
      "p@usud@s",
      "pecherec@",
      "peid@o",
      "peitud@",
      "peitud@s",
      "periquit@",
      "pic@",
      "pir@nhud@",
      "piroc@",
      "piroc@o",
      "piroc@s",
      "pitbitoc@",
      "pitchbich@",
      "pitchbitoc@",
      "pithbich@",
      "pithbitoc@",
      "pitibich@",
      "pitric@",
      "pixot@",
      "porr@",
      "prenchec@",
      "prexec@",
      "priquit@",
      "punhet@",
      "put@ri@",
      "put@s",
      "putinh@",
      "queng@",
      "r@bud@",
      "r@bud@s",
      "r@meir@",
      "r@p@rig@",
      "ret@rd@do",
      "s@f@d@",
      "s@f@d@s",
      "s@f@do",
      "s@f@dos",
      "sequel@d@",
      "sexg@t@s",
      "siric@",
      "siriric@",
      "sotr@vesti",
      "surub@",
      "surub@s",
      "t@iob@",
      "t@r@d@",
      "tch@c@",
      "tchec@",
      "tchong@",
      "tchuchuc@",
      "tchutchuc@",
      "tesud@",
      "tesud@s",
      "tetinh@",
      "tez@o",
      "tezud@",
      "tg@t@s",
      "tobinh@",
      "tomb@-m@cho",
      "tr@ns@",
      "tr@ns@ndo",
      "tr@vec@s",
      "trep@d@",
      "trep@d@s",
      "v@cil@o",
      "v@dj@in@",
      "v@dia",
      "v@g@bund@",
      "v@g@bundo",
      "v@ginismo",
      "v@joc@",
      "vei@c@",
      "vei@co",
      "vi@dinho",
      "vi@do",
      "x@b@sc@",
      "x@n@",
      "x@ninh@",
      "x@tico",
      "x@v@sc@",
      "xebrec@",
      "xerec@",
      "xexec@",
      "xoroc@",
      "xot@",
      "xotinh@",
      "xoxot@",
      "xoxot@s",
      "xoxotinh@",
      "xulip@",
      "xumbreg@",
      "xup@xot@",
      "xupet@",
      "xupetinh@"
    ];

    // estado de carregamento do leo
    let _leoLoaded = false;
    let _leoLoadPromise = null;

    // ---------- helper para injetar script ----------
    function _tryScriptInject(src, { async = true, crossorigin = false } = {}) {
      return new Promise((resolve) => {
        try {
          const existing = Array.from(document.getElementsByTagName('script'))
            .find(s => s.src && s.src.indexOf(src) !== -1);
          if (existing) {
            existing.onload = () => resolve(window.leoProfanity || null);
            existing.onerror = () => resolve(null);
            if (typeof window.leoProfanity !== 'undefined') return resolve(window.leoProfanity);
            return;
          }

          const s = document.createElement('script');
          s.src = src;
          s.async = async;
          if (crossorigin) s.crossOrigin = 'anonymous';
          s.onload = () => resolve(window.leoProfanity || null);
          s.onerror = () => resolve(null);
          document.head.appendChild(s);
        } catch (e) {
          resolve(null);
        }
      });
    }

    // ---------- loader simples e seguro (SEM import) ----------
    async function safeLoadLeo() {
      if (_leoLoadPromise) return _leoLoadPromise;
      _leoLoadPromise = (async () => {
        if (typeof window.leoProfanity !== 'undefined') {
          _leoLoaded = true;
          return window.leoProfanity;
        }

        // local file
        try {
          const local = await _tryScriptInject('/js/leo-profanity.min.js', { async: true });
          if (local) {
            try { local.loadDictionary && local.loadDictionary(); } catch(e){}
            _leoLoaded = true;
            return local;
          }
        } catch (e) { /* ignore */ }

        // CDN fallback
        try {
          const cdn = await _tryScriptInject('https://cdn.jsdelivr.net/npm/leo-profanity@1.12.0/lib/leo-profanity.min.js', { async: true, crossorigin: true });
          if (cdn) {
            try { cdn.loadDictionary && cdn.loadDictionary(); } catch(e){}
            _leoLoaded = true;
            return cdn;
          }
        } catch (e) { /* ignore */ }

        _leoLoaded = false;
        return null;
      })();
      return _leoLoadPromise;
    }

    // ---------- init ----------
    async function init(opts = {}) {
      csrfToken = opts.csrfToken || csrfToken;
      endpoint = opts.endpoint || endpoint;
      debounceMs = typeof opts.debounceMs === 'number' ? opts.debounceMs : debounceMs;
      
      // Se customWords forem passadas no init, ADICIONA às padrão (não substitui)
      if (Array.isArray(opts.customWords) && opts.customWords.length) {
        customWords = [...new Set([...customWords, ...opts.customWords])];
      }

      const lp = await safeLoadLeo();

      if (lp && Array.isArray(customWords) && customWords.length) {
        try { lp.add(customWords); } catch (e) { /* ignore */ }
      }

      return { ok: true, leo: !!lp, customWordsCount: customWords.length };
    }

    // ---------- normalização / heurísticas ----------
    function removeDiacritics(str) {
      try {
        return str.normalize ? str.normalize('NFD').replace(/[\u0300-\u036f]/g, '') : str;
      } catch (e) {
        return str;
      }
    }

    function collapseRepeats(str) {
      return str.replace(/(.)\1{2,}/g, '$1');
    }

    function applyLeetMap(str) {
      const map = {
        '4': 'a', '@': 'a', '8': 'b', '3': 'e', '€': 'e', '1': 'i', '!': 'i', '0': 'o',
        '9': 'g', '5': 's', '$': 's', '7': 't', '+': 't', '2': 'z', '6': 'g', '#': 'h'
      };
      return str.replace(/[^]/g, function(ch) {
        const lower = ch.toLowerCase();
        return map[lower] || ch;
      });
    }

    function escapeRegExp(s) {
      return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // CORRIGIDO: Regex agora usa word boundaries para evitar falsos positivos
    function looseRegexFromWord(word) {
      const parts = Array.from(word).map(ch => escapeRegExp(ch));
      // Adiciona word boundary no início e fim
      return new RegExp('\\b' + parts.join('[^a-z0-9]*') + '\\b', 'i');
    }

    function _tokenize(text) {
      return (text || '').split(/\s+/).map(t => t.replace(/[^\wÀ-ÿ]/g,'').toLowerCase()).filter(Boolean);
    }

    function buildNormalizedDict() {
      let dict = [];
      try {
        if (typeof window.leoProfanity !== 'undefined' && window.leoProfanity.list) {
          dict = (window.leoProfanity.list() || []).slice();
        }
      } catch (e) {
        dict = [];
      }
      if (Array.isArray(customWords) && customWords.length) {
        dict = dict.concat(customWords);
      }
      const norm = Array.from(new Set((dict || []).map(w => {
        let s = (w || '').toString().toLowerCase();
        s = removeDiacritics(s);
        s = applyLeetMap(s);
        s = s.replace(/[^a-z0-9]+/g,'');
        s = collapseRepeats(s);
        return s;
      }).filter(Boolean)))
      // CORRIGIDO: Filtra palavras muito curtas que causam falsos positivos
      .filter(token => token.length >= 3);
      return norm;
    }

    // ---------- checkLocal melhorado ----------
    function checkLocal(text) {
      const resDefault = { inappropriate: false, cleaned: text, matches: [] };
      if (!text || typeof text !== 'string') return resDefault;

      try {
        if (typeof window.leoProfanity !== 'undefined') {
          const inappropriate = !!window.leoProfanity.check(text);
          let cleaned = text;
          try { cleaned = window.leoProfanity.clean(text); } catch(e){}
          const dict = (window.leoProfanity.list && window.leoProfanity.list()) || [];
          const dictSet = new Set((dict || []).map(w=>w.toLowerCase()));
          const tokens = _tokenize(text);
          const matches = tokens.filter(t => dictSet.has(t));
          if (inappropriate || matches.length) {
            return { inappropriate: !!inappropriate, cleaned, matches };
          }
        }
      } catch (e) {
        // segue para heurísticas
      }

      const original = text;
      let norm = original.toLowerCase();
      norm = removeDiacritics(norm);
      const leeted = applyLeetMap(norm);
      const alnum = leeted.replace(/[^a-z0-9\s]+/g, ' '); // Mantém espaços
      const collapsed = collapseRepeats(alnum);

      const normDict = buildNormalizedDict();
      const matchesSet = new Set();

      normDict.forEach(bad => {
        if (!bad || bad.length < 3) return; // Ignora palavras muito curtas
        
        // CORRIGIDO: Usa split por espaços para buscar palavras completas
        const words = collapsed.split(/\s+/);
        
        // Verifica se a palavra ruim aparece como palavra completa
        if (words.includes(bad)) {
          matchesSet.add(bad);
          return;
        }
        
        // Para palavras maiores, verifica com regex com word boundaries
        if (bad.length >= 4) {
          const loose = looseRegexFromWord(bad);
          if (loose.test(leeted)) {
            matchesSet.add(bad);
          }
        }
      });

      const matches = Array.from(matchesSet);
      const inappropriate = matches.length > 0;

      let cleaned = original;
      try {
        if (typeof window.leoProfanity !== 'undefined' && window.leoProfanity.clean) {
          cleaned = window.leoProfanity.clean(original);
        } else {
          matches.forEach(bad => {
            const r = looseRegexFromWord(bad);
            cleaned = cleaned.replace(r, (m) => '*'.repeat(Math.max(3, Math.min(m.length, 10))));
          });
        }
      } catch (e) {
        // ignore
      }

      return { inappropriate, cleaned, matches };
    }

    // ---------- moderateServer ----------
    async function moderateServer(text) {
      if (!text) return { ok: true, data: { inappropriate: false } };
      try {
        const res = await fetch(endpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ text })
        });
        if (!res.ok) {
          const body = await res.json().catch(()=>({}));
          return { ok: false, status: res.status, body };
        }
        const data = await res.json();
        return { ok: true, data };
      } catch (err) {
        return { ok: false, status: 0, body: { message: 'network' } };
      }
    }

    // ---------- debounce util ----------
    function debounce(fn, wait) {
      let t;
      return function(...args){
        clearTimeout(t);
        t = setTimeout(()=>fn.apply(this,args), wait);
      };
    }

    // ---------- attachInput ----------
    function attachInput(selector, fieldName, callbacks = {}) {
      const el = document.querySelector(selector);
      if (!el) {
        console.warn('[Moderation] attachInput: elemento não encontrado:', selector);
        return null;
      }
      const onLocal = typeof callbacks.onLocal === 'function' ? callbacks.onLocal : ()=>{};
      const onServer = typeof callbacks.onServer === 'function' ? callbacks.onServer : ()=>{};

      const handleInput = debounce(function(e){
        const v = e.target.value || '';
        const local = checkLocal(v);
        el.dispatchEvent(new CustomEvent('moderation:local', { detail: Object.assign({ field: fieldName }, local) }));
        onLocal(Object.assign({ field: fieldName }, local));
      }, debounceMs);

      const handleBlur = async function(e){
        const v = e.target.value || '';
        const server = await moderateServer(v);
        el.dispatchEvent(new CustomEvent('moderation:server', { detail: Object.assign({ field: fieldName }, server) }));
        onServer(Object.assign({ field: fieldName }, server));
      };

      el.addEventListener('input', handleInput);
      el.addEventListener('blur', handleBlur);

      return {
        destroy() {
          el.removeEventListener('input', handleInput);
          el.removeEventListener('blur', handleBlur);
        },
        element: el
      };
    }

    // ---------- attachFormSubmit ----------
    function attachFormSubmit(formSelector, fields = []) {
      const form = document.querySelector(formSelector);
      if (!form) {
        console.warn('[Moderation] attachFormSubmit: form não encontrado:', formSelector);
        return null;
      }
      const handler = async function(e){
        // Prevenir submit padrão temporariamente
        e.preventDefault();
        
        // Fazer checagem local primeiro (mais rápida)
        let hasLocalIssues = false;
        const localResults = [];
        
        fields.forEach(f => {
          const el = document.querySelector(f.selector);
          const text = el ? el.value || '' : '';
          const localCheck = checkLocal(text);
          
          if (localCheck.inappropriate) {
            hasLocalIssues = true;
            localResults.push({ field: f.fieldName, result: localCheck });
          }
        });
        
        // Se detectou problema localmente, bloquear
        if (hasLocalIssues) {
          form.dispatchEvent(new CustomEvent('moderation:blocked', { detail: localResults }));
          return false;
        }
        
        // Se passou na checagem local, verificar no servidor
        const checks = fields.map(f => {
          const el = document.querySelector(f.selector);
          const text = el ? el.value || '' : '';
          return moderateServer(text).then(r => ({ field: f.fieldName, result: r }));
        });
        
        const results = await Promise.all(checks);
        const blocked = results.some(r => {
          if (!r.result.ok) return false; // Erro de rede não bloqueia
          return r.result.data && r.result.data.inappropriate;
        });
        
        if (blocked) {
          form.dispatchEvent(new CustomEvent('moderation:blocked', { detail: results }));
          return false;
        }
        
        // Tudo OK, submeter o formulário
        form.dispatchEvent(new CustomEvent('moderation:ok', { detail: results }));
        form.submit(); // Submit real
        return true;
      };
      form.addEventListener('submit', handler);
      return { destroy() { form.removeEventListener('submit', handler); } };
    }

    // ---------- expõe API ----------
    return {
      init,
      checkLocal,
      moderateServer,
      attachInput,
      attachFormSubmit,
      ensureLeo: safeLoadLeo,
      _debug: () => ({ leoLoaded: _leoLoaded, customWords: customWords.slice() })
    };
  })();

  global.Moderation = Moderation;
})(window);