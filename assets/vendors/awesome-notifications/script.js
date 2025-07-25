var AWN;
(() => {
    var t = {
        628   : (t, e, n) => {
            "use strict";

            function o(t) {
                return o = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
                    return typeof t
                } : function (t) {
                    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
                }, o(t)
            }

            function r(t, e) {
                if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
            }

            function i(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var o = e[n];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                }
            }

            n.d(e, {default: () => I});
            var a  = {
                maxNotifications : 10,
                animationDuration: 300,
                position         : "bottom-right",
                labels           : {
                    tip          : "Tip",
                    info         : "Info",
                    success      : "Success",
                    warning      : "Attention",
                    alert        : "Error",
                    async        : "Loading",
                    confirm      : "Confirmation required",
                    confirmOk    : "OK",
                    confirmCancel: "Cancel"
                },
                icons            : {
                    tip    : "question-circle",
                    info   : "info-circle",
                    success: "check-circle",
                    warning: "exclamation-circle",
                    alert  : "exclamation-triangle",
                    async  : "cog fa-spin",
                    confirm: "exclamation-triangle",
                    prefix : "<i class='fa fas fa-fw fa-",
                    suffix : "'></i>",
                    enabled: !0
                },
                replacements     : {
                    tip          : null,
                    info         : null,
                    success      : null,
                    warning      : null,
                    alert        : null,
                    async        : null,
                    "async-block": null,
                    modal        : null,
                    confirm      : null,
                    general      : {"<script>": "", "<\/script>": ""}
                },
                messages         : {
                    tip          : "",
                    info         : "",
                    success      : "Action has been succeeded",
                    warning      : "",
                    alert        : "Action has been failed",
                    confirm      : "This action can't be undone. Continue?",
                    async        : "Please, wait...",
                    "async-block": "Loading"
                },
                formatError      : function (t) {
                    if (t.response) {
                        if (!t.response.data) return "500 API Server Error";
                        if (t.response.data.errors) return t.response.data.errors.map((function (t) {
                            return t.detail
                        })).join("<br>");
                        if (t.response.statusText) return "".concat(t.response.status, " ").concat(t.response.statusText, ": ").concat(t.response.data)
                    }
                    return t.message ? t.message : t
                },
                durations        : {global: 5e3, success: null, info: null, tip: null, warning: null, alert: null},
                minDurations     : {async: 1e3, "async-block": 1e3}
            }, c   = function () {
                function t() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {},
                        n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : a;
                    r(this, t), Object.assign(this, this.defaultsDeep(n, e))
                }

                var e, n;
                return e = t, n = [{
                    key: "icon", value: function (t) {
                        return this.icons.enabled ? "".concat(this.icons.prefix).concat(this.icons[t]).concat(this.icons.suffix) : ""
                    }
                }, {
                    key: "label", value: function (t) {
                        return this.labels[t]
                    }
                }, {
                    key: "duration", value: function (t) {
                        var e = this.durations[t];
                        return null === e ? this.durations.global : e
                    }
                }, {
                    key: "toSecs", value: function (t) {
                        return "".concat(t / 1e3, "s")
                    }
                }, {
                    key: "applyReplacements", value: function (t, e) {
                        if (!t) return this.messages[e] || "";
                        for (var n = 0, o = ["general", e]; n < o.length; n++) {
                            var r = o[n];
                            if (this.replacements[r]) for (var i in this.replacements[r]) t = t.replace(i, this.replacements[r][i])
                        }
                        return t
                    }
                }, {
                    key: "override", value: function (e) {
                        return e ? new t(e, this) : this
                    }
                }, {
                    key: "defaultsDeep", value: function (t, e) {
                        var n = {};
                        for (var r in t) e.hasOwnProperty(r) ? n[r] = "object" === o(t[r]) && null !== t[r] ? this.defaultsDeep(t[r], e[r]) : e[r] : n[r] = t[r];
                        return n
                    }
                }], n && i(e.prototype, n), t
            }(), s = "awn", u = {
                popup  : "".concat(s, "-popup"),
                toast  : "".concat(s, "-toast"),
                btn    : "".concat(s, "-btn"),
                confirm: "".concat(s, "-confirm")
            }, l   = {
                prefix: u.toast,
                klass : {
                    label           : "".concat(u.toast, "-label"),
                    content         : "".concat(u.toast, "-content"),
                    icon            : "".concat(u.toast, "-icon"),
                    progressBar     : "".concat(u.toast, "-progress-bar"),
                    progressBarPause: "".concat(u.toast, "-progress-bar-paused")
                },
                ids   : {container: "".concat(u.toast, "-container")}
            }, f   = {
                prefix: u.popup,
                klass : {
                    buttons     : "".concat(s, "-buttons"),
                    button      : u.btn,
                    successBtn  : "".concat(u.btn, "-success"),
                    cancelBtn   : "".concat(u.btn, "-cancel"),
                    title       : "".concat(u.popup, "-title"),
                    body        : "".concat(u.popup, "-body"),
                    content     : "".concat(u.popup, "-content"),
                    dotAnimation: "".concat(u.popup, "-loading-dots")
                },
                ids   : {
                    wrapper      : "".concat(u.popup, "-wrapper"),
                    confirmOk    : "".concat(u.confirm, "-ok"),
                    confirmCancel: "".concat(u.confirm, "-cancel")
                }
            }, p   = {klass: {hiding: "".concat(s, "-hiding")}, lib: s};

            function d(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var o = e[n];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                }
            }

            var h = function () {
                function t(e, n, o, r, i) {
                    !function (t, e) {
                        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
                    }(this, t), this.newNode = document.createElement("div"), n && (this.newNode.id = n), o && (this.newNode.className = o), r && (this.newNode.style.cssText = r), this.parent = e, this.options = i
                }

                var e, n;
                return e = t, n = [{
                    key: "beforeInsert", value: function () {
                    }
                }, {
                    key: "afterInsert", value: function () {
                    }
                }, {
                    key: "insert", value: function () {
                        return this.beforeInsert(), this.el = this.parent.appendChild(this.newNode), this.afterInsert(), this
                    }
                }, {
                    key: "replace", value: function (t) {
                        var e = this;
                        if (this.getElement()) return this.beforeDelete().then((function () {
                            return e.updateType(t.type), e.parent.replaceChild(t.newNode, e.el), e.el = e.getElement(t.newNode), e.afterInsert(), e
                        }))
                    }
                }, {
                    key: "beforeDelete", value: function () {
                        var t = this, e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : this.el,
                            n = 0;
                        return this.start && (n = this.options.minDurations[this.type] + this.start - Date.now()) < 0 && (n = 0), new Promise((function (o) {
                            setTimeout((function () {
                                e.classList.add(p.klass.hiding), setTimeout(o, t.options.animationDuration)
                            }), n)
                        }))
                    }
                }, {
                    key: "delete", value: function () {
                        var t = this, e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : this.el;
                        return this.getElement(e) ? this.beforeDelete(e).then((function () {
                            e.remove(), t.afterDelete()
                        })) : null
                    }
                }, {
                    key: "afterDelete", value: function () {
                    }
                }, {
                    key: "getElement", value: function () {
                        var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : this.el;
                        return t ? document.getElementById(t.id) : null
                    }
                }, {
                    key: "addEvent", value: function (t, e) {
                        this.el.addEventListener(t, e)
                    }
                }, {
                    key: "toggleClass", value: function (t) {
                        this.el.classList.toggle(t)
                    }
                }, {
                    key: "updateType", value: function (t) {
                        this.type = t, this.duration = this.options.duration(this.type)
                    }
                }], n && d(e.prototype, n), t
            }();

            function y(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var o = e[n];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                }
            }

            var v = function () {
                function t(e, n) {
                    !function (t, e) {
                        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
                    }(this, t), this.callback = e, this.remaining = n, this.resume()
                }

                var e, n;
                return e = t, (n = [{
                    key: "pause", value: function () {
                        this.paused = !0, window.clearTimeout(this.timerId), this.remaining -= new Date - this.start
                    }
                }, {
                    key: "resume", value: function () {
                        var t = this;
                        this.paused = !1, this.start = new Date, window.clearTimeout(this.timerId), this.timerId = window.setTimeout((function () {
                            window.clearTimeout(t.timerId), t.callback()
                        }), this.remaining)
                    }
                }, {
                    key: "toggle", value: function () {
                        this.paused ? this.resume() : this.pause()
                    }
                }]) && y(e.prototype, n), t
            }();

            function m(t) {
                return m = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
                    return typeof t
                } : function (t) {
                    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
                }, m(t)
            }

            function b(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var o = e[n];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                }
            }

            function k(t, e) {
                return k = Object.setPrototypeOf || function (t, e) {
                    return t.__proto__ = e, t
                }, k(t, e)
            }

            function g(t, e) {
                if (e && ("object" === m(e) || "function" == typeof e)) return e;
                if (void 0 !== e) throw new TypeError("Derived constructors may only return object or undefined");
                return function (t) {
                    if (void 0 === t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                    return t
                }(t)
            }

            function w(t) {
                return w = Object.setPrototypeOf ? Object.getPrototypeOf : function (t) {
                    return t.__proto__ || Object.getPrototypeOf(t)
                }, w(t)
            }

            var O = function (t) {
                !function (t, e) {
                    if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function");
                    t.prototype = Object.create(e && e.prototype, {
                        constructor: {
                            value       : t,
                            writable    : !0,
                            configurable: !0
                        }
                    }), e && k(t, e)
                }(a, t);
                var e, n, o, r, i = (o = a, r = function () {
                    if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
                    if (Reflect.construct.sham) return !1;
                    if ("function" == typeof Proxy) return !0;
                    try {
                        return Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], (function () {
                        }))), !0
                    } catch (t) {
                        return !1
                    }
                }(), function () {
                    var t, e = w(o);
                    if (r) {
                        var n = w(this).constructor;
                        t     = Reflect.construct(e, arguments, n)
                    } else t = e.apply(this, arguments);
                    return g(this, t)
                });

                function a(t, e, n, o) {
                    var r;
                    return function (t, e) {
                        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
                    }(this, a), (r = i.call(this, o, "".concat(l.prefix, "-").concat(Math.floor(Date.now() - 100 * Math.random())), "".concat(l.prefix, " ").concat(l.prefix, "-").concat(e), "animation-duration: ".concat(n.toSecs(n.animationDuration), ";"), n)).updateType(e), r.setInnerHtml(t), r
                }

                return e = a, n = [{
                    key: "setInnerHtml", value: function (t) {
                        "alert" === this.type && t && (t = this.options.formatError(t)), t = this.options.applyReplacements(t, this.type), this.newNode.innerHTML = '<div class="awn-toast-wrapper">'.concat(this.progressBar).concat(this.label, '<div class="').concat(l.klass.content, '">').concat(t, '</div><span class="').concat(l.klass.icon, '">').concat(this.options.icon(this.type), "</span></div>")
                    }
                }, {
                    key: "beforeInsert", value: function () {
                        var t = this;
                        if (this.parent.childElementCount >= this.options.maxNotifications) {
                            var e = Array.from(this.parent.getElementsByClassName(l.prefix));
                            this.delete(e.find((function (e) {
                                return !t.isDeleted(e)
                            })))
                        }
                    }
                }, {
                    key: "afterInsert", value: function () {
                        var t = this;
                        if ("async" == this.type) return this.start = Date.now();
                        if (this.addEvent("click", (function () {
                            return t.delete()
                        })), !(this.duration <= 0)) {
                            this.timer = new v((function () {
                                return t.delete()
                            }), this.duration);
                            for (var e = 0, n = ["mouseenter", "mouseleave"]; e < n.length; e++) {
                                var o = n[e];
                                this.addEvent(o, (function () {
                                    t.isDeleted() || (t.toggleClass(l.klass.progressBarPause), t.timer.toggle())
                                }))
                            }
                        }
                    }
                }, {
                    key: "isDeleted", value: function () {
                        var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : this.el;
                        return t.classList.contains(p.klass.hiding)
                    }
                }, {
                    key: "progressBar", get: function () {
                        return this.duration <= 0 || "async" === this.type ? "" : "<div class='".concat(l.klass.progressBar, "' style=\"animation-duration:").concat(this.options.toSecs(this.duration), ';"></div>')
                    }
                }, {
                    key: "label", get: function () {
                        return '<b class="'.concat(l.klass.label, '">').concat(this.options.label(this.type), "</b>")
                    }
                }], n && b(e.prototype, n), a
            }(h);

            function E(t) {
                return E = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
                    return typeof t
                } : function (t) {
                    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
                }, E(t)
            }

            function T(t, e) {
                if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
            }

            function _(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var o = e[n];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                }
            }

            function C(t, e) {
                return C = Object.setPrototypeOf || function (t, e) {
                    return t.__proto__ = e, t
                }, C(t, e)
            }

            function D(t, e) {
                if (e && ("object" === E(e) || "function" == typeof e)) return e;
                if (void 0 !== e) throw new TypeError("Derived constructors may only return object or undefined");
                return function (t) {
                    if (void 0 === t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                    return t
                }(t)
            }

            function x(t) {
                return x = Object.setPrototypeOf ? Object.getPrototypeOf : function (t) {
                    return t.__proto__ || Object.getPrototypeOf(t)
                }, x(t)
            }

            var P = function (t) {
                !function (t, e) {
                    if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function");
                    t.prototype = Object.create(e && e.prototype, {
                        constructor: {
                            value       : t,
                            writable    : !0,
                            configurable: !0
                        }
                    }), e && C(t, e)
                }(a, t);
                var e, n, o, r, i = (o = a, r = function () {
                    if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
                    if (Reflect.construct.sham) return !1;
                    if ("function" == typeof Proxy) return !0;
                    try {
                        return Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], (function () {
                        }))), !0
                    } catch (t) {
                        return !1
                    }
                }(), function () {
                    var t, e = x(o);
                    if (r) {
                        var n = x(this).constructor;
                        t     = Reflect.construct(e, arguments, n)
                    } else t = e.apply(this, arguments);
                    return D(this, t)
                });

                function a(t) {
                    var e, n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "modal",
                        o = arguments.length > 2 ? arguments[2] : void 0,
                        r = arguments.length > 3 ? arguments[3] : void 0,
                        c = arguments.length > 4 ? arguments[4] : void 0;
                    T(this, a);
                    var s = "animation-duration: ".concat(o.toSecs(o.animationDuration), ";");
                    return (e = i.call(this, document.body, f.ids.wrapper, null, s, o))[f.ids.confirmOk] = r, e[f.ids.confirmCancel] = c, e.className = "".concat(f.prefix, "-").concat(n), ["confirm", "async-block", "modal"].includes(n) || (n = "modal"), e.updateType(n), e.setInnerHtml(t), e.insert(), e
                }

                return e = a, (n = [{
                    key: "setInnerHtml", value: function (t) {
                        var e = this.options.applyReplacements(t, this.type);
                        switch (this.type) {
                            case"confirm":
                                var n = ["<button class='".concat(f.klass.button, " ").concat(f.klass.successBtn, "'id='").concat(f.ids.confirmOk, "'>").concat(this.options.labels.confirmOk, "</button>")];
                                !1 !== this[f.ids.confirmCancel] && n.push("<button class='".concat(f.klass.button, " ").concat(f.klass.cancelBtn, "'id='").concat(f.ids.confirmCancel, "'>").concat(this.options.labels.confirmCancel, "</button>")), e = "".concat(this.options.icon(this.type), "<div class='").concat(f.klass.title, "'>").concat(this.options.label(this.type), '</div><div class="').concat(f.klass.content, '">').concat(e, "</div><div class='").concat(f.klass.buttons, " ").concat(f.klass.buttons, "-").concat(n.length, "'>").concat(n.join(""), "</div>");
                                break;
                            case"async-block":
                                e = "".concat(e, '<div class="').concat(f.klass.dotAnimation, '"></div>')
                        }
                        this.newNode.innerHTML = '<div class="'.concat(f.klass.body, " ").concat(this.className, '">').concat(e, "</div>")
                    }
                }, {
                    key: "keyupListener", value: function (t) {
                        if ("async-block" === this.type) return t.preventDefault();
                        switch (t.code) {
                            case"Escape":
                                t.preventDefault(), this.delete();
                            case"Tab":
                                if (t.preventDefault(), "confirm" !== this.type || !1 === this[f.ids.confirmCancel]) return !0;
                                var e = this.okBtn;
                                t.shiftKey ? document.activeElement.id == f.ids.confirmOk && (e = this.cancelBtn) : document.activeElement.id !== f.ids.confirmCancel && (e = this.cancelBtn), e.focus()
                        }
                    }
                }, {
                    key: "afterInsert", value: function () {
                        var t = this;
                        switch (this.listener = function (e) {
                            return t.keyupListener(e)
                        }, window.addEventListener("keydown", this.listener), this.type) {
                            case"async-block":
                                this.start = Date.now();
                                break;
                            case"confirm":
                                this.okBtn.focus(), this.addEvent("click", (function (e) {
                                    if ("BUTTON" !== e.target.nodeName) return !1;
                                    t.delete(), t[e.target.id] && t[e.target.id]()
                                }));
                                break;
                            default:
                                document.activeElement.blur(), this.addEvent("click", (function (e) {
                                    e.target.id === t.newNode.id && t.delete()
                                }))
                        }
                    }
                }, {
                    key: "afterDelete", value: function () {
                        window.removeEventListener("keydown", this.listener)
                    }
                }, {
                    key: "okBtn", get: function () {
                        return document.getElementById(f.ids.confirmOk)
                    }
                }, {
                    key: "cancelBtn", get: function () {
                        return document.getElementById(f.ids.confirmCancel)
                    }
                }]) && _(e.prototype, n), a
            }(h);

            function S(t) {
                return S = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
                    return typeof t
                } : function (t) {
                    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
                }, S(t)
            }

            function B(t, e) {
                if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
            }

            function j(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var o = e[n];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                }
            }

            var I = function () {
                function t() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {};
                    B(this, t), this.options = new c(e)
                }

                var e, n;
                return e = t, (n = [{
                    key: "tip", value: function (t, e) {
                        return this._addToast(t, "tip", e).el
                    }
                }, {
                    key: "info", value: function (t, e) {
                        return this._addToast(t, "info", e).el
                    }
                }, {
                    key: "success", value: function (t, e) {
                        return this._addToast(t, "success", e).el
                    }
                }, {
                    key: "warning", value: function (t, e) {
                        return this._addToast(t, "warning", e).el
                    }
                }, {
                    key: "alert", value: function (t, e) {
                        return this._addToast(t, "alert", e).el
                    }
                }, {
                    key: "async", value: function (t, e, n, o, r) {
                        var i = this._addToast(o, "async", r);
                        return this._afterAsync(t, e, n, r, i)
                    }
                }, {
                    key: "confirm", value: function (t, e, n, o) {
                        return this._addPopup(t, "confirm", o, e, n)
                    }
                }, {
                    key: "asyncBlock", value: function (t, e, n, o, r) {
                        var i = this._addPopup(o, "async-block", r);
                        return this._afterAsync(t, e, n, r, i)
                    }
                }, {
                    key: "modal", value: function (t, e, n) {
                        return this._addPopup(t, e, n)
                    }
                }, {
                    key: "closeToasts", value: function () {
                        for (var t = this.container; t.firstChild;) t.removeChild(t.firstChild)
                    }
                }, {
                    key: "_addPopup", value: function (t, e, n, o, r) {
                        return new P(t, e, this.options.override(n), o, r)
                    }
                }, {
                    key: "_addToast", value: function (t, e, n, o) {
                        n     = this.options.override(n);
                        var r = new O(t, e, n, this.container);
                        return o ? o instanceof P ? o.delete().then((function () {
                            return r.insert()
                        })) : o.replace(r) : r.insert()
                    }
                }, {
                    key: "_afterAsync", value: function (t, e, n, o, r) {
                        return t.then(this._responseHandler(e, "success", o, r), this._responseHandler(n, "alert", o, r))
                    }
                }, {
                    key: "_responseHandler", value: function (t, e, n, o) {
                        var r = this;
                        return function (i) {
                            switch (S(t)) {
                                case"undefined":
                                case"string":
                                    var a = "alert" === e ? t || i : t;
                                    r._addToast(a, e, n, o);
                                    break;
                                default:
                                    o.delete().then((function () {
                                        t && t(i)
                                    }))
                            }
                        }
                    }
                }, {
                    key: "_createContainer", value: function () {
                        return new h(document.body, l.ids.container, "awn-".concat(this.options.position)).insert().el
                    }
                }, {
                    key: "container", get: function () {
                        return document.getElementById(l.ids.container) || this._createContainer()
                    }
                }]) && j(e.prototype, n), t
            }()
        }, 612: (t, e, n) => {
            t.exports = n(628).default
        }
    }, e  = {};

    function n(o) {
        var r = e[o];
        if (void 0 !== r) return r.exports;
        var i = e[o] = {exports: {}};
        return t[o](i, i.exports, n), i.exports
    }

    n.d = (t, e) => {
        for (var o in e) n.o(e, o) && !n.o(t, o) && Object.defineProperty(t, o, {enumerable: !0, get: e[o]})
    }, n.o = (t, e) => Object.prototype.hasOwnProperty.call(t, e);
    var o = n(612);
    AWN   = o
})();