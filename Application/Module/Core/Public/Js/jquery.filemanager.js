/*!
 * jQuery Form Plugin
 * version: 3.51.0-2014.06.20
 * Requires jQuery v1.5 or later
 * Copyright (c) 2014 M. Alsup
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Project repository: https://github.com/malsup/form
 * Dual licensed under the MIT and GPL licenses.
 * https://github.com/malsup/form#copyright-and-license
 */
!function(e){"use strict"; "function" == typeof define && define.amd?define(["jquery"], e):e("undefined" != typeof jQuery?jQuery:window.Zepto)}(function(e){"use strict"; function t(t){var r = t.data; t.isDefaultPrevented() || (t.preventDefault(), e(t.target).ajaxSubmit(r))}function r(t){var r = t.target, a = e(r); if (!a.is("[type=submit],[type=image]")){var n = a.closest("[type=submit]"); if (0 === n.length)return; r = n[0]}var i = this; if (i.clk = r, "image" == r.type)if (void 0 !== t.offsetX)i.clk_x = t.offsetX, i.clk_y = t.offsetY; else if ("function" == typeof e.fn.offset){var o = a.offset(); i.clk_x = t.pageX - o.left, i.clk_y = t.pageY - o.top} else i.clk_x = t.pageX - r.offsetLeft, i.clk_y = t.pageY - r.offsetTop; setTimeout(function(){i.clk = i.clk_x = i.clk_y = null}, 100)}function a(){if (e.fn.ajaxSubmit.debug){var t = "[jquery.form] " + Array.prototype.join.call(arguments, ""); window.console && window.console.log?window.console.log(t):window.opera && window.opera.postError && window.opera.postError(t)}}var n = {}; n.fileapi = void 0 !== e("<input type='file'/>").get(0).files, n.formdata = void 0 !== window.FormData; var i = !!e.fn.prop; e.fn.attr2 = function(){if (!i)return this.attr.apply(this, arguments); var e = this.prop.apply(this, arguments); return e && e.jquery || "string" == typeof e?e:this.attr.apply(this, arguments)}, e.fn.ajaxSubmit = function(t){function r(r){var a, n, i = e.param(r, t.traditional).split("&"), o = i.length, s = []; for (a = 0; o > a; a++)i[a] = i[a].replace(/\+/g, " "), n = i[a].split("="), s.push([decodeURIComponent(n[0]), decodeURIComponent(n[1])]); return s}function o(a){for (var n = new FormData, i = 0; i < a.length; i++)n.append(a[i].name, a[i].value); if (t.extraData){var o = r(t.extraData); for (i = 0; i < o.length; i++)o[i] && n.append(o[i][0], o[i][1])}t.data = null; var s = e.extend(!0, {}, e.ajaxSettings, t, {contentType:!1, processData:!1, cache:!1, type:u || "POST"}); t.uploadProgress && (s.xhr = function(){var r = e.ajaxSettings.xhr(); return r.upload && r.upload.addEventListener("progress", function(e){var r = 0, a = e.loaded || e.position, n = e.total; e.lengthComputable && (r = Math.ceil(a / n * 100)), t.uploadProgress(e, a, n, r)}, !1), r}), s.data = null; var c = s.beforeSend; return s.beforeSend = function(e, r){r.data = t.formData?t.formData:n, c && c.call(this, e, r)}, e.ajax(s)}function s(r){function n(e){var t = null; try{e.contentWindow && (t = e.contentWindow.document)} catch (r){a("cannot get iframe.contentWindow document: " + r)}if (t)return t; try{t = e.contentDocument?e.contentDocument:e.document} catch (r){a("cannot get iframe.contentDocument: " + r), t = e.document}return t}function o(){function t(){try{var e = n(g).readyState; a("state = " + e), e && "uninitialized" == e.toLowerCase() && setTimeout(t, 50)} catch (r){a("Server abort: ", r, " (", r.name, ")"), s(k), j && clearTimeout(j), j = void 0}}var r = f.attr2("target"), i = f.attr2("action"), o = "multipart/form-data", c = f.attr("enctype") || f.attr("encoding") || o; w.setAttribute("target", p), (!u || /post/i.test(u)) && w.setAttribute("method", "POST"), i != m.url && w.setAttribute("action", m.url), m.skipEncodingOverride || u && !/post/i.test(u) || f.attr({encoding:"multipart/form-data", enctype:"multipart/form-data"}), m.timeout && (j = setTimeout(function(){T = !0, s(D)}, m.timeout)); var l = []; try{if (m.extraData)for (var d in m.extraData)m.extraData.hasOwnProperty(d) && l.push(e.isPlainObject(m.extraData[d]) && m.extraData[d].hasOwnProperty("name") && m.extraData[d].hasOwnProperty("value")?e('<input type="hidden" name="' + m.extraData[d].name + '">').val(m.extraData[d].value).appendTo(w)[0]:e('<input type="hidden" name="' + d + '">').val(m.extraData[d]).appendTo(w)[0]); m.iframeTarget || v.appendTo("body"), g.attachEvent?g.attachEvent("onload", s):g.addEventListener("load", s, !1), setTimeout(t, 15); try{w.submit()} catch (h){var x = document.createElement("form").submit; x.apply(w)}} finally{w.setAttribute("action", i), w.setAttribute("enctype", c), r?w.setAttribute("target", r):f.removeAttr("target"), e(l).remove()}}function s(t){if (!x.aborted && !F){if (M = n(g), M || (a("cannot access response document"), t = k), t === D && x)return x.abort("timeout"), void S.reject(x, "timeout"); if (t == k && x)return x.abort("server abort"), void S.reject(x, "error", "server abort"); if (M && M.location.href != m.iframeSrc || T){g.detachEvent?g.detachEvent("onload", s):g.removeEventListener("load", s, !1); var r, i = "success"; try{if (T)throw"timeout"; var o = "xml" == m.dataType || M.XMLDocument || e.isXMLDoc(M); if (a("isXml=" + o), !o && window.opera && (null === M.body || !M.body.innerHTML) && --O)return a("requeing onLoad callback, DOM not available"), void setTimeout(s, 250); var u = M.body?M.body:M.documentElement; x.responseText = u?u.innerHTML:null, x.responseXML = M.XMLDocument?M.XMLDocument:M, o && (m.dataType = "xml"), x.getResponseHeader = function(e){var t = {"content-type":m.dataType}; return t[e.toLowerCase()]}, u && (x.status = Number(u.getAttribute("status")) || x.status, x.statusText = u.getAttribute("statusText") || x.statusText); var c = (m.dataType || "").toLowerCase(), l = /(json|script|text)/.test(c); if (l || m.textarea){var f = M.getElementsByTagName("textarea")[0]; if (f)x.responseText = f.value, x.status = Number(f.getAttribute("status")) || x.status, x.statusText = f.getAttribute("statusText") || x.statusText; else if (l){var p = M.getElementsByTagName("pre")[0], h = M.getElementsByTagName("body")[0]; p?x.responseText = p.textContent?p.textContent:p.innerText:h && (x.responseText = h.textContent?h.textContent:h.innerText)}} else"xml" == c && !x.responseXML && x.responseText && (x.responseXML = X(x.responseText)); try{E = _(x, c, m)} catch (y){i = "parsererror", x.error = r = y || i}} catch (y){a("error caught: ", y), i = "error", x.error = r = y || i}x.aborted && (a("upload aborted"), i = null), x.status && (i = x.status >= 200 && x.status < 300 || 304 === x.status?"success":"error"), "success" === i?(m.success && m.success.call(m.context, E, "success", x), S.resolve(x.responseText, "success", x), d && e.event.trigger("ajaxSuccess", [x, m])):i && (void 0 === r && (r = x.statusText), m.error && m.error.call(m.context, x, i, r), S.reject(x, "error", r), d && e.event.trigger("ajaxError", [x, m, r])), d && e.event.trigger("ajaxComplete", [x, m]), d && !--e.active && e.event.trigger("ajaxStop"), m.complete && m.complete.call(m.context, x, i), F = !0, m.timeout && clearTimeout(j), setTimeout(function(){m.iframeTarget?v.attr("src", m.iframeSrc):v.remove(), x.responseXML = null}, 100)}}}var c, l, m, d, p, v, g, x, y, b, T, j, w = f[0], S = e.Deferred(); if (S.abort = function(e){x.abort(e)}, r)for (l = 0; l < h.length; l++)c = e(h[l]), i?c.prop("disabled", !1):c.removeAttr("disabled"); if (m = e.extend(!0, {}, e.ajaxSettings, t), m.context = m.context || m, p = "jqFormIO" + (new Date).getTime(), m.iframeTarget?(v = e(m.iframeTarget), b = v.attr2("name"), b?p = b:v.attr2("name", p)):(v = e('<iframe name="' + p + '" src="' + m.iframeSrc + '" />'), v.css({position:"absolute", top:"-1000px", left:"-1000px"})), g = v[0], x = {aborted:0, responseText:null, responseXML:null, status:0, statusText:"n/a", getAllResponseHeaders:function(){}, getResponseHeader:function(){}, setRequestHeader:function(){}, abort:function(t){var r = "timeout" === t?"timeout":"aborted"; a("aborting upload... " + r), this.aborted = 1; try{g.contentWindow.document.execCommand && g.contentWindow.document.execCommand("Stop")} catch (n){}v.attr("src", m.iframeSrc), x.error = r, m.error && m.error.call(m.context, x, r, t), d && e.event.trigger("ajaxError", [x, m, r]), m.complete && m.complete.call(m.context, x, r)}}, d = m.global, d && 0 === e.active++ && e.event.trigger("ajaxStart"), d && e.event.trigger("ajaxSend", [x, m]), m.beforeSend && m.beforeSend.call(m.context, x, m) === !1)return m.global && e.active--, S.reject(), S; if (x.aborted)return S.reject(), S; y = w.clk, y && (b = y.name, b && !y.disabled && (m.extraData = m.extraData || {}, m.extraData[b] = y.value, "image" == y.type && (m.extraData[b + ".x"] = w.clk_x, m.extraData[b + ".y"] = w.clk_y))); var D = 1, k = 2, A = e("meta[name=csrf-token]").attr("content"), L = e("meta[name=csrf-param]").attr("content"); L && A && (m.extraData = m.extraData || {}, m.extraData[L] = A), m.forceSync?o():setTimeout(o, 10); var E, M, F, O = 50, X = e.parseXML || function(e, t){return window.ActiveXObject?(t = new ActiveXObject("Microsoft.XMLDOM"), t.async = "false", t.loadXML(e)):t = (new DOMParser).parseFromString(e, "text/xml"), t && t.documentElement && "parsererror" != t.documentElement.nodeName?t:null}, C = e.parseJSON || function(e){return window.eval("(" + e + ")")}, _ = function(t, r, a){var n = t.getResponseHeader("content-type") || "", i = "xml" === r || !r && n.indexOf("xml") >= 0, o = i?t.responseXML:t.responseText; return i && "parsererror" === o.documentElement.nodeName && e.error && e.error("parsererror"), a && a.dataFilter && (o = a.dataFilter(o, r)), "string" == typeof o && ("json" === r || !r && n.indexOf("json") >= 0?o = C(o):("script" === r || !r && n.indexOf("javascript") >= 0) && e.globalEval(o)), o}; return S}if (!this.length)return a("ajaxSubmit: skipping submit process - no element selected"), this; var u, c, l, f = this; "function" == typeof t?t = {success:t}:void 0 === t && (t = {}), u = t.type || this.attr2("method"), c = t.url || this.attr2("action"), l = "string" == typeof c?e.trim(c):"", l = l || window.location.href || "", l && (l = (l.match(/^([^#]+)/) || [])[1]), t = e.extend(!0, {url:l, success:e.ajaxSettings.success, type:u || e.ajaxSettings.type, iframeSrc:/^https/i.test(window.location.href || "")?"javascript:false":"about:blank"}, t); var m = {}; if (this.trigger("form-pre-serialize", [this, t, m]), m.veto)return a("ajaxSubmit: submit vetoed via form-pre-serialize trigger"), this; if (t.beforeSerialize && t.beforeSerialize(this, t) === !1)return a("ajaxSubmit: submit aborted via beforeSerialize callback"), this; var d = t.traditional; void 0 === d && (d = e.ajaxSettings.traditional); var p, h = [], v = this.formToArray(t.semantic, h); if (t.data && (t.extraData = t.data, p = e.param(t.data, d)), t.beforeSubmit && t.beforeSubmit(v, this, t) === !1)return a("ajaxSubmit: submit aborted via beforeSubmit callback"), this; if (this.trigger("form-submit-validate", [v, this, t, m]), m.veto)return a("ajaxSubmit: submit vetoed via form-submit-validate trigger"), this; var g = e.param(v, d); p && (g = g?g + "&" + p:p), "GET" == t.type.toUpperCase()?(t.url += (t.url.indexOf("?") >= 0?"&":"?") + g, t.data = null):t.data = g; var x = []; if (t.resetForm && x.push(function(){f.resetForm()}), t.clearForm && x.push(function(){f.clearForm(t.includeHidden)}), !t.dataType && t.target){var y = t.success || function(){}; x.push(function(r){var a = t.replaceTarget?"replaceWith":"html"; e(t.target)[a](r).each(y, arguments)})} else t.success && x.push(t.success); if (t.success = function(e, r, a){for (var n = t.context || this, i = 0, o = x.length; o > i; i++)x[i].apply(n, [e, r, a || f, f])}, t.error){var b = t.error; t.error = function(e, r, a){var n = t.context || this; b.apply(n, [e, r, a, f])}}if (t.complete){var T = t.complete; t.complete = function(e, r){var a = t.context || this; T.apply(a, [e, r, f])}}var j = e("input[type=file]:enabled", this).filter(function(){return"" !== e(this).val()}), w = j.length > 0, S = "multipart/form-data", D = f.attr("enctype") == S || f.attr("encoding") == S, k = n.fileapi && n.formdata; a("fileAPI :" + k); var A, L = (w || D) && !k; t.iframe !== !1 && (t.iframe || L)?t.closeKeepAlive?e.get(t.closeKeepAlive, function(){A = s(v)}):A = s(v):A = (w || D) && k?o(v):e.ajax(t), f.removeData("jqxhr").data("jqxhr", A); for (var E = 0; E < h.length; E++)h[E] = null; return this.trigger("form-submit-notify", [this, t]), this}, e.fn.ajaxForm = function(n){if (n = n || {}, n.delegation = n.delegation && e.isFunction(e.fn.on), !n.delegation && 0 === this.length){var i = {s:this.selector, c:this.context}; return!e.isReady && i.s?(a("DOM not ready, queuing ajaxForm"), e(function(){e(i.s, i.c).ajaxForm(n)}), this):(a("terminating; zero elements found by selector" + (e.isReady?"":" (DOM not ready)")), this)}return n.delegation?(e(document).off("submit.form-plugin", this.selector, t).off("click.form-plugin", this.selector, r).on("submit.form-plugin", this.selector, n, t).on("click.form-plugin", this.selector, n, r), this):this.ajaxFormUnbind().bind("submit.form-plugin", n, t).bind("click.form-plugin", n, r)}, e.fn.ajaxFormUnbind = function(){return this.unbind("submit.form-plugin click.form-plugin")}, e.fn.formToArray = function(t, r){var a = []; if (0 === this.length)return a; var i, o = this[0], s = this.attr("id"), u = t?o.getElementsByTagName("*"):o.elements; if (u && !/MSIE [678]/.test(navigator.userAgent) && (u = e(u).get()), s && (i = e(':input[form="' + s + '"]').get(), i.length && (u = (u || []).concat(i))), !u || !u.length)return a; var c, l, f, m, d, p, h; for (c = 0, p = u.length; p > c; c++)if (d = u[c], f = d.name, f && !d.disabled)if (t && o.clk && "image" == d.type)o.clk == d && (a.push({name:f, value:e(d).val(), type:d.type}), a.push({name:f + ".x", value:o.clk_x}, {name:f + ".y", value:o.clk_y})); else if (m = e.fieldValue(d, !0), m && m.constructor == Array)for (r && r.push(d), l = 0, h = m.length; h > l; l++)a.push({name:f, value:m[l]}); else if (n.fileapi && "file" == d.type){r && r.push(d); var v = d.files; if (v.length)for (l = 0; l < v.length; l++)a.push({name:f, value:v[l], type:d.type}); else a.push({name:f, value:"", type:d.type})} else null !== m && "undefined" != typeof m && (r && r.push(d), a.push({name:f, value:m, type:d.type, required:d.required})); if (!t && o.clk){var g = e(o.clk), x = g[0]; f = x.name, f && !x.disabled && "image" == x.type && (a.push({name:f, value:g.val()}), a.push({name:f + ".x", value:o.clk_x}, {name:f + ".y", value:o.clk_y}))}return a}, e.fn.formSerialize = function(t){return e.param(this.formToArray(t))}, e.fn.fieldSerialize = function(t){var r = []; return this.each(function(){var a = this.name; if (a){var n = e.fieldValue(this, t); if (n && n.constructor == Array)for (var i = 0, o = n.length; o > i; i++)r.push({name:a, value:n[i]}); else null !== n && "undefined" != typeof n && r.push({name:this.name, value:n})}}), e.param(r)}, e.fn.fieldValue = function(t){for (var r = [], a = 0, n = this.length; n > a; a++){var i = this[a], o = e.fieldValue(i, t); null === o || "undefined" == typeof o || o.constructor == Array && !o.length || (o.constructor == Array?e.merge(r, o):r.push(o))}return r}, e.fieldValue = function(t, r){var a = t.name, n = t.type, i = t.tagName.toLowerCase(); if (void 0 === r && (r = !0), r && (!a || t.disabled || "reset" == n || "button" == n || ("checkbox" == n || "radio" == n) && !t.checked || ("submit" == n || "image" == n) && t.form && t.form.clk != t || "select" == i && - 1 == t.selectedIndex))return null; if ("select" == i){var o = t.selectedIndex; if (0 > o)return null; for (var s = [], u = t.options, c = "select-one" == n, l = c?o + 1:u.length, f = c?o:0; l > f; f++){var m = u[f]; if (m.selected){var d = m.value; if (d || (d = m.attributes && m.attributes.value && !m.attributes.value.specified?m.text:m.value), c)return d; s.push(d)}}return s}return e(t).val()}, e.fn.clearForm = function(t){return this.each(function(){e("input,select,textarea", this).clearFields(t)})}, e.fn.clearFields = e.fn.clearInputs = function(t){var r = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i; return this.each(function(){var a = this.type, n = this.tagName.toLowerCase(); r.test(a) || "textarea" == n?this.value = "":"checkbox" == a || "radio" == a?this.checked = !1:"select" == n?this.selectedIndex = - 1:"file" == a?/MSIE/.test(navigator.userAgent)?e(this).replaceWith(e(this).clone(!0)):e(this).val(""):t && (t === !0 && /hidden/.test(a) || "string" == typeof t && e(this).is(t)) && (this.value = "")})}, e.fn.resetForm = function(){return this.each(function(){("function" == typeof this.reset || "object" == typeof this.reset && !this.reset.nodeType) && this.reset()})}, e.fn.enable = function(e){return void 0 === e && (e = !0), this.each(function(){this.disabled = !e})}, e.fn.selected = function(t){return void 0 === t && (t = !0), this.each(function(){var r = this.type; if ("checkbox" == r || "radio" == r)this.checked = t; else if ("option" == this.tagName.toLowerCase()){var a = e(this).parent("select"); t && a[0] && "select-one" == a[0].type && a.find("option").selected(!1), this.selected = t}})}, e.fn.ajaxSubmit.debug = !1});
        /*!
         * jQuery Upload File Plugin
         * version: 4.0.10
         * @requires jQuery v1.5 or later & form plugin
         * Copyright (c) 2013 Ravishanker Kusuma
         * http://hayageek.com/
         */
        !function(e){void 0 == e.fn.ajaxForm && e.getScript(("https:" == document.location.protocol?"https://":"http://") + "malsup.github.io/jquery.form.js"); var a = {}; a.fileapi = void 0 !== e("<input type='file'/>").get(0).files, a.formdata = void 0 !== window.FormData, e.fn.uploadFile = function(t){function r(){S || (S = !0, function e(){if (w.sequential || (w.sequentialCount = 99999), 0 == x.length && 0 == D.length)w.afterUploadAll && w.afterUploadAll(C), S = !1; else{if (D.length < w.sequentialCount){var a = x.shift(); void 0 != a && (D.push(a), a.removeClass(C.formGroup), a.submit())}window.setTimeout(e, 100)}}())}function o(a, t, r){r.on("dragenter", function(a){a.stopPropagation(), a.preventDefault(), e(this).addClass(t.dragDropHoverClass)}), r.on("dragover", function(a){a.stopPropagation(), a.preventDefault(); var r = e(this); r.hasClass(t.dragDropContainerClass) && !r.hasClass(t.dragDropHoverClass) && r.addClass(t.dragDropHoverClass)}), r.on("drop", function(r){r.preventDefault(), e(this).removeClass(t.dragDropHoverClass), a.errorLog.html(""); var o = r.originalEvent.dataTransfer.files; return!t.multiple && o.length > 1?void(t.showError && e("<div class='" + t.errorClass + "'>" + t.multiDragErrorStr + "</div>").appendTo(a.errorLog)):void(0 != t.onSelect(o) && l(t, a, o))}), r.on("dragleave", function(a){e(this).removeClass(t.dragDropHoverClass)}), e(document).on("dragenter", function(e){e.stopPropagation(), e.preventDefault()}), e(document).on("dragover", function(a){a.stopPropagation(), a.preventDefault(); var r = e(this); r.hasClass(t.dragDropContainerClass) || r.removeClass(t.dragDropHoverClass)}), e(document).on("drop", function(a){a.stopPropagation(), a.preventDefault(), e(this).removeClass(t.dragDropHoverClass)})}function s(e){var a = "", t = e / 1024; if (parseInt(t) > 1024){var r = t / 1024; a = r.toFixed(2) + " MB"} else a = t.toFixed(2) + " KB"; return a}function i(a){var t = []; t = "string" == jQuery.type(a)?a.split("&"):e.param(a).split("&"); var r, o, s = t.length, i = []; for (r = 0; s > r; r++)t[r] = t[r].replace(/\+/g, " "), o = t[r].split("="), i.push([decodeURIComponent(o[0]), decodeURIComponent(o[1])]); return i}function l(a, t, r){for (var o = 0; o < r.length; o++)if (n(t, a, r[o].name))if (a.allowDuplicates || !d(t, r[o].name))if ( - 1 != a.maxFileSize && r[o].size > a.maxFileSize)a.showError && e("<div class='" + a.errorClass + "'><b>" + r[o].name + "</b> " + a.sizeErrorStr + s(a.maxFileSize) + "</div>").appendTo(t.errorLog); else if ( - 1 != a.maxFileCount && t.selectedFiles >= a.maxFileCount)a.showError && e("<div class='" + a.errorClass + "'><b>" + r[o].name + "</b> " + a.maxFileCountErrorStr + a.maxFileCount + "</div>").appendTo(t.errorLog); else{t.selectedFiles++, t.existingFileNames.push(r[o].name); var l = a, p = new FormData, u = a.fileName.replace("[]", ""); p.append(u, r[o]); var c = a.formData; if (c)for (var h = i(c), f = 0; f < h.length; f++)h[f] && p.append(h[f][0], h[f][1]); l.fileData = p; var w = new m(t, a), g = ""; g = a.showFileCounter?t.fileCounter + a.fileCounterStyle + r[o].name:r[o].name, a.showFileSize && (g += " (" + s(r[o].size) + ")"), w.filename.html(g); var C = e("<form style='display:block; position:absolute;left: 150px;' class='" + t.formGroup + "' method='" + a.method + "' action='" + a.url + "' enctype='" + a.enctype + "'></form>"); C.appendTo("body"); var b = []; b.push(r[o].name), v(C, l, w, b, t, r[o]), t.fileCounter++} else a.showError && e("<div class='" + a.errorClass + "'><b>" + r[o].name + "</b> " + a.duplicateErrorStr + "</div>").appendTo(t.errorLog); else a.showError && e("<div class='" + a.errorClass + "'><b>" + r[o].name + "</b> " + a.extErrorStr + a.allowedTypes + "</div>").appendTo(t.errorLog)}function n(e, a, t){var r = a.allowedTypes.toLowerCase().split(/[\s,]+/g), o = t.split(".").pop().toLowerCase(); return"*" != a.allowedTypes && jQuery.inArray(o, r) < 0?!1:!0}function d(e, a){var t = !1; if (e.existingFileNames.length)for (var r = 0; r < e.existingFileNames.length; r++)(e.existingFileNames[r] == a || w.duplicateStrict && e.existingFileNames[r].toLowerCase() == a.toLowerCase()) && (t = !0); return t}function p(e, a){if (e.existingFileNames.length)for (var t = 0; t < a.length; t++){var r = e.existingFileNames.indexOf(a[t]); - 1 != r && e.existingFileNames.splice(r, 1)}}function u(e, a){if (e){a.show(); var t = new FileReader; t.onload = function(e){a.attr("src", e.target.result)}, t.readAsDataURL(e)}}function c(a, t){if (a.showFileCounter){var r = e(t.container).find(".ajax-file-upload-filename").length; t.fileCounter = r + 1, e(t.container).find(".ajax-file-upload-filename").each(function(t, o){var s = e(this).html().split(a.fileCounterStyle), i = (parseInt(s[0]) - 1, r + a.fileCounterStyle + s[1]); e(this).html(i), r--})}}function h(t, r, o, s){var i = "ajax-upload-id-" + (new Date).getTime(), d = e("<form method='" + o.method + "' action='" + o.url + "' enctype='" + o.enctype + "'></form>"), p = "<input type='file' id='" + i + "' name='" + o.fileName + "' accept='" + o.acceptFiles + "'/>"; o.multiple && (o.fileName.indexOf("[]") != o.fileName.length - 2 && (o.fileName += "[]"), p = "<input type='file' id='" + i + "' name='" + o.fileName + "' accept='" + o.acceptFiles + "' multiple/>"); var u = e(p).appendTo(d); u.change(function(){t.errorLog.html(""); var i = (o.allowedTypes.toLowerCase().split(","), []); if (this.files){for (g = 0; g < this.files.length; g++)i.push(this.files[g].name); if (0 == o.onSelect(this.files))return} else{var p = e(this).val(), u = []; if (i.push(p), !n(t, o, p))return void(o.showError && e("<div class='" + o.errorClass + "'><b>" + p + "</b> " + o.extErrorStr + o.allowedTypes + "</div>").appendTo(t.errorLog)); if (u.push({name:p, size:"NA"}), 0 == o.onSelect(u))return}if (c(o, t), s.unbind("click"), d.hide(), h(t, r, o, s), d.addClass(r), o.serialize && a.fileapi && a.formdata){d.removeClass(r); var f = this.files; d.remove(), l(o, t, f)} else{for (var w = "", g = 0; g < i.length; g++)w += o.showFileCounter?t.fileCounter + o.fileCounterStyle + i[g] + "<br>":i[g] + "<br>", t.fileCounter++; if ( - 1 != o.maxFileCount && t.selectedFiles + i.length > o.maxFileCount)return void(o.showError && e("<div class='" + o.errorClass + "'><b>" + w + "</b> " + o.maxFileCountErrorStr + o.maxFileCount + "</div>").appendTo(t.errorLog)); t.selectedFiles += i.length; var C = new m(t, o); C.filename.html(w), v(d, o, C, i, t, null)}}), o.nestedForms?(d.css({margin:0, padding:0}), s.css({position:"relative", overflow:"hidden", cursor:"default"}), u.css({position:"absolute", cursor:"pointer", top:"0px", width:"100%", height:"100%", left:"0px", "z-index":"100", opacity:"0.0", filter:"alpha(opacity=0)", "-ms-filter":"alpha(opacity=0)", "-khtml-opacity":"0.0", "-moz-opacity":"0.0"}), d.appendTo(s)):(d.appendTo(e("body")), d.css({margin:0, padding:0, display:"block", position:"absolute", left:"-250px"}), - 1 != navigator.appVersion.indexOf("MSIE ")?s.attr("for", i):s.click(function(){u.click()}))}function f(a, t){return this.statusbar = e("<div class='ajax-file-upload-statusbar'></div>").width(t.statusBarWidth), this.preview = e("<img class='ajax-file-upload-preview' />").width(t.previewWidth).height(t.previewHeight).appendTo(this.statusbar).hide(), this.filename = e("<div class='ajax-file-upload-filename'></div>").appendTo(this.statusbar), this.progressDiv = e("<div class='ajax-file-upload-progress'>").appendTo(this.statusbar).hide(), this.progressbar = e("<div class='ajax-file-upload-bar'></div>").appendTo(this.progressDiv), this.abort = e("<div>" + t.abortStr + "</div>").appendTo(this.statusbar).hide(), this.cancel = e("<div>" + t.cancelStr + "</div>").appendTo(this.statusbar).hide(), this.done = e("<div>" + t.doneStr + "</div>").appendTo(this.statusbar).hide(), this.download = e("<div>" + t.downloadStr + "</div>").appendTo(this.statusbar).hide(), this.del = e("<div>" + t.deletelStr + "</div>").appendTo(this.statusbar).hide(), this.abort.addClass("ajax-file-upload-red"), this.done.addClass("ajax-file-upload-green"), this.download.addClass("ajax-file-upload-green"), this.cancel.addClass("ajax-file-upload-red"), this.del.addClass("ajax-file-upload-red"), this}function m(a, t){var r = null; return r = t.customProgressBar?new t.customProgressBar(a, t):new f(a, t), r.abort.addClass(a.formGroup), r.abort.addClass(t.abortButtonClass), r.cancel.addClass(a.formGroup), r.cancel.addClass(t.cancelButtonClass), t.extraHTML && (r.extraHTML = e("<div class='extrahtml'>" + t.extraHTML() + "</div>").insertAfter(r.filename)), "bottom" == t.uploadQueueOrder?e(a.container).append(r.statusbar):e(a.container).prepend(r.statusbar), r}function v(t, o, s, l, n, d){var h = {cache:!1, contentType:!1, processData:!1, forceSync:!1, type:o.method, data:o.formData, formData:o.fileData, dataType:o.returnType, beforeSubmit:function(a, r, d){if (0 != o.onSubmit.call(this, l)){if (o.dynamicFormData){var u = i(o.dynamicFormData()); if (u)for (var h = 0; h < u.length; h++)u[h] && (void 0 != o.fileData?d.formData.append(u[h][0], u[h][1]):d.data[u[h][0]] = u[h][1])}return o.extraHTML && e(s.extraHTML).find("input,select,textarea").each(function(a, t){void 0 != o.fileData?d.formData.append(e(this).attr("name"), e(this).val()):d.data[e(this).attr("name")] = e(this).val()}), !0}return s.statusbar.append("<div class='" + o.errorClass + "'>" + o.uploadErrorStr + "</div>"), s.cancel.show(), t.remove(), s.cancel.click(function(){x.splice(x.indexOf(t), 1), p(n, l), s.statusbar.remove(), o.onCancel.call(n, l, s), n.selectedFiles -= l.length, c(o, n)}), !1}, beforeSend:function(e, t){s.progressDiv.show(), s.cancel.hide(), s.done.hide(), o.showAbort && (s.abort.show(), s.abort.click(function(){p(n, l), e.abort(), n.selectedFiles -= l.length, o.onAbort.call(n, l, s)})), a.formdata?s.progressbar.width("1%"):s.progressbar.width("5%")}, uploadProgress:function(e, a, t, r){r > 98 && (r = 98); var i = r + "%"; r > 1 && s.progressbar.width(i), o.showProgress && (s.progressbar.html(i), s.progressbar.css("text-align", "center"))}, success:function(a, r, i){if (s.cancel.remove(), D.pop(), "json" == o.returnType && "object" == e.type(a) && a.hasOwnProperty(o.customErrorKeyStr)){s.abort.hide(); var d = a[o.customErrorKeyStr]; return o.onError.call(this, l, 200, d, s), o.showStatusAfterError?(s.progressDiv.hide(), s.statusbar.append("<span class='" + o.errorClass + "'>ERROR: " + d + "</span>")):(s.statusbar.hide(), s.statusbar.remove()), n.selectedFiles -= l.length, void t.remove()}n.responses.push(a), s.progressbar.width("100%"), o.showProgress && (s.progressbar.html("100%"), s.progressbar.css("text-align", "center")), s.abort.hide(), o.onSuccess.call(this, l, a, i, s), o.showStatusAfterSuccess?(o.showDone?(s.done.show(), s.done.click(function(){s.statusbar.hide("slow"), s.statusbar.remove()})):s.done.hide(), o.showDelete?(s.del.show(), s.del.click(function(){p(n, l), s.statusbar.hide().remove(), o.deleteCallback && o.deleteCallback.call(this, a, s), n.selectedFiles -= l.length, c(o, n)})):s.del.hide()):(s.statusbar.hide("slow"), s.statusbar.remove()), o.showDownload && (s.download.show(), s.download.click(function(){o.downloadCallback && o.downloadCallback(a)})), t.remove()}, error:function(e, a, r){s.cancel.remove(), D.pop(), s.abort.hide(), "abort" == e.statusText?(s.statusbar.hide("slow").remove(), c(o, n)):(o.onError.call(this, l, a, r, s), o.showStatusAfterError?(s.progressDiv.hide(), s.statusbar.append("<span class='" + o.errorClass + "'>ERROR: " + r + "</span>")):(s.statusbar.hide(), s.statusbar.remove()), n.selectedFiles -= l.length), t.remove()}}; o.showPreview && null != d && "image" == d.type.toLowerCase().split("/").shift() && u(d, s.preview), o.autoSubmit?(t.ajaxForm(h), x.push(t), r()):(o.showCancel && (s.cancel.show(), s.cancel.click(function(){x.splice(x.indexOf(t), 1), p(n, l), t.remove(), s.statusbar.remove(), o.onCancel.call(n, l, s), n.selectedFiles -= l.length, c(o, n)})), t.ajaxForm(h))}var w = e.extend({url:"", method:"POST", enctype:"multipart/form-data", returnType:null, allowDuplicates:!0, duplicateStrict:!1, allowedTypes:"*", acceptFiles:"*", fileName:"file", formData:!1, dynamicFormData:!1, maxFileSize: - 1, maxFileCount: - 1, multiple:!0, dragDrop:!0, autoSubmit:!0, showCancel:!0, showAbort:!0, showDone:!1, showDelete:!1, showError:!0, showStatusAfterSuccess:!0, showStatusAfterError:!0, showFileCounter:!0, fileCounterStyle:"). ", showFileSize:!0, showProgress:!1, nestedForms:!0, showDownload:!1, onLoad:function(e){}, onSelect:function(e){return!0}, onSubmit:function(e, a){}, onSuccess:function(e, a, t, r){}, onError:function(e, a, t, r){}, onCancel:function(e, a){}, onAbort:function(e, a){}, downloadCallback:!1, deleteCallback:!1, afterUploadAll:!1, serialize:!0, sequential:!1, sequentialCount:2, customProgressBar:!1, abortButtonClass:"ajax-file-upload-abort", cancelButtonClass:"ajax-file-upload-cancel", dragDropContainerClass:"ajax-upload-dragdrop", dragDropHoverClass:"state-hover", errorClass:"ajax-file-upload-error", uploadButtonClass:"ajax-file-upload", dragDropStr:"<span><b>Drag &amp; Drop Files</b></span>", uploadStr:"Upload", abortStr:"Abort", cancelStr:"Cancel", deletelStr:"Delete", doneStr:"Done", multiDragErrorStr:"Multiple File Drag &amp; Drop is not allowed.", extErrorStr:"is not allowed. Allowed extensions: ", duplicateErrorStr:"is not allowed. File already exists.", sizeErrorStr:"is not allowed. Allowed Max size: ", uploadErrorStr:"Upload is not allowed", maxFileCountErrorStr:" is not allowed. Maximum allowed files are:", downloadStr:"Download", customErrorKeyStr:"jquery-upload-file-error", showQueueDiv:!1, statusBarWidth:400, dragdropWidth:400, showPreview:!1, previewHeight:"auto", previewWidth:"100%", extraHTML:!1, uploadQueueOrder:"top"}, t); this.fileCounter = 1, this.selectedFiles = 0; var g = "ajax-file-upload-" + (new Date).getTime(); this.formGroup = g, this.errorLog = e("<div></div>"), this.responses = [], this.existingFileNames = [], a.formdata || (w.dragDrop = !1), a.formdata || (w.multiple = !1), e(this).html(""); var C = this, b = e("<div>" + w.uploadStr + "</div>"); e(b).addClass(w.uploadButtonClass), function F(){if (e.fn.ajaxForm){if (w.dragDrop){var a = e('<div class="' + w.dragDropContainerClass + '" style="vertical-align:top;"></div>').width(w.dragdropWidth); e(C).append(a), e(a).append(b), e(a).append(e(w.dragDropStr)), o(C, w, a)} else e(C).append(b); e(C).append(C.errorLog), w.showQueueDiv?C.container = e("#" + w.showQueueDiv):C.container = e("<div class='ajax-file-upload-container'></div>").insertAfter(e(C)), w.onLoad.call(this, C), h(C, g, w, b)} else window.setTimeout(F, 10)}(), this.startUpload = function(){e("form").each(function(a, t){e(this).hasClass(C.formGroup) && x.push(e(this))}), x.length >= 1 && r()}, this.getFileCount = function(){return C.selectedFiles}, this.stopUpload = function(){e("." + w.abortButtonClass).each(function(a, t){e(this).hasClass(C.formGroup) && e(this).click()}), e("." + w.cancelButtonClass).each(function(a, t){e(this).hasClass(C.formGroup) && e(this).click()})}, this.cancelAll = function(){e("." + w.cancelButtonClass).each(function(a, t){e(this).hasClass(C.formGroup) && e(this).click()})}, this.update = function(a){w = e.extend(w, a)}, this.reset = function(e){C.fileCounter = 1, C.selectedFiles = 0, C.errorLog.html(""), 0 != e && C.container.html("")}, this.remove = function(){C.container.html(""), e(C).remove()}, this.createProgress = function(e, a, t){var r = new m(this, w); r.progressDiv.show(), r.progressbar.width("100%"); var o = ""; return o = w.showFileCounter?C.fileCounter + w.fileCounterStyle + e:e, w.showFileSize && (o += " (" + s(t) + ")"), r.filename.html(o), C.fileCounter++, C.selectedFiles++, w.showPreview && (r.preview.attr("src", a), r.preview.show()), w.showDownload && (r.download.show(), r.download.click(function(){w.downloadCallback && w.downloadCallback.call(C, [e])})), w.showDelete && (r.del.show(), r.del.click(function(){r.statusbar.hide().remove(); var a = [e]; w.deleteCallback && w.deleteCallback.call(this, a, r), C.selectedFiles -= 1, c(w, C)})), r}, this.getResponses = function(){return this.responses}; var x = [], D = [], S = !1; return this}}(jQuery);
        
 // Create closure.
(function($) {
    var defaults = {
        url: 'index.php',
        views: 'thumbs',
        insertButton: true,
        token: 'jashd4a5sd4sa',
        buttonHolder: '#filemanager-button-holder',
        classHeader: '',
        height: 'auto',
        upload: {
            url: 'index.php',
            maxFileSize: 1000 * 1024,
            maxFileCount: 1,
            fileName: 'filemanager',
            dragDrop: false,
            multiple: false,
            allowedTypes: '*',
            showFileCounter: false,
        },
        deleteUrl: '',
        detailPanel: false,
    };
    this.opts = {};
    this.currentPath = "";
    this.uploader = null;
    $.fn.fxMediaManager = function(options) {
        var upload = $.extend(defaults.upload, options.upload);
        this.opts = $.extend({}, defaults, options);
        this.opts.upload = upload;
        var path = $(this).attr('path');
        this.selectedFiles = [];
        if (path) {
            this.currentPath = this.opts.path = path;
        }
        this.getHash = function(txt) {
            var hash = 0,
                i, chr, len;
            if (txt.length === 0) return hash;
            for (i = 0, len = txt.length; i < len; i++) {
                chr = txt.charCodeAt(i);
                hash = ((hash << 5) - hash) + chr;
                hash |= 0; // Convert to 32bit integer
            }
            return hash;
        }
        this.loading = function(show, e) {
            if (typeof e == "undefined") {
                e = this;
            }
            if (show) {
                $(e).append('<div class="media-ovelay"><span><i class="fa fa-spin fa-refresh"></i> Loading ...</span></div>');
            } else {
                $(e).find('.media-ovelay').remove();
            }
        }
        this.loadPanel = function() {
            var _html = [];
            _html.push('<div class="browse-panel media-panel"></div>');
            if (this.opts.detailPanel == true) {
                _html.push('<div class="detail-panel media-panel"></div>');
                _html.push('<div class="clear"></div>');
            }
            _html.push('<div class="upload-panel media-panel"></div>');
            this.html(_html.join(''));
            if (this.opts.detailPanel == true) {
                $(this).addClass('has-detail-panel');
                $(this).find('.detail-panel.media-panel').html('<span class="no-preview"> No Preview </span>');
            }

            if (this.opts.height == "auto") {
                var height = $(this).parent().height();
            } else {
                var height = this.opts.height;
            }
            $(this).find('.media-panel').css('height', height + 'px');
        };
        this.browse = function(opts) {
            this.loading(true);
            var self = this;
            $.ajax({
                url: self.opts['url'],
                data: opts,
                type: 'POST',
                dataType: 'JSON'
            }).done(function(data) {
                self.currentPath = opts.path;
                self.buildBreadCrumb(self.currentPath);
                var html = [];
                if (data.length > 0) {
                    for (i = 0; i < data.length; i++) {
                        var item = data[i];
                        var _itemHTML = self.getItemHTML(item);
                        html.push(_itemHTML);
                    }
                }
                 $(self).find('.browse-panel').html('<ul class="media-list" >' + html.join('') + '</ul>');
                self.bind();
                self.loading(false);
            }).fail(function(data) {
                self.loading(false);
            });
        }
        this.getItemHTML = function(item) {
            var _default = {
                'type': 'file',
                'title': '',
                'full_path': '#',
                'mode': 'normal',
                'defaultView': false,
                'orginal': '',
                'absolute_path': '',
            }
            
            item = $.extend(_default, item);
            var _cl = "item-content";
            var _l = "";
            if (item.mode == "uploading") {
                _l = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i><p>Uploading...</p></div>';
            }
            var content = '';
            switch (item.type) {
                case 'folder':
                    content = '<span class="icon fa fa-folder-open" aria-hidden="true"></span>';
                    break;
                case 'file':
                default:
                    if (item.thumb) {
                        content = '<img src="' + item.thumb + '" />';
                    } else {
                        content = '<span class="icon fa fa-file" aria-hidden="true"></span>';
                    }
                    break;
            }
            var selected = false;
            var hash = this.getHash(item.absolute_path);
            for (var i = 0; i < this.selectedFiles.length; i++) {
                if (this.selectedFiles[i].hash == hash) {
                    selected = true;
                    break;
                }
            }
            var title = '<div class="col name"><h3><span class="texto"> ' + item.title + '</span></h3></div>';
            var html = "<li class='mode-" + item.mode + " item-data-holder item-data-" + this.getHash(item.title) + "'>" +
                "<div class='media-item item-type-" + item.type + ((selected) ? " active " : "") + " ' rel = '" + item.absolute_path + "' >" + _l;
            if (item.defaultView === false) {
                html += '<div class="check"><label><input class="item-checkbox" type="checkbox" name="check[]" value="' + item.absolute_path + '" ' + ((selected) ? " checked " : "") + ' url="' + item.original + '" t="' + item.type + '" title="' + item.title + '"></label></div>';
            }
            html +=
                "<a href='javascript:void(0)' class='" + _cl + "' > " +
                content +
                "</a>" + title +
                "</div>" +
                "</li>";
            return html;
        };
        this.buildBreadCrumb = function(path) {
            if ($(this.opts.classHeader).length > 0) {
                var breadcrumbs = [];
                if (typeof path != "undefined" && path) {
                    var parts = path.split('/');
                    breadcrumbs.push('<a class="" href="javascript:void(0)"><i class="fa fa-home"></i></a>');
                    breadcrumbs.push('<span>&#187;</span>');
                    for (i = 0; i < parts.length; i++) {
                        if (parts[i]) {
                            if (i > 0) {
                                var _subPath = parts.slice(0, i + 1);
                                _subPath = _subPath.join('/');
                            } else {
                                var _subPath = parts[i];
                            }

                            breadcrumbs.push('<a href="javascript:void(0)" path="' + _subPath + '">' + parts[i] + '</a>');
                            if (i < parts.length - 1) {
                                breadcrumbs.push('<span>&#187;</span>');
                            }
                        }

                    }
                }
                if ($(this.opts.classHeader).find(".box-breadcrumb").length <= 0) {
                    $(this.opts.classHeader).append('<span class="box-breadcrumb"></span>')
                }
                $(this.opts.classHeader).find(".box-breadcrumb").html(breadcrumbs.join(''));
            }


        }
        this.indexOfSelectedFile = function(path) {

            var indexOfSelectedFileValue = -1;
            if (this.selectedFiles.length > 0 && path != "") {
                var hash = this.getHash(path);
                for (i = 0; i < this.selectedFiles.length; i++) {
                    if (this.selectedFiles[i].hash == hash) {
                        indexOfSelectedFileValue = i;
                        break;
                    }
                }
            }
            return indexOfSelectedFileValue;
        }
        this.selectFile = function(file) {

            if (this.indexOfSelectedFile(file.path) < 0) {
                var self = this;
                this.selectedFiles.push(file);
                var thumb = file.thumb;
                if (typeof thumb == "undefined") {
                    thumb = "<i class='fa fa-file'></i>";
                } else {
                    thumb = '<img src="' + thumb + '"/>';
                }
                var html = '<a href="javascript:void(0);" title="' + file.path + '" class="selected-item-holder" path="' + file.path + '"> ' + thumb + ' </a>';
                var select_holder = $(this.opts.buttonHolder).find('.media-selected-files > .media-selected-files-mask');
                select_holder.prepend(html);
                select_holder.find('a.selected-item-holder').off('click').on('click', function() {
                    self.showPreview($(this).attr('path'));
                });
                this.checkHasViewAllSelectedFile();
            }
        }
        this.removeSelectedFile = function(file) {
            var index = this.indexOfSelectedFile(file.path);
            var length = this.selectedFiles.length;
            if (index >= 0) {

                this.selectedFiles.splice(index, 1);
                var select_holder = $(this.opts.buttonHolder).find('.media-selected-files');
                select_holder.find('.selected-item-holder:eq(' + (length - index - 1) + ')').remove();
                var hash = this.getHash(file.path);
                $(this).find('.item-data-' + hash).find('.media-item').removeClass('active');
                $(this).find('.item-data-' + hash).find('.item-checkbox').prop('checked', false);
                this.checkHasViewAllSelectedFile();
            }
        }
        this.checkHasViewAllSelectedFile = function() {
            var select_holder = $(this.opts.buttonHolder).find('.media-selected-files');
            var view_all_items = $(this.opts.buttonHolder).find('.media-view-all-selected-files');
            var self = this;
            if (this.selectedFiles.length > 8) {
                if (view_all_items.length <= 0) {
                    $('<span class="media-view-all-selected-files"><a href="javascript:void(0)" title="Next" rel="-1">&#187;</a></span>').insertAfter(select_holder);
                    $('<span class="media-view-all-selected-files"><a href="javascript:void(0)" title="Prev" rel="1">&#171;</a></span>').insertBefore(select_holder);
                    $('.media-view-all-selected-files > a').on('click', function(e) {
                        e.preventDefault();
                        var a_w = $(self.opts.buttonHolder).find('.media-selected-files-mask a.selected-item-holder').length * 38;
                        var h_w = select_holder.width();
                        var r = $(this).attr('rel');
                        r = parseInt(r);
                        var w = 38 * r;
                        var _left = $('.media-selected-files-mask').position().left;
                        if (r == -1) {
                            if (_left > h_w - a_w) {
                                _left = _left + w;
                            }
                        } else {
                            if (_left < 0) {
                                _left = _left + w;
                            }
                        }
                        $('.media-selected-files-mask').animate({
                            left: _left
                        }, 500);
                    });
                }
            } else {
                view_all_items.remove();
                $('.media-selected-files-mask').animate({
                    left: 0
                }, 500);
            }
        }
        this.bind = function() {
            var self = this;
            $(this.opts.classHeader).find(".box-breadcrumb > a").off('click').on('click', function() {
                var path = $(this).attr('path');
                self.browse({
                    path: path
                });
            });
            self.find('.item-checkbox').off('click').on('click', function(e) {
                $(this).parent().parent().parent().addClass('item-checkbox-click');
                var thumb = $(this).parent().parent().parent().find('.item-content img').attr('src');
                var url = $(this).attr('url');
                var path = $(this).val();
                var file = {
                    path: path,
                    thumb: thumb,
                    hash: self.getHash(path),
                    url: $(this).attr('url'),
                    type: $(this).attr('t'),
                    title: $(this).attr('title'),
                    absolute_path: $(this).val()
                }
                if ($(this).is(':checked')) {
                    $(this).parent().parent().parent().addClass('active');
                    self.selectFile(file);
                } else {
                    $(this).parent().parent().parent().removeClass('active');
                    self.removeSelectedFile(file);
                }
                self.showPreview($(this).val());
            });
            self.find('.media-item').off('click').on('click', function() {
                if ($(this).hasClass('item-checkbox-click')) {
                    $(this).removeClass('item-checkbox-click')
                    return;
                }
                var rel = $(this).attr('rel');
                if ($(this).hasClass('item-type-folder')) {
                    self.browse({
                        path: rel
                    });
                } else {
                    $(this).find('.item-checkbox').trigger('click');
                }
            });
            $(self.opts.buttonHolder).find('.btn-remove').off('click').on('click', function() {
                var selected_items = [];
                $('.item-checkbox:checked').each(function(i, e) {
                    selected_items.push($(e).val());
                });
                if (selected_items.length > 0) {
                    if (confirm('Are you sure?')) {
                        $.ajax({
                            url: self.opts.deleteUrl,
                            method: 'POST',
                            dataType: 'JSON',
                            data: {
                                files: selected_items.join('|')
                            }
                        }).done(function() {
                            $('.item-checkbox:checked').each(function(i, e) {
                                $(e).parent().parent().parent().parent().remove();
                            });
                        }).error(function() {

                        });
                    }
                }
            });
            $(self.opts.buttonHolder).find('.search-input-keyword').off('keyup').on('keyup', function() {
                var v = $(this).val();
                if (v.length > 2) {
                    $('.item-data-holder').each(function(i, e) {
                        var name = $(e).find('span.texto').text();
                        if (name.indexOf(v) < 0) {
                            $(e).hide();
                        } else {
                            $(e).show();
                        }
                    });
                } else {
                    $('.item-data-holder').show();
                }
            });
            var upload_opts = self.opts.upload;
            upload_opts.onSubmit = function(files) {
				
                var li = $(self).find('.browse-panel .media-list li:eq(0)');
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var hash = self.getHash(file);
                    if ($('.item-data-' + hash).length > 0) {
                        $('.item-data-' + hash).removeClass('mode-normal').addClass('mode-uploading');
                        $('.item-data-' + hash).prepend('<div class="overlay"><i class="fa fa-refresh fa-spin"></i><p>Uploading...</p></div>');
                    } else {
                        var itemHTML = self.getItemHTML({
                            'mode': 'uploading',
                            'title': file,
                        });
                       
                        if(li.length > 0){
							$(itemHTML).insertAfter(li);
						}
						else{
							 console.log('li-item'); 
							console.log($(self).find('.browse-panel .media-list'));
							$(self).find('.browse-panel .media-list').append(itemHTML);
						}
                    }

                }
            }
            upload_opts.onSuccess = function(files, data, xhr, pd) {
				console.log(data);
                data = data.trim();
                data = $.parseJSON(data);
                var hash = self.getHash(data.title);
                $('.item-data-' + hash).removeClass('mode-uploading').addClass('mode-normal');
                $('.item-data-' + hash).find('.overlay').remove();
               
                if (data.thumb) {
                    $('.item-data-' + hash).find('.item-content').html('<img src="' + data.thumb + '" alt=""/>');
                }
                $('.item-data-' + hash).find('input.item-checkbox').val(data.absolute_path);
                $('.item-data-' + hash).find('input.item-checkbox').attr('url',data.original);
            };
            upload_opts.afterUploadAll = function(obj) {
                self.bind();
            };
            upload_opts.onError = function(files, status, errMsg, pd) {
                console.log(files);
                console.log('on-err');
            };
            upload_opts.dynamicFormData = function() {
                var data = {
                    pathUpload: self.currentPath,
                    token: self.token
                };
                return data;
            };
            upload_opts.onSelect = function(files) {

                var existed_files = [];
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (file.name) {
                        var hash = self.getHash(file.name);
                        if ($('.item-data-' + hash).length > 0) {
                            existed_files.push(file.name);
                        }

                    }
                }
                if (existed_files.length > 0) {
                    if (confirm('File(s) existed. Do you want to overwrite? [' + existed_files.join(',') + ']')) {
                        return true;
                    }
                } else {
                    return true;
                }
                return false;
            }
            self.uploader = $($(self.opts.buttonHolder).find('.btn-upload')).uploadFile(self.opts.upload);
        }
        this.showPreview = function(v) {
            var self = this;
            this.loading(true, $(self).find('.detail-panel.media-panel'));
            $.ajax({
                url: self.opts.url,
                data: {
                    preview: true,
                    file: v
                },
                type: 'POST',
                dataType: 'HTML'
            }).done(function(content) {
                self.loading(false, $(self).find('.detail-panel.media-panel'));
                //$(self).find('.detail-panel.media-panel .no-preview').hide();
                $(self).find('.detail-panel.media-panel').html(content);
                $(self).find('.detail-panel.media-panel .thumb-view .remove-item').on('click', function() {
                    self.removeSelectedFile({
                        path: $(this).attr('path')
                    });
                    $(self).find('.detail-panel.media-panel').html('<span class="no-preview"> No Preview </span>');
                });
            }).error(function() {
                self.loading(false, $(self).find('.detail-panel.media-panel'));
            });
        }
        this.buildButtons = function() {
            var html = [];
            html.push('<span class="media-search-box"><input type="text" class="search-input-keyword form-control" value="" placeholder="Type to search" /></span>');
            html.push('<span class="media-selected-files"><span class="media-selected-files-mask"></span></span>');
            if (this.opts.deleteUrl != '') {
                html.push('<a href="javascript:void(0)" class="btn btn-danger btn-remove"><i class="fa fa-trash"></i> Delete</a>');
            }
            html.push('<a href="javascript:void(0)" class="btn btn-info btn-upload" ><i class="fa fa-upload"></i> Upload </a>');
            $(this.opts.buttonHolder).append(html.join(''));
        }
        this.loadPanel();
        this.buildButtons();
        this.browse({
            path: this.opts.path
        });
        this.bind();
        this.changeView = function(mode) {
            switch (mode) {
                case 'upload':
                    this.uploadMode();
                    break;
                case 'browse':
                default:
                    self.browse({
                        path: this.currentPath
                    });
            }
        }

        this.uploadMode = function() {
            //console.log(this);
            this.find('.media-panel').hide();
            var _html = '<div class="media-input-dragdrop">' +
                '<div class="media-inner-box">' +
                '<div class="media-input-icon"><i class="fa fa-folder-open"></i></div>' +
                '<div class="media-input-text">Drag & Drop Here <br/><span style="display:inline-block; margin: 15px 0">or</span></div>' +
                '<a class=" btn btn-success btn-media-upload">Browse Files</a>' +
                '</div>'
            '</div>';
            this.find('.upload-panel').html(_html).show();
        }
        this.getSelectedFiles = function() {
            return this.selectedFiles;
        }
        return this;
    };
})(jQuery);
