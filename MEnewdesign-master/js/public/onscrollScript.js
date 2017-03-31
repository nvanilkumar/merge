/**
 * @license almond 0.3.0 Copyright (c) 2011-2014, The Dojo Foundation All Rights Reserved.
 * Available via the MIT or new BSD license.
 * see: http://github.com/jrburke/almond for details
 */
/*! fancyBox v2.1.5 fancyapps.com | fancyapps.com/fancybox/#license */
//     Underscore.js 1.7.0
//     http://underscorejs.org
//     (c) 2009-2014 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
//     Underscore may be freely distributed under the MIT license.
/*! Hammer.JS - v2.0.4 - 2014-09-28
 * http://hammerjs.github.io/
 *
 * Copyright (c) 2014 Jorik Tangelder;
 * Licensed under the MIT license */
/*!
 * jQuery Validation Plugin v1.13.0
 *
 * http://jqueryvalidation.org/
 *
 * Copyright (c) 2014 Jörn Zaefferer
 * Released under the MIT license
 */
/*! http://mths.be/placeholder v2.0.8 by @mathias */
/*!

 handlebars v2.0.0

Copyright (C) 2011-2014 by Yehuda Katz

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

@license
*/
/**
 * History.js jQuery Adapter
 * @author Benjamin Arthur Lupton <contact@balupton.com>
 * @copyright 2010-2011 Benjamin Arthur Lupton <contact@balupton.com>
 * @license New BSD License <http://creativecommons.org/licenses/BSD/>
 */
/**
 * History.js Core
 * @author Benjamin Arthur Lupton <contact@balupton.com>
 * @copyright 2010-2011 Benjamin Arthur Lupton <contact@balupton.com>
 * @license New BSD License <http://creativecommons.org/licenses/BSD/>
 */
/**
 * History.getInternetExplorerMajorVersion()
 * Get's the major version of Internet Explorer
 * @return {integer}
 * @license Public Domain
 * @author Benjamin Arthur Lupton <contact@balupton.com>
 * @author James Padolsey <https://gist.github.com/527683>
 */
/**
 * History.isInternetExplorer()
 * Are we using Internet Explorer?
 * @return {boolean}
 * @license Public Domain
 * @author Benjamin Arthur Lupton <contact@balupton.com>
 */
/*!
jQuery Waypoints - v2.0.5
Copyright (c) 2011-2014 Caleb Troughton
Licensed under the MIT license.
https://github.com/imakewebthings/jquery-waypoints/blob/master/licenses.txt
*/
var requirejs, require, define;
! function(e) {
    function t(e, t) {
        return v.call(e, t)
    }

    function n(e, t) {
        var n, i, r, s, o, a, l, c, u, h, d, p = t && t.split("/"),
            f = m.map,
            g = f && f["*"] || {};
        if (e && "." === e.charAt(0))
            if (t) {
                for (p = p.slice(0, p.length - 1), e = e.split("/"), o = e.length - 1, m.nodeIdCompat && w.test(e[o]) && (e[o] = e[o].replace(w, "")), e = p.concat(e), u = 0; u < e.length; u += 1)
                    if (d = e[u], "." === d) e.splice(u, 1), u -= 1;
                    else if (".." === d) {
                    if (1 === u && (".." === e[2] || ".." === e[0])) break;
                    u > 0 && (e.splice(u - 1, 2), u -= 2)
                }
                e = e.join("/")
            } else 0 === e.indexOf("./") && (e = e.substring(2));
        if ((p || g) && f) {
            for (n = e.split("/"), u = n.length; u > 0; u -= 1) {
                if (i = n.slice(0, u).join("/"), p)
                    for (h = p.length; h > 0; h -= 1)
                        if (r = f[p.slice(0, h).join("/")], r && (r = r[i])) {
                            s = r, a = u;
                            break
                        }
                if (s) break;
                !l && g && g[i] && (l = g[i], c = u)
            }!s && l && (s = l, a = c), s && (n.splice(0, a, s), e = n.join("/"))
        }
        return e
    }

    function i(t, n) {
        return function() {
            var i = y.call(arguments, 0);
            return "string" != typeof i[0] && 1 === i.length && i.push(null), u.apply(e, i.concat([t, n]))
        }
    }

    function r(e) {
        return function(t) {
            return n(t, e)
        }
    }

    function s(e) {
        return function(t) {
            p[e] = t
        }
    }

    function o(n) {
        if (t(f, n)) {
            var i = f[n];
            delete f[n], g[n] = !0, c.apply(e, i)
        }
        if (!t(p, n) && !t(g, n)) throw new Error("No " + n);
        return p[n]
    }

    function a(e) {
        var t, n = e ? e.indexOf("!") : -1;
        return n > -1 && (t = e.substring(0, n), e = e.substring(n + 1, e.length)), [t, e]
    }

    function l(e) {
        return function() {
            return m && m.config && m.config[e] || {}
        }
    }
    var c, u, h, d, p = {},
        f = {},
        m = {},
        g = {},
        v = Object.prototype.hasOwnProperty,
        y = [].slice,
        w = /\.js$/;
    h = function(e, t) {
        var i, s = a(e),
            l = s[0];
        return e = s[1], l && (l = n(l, t), i = o(l)), l ? e = i && i.normalize ? i.normalize(e, r(t)) : n(e, t) : (e = n(e, t), s = a(e), l = s[0], e = s[1], l && (i = o(l))), {
            f: l ? l + "!" + e : e,
            n: e,
            pr: l,
            p: i
        }
    }, d = {
        require: function(e) {
            return i(e)
        },
        exports: function(e) {
            var t = p[e];
            return "undefined" != typeof t ? t : p[e] = {}
        },
        module: function(e) {
            return {
                id: e,
                uri: "",
                exports: p[e],
                config: l(e)
            }
        }
    }, c = function(n, r, a, l) {
        var c, u, m, v, y, w, b = [],
            _ = typeof a;
        if (l = l || n, "undefined" === _ || "function" === _) {
            for (r = !r.length && a.length ? ["require", "exports", "module"] : r, y = 0; y < r.length; y += 1)
                if (v = h(r[y], l), u = v.f, "require" === u) b[y] = d.require(n);
                else if ("exports" === u) b[y] = d.exports(n), w = !0;
            else if ("module" === u) c = b[y] = d.module(n);
            else if (t(p, u) || t(f, u) || t(g, u)) b[y] = o(u);
            else {
                if (!v.p) throw new Error(n + " missing " + u);
                v.p.load(v.n, i(l, !0), s(u), {}), b[y] = p[u]
            }
            m = a ? a.apply(p[n], b) : void 0, n && (c && c.exports !== e && c.exports !== p[n] ? p[n] = c.exports : m === e && w || (p[n] = m))
        } else n && (p[n] = a)
    }, requirejs = require = u = function(t, n, i, r, s) {
        if ("string" == typeof t) return d[t] ? d[t](n) : o(h(t, n).f);
        if (!t.splice) {
            if (m = t, m.deps && u(m.deps, m.callback), !n) return;
            n.splice ? (t = n, n = i, i = null) : t = e
        }
        return n = n || function() {}, "function" == typeof i && (i = r, r = s), r ? c(e, t, n, i) : setTimeout(function() {
            c(e, t, n, i)
        }, 4), u
    }, u.config = function(e) {
        return u(e)
    }, requirejs._defined = p, define = function(e, n, i) {
        n.splice || (i = n, n = []), t(p, e) || t(f, e) || (f[e] = [e, n, i])
    }, define.amd = {
        jQuery: !0
    }
}(), define("vendor/almond/almond", function() {}),
    function(e, t, n, i) {
        var r = n("html"),
            s = n(e),
            o = n(t),
            a = n.fancybox = function() {
                a.open.apply(this, arguments)
            },
            l = navigator.userAgent.match(/msie/i),
            c = null,
            u = t.createTouch !== i,
            h = function(e) {
                return e && e.hasOwnProperty && e instanceof n
            },
            d = function(e) {
                return e && "string" === n.type(e)
            },
            p = function(e) {
                return d(e) && 0 < e.indexOf("%")
            },
            f = function(e, t) {
                var n = parseInt(e, 10) || 0;
                return t && p(e) && (n *= a.getViewport()[t] / 100), Math.ceil(n)
            },
            m = function(e, t) {
                return f(e, t) + "px"
            };
        n.extend(a, {
            version: "2.1.5",
            defaults: {
                padding: 15,
                margin: 20,
                width: 800,
                height: 600,
                minWidth: 100,
                minHeight: 100,
                maxWidth: 9999,
                maxHeight: 9999,
                pixelRatio: 1,
                autoSize: !0,
                autoHeight: !1,
                autoWidth: !1,
                autoResize: !0,
                autoCenter: !u,
                fitToView: !0,
                aspectRatio: !1,
                topRatio: .5,
                leftRatio: .5,
                scrolling: "auto",
                wrapCSS: "",
                arrows: !0,
                closeBtn: !0,
                closeClick: !1,
                nextClick: !1,
                mouseWheel: !0,
                autoPlay: !1,
                playSpeed: 3e3,
                preload: 3,
                modal: !1,
                loop: !0,
                ajax: {
                    dataType: "html",
                    headers: {
                        "X-fancyBox": !0
                    }
                },
                iframe: {
                    scrolling: "auto",
                    preload: !0
                },
                swf: {
                    wmode: "transparent",
                    allowfullscreen: "true",
                    allowscriptaccess: "always"
                },
                keys: {
                    next: {
                        13: "left",
                        34: "up",
                        39: "left",
                        40: "up"
                    },
                    prev: {
                        8: "right",
                        33: "down",
                        37: "right",
                        38: "down"
                    },
                    close: [27],
                    play: [32],
                    toggle: [70]
                },
                direction: {
                    next: "left",
                    prev: "right"
                },
                scrollOutside: !0,
                index: 0,
                type: null,
                href: null,
                content: null,
                title: null,
                tpl: {
                    wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
                    image: '<img class="fancybox-image" src="{href}" alt="" />',
                    iframe: '<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen' + (l ? ' allowtransparency="true"' : "") + "></iframe>",
                    error: '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
                    closeBtn: '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',
                    next: '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
                    prev: '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
                },
                openEffect: "fade",
                openSpeed: 250,
                openEasing: "swing",
                openOpacity: !0,
                openMethod: "zoomIn",
                closeEffect: "fade",
                closeSpeed: 250,
                closeEasing: "swing",
                closeOpacity: !0,
                closeMethod: "zoomOut",
                nextEffect: "elastic",
                nextSpeed: 250,
                nextEasing: "swing",
                nextMethod: "changeIn",
                prevEffect: "elastic",
                prevSpeed: 250,
                prevEasing: "swing",
                prevMethod: "changeOut",
                helpers: {
                    overlay: !0,
                    title: !0
                },
                onCancel: n.noop,
                beforeLoad: n.noop,
                afterLoad: n.noop,
                beforeShow: n.noop,
                afterShow: n.noop,
                beforeChange: n.noop,
                beforeClose: n.noop,
                afterClose: n.noop
            },
            group: {},
            opts: {},
            previous: null,
            coming: null,
            current: null,
            isActive: !1,
            isOpen: !1,
            isOpened: !1,
            wrap: null,
            skin: null,
            outer: null,
            inner: null,
            player: {
                timer: null,
                isActive: !1
            },
            ajaxLoad: null,
            imgPreload: null,
            transitions: {},
            helpers: {},
            open: function(e, t) {
                return e && (n.isPlainObject(t) || (t = {}), !1 !== a.close(!0)) ? (n.isArray(e) || (e = h(e) ? n(e).get() : [e]), n.each(e, function(r, s) {
                    var o, l, c, u, p, f = {};
                    "object" === n.type(s) && (s.nodeType && (s = n(s)), h(s) ? (f = {
                        href: s.data("fancybox-href") || s.attr("href"),
                        title: s.data("fancybox-title") || s.attr("title"),
                        isDom: !0,
                        element: s
                    }, n.metadata && n.extend(!0, f, s.metadata())) : f = s), o = t.href || f.href || (d(s) ? s : null), l = t.title !== i ? t.title : f.title || "", u = (c = t.content || f.content) ? "html" : t.type || f.type, !u && f.isDom && (u = s.data("fancybox-type"), u || (u = (u = s.prop("class").match(/fancybox\.(\w+)/)) ? u[1] : null)), d(o) && (u || (a.isImage(o) ? u = "image" : a.isSWF(o) ? u = "swf" : "#" === o.charAt(0) ? u = "inline" : d(s) && (u = "html", c = s)), "ajax" === u && (p = o.split(/\s+/, 2), o = p.shift(), p = p.shift())), c || ("inline" === u ? o ? c = n(d(o) ? o.replace(/.*(?=#[^\s]+$)/, "") : o) : f.isDom && (c = s) : "html" === u ? c = o : !u && !o && f.isDom && (u = "inline", c = s)), n.extend(f, {
                        href: o,
                        type: u,
                        content: c,
                        title: l,
                        selector: p
                    }), e[r] = f
                }), a.opts = n.extend(!0, {}, a.defaults, t), t.keys !== i && (a.opts.keys = t.keys ? n.extend({}, a.defaults.keys, t.keys) : !1), a.group = e, a._start(a.opts.index)) : void 0
            },
            cancel: function() {
                var e = a.coming;
                e && !1 !== a.trigger("onCancel") && (a.hideLoading(), a.ajaxLoad && a.ajaxLoad.abort(), a.ajaxLoad = null, a.imgPreload && (a.imgPreload.onload = a.imgPreload.onerror = null), e.wrap && e.wrap.stop(!0, !0).trigger("onReset").remove(), a.coming = null, a.current || a._afterZoomOut(e))
            },
            close: function(e) {
                a.cancel(), !1 !== a.trigger("beforeClose") && (a.unbindEvents(), a.isActive && (a.isOpen && !0 !== e ? (a.isOpen = a.isOpened = !1, a.isClosing = !0, n(".fancybox-item, .fancybox-nav").remove(), a.wrap.stop(!0, !0).removeClass("fancybox-opened"), a.transitions[a.current.closeMethod]()) : (n(".fancybox-wrap").stop(!0).trigger("onReset").remove(), a._afterZoomOut())))
            },
            play: function(e) {
                var t = function() {
                        clearTimeout(a.player.timer)
                    },
                    n = function() {
                        t(), a.current && a.player.isActive && (a.player.timer = setTimeout(a.next, a.current.playSpeed))
                    },
                    i = function() {
                        t(), o.unbind(".player"), a.player.isActive = !1, a.trigger("onPlayEnd")
                    };
                !0 === e || !a.player.isActive && !1 !== e ? a.current && (a.current.loop || a.current.index < a.group.length - 1) && (a.player.isActive = !0, o.bind({
                    "onCancel.player beforeClose.player": i,
                    "onUpdate.player": n,
                    "beforeLoad.player": t
                }), n(), a.trigger("onPlayStart")) : i()
            },
            next: function(e) {
                var t = a.current;
                t && (d(e) || (e = t.direction.next), a.jumpto(t.index + 1, e, "next"))
            },
            prev: function(e) {
                var t = a.current;
                t && (d(e) || (e = t.direction.prev), a.jumpto(t.index - 1, e, "prev"))
            },
            jumpto: function(e, t, n) {
                var r = a.current;
                r && (e = f(e), a.direction = t || r.direction[e >= r.index ? "next" : "prev"], a.router = n || "jumpto", r.loop && (0 > e && (e = r.group.length + e % r.group.length), e %= r.group.length), r.group[e] !== i && (a.cancel(), a._start(e)))
            },
            reposition: function(e, t) {
                var i, r = a.current,
                    s = r ? r.wrap : null;
                s && (i = a._getPosition(t), e && "scroll" === e.type ? (delete i.position, s.stop(!0, !0).animate(i, 200)) : (s.css(i), r.pos = n.extend({}, r.dim, i)))
            },
            update: function(e) {
                var t = e && e.type,
                    n = !t || "orientationchange" === t;
                n && (clearTimeout(c), c = null), a.isOpen && !c && (c = setTimeout(function() {
                    var i = a.current;
                    i && !a.isClosing && (a.wrap.removeClass("fancybox-tmp"), (n || "load" === t || "resize" === t && i.autoResize) && a._setDimension(), "scroll" === t && i.canShrink || a.reposition(e), a.trigger("onUpdate"), c = null)
                }, n && !u ? 0 : 300))
            },
            toggle: function(e) {
                a.isOpen && (a.current.fitToView = "boolean" === n.type(e) ? e : !a.current.fitToView, u && (a.wrap.removeAttr("style").addClass("fancybox-tmp"), a.trigger("onUpdate")), a.update())
            },
            hideLoading: function() {
                o.unbind(".loading"), n("#fancybox-loading").remove()
            },
            showLoading: function() {
                var e, t;
                a.hideLoading(), e = n('<div id="fancybox-loading"><div></div></div>').click(a.cancel).appendTo("body"), o.bind("keydown.loading", function(e) {
                    27 === (e.which || e.keyCode) && (e.preventDefault(), a.cancel())
                }), a.defaults.fixed || (t = a.getViewport(), e.css({
                    position: "absolute",
                    top: .5 * t.h + t.y,
                    left: .5 * t.w + t.x
                }))
            },
            getViewport: function() {
                var t = a.current && a.current.locked || !1,
                    n = {
                        x: s.scrollLeft(),
                        y: s.scrollTop()
                    };
                return t ? (n.w = t[0].clientWidth, n.h = t[0].clientHeight) : (n.w = u && e.innerWidth ? e.innerWidth : s.width(), n.h = u && e.innerHeight ? e.innerHeight : s.height()), n
            },
            unbindEvents: function() {
                a.wrap && h(a.wrap) && a.wrap.unbind(".fb"), o.unbind(".fb"), s.unbind(".fb")
            },
            bindEvents: function() {
                var e, t = a.current;
                t && (s.bind("orientationchange.fb" + (u ? "" : " resize.fb") + (t.autoCenter && !t.locked ? " scroll.fb" : ""), a.update), (e = t.keys) && o.bind("keydown.fb", function(r) {
                    var s = r.which || r.keyCode,
                        o = r.target || r.srcElement;
                    return 27 === s && a.coming ? !1 : void!(r.ctrlKey || r.altKey || r.shiftKey || r.metaKey || o && (o.type || n(o).is("[contenteditable]")) || !n.each(e, function(e, o) {
                        return 1 < t.group.length && o[s] !== i ? (a[e](o[s]), r.preventDefault(), !1) : -1 < n.inArray(s, o) ? (a[e](), r.preventDefault(), !1) : void 0
                    }))
                }), n.fn.mousewheel && t.mouseWheel && a.wrap.bind("mousewheel.fb", function(e, i, r, s) {
                    for (var o = n(e.target || null), l = !1; o.length && !l && !o.is(".fancybox-skin") && !o.is(".fancybox-wrap");) l = o[0] && !(o[0].style.overflow && "hidden" === o[0].style.overflow) && (o[0].clientWidth && o[0].scrollWidth > o[0].clientWidth || o[0].clientHeight && o[0].scrollHeight > o[0].clientHeight), o = n(o).parent();
                    0 !== i && !l && 1 < a.group.length && !t.canShrink && (s > 0 || r > 0 ? a.prev(s > 0 ? "down" : "left") : (0 > s || 0 > r) && a.next(0 > s ? "up" : "right"), e.preventDefault())
                }))
            },
            trigger: function(e, t) {
                var i, r = t || a.coming || a.current;
                if (r) {
                    if (n.isFunction(r[e]) && (i = r[e].apply(r, Array.prototype.slice.call(arguments, 1))), !1 === i) return !1;
                    r.helpers && n.each(r.helpers, function(t, i) {
                        i && a.helpers[t] && n.isFunction(a.helpers[t][e]) && a.helpers[t][e](n.extend(!0, {}, a.helpers[t].defaults, i), r)
                    }), o.trigger(e)
                }
            },
            isImage: function(e) {
                return d(e) && e.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg)((\?|#).*)?$)/i)
            },
            isSWF: function(e) {
                return d(e) && e.match(/\.(swf)((\?|#).*)?$/i)
            },
            _start: function(e) {
                var t, i, r = {};
                if (e = f(e), t = a.group[e] || null, !t) return !1;
                if (r = n.extend(!0, {}, a.opts, t), t = r.margin, i = r.padding, "number" === n.type(t) && (r.margin = [t, t, t, t]), "number" === n.type(i) && (r.padding = [i, i, i, i]), r.modal && n.extend(!0, r, {
                        closeBtn: !1,
                        closeClick: !1,
                        nextClick: !1,
                        arrows: !1,
                        mouseWheel: !1,
                        keys: null,
                        helpers: {
                            overlay: {
                                closeClick: !1
                            }
                        }
                    }), r.autoSize && (r.autoWidth = r.autoHeight = !0), "auto" === r.width && (r.autoWidth = !0), "auto" === r.height && (r.autoHeight = !0), r.group = a.group, r.index = e, a.coming = r, !1 === a.trigger("beforeLoad")) a.coming = null;
                else {
                    if (i = r.type, t = r.href, !i) return a.coming = null, a.current && a.router && "jumpto" !== a.router ? (a.current.index = e, a[a.router](a.direction)) : !1;
                    if (a.isActive = !0, ("image" === i || "swf" === i) && (r.autoHeight = r.autoWidth = !1, r.scrolling = "visible"), "image" === i && (r.aspectRatio = !0), "iframe" === i && u && (r.scrolling = "scroll"), r.wrap = n(r.tpl.wrap).addClass("fancybox-" + (u ? "mobile" : "desktop") + " fancybox-type-" + i + " fancybox-tmp " + r.wrapCSS).appendTo(r.parent || "body"), n.extend(r, {
                            skin: n(".fancybox-skin", r.wrap),
                            outer: n(".fancybox-outer", r.wrap),
                            inner: n(".fancybox-inner", r.wrap)
                        }), n.each(["Top", "Right", "Bottom", "Left"], function(e, t) {
                            r.skin.css("padding" + t, m(r.padding[e]))
                        }), a.trigger("onReady"), "inline" === i || "html" === i) {
                        if (!r.content || !r.content.length) return a._error("content")
                    } else if (!t) return a._error("href");
                    "image" === i ? a._loadImage() : "ajax" === i ? a._loadAjax() : "iframe" === i ? a._loadIframe() : a._afterLoad()
                }
            },
            _error: function(e) {
                n.extend(a.coming, {
                    type: "html",
                    autoWidth: !0,
                    autoHeight: !0,
                    minWidth: 0,
                    minHeight: 0,
                    scrolling: "no",
                    hasError: e,
                    content: a.coming.tpl.error
                }), a._afterLoad()
            },
            _loadImage: function() {
                var e = a.imgPreload = new Image;
                e.onload = function() {
                    this.onload = this.onerror = null, a.coming.width = this.width / a.opts.pixelRatio, a.coming.height = this.height / a.opts.pixelRatio, a._afterLoad()
                }, e.onerror = function() {
                    this.onload = this.onerror = null, a._error("image")
                }, e.src = a.coming.href, !0 !== e.complete && a.showLoading()
            },
            _loadAjax: function() {
                var e = a.coming;
                a.showLoading(), a.ajaxLoad = n.ajax(n.extend({}, e.ajax, {
                    url: e.href,
                    error: function(e, t) {
                        a.coming && "abort" !== t ? a._error("ajax", e) : a.hideLoading()
                    },
                    success: function(t, n) {
                        "success" === n && (e.content = t, a._afterLoad())
                    }
                }))
            },
            _loadIframe: function() {
                var e = a.coming,
                    t = n(e.tpl.iframe.replace(/\{rnd\}/g, (new Date).getTime())).attr("scrolling", u ? "auto" : e.iframe.scrolling).attr("src", e.href);
                n(e.wrap).bind("onReset", function() {
                    try {
                        n(this).find("iframe").hide().attr("src", "//about:blank").end().empty()
                    } catch (e) {}
                }), e.iframe.preload && (a.showLoading(), t.one("load", function() {
                    n(this).data("ready", 1), u || n(this).bind("load.fb", a.update), n(this).parents(".fancybox-wrap").width("100%").removeClass("fancybox-tmp").show(), a._afterLoad()
                })), e.content = t.appendTo(e.inner), e.iframe.preload || a._afterLoad()
            },
            _preloadImages: function() {
                var e, t, n = a.group,
                    i = a.current,
                    r = n.length,
                    s = i.preload ? Math.min(i.preload, r - 1) : 0;
                for (t = 1; s >= t; t += 1) e = n[(i.index + t) % r], "image" === e.type && e.href && ((new Image).src = e.href)
            },
            _afterLoad: function() {
                var e, t, i, r, s, o = a.coming,
                    l = a.current;
                if (a.hideLoading(), o && !1 !== a.isActive)
                    if (!1 === a.trigger("afterLoad", o, l)) o.wrap.stop(!0).trigger("onReset").remove(), a.coming = null;
                    else {
                        switch (l && (a.trigger("beforeChange", l), l.wrap.stop(!0).removeClass("fancybox-opened").find(".fancybox-item, .fancybox-nav").remove()), a.unbindEvents(), e = o.content, t = o.type, i = o.scrolling, n.extend(a, {
                            wrap: o.wrap,
                            skin: o.skin,
                            outer: o.outer,
                            inner: o.inner,
                            current: o,
                            previous: l
                        }), r = o.href, t) {
                            case "inline":
                            case "ajax":
                            case "html":
                                o.selector ? e = n("<div>").html(e).find(o.selector) : h(e) && (e.data("fancybox-placeholder") || e.data("fancybox-placeholder", n('<div class="fancybox-placeholder"></div>').insertAfter(e).hide()), e = e.show().detach(), o.wrap.bind("onReset", function() {
                                    n(this).find(e).length && e.hide().replaceAll(e.data("fancybox-placeholder")).data("fancybox-placeholder", !1)
                                }));
                                break;
                            case "image":
                                e = o.tpl.image.replace("{href}", r);
                                break;
                            case "swf":
                                e = '<object id="fancybox-swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="' + r + '"></param>', s = "", n.each(o.swf, function(t, n) {
                                    e += '<param name="' + t + '" value="' + n + '"></param>', s += " " + t + '="' + n + '"'
                                }), e += '<embed src="' + r + '" type="application/x-shockwave-flash" width="100%" height="100%"' + s + "></embed></object>"
                        }(!h(e) || !e.parent().is(o.inner)) && o.inner.append(e), a.trigger("beforeShow"), o.inner.css("overflow", "yes" === i ? "scroll" : "no" === i ? "hidden" : i), a._setDimension(), a.reposition(), a.isOpen = !1, a.coming = null, a.bindEvents(), a.isOpened ? l.prevMethod && a.transitions[l.prevMethod]() : n(".fancybox-wrap").not(o.wrap).stop(!0).trigger("onReset").remove(), a.transitions[a.isOpened ? o.nextMethod : o.openMethod](), a._preloadImages()
                    }
            },
            _setDimension: function() {
                var e, t, i, r, s, o, l, c, u, h = a.getViewport(),
                    d = 0,
                    g = !1,
                    v = !1,
                    g = a.wrap,
                    y = a.skin,
                    w = a.inner,
                    b = a.current,
                    v = b.width,
                    _ = b.height,
                    x = b.minWidth,
                    S = b.minHeight,
                    k = b.maxWidth,
                    C = b.maxHeight,
                    T = b.scrolling,
                    E = b.scrollOutside ? b.scrollbarWidth : 0,
                    $ = b.margin,
                    A = f($[1] + $[3]),
                    P = f($[0] + $[2]);
                if (g.add(y).add(w).width("auto").height("auto").removeClass("fancybox-tmp"), $ = f(y.outerWidth(!0) - y.width()), e = f(y.outerHeight(!0) - y.height()), t = A + $, i = P + e, r = p(v) ? (h.w - t) * f(v) / 100 : v, s = p(_) ? (h.h - i) * f(_) / 100 : _, "iframe" === b.type) {
                    if (u = b.content, b.autoHeight && 1 === u.data("ready")) try {
                        u[0].contentWindow.document.location && (w.width(r).height(9999), o = u.contents().find("body"), E && o.css("overflow-x", "hidden"), s = o.outerHeight(!0))
                    } catch (j) {}
                } else(b.autoWidth || b.autoHeight) && (w.addClass("fancybox-tmp"), b.autoWidth || w.width(r), b.autoHeight || w.height(s), b.autoWidth && (r = w.width()), b.autoHeight && (s = w.height()), w.removeClass("fancybox-tmp"));
                if (v = f(r), _ = f(s), c = r / s, x = f(p(x) ? f(x, "w") - t : x), k = f(p(k) ? f(k, "w") - t : k), S = f(p(S) ? f(S, "h") - i : S), C = f(p(C) ? f(C, "h") - i : C), o = k, l = C, b.fitToView && (k = Math.min(h.w - t, k), C = Math.min(h.h - i, C)), t = h.w - A, P = h.h - P, b.aspectRatio ? (v > k && (v = k, _ = f(v / c)), _ > C && (_ = C, v = f(_ * c)), x > v && (v = x, _ = f(v / c)), S > _ && (_ = S, v = f(_ * c))) : (v = Math.max(x, Math.min(v, k)), b.autoHeight && "iframe" !== b.type && (w.width(v), _ = w.height()), _ = Math.max(S, Math.min(_, C))), b.fitToView)
                    if (w.width(v).height(_), g.width(v + $), h = g.width(), A = g.height(), b.aspectRatio)
                        for (;
                            (h > t || A > P) && v > x && _ > S && !(19 < d++);) _ = Math.max(S, Math.min(C, _ - 10)), v = f(_ * c), x > v && (v = x, _ = f(v / c)), v > k && (v = k, _ = f(v / c)), w.width(v).height(_), g.width(v + $), h = g.width(), A = g.height();
                    else v = Math.max(x, Math.min(v, v - (h - t))), _ = Math.max(S, Math.min(_, _ - (A - P)));
                E && "auto" === T && s > _ && t > v + $ + E && (v += E), w.width(v).height(_), g.width(v + $), h = g.width(), A = g.height(), g = (h > t || A > P) && v > x && _ > S, v = b.aspectRatio ? o > v && l > _ && r > v && s > _ : (o > v || l > _) && (r > v || s > _), n.extend(b, {
                    dim: {
                        width: m(h),
                        height: m(A)
                    },
                    origWidth: r,
                    origHeight: s,
                    canShrink: g,
                    canExpand: v,
                    wPadding: $,
                    hPadding: e,
                    wrapSpace: A - y.outerHeight(!0),
                    skinSpace: y.height() - _
                }), !u && b.autoHeight && _ > S && C > _ && !v && w.height("auto")
            },
            _getPosition: function(e) {
                var t = a.current,
                    n = a.getViewport(),
                    i = t.margin,
                    r = a.wrap.width() + i[1] + i[3],
                    s = a.wrap.height() + i[0] + i[2],
                    i = {
                        position: "absolute",
                        top: i[0],
                        left: i[3]
                    };
                return t.autoCenter && t.fixed && !e && s <= n.h && r <= n.w ? i.position = "fixed" : t.locked || (i.top += n.y, i.left += n.x), i.top = m(Math.max(i.top, i.top + (n.h - s) * t.topRatio)), i.left = m(Math.max(i.left, i.left + (n.w - r) * t.leftRatio)), i
            },
            _afterZoomIn: function() {
                var e = a.current;
                e && (a.isOpen = a.isOpened = !0, a.wrap.css("overflow", "visible").addClass("fancybox-opened"), a.update(), (e.closeClick || e.nextClick && 1 < a.group.length) && a.inner.css("cursor", "pointer").bind("click.fb", function(t) {
                    !n(t.target).is("a") && !n(t.target).parent().is("a") && (t.preventDefault(), a[e.closeClick ? "close" : "next"]())
                }), e.closeBtn && n(e.tpl.closeBtn).appendTo(a.skin).bind("click.fb", function(e) {
                    e.preventDefault(), a.close()
                }), e.arrows && 1 < a.group.length && ((e.loop || 0 < e.index) && n(e.tpl.prev).appendTo(a.outer).bind("click.fb", a.prev), (e.loop || e.index < a.group.length - 1) && n(e.tpl.next).appendTo(a.outer).bind("click.fb", a.next)), a.trigger("afterShow"), e.loop || e.index !== e.group.length - 1 ? a.opts.autoPlay && !a.player.isActive && (a.opts.autoPlay = !1, a.play()) : a.play(!1))
            },
            _afterZoomOut: function(e) {
                e = e || a.current, n(".fancybox-wrap").trigger("onReset").remove(), n.extend(a, {
                    group: {},
                    opts: {},
                    router: !1,
                    current: null,
                    isActive: !1,
                    isOpened: !1,
                    isOpen: !1,
                    isClosing: !1,
                    wrap: null,
                    skin: null,
                    outer: null,
                    inner: null
                }), a.trigger("afterClose", e)
            }
        }), a.transitions = {
            getOrigPosition: function() {
                var e = a.current,
                    t = e.element,
                    n = e.orig,
                    i = {},
                    r = 50,
                    s = 50,
                    o = e.hPadding,
                    l = e.wPadding,
                    c = a.getViewport();
                return !n && e.isDom && t.is(":visible") && (n = t.find("img:first"), n.length || (n = t)), h(n) ? (i = n.offset(), n.is("img") && (r = n.outerWidth(), s = n.outerHeight())) : (i.top = c.y + (c.h - s) * e.topRatio, i.left = c.x + (c.w - r) * e.leftRatio), ("fixed" === a.wrap.css("position") || e.locked) && (i.top -= c.y, i.left -= c.x), i = {
                    top: m(i.top - o * e.topRatio),
                    left: m(i.left - l * e.leftRatio),
                    width: m(r + l),
                    height: m(s + o)
                }
            },
            step: function(e, t) {
                var n, i, r = t.prop;
                i = a.current;
                var s = i.wrapSpace,
                    o = i.skinSpace;
                ("width" === r || "height" === r) && (n = t.end === t.start ? 1 : (e - t.start) / (t.end - t.start), a.isClosing && (n = 1 - n), i = "width" === r ? i.wPadding : i.hPadding, i = e - i, a.skin[r](f("width" === r ? i : i - s * n)), a.inner[r](f("width" === r ? i : i - s * n - o * n)))
            },
            zoomIn: function() {
                var e = a.current,
                    t = e.pos,
                    i = e.openEffect,
                    r = "elastic" === i,
                    s = n.extend({
                        opacity: 1
                    }, t);
                delete s.position, r ? (t = this.getOrigPosition(), e.openOpacity && (t.opacity = .1)) : "fade" === i && (t.opacity = .1), a.wrap.css(t).animate(s, {
                    duration: "none" === i ? 0 : e.openSpeed,
                    easing: e.openEasing,
                    step: r ? this.step : null,
                    complete: a._afterZoomIn
                })
            },
            zoomOut: function() {
                var e = a.current,
                    t = e.closeEffect,
                    n = "elastic" === t,
                    i = {
                        opacity: .1
                    };
                n && (i = this.getOrigPosition(), e.closeOpacity && (i.opacity = .1)), a.wrap.animate(i, {
                    duration: "none" === t ? 0 : e.closeSpeed,
                    easing: e.closeEasing,
                    step: n ? this.step : null,
                    complete: a._afterZoomOut
                })
            },
            changeIn: function() {
                var e, t = a.current,
                    n = t.nextEffect,
                    i = t.pos,
                    r = {
                        opacity: 1
                    },
                    s = a.direction;
                i.opacity = .1, "elastic" === n && (e = "down" === s || "up" === s ? "top" : "left", "down" === s || "right" === s ? (i[e] = m(f(i[e]) - 200), r[e] = "+=200px") : (i[e] = m(f(i[e]) + 200), r[e] = "-=200px")), "none" === n ? a._afterZoomIn() : a.wrap.css(i).animate(r, {
                    duration: t.nextSpeed,
                    easing: t.nextEasing,
                    complete: a._afterZoomIn
                })
            },
            changeOut: function() {
                var e = a.previous,
                    t = e.prevEffect,
                    i = {
                        opacity: .1
                    },
                    r = a.direction;
                "elastic" === t && (i["down" === r || "up" === r ? "top" : "left"] = ("up" === r || "left" === r ? "-" : "+") + "=200px"), e.wrap.animate(i, {
                    duration: "none" === t ? 0 : e.prevSpeed,
                    easing: e.prevEasing,
                    complete: function() {
                        n(this).trigger("onReset").remove()
                    }
                })
            }
        }, a.helpers.overlay = {
            defaults: {
                closeClick: !0,
                speedOut: 200,
                showEarly: !0,
                css: {},
                locked: !u,
                fixed: !0
            },
            overlay: null,
            fixed: !1,
            el: n("html"),
            create: function(e) {
                e = n.extend({}, this.defaults, e), this.overlay && this.close(), this.overlay = n('<div class="fancybox-overlay"></div>').appendTo(a.coming ? a.coming.parent : e.parent), this.fixed = !1, e.fixed && a.defaults.fixed && (this.overlay.addClass("fancybox-overlay-fixed"), this.fixed = !0)
            },
            open: function(e) {
                var t = this;
                e = n.extend({}, this.defaults, e), this.overlay ? this.overlay.unbind(".overlay").width("auto").height("auto") : this.create(e), this.fixed || (s.bind("resize.overlay", n.proxy(this.update, this)), this.update()), e.closeClick && this.overlay.bind("click.overlay", function(e) {
                    return n(e.target).hasClass("fancybox-overlay") ? (a.isActive ? a.close() : t.close(), !1) : void 0
                }), this.overlay.css(e.css).show()
            },
            close: function() {
                var e, t;
                s.unbind("resize.overlay"), this.el.hasClass("fancybox-lock") && (n(".fancybox-margin").removeClass("fancybox-margin"), e = s.scrollTop(), t = s.scrollLeft(), this.el.removeClass("fancybox-lock"), s.scrollTop(e).scrollLeft(t)), n(".fancybox-overlay").remove().hide(), n.extend(this, {
                    overlay: null,
                    fixed: !1
                })
            },
            update: function() {
                var e, n = "100%";
                this.overlay.width(n).height("100%"), l ? (e = Math.max(t.documentElement.offsetWidth, t.body.offsetWidth), o.width() > e && (n = o.width())) : o.width() > s.width() && (n = o.width()), this.overlay.width(n).height(o.height())
            },
            onReady: function(e, t) {
                var i = this.overlay;
                n(".fancybox-overlay").stop(!0, !0), i || this.create(e), e.locked && this.fixed && t.fixed && (i || (this.margin = o.height() > s.height() ? n("html").css("margin-right").replace("px", "") : !1), t.locked = this.overlay.append(t.wrap), t.fixed = !1), !0 === e.showEarly && this.beforeShow.apply(this, arguments)
            },
            beforeShow: function(e, t) {
                var i, r;
                t.locked && (!1 !== this.margin && (n("*").filter(function() {
                    return "fixed" === n(this).css("position") && !n(this).hasClass("fancybox-overlay") && !n(this).hasClass("fancybox-wrap")
                }).addClass("fancybox-margin"), this.el.addClass("fancybox-margin")), i = s.scrollTop(), r = s.scrollLeft(), this.el.addClass("fancybox-lock"), s.scrollTop(i).scrollLeft(r)), this.open(e)
            },
            onUpdate: function() {
                this.fixed || this.update()
            },
            afterClose: function(e) {
                this.overlay && !a.coming && this.overlay.fadeOut(e.speedOut, n.proxy(this.close, this))
            }
        }, a.helpers.title = {
            defaults: {
                type: "float",
                position: "bottom"
            },
            beforeShow: function(e) {
                var t = a.current,
                    i = t.title,
                    r = e.type;
                if (n.isFunction(i) && (i = i.call(t.element, t)), d(i) && "" !== n.trim(i)) {
                    switch (t = n('<div class="fancybox-title fancybox-title-' + r + '-wrap">' + i + "</div>"), r) {
                        case "inside":
                            r = a.skin;
                            break;
                        case "outside":
                            r = a.wrap;
                            break;
                        case "over":
                            r = a.inner;
                            break;
                        default:
                            r = a.skin, t.appendTo("body"), l && t.width(t.width()), t.wrapInner('<span class="child"></span>'), a.current.margin[2] += Math.abs(f(t.css("margin-bottom")))
                    }
                    t["top" === e.position ? "prependTo" : "appendTo"](r)
                }
            }
        }, n.fn.fancybox = function(e) {
            var t, i = n(this),
                r = this.selector || "",
                s = function(s) {
                    var o, l, c = n(this).blur(),
                        u = t;
                    !(s.ctrlKey || s.altKey || s.shiftKey || s.metaKey || c.is(".fancybox-wrap") || (o = e.groupAttr || "data-fancybox-group", l = c.attr(o), l || (o = "rel", l = c.get(0)[o]), l && "" !== l && "nofollow" !== l && (c = r.length ? n(r) : i, c = c.filter("[" + o + '="' + l + '"]'), u = c.index(this)), e.index = u, !1 === a.open(c, e) || !s.preventDefault()))
                };
            return e = e || {}, t = e.index || 0, r && !1 !== e.live ? o.undelegate(r, "click.fb-start").delegate(r + ":not('.fancybox-item, .fancybox-nav')", "click.fb-start", s) : i.unbind("click.fb-start").bind("click.fb-start", s), this.filter("[data-fancybox-start=1]").trigger("click"), this
        }, o.ready(function() {
            var t, s;
            if (n.scrollbarWidth === i && (n.scrollbarWidth = function() {
                    var e = n('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo("body"),
                        t = e.children(),
                        t = t.innerWidth() - t.height(99).innerWidth();
                    return e.remove(), t
                }), n.support.fixedPosition === i) {
                t = n.support, s = n('<div style="position:fixed;top:20px;"></div>').appendTo("body");
                var o = 20 === s[0].offsetTop || 15 === s[0].offsetTop;
                s.remove(), t.fixedPosition = o
            }
            n.extend(a.defaults, {
                scrollbarWidth: n.scrollbarWidth(),
                fixed: n.support.fixedPosition,
                parent: n("body")
            }), t = n(e).width(), r.addClass("fancybox-lock-test"), s = n(e).width(), r.removeClass("fancybox-lock-test"), n("<style type='text/css'>.fancybox-margin{margin-right:" + (s - t) + "px;}</style>").appendTo("head")
        })
    }(window, document, jQuery), define("fancybox", function() {}), define("common/fancybox", ["jquery", "fancybox"], function(e) {
        e("body").on("touchmove", function(t) {
            e(".fancybox-overlay").length && t.preventDefault()
        }), e(".js-has-video").fancybox({
            type: "iframe",
            padding: 0,
            fitToView: !0,
            aspectRatio: !0,
            width: 16 / 9 * (screen.height / 2),
            height: screen.height / 2,
            autoSize: !0,
            openEffect: "none",
            closeEffect: "none"
        }), e(".js-has-video--html5").fancybox({
            type: "inline",
            padding: 0,
            fitToView: !0,
            aspectRatio: !0,
            width: 16 / 9 * (screen.height / 2),
            height: screen.height / 2,
            autoSize: !1,
            openEffect: "none",
            closeEffect: "none",
            afterShow: function() {
                e(".fancybox-overlay .videoplayer").get(0).play()
            },
            afterClose: function() {
                e(".videoplayer").get(0).currentTime = 0
            }
        })
    }),
    function() {
        var e = this,
            t = e._,
            n = Array.prototype,
            i = Object.prototype,
            r = Function.prototype,
            s = n.push,
            o = n.slice,
            a = n.concat,
            l = i.toString,
            c = i.hasOwnProperty,
            u = Array.isArray,
            h = Object.keys,
            d = r.bind,
            p = function(e) {
                return e instanceof p ? e : this instanceof p ? void(this._wrapped = e) : new p(e)
            };
        "undefined" != typeof exports ? ("undefined" != typeof module && module.exports && (exports = module.exports = p), exports._ = p) : e._ = p, p.VERSION = "1.7.0";
        var f = function(e, t, n) {
            if (void 0 === t) return e;
            switch (null == n ? 3 : n) {
                case 1:
                    return function(n) {
                        return e.call(t, n)
                    };
                case 2:
                    return function(n, i) {
                        return e.call(t, n, i)
                    };
                case 3:
                    return function(n, i, r) {
                        return e.call(t, n, i, r)
                    };
                case 4:
                    return function(n, i, r, s) {
                        return e.call(t, n, i, r, s)
                    }
            }
            return function() {
                return e.apply(t, arguments)
            }
        };
        p.iteratee = function(e, t, n) {
            return null == e ? p.identity : p.isFunction(e) ? f(e, t, n) : p.isObject(e) ? p.matches(e) : p.property(e)
        }, p.each = p.forEach = function(e, t, n) {
            if (null == e) return e;
            t = f(t, n);
            var i, r = e.length;
            if (r === +r)
                for (i = 0; r > i; i++) t(e[i], i, e);
            else {
                var s = p.keys(e);
                for (i = 0, r = s.length; r > i; i++) t(e[s[i]], s[i], e)
            }
            return e
        }, p.map = p.collect = function(e, t, n) {
            if (null == e) return [];
            t = p.iteratee(t, n);
            for (var i, r = e.length !== +e.length && p.keys(e), s = (r || e).length, o = Array(s), a = 0; s > a; a++) i = r ? r[a] : a, o[a] = t(e[i], i, e);
            return o
        };
        var m = "Reduce of empty array with no initial value";
        p.reduce = p.foldl = p.inject = function(e, t, n, i) {
            null == e && (e = []), t = f(t, i, 4);
            var r, s = e.length !== +e.length && p.keys(e),
                o = (s || e).length,
                a = 0;
            if (arguments.length < 3) {
                if (!o) throw new TypeError(m);
                n = e[s ? s[a++] : a++]
            }
            for (; o > a; a++) r = s ? s[a] : a, n = t(n, e[r], r, e);
            return n
        }, p.reduceRight = p.foldr = function(e, t, n, i) {
            null == e && (e = []), t = f(t, i, 4);
            var r, s = e.length !== +e.length && p.keys(e),
                o = (s || e).length;
            if (arguments.length < 3) {
                if (!o) throw new TypeError(m);
                n = e[s ? s[--o] : --o]
            }
            for (; o--;) r = s ? s[o] : o, n = t(n, e[r], r, e);
            return n
        }, p.find = p.detect = function(e, t, n) {
            var i;
            return t = p.iteratee(t, n), p.some(e, function(e, n, r) {
                return t(e, n, r) ? (i = e, !0) : void 0
            }), i
        }, p.filter = p.select = function(e, t, n) {
            var i = [];
            return null == e ? i : (t = p.iteratee(t, n), p.each(e, function(e, n, r) {
                t(e, n, r) && i.push(e)
            }), i)
        }, p.reject = function(e, t, n) {
            return p.filter(e, p.negate(p.iteratee(t)), n)
        }, p.every = p.all = function(e, t, n) {
            if (null == e) return !0;
            t = p.iteratee(t, n);
            var i, r, s = e.length !== +e.length && p.keys(e),
                o = (s || e).length;
            for (i = 0; o > i; i++)
                if (r = s ? s[i] : i, !t(e[r], r, e)) return !1;
            return !0
        }, p.some = p.any = function(e, t, n) {
            if (null == e) return !1;
            t = p.iteratee(t, n);
            var i, r, s = e.length !== +e.length && p.keys(e),
                o = (s || e).length;
            for (i = 0; o > i; i++)
                if (r = s ? s[i] : i, t(e[r], r, e)) return !0;
            return !1
        }, p.contains = p.include = function(e, t) {
            return null == e ? !1 : (e.length !== +e.length && (e = p.values(e)), p.indexOf(e, t) >= 0)
        }, p.invoke = function(e, t) {
            var n = o.call(arguments, 2),
                i = p.isFunction(t);
            return p.map(e, function(e) {
                return (i ? t : e[t]).apply(e, n)
            })
        }, p.pluck = function(e, t) {
            return p.map(e, p.property(t))
        }, p.where = function(e, t) {
            return p.filter(e, p.matches(t))
        }, p.findWhere = function(e, t) {
            return p.find(e, p.matches(t))
        }, p.max = function(e, t, n) {
            var i, r, s = -1 / 0,
                o = -1 / 0;
            if (null == t && null != e) {
                e = e.length === +e.length ? e : p.values(e);
                for (var a = 0, l = e.length; l > a; a++) i = e[a], i > s && (s = i)
            } else t = p.iteratee(t, n), p.each(e, function(e, n, i) {
                r = t(e, n, i), (r > o || r === -1 / 0 && s === -1 / 0) && (s = e, o = r)
            });
            return s
        }, p.min = function(e, t, n) {
            var i, r, s = 1 / 0,
                o = 1 / 0;
            if (null == t && null != e) {
                e = e.length === +e.length ? e : p.values(e);
                for (var a = 0, l = e.length; l > a; a++) i = e[a], s > i && (s = i)
            } else t = p.iteratee(t, n), p.each(e, function(e, n, i) {
                r = t(e, n, i), (o > r || 1 / 0 === r && 1 / 0 === s) && (s = e, o = r)
            });
            return s
        }, p.shuffle = function(e) {
            for (var t, n = e && e.length === +e.length ? e : p.values(e), i = n.length, r = Array(i), s = 0; i > s; s++) t = p.random(0, s), t !== s && (r[s] = r[t]), r[t] = n[s];
            return r
        }, p.sample = function(e, t, n) {
            return null == t || n ? (e.length !== +e.length && (e = p.values(e)), e[p.random(e.length - 1)]) : p.shuffle(e).slice(0, Math.max(0, t))
        }, p.sortBy = function(e, t, n) {
            return t = p.iteratee(t, n), p.pluck(p.map(e, function(e, n, i) {
                return {
                    value: e,
                    index: n,
                    criteria: t(e, n, i)
                }
            }).sort(function(e, t) {
                var n = e.criteria,
                    i = t.criteria;
                if (n !== i) {
                    if (n > i || void 0 === n) return 1;
                    if (i > n || void 0 === i) return -1
                }
                return e.index - t.index
            }), "value")
        };
        var g = function(e) {
            return function(t, n, i) {
                var r = {};
                return n = p.iteratee(n, i), p.each(t, function(i, s) {
                    var o = n(i, s, t);
                    e(r, i, o)
                }), r
            }
        };
        p.groupBy = g(function(e, t, n) {
            p.has(e, n) ? e[n].push(t) : e[n] = [t]
        }), p.indexBy = g(function(e, t, n) {
            e[n] = t
        }), p.countBy = g(function(e, t, n) {
            p.has(e, n) ? e[n] ++ : e[n] = 1
        }), p.sortedIndex = function(e, t, n, i) {
            n = p.iteratee(n, i, 1);
            for (var r = n(t), s = 0, o = e.length; o > s;) {
                var a = s + o >>> 1;
                n(e[a]) < r ? s = a + 1 : o = a
            }
            return s
        }, p.toArray = function(e) {
            return e ? p.isArray(e) ? o.call(e) : e.length === +e.length ? p.map(e, p.identity) : p.values(e) : []
        }, p.size = function(e) {
            return null == e ? 0 : e.length === +e.length ? e.length : p.keys(e).length
        }, p.partition = function(e, t, n) {
            t = p.iteratee(t, n);
            var i = [],
                r = [];
            return p.each(e, function(e, n, s) {
                (t(e, n, s) ? i : r).push(e)
            }), [i, r]
        }, p.first = p.head = p.take = function(e, t, n) {
            return null == e ? void 0 : null == t || n ? e[0] : 0 > t ? [] : o.call(e, 0, t)
        }, p.initial = function(e, t, n) {
            return o.call(e, 0, Math.max(0, e.length - (null == t || n ? 1 : t)))
        }, p.last = function(e, t, n) {
            return null == e ? void 0 : null == t || n ? e[e.length - 1] : o.call(e, Math.max(e.length - t, 0))
        }, p.rest = p.tail = p.drop = function(e, t, n) {
            return o.call(e, null == t || n ? 1 : t)
        }, p.compact = function(e) {
            return p.filter(e, p.identity)
        };
        var v = function(e, t, n, i) {
            if (t && p.every(e, p.isArray)) return a.apply(i, e);
            for (var r = 0, o = e.length; o > r; r++) {
                var l = e[r];
                p.isArray(l) || p.isArguments(l) ? t ? s.apply(i, l) : v(l, t, n, i) : n || i.push(l)
            }
            return i
        };
        p.flatten = function(e, t) {
            return v(e, t, !1, [])
        }, p.without = function(e) {
            return p.difference(e, o.call(arguments, 1))
        }, p.uniq = p.unique = function(e, t, n, i) {
            if (null == e) return [];
            p.isBoolean(t) || (i = n, n = t, t = !1), null != n && (n = p.iteratee(n, i));
            for (var r = [], s = [], o = 0, a = e.length; a > o; o++) {
                var l = e[o];
                if (t) o && s === l || r.push(l), s = l;
                else if (n) {
                    var c = n(l, o, e);
                    p.indexOf(s, c) < 0 && (s.push(c), r.push(l))
                } else p.indexOf(r, l) < 0 && r.push(l)
            }
            return r
        }, p.union = function() {
            return p.uniq(v(arguments, !0, !0, []))
        }, p.intersection = function(e) {
            if (null == e) return [];
            for (var t = [], n = arguments.length, i = 0, r = e.length; r > i; i++) {
                var s = e[i];
                if (!p.contains(t, s)) {
                    for (var o = 1; n > o && p.contains(arguments[o], s); o++);
                    o === n && t.push(s)
                }
            }
            return t
        }, p.difference = function(e) {
            var t = v(o.call(arguments, 1), !0, !0, []);
            return p.filter(e, function(e) {
                return !p.contains(t, e)
            })
        }, p.zip = function(e) {
            if (null == e) return [];
            for (var t = p.max(arguments, "length").length, n = Array(t), i = 0; t > i; i++) n[i] = p.pluck(arguments, i);
            return n
        }, p.object = function(e, t) {
            if (null == e) return {};
            for (var n = {}, i = 0, r = e.length; r > i; i++) t ? n[e[i]] = t[i] : n[e[i][0]] = e[i][1];
            return n
        }, p.indexOf = function(e, t, n) {
            if (null == e) return -1;
            var i = 0,
                r = e.length;
            if (n) {
                if ("number" != typeof n) return i = p.sortedIndex(e, t), e[i] === t ? i : -1;
                i = 0 > n ? Math.max(0, r + n) : n
            }
            for (; r > i; i++)
                if (e[i] === t) return i;
            return -1
        }, p.lastIndexOf = function(e, t, n) {
            if (null == e) return -1;
            var i = e.length;
            for ("number" == typeof n && (i = 0 > n ? i + n + 1 : Math.min(i, n + 1)); --i >= 0;)
                if (e[i] === t) return i;
            return -1
        }, p.range = function(e, t, n) {
            arguments.length <= 1 && (t = e || 0, e = 0), n = n || 1;
            for (var i = Math.max(Math.ceil((t - e) / n), 0), r = Array(i), s = 0; i > s; s++, e += n) r[s] = e;
            return r
        };
        var y = function() {};
        p.bind = function(e, t) {
            var n, i;
            if (d && e.bind === d) return d.apply(e, o.call(arguments, 1));
            if (!p.isFunction(e)) throw new TypeError("Bind must be called on a function");
            return n = o.call(arguments, 2), i = function() {
                if (!(this instanceof i)) return e.apply(t, n.concat(o.call(arguments)));
                y.prototype = e.prototype;
                var r = new y;
                y.prototype = null;
                var s = e.apply(r, n.concat(o.call(arguments)));
                return p.isObject(s) ? s : r
            }
        }, p.partial = function(e) {
            var t = o.call(arguments, 1);
            return function() {
                for (var n = 0, i = t.slice(), r = 0, s = i.length; s > r; r++) i[r] === p && (i[r] = arguments[n++]);
                for (; n < arguments.length;) i.push(arguments[n++]);
                return e.apply(this, i)
            }
        }, p.bindAll = function(e) {
            var t, n, i = arguments.length;
            if (1 >= i) throw new Error("bindAll must be passed function names");
            for (t = 1; i > t; t++) n = arguments[t], e[n] = p.bind(e[n], e);
            return e
        }, p.memoize = function(e, t) {
            var n = function(i) {
                var r = n.cache,
                    s = t ? t.apply(this, arguments) : i;
                return p.has(r, s) || (r[s] = e.apply(this, arguments)), r[s]
            };
            return n.cache = {}, n
        }, p.delay = function(e, t) {
            var n = o.call(arguments, 2);
            return setTimeout(function() {
                return e.apply(null, n)
            }, t)
        }, p.defer = function(e) {
            return p.delay.apply(p, [e, 1].concat(o.call(arguments, 1)))
        }, p.throttle = function(e, t, n) {
            var i, r, s, o = null,
                a = 0;
            n || (n = {});
            var l = function() {
                a = n.leading === !1 ? 0 : p.now(), o = null, s = e.apply(i, r), o || (i = r = null)
            };
            return function() {
                var c = p.now();
                a || n.leading !== !1 || (a = c);
                var u = t - (c - a);
                return i = this, r = arguments, 0 >= u || u > t ? (clearTimeout(o), o = null, a = c, s = e.apply(i, r), o || (i = r = null)) : o || n.trailing === !1 || (o = setTimeout(l, u)), s
            }
        }, p.debounce = function(e, t, n) {
            var i, r, s, o, a, l = function() {
                var c = p.now() - o;
                t > c && c > 0 ? i = setTimeout(l, t - c) : (i = null, n || (a = e.apply(s, r), i || (s = r = null)))
            };
            return function() {
                s = this, r = arguments, o = p.now();
                var c = n && !i;
                return i || (i = setTimeout(l, t)), c && (a = e.apply(s, r), s = r = null), a
            }
        }, p.wrap = function(e, t) {
            return p.partial(t, e)
        }, p.negate = function(e) {
            return function() {
                return !e.apply(this, arguments)
            }
        }, p.compose = function() {
            var e = arguments,
                t = e.length - 1;
            return function() {
                for (var n = t, i = e[t].apply(this, arguments); n--;) i = e[n].call(this, i);
                return i
            }
        }, p.after = function(e, t) {
            return function() {
                return --e < 1 ? t.apply(this, arguments) : void 0
            }
        }, p.before = function(e, t) {
            var n;
            return function() {
                return --e > 0 ? n = t.apply(this, arguments) : t = null, n
            }
        }, p.once = p.partial(p.before, 2), p.keys = function(e) {
            if (!p.isObject(e)) return [];
            if (h) return h(e);
            var t = [];
            for (var n in e) p.has(e, n) && t.push(n);
            return t
        }, p.values = function(e) {
            for (var t = p.keys(e), n = t.length, i = Array(n), r = 0; n > r; r++) i[r] = e[t[r]];
            return i
        }, p.pairs = function(e) {
            for (var t = p.keys(e), n = t.length, i = Array(n), r = 0; n > r; r++) i[r] = [t[r], e[t[r]]];
            return i
        }, p.invert = function(e) {
            for (var t = {}, n = p.keys(e), i = 0, r = n.length; r > i; i++) t[e[n[i]]] = n[i];
            return t
        }, p.functions = p.methods = function(e) {
            var t = [];
            for (var n in e) p.isFunction(e[n]) && t.push(n);
            return t.sort()
        }, p.extend = function(e) {
            if (!p.isObject(e)) return e;
            for (var t, n, i = 1, r = arguments.length; r > i; i++) {
                t = arguments[i];
                for (n in t) c.call(t, n) && (e[n] = t[n])
            }
            return e
        }, p.pick = function(e, t, n) {
            var i, r = {};
            if (null == e) return r;
            if (p.isFunction(t)) {
                t = f(t, n);
                for (i in e) {
                    var s = e[i];
                    t(s, i, e) && (r[i] = s)
                }
            } else {
                var l = a.apply([], o.call(arguments, 1));
                e = new Object(e);
                for (var c = 0, u = l.length; u > c; c++) i = l[c], i in e && (r[i] = e[i])
            }
            return r
        }, p.omit = function(e, t, n) {
            if (p.isFunction(t)) t = p.negate(t);
            else {
                var i = p.map(a.apply([], o.call(arguments, 1)), String);
                t = function(e, t) {
                    return !p.contains(i, t)
                }
            }
            return p.pick(e, t, n)
        }, p.defaults = function(e) {
            if (!p.isObject(e)) return e;
            for (var t = 1, n = arguments.length; n > t; t++) {
                var i = arguments[t];
                for (var r in i) void 0 === e[r] && (e[r] = i[r])
            }
            return e
        }, p.clone = function(e) {
            return p.isObject(e) ? p.isArray(e) ? e.slice() : p.extend({}, e) : e
        }, p.tap = function(e, t) {
            return t(e), e
        };
        var w = function(e, t, n, i) {
            if (e === t) return 0 !== e || 1 / e === 1 / t;
            if (null == e || null == t) return e === t;
            e instanceof p && (e = e._wrapped), t instanceof p && (t = t._wrapped);
            var r = l.call(e);
            if (r !== l.call(t)) return !1;
            switch (r) {
                case "[object RegExp]":
                case "[object String]":
                    return "" + e == "" + t;
                case "[object Number]":
                    return +e !== +e ? +t !== +t : 0 === +e ? 1 / +e === 1 / t : +e === +t;
                case "[object Date]":
                case "[object Boolean]":
                    return +e === +t
            }
            if ("object" != typeof e || "object" != typeof t) return !1;
            for (var s = n.length; s--;)
                if (n[s] === e) return i[s] === t;
            var o = e.constructor,
                a = t.constructor;
            if (o !== a && "constructor" in e && "constructor" in t && !(p.isFunction(o) && o instanceof o && p.isFunction(a) && a instanceof a)) return !1;
            n.push(e), i.push(t);
            var c, u;
            if ("[object Array]" === r) {
                if (c = e.length, u = c === t.length)
                    for (; c-- && (u = w(e[c], t[c], n, i)););
            } else {
                var h, d = p.keys(e);
                if (c = d.length, u = p.keys(t).length === c)
                    for (; c-- && (h = d[c], u = p.has(t, h) && w(e[h], t[h], n, i)););
            }
            return n.pop(), i.pop(), u
        };
        p.isEqual = function(e, t) {
            return w(e, t, [], [])
        }, p.isEmpty = function(e) {
            if (null == e) return !0;
            if (p.isArray(e) || p.isString(e) || p.isArguments(e)) return 0 === e.length;
            for (var t in e)
                if (p.has(e, t)) return !1;
            return !0
        }, p.isElement = function(e) {
            return !(!e || 1 !== e.nodeType)
        }, p.isArray = u || function(e) {
            return "[object Array]" === l.call(e)
        }, p.isObject = function(e) {
            var t = typeof e;
            return "function" === t || "object" === t && !!e
        }, p.each(["Arguments", "Function", "String", "Number", "Date", "RegExp"], function(e) {
            p["is" + e] = function(t) {
                return l.call(t) === "[object " + e + "]"
            }
        }), p.isArguments(arguments) || (p.isArguments = function(e) {
            return p.has(e, "callee")
        }), "function" != typeof /./ && (p.isFunction = function(e) {
            return "function" == typeof e || !1
        }), p.isFinite = function(e) {
            return isFinite(e) && !isNaN(parseFloat(e))
        }, p.isNaN = function(e) {
            return p.isNumber(e) && e !== +e
        }, p.isBoolean = function(e) {
            return e === !0 || e === !1 || "[object Boolean]" === l.call(e)
        }, p.isNull = function(e) {
            return null === e
        }, p.isUndefined = function(e) {
            return void 0 === e
        }, p.has = function(e, t) {
            return null != e && c.call(e, t)
        }, p.noConflict = function() {
            return e._ = t, this
        }, p.identity = function(e) {
            return e
        }, p.constant = function(e) {
            return function() {
                return e
            }
        }, p.noop = function() {}, p.property = function(e) {
            return function(t) {
                return t[e]
            }
        }, p.matches = function(e) {
            var t = p.pairs(e),
                n = t.length;
            return function(e) {
                if (null == e) return !n;
                e = new Object(e);
                for (var i = 0; n > i; i++) {
                    var r = t[i],
                        s = r[0];
                    if (r[1] !== e[s] || !(s in e)) return !1
                }
                return !0
            }
        }, p.times = function(e, t, n) {
            var i = Array(Math.max(0, e));
            t = f(t, n, 1);
            for (var r = 0; e > r; r++) i[r] = t(r);
            return i
        }, p.random = function(e, t) {
            return null == t && (t = e, e = 0), e + Math.floor(Math.random() * (t - e + 1))
        }, p.now = Date.now || function() {
            return (new Date).getTime()
        };
        var b = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#x27;",
                "`": "&#x60;"
            },
            _ = p.invert(b),
            x = function(e) {
                var t = function(t) {
                        return e[t]
                    },
                    n = "(?:" + p.keys(e).join("|") + ")",
                    i = RegExp(n),
                    r = RegExp(n, "g");
                return function(e) {
                    return e = null == e ? "" : "" + e, i.test(e) ? e.replace(r, t) : e
                }
            };
        p.escape = x(b), p.unescape = x(_), p.result = function(e, t) {
            if (null == e) return void 0;
            var n = e[t];
            return p.isFunction(n) ? e[t]() : n
        };
        var S = 0;
        p.uniqueId = function(e) {
            var t = ++S + "";
            return e ? e + t : t
        }, p.templateSettings = {
            evaluate: /<%([\s\S]+?)%>/g,
            interpolate: /<%=([\s\S]+?)%>/g,
            escape: /<%-([\s\S]+?)%>/g
        };
        var k = /(.)^/,
            C = {
                "'": "'",
                "\\": "\\",
                "\r": "r",
                "\n": "n",
                "\u2028": "u2028",
                "\u2029": "u2029"
            },
            T = /\\|'|\r|\n|\u2028|\u2029/g,
            E = function(e) {
                return "\\" + C[e]
            };
        p.template = function(e, t, n) {
            !t && n && (t = n), t = p.defaults({}, t, p.templateSettings);
            var i = RegExp([(t.escape || k).source, (t.interpolate || k).source, (t.evaluate || k).source].join("|") + "|$", "g"),
                r = 0,
                s = "__p+='";
            e.replace(i, function(t, n, i, o, a) {
                return s += e.slice(r, a).replace(T, E), r = a + t.length, n ? s += "'+\n((__t=(" + n + "))==null?'':_.escape(__t))+\n'" : i ? s += "'+\n((__t=(" + i + "))==null?'':__t)+\n'" : o && (s += "';\n" + o + "\n__p+='"), t
            }), s += "';\n", t.variable || (s = "with(obj||{}){\n" + s + "}\n"), s = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + s + "return __p;\n";
            try {
                var o = new Function(t.variable || "obj", "_", s)
            } catch (a) {
                throw a.source = s, a
            }
            var l = function(e) {
                    return o.call(this, e, p)
                },
                c = t.variable || "obj";
            return l.source = "function(" + c + "){\n" + s + "}", l
        }, p.chain = function(e) {
            var t = p(e);
            return t._chain = !0, t
        };
        var $ = function(e) {
            return this._chain ? p(e).chain() : e
        };
        p.mixin = function(e) {
            p.each(p.functions(e), function(t) {
                var n = p[t] = e[t];
                p.prototype[t] = function() {
                    var e = [this._wrapped];
                    return s.apply(e, arguments), $.call(this, n.apply(p, e))
                }
            })
        }, p.mixin(p), p.each(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function(e) {
            var t = n[e];
            p.prototype[e] = function() {
                var n = this._wrapped;
                return t.apply(n, arguments), "shift" !== e && "splice" !== e || 0 !== n.length || delete n[0], $.call(this, n)
            }
        }), p.each(["concat", "join", "slice"], function(e) {
            var t = n[e];
            p.prototype[e] = function() {
                return $.call(this, t.apply(this._wrapped, arguments))
            }
        }), p.prototype.value = function() {
            return this._wrapped
        }, "function" == typeof define && define.amd && define("underscore", [], function() {
            return p
        })
    }.call(this),
    function(e, t, n, i) {
        function r(e, t, n) {
            return setTimeout(u(e, n), t)
        }

        function s(e, t, n) {
            return Array.isArray(e) ? (o(e, n[t], n), !0) : !1
        }

        function o(e, t, n) {
            var r;
            if (e)
                if (e.forEach) e.forEach(t, n);
                else if (e.length !== i)
                for (r = 0; r < e.length;) t.call(n, e[r], r, e), r++;
            else
                for (r in e) e.hasOwnProperty(r) && t.call(n, e[r], r, e)
        }

        function a(e, t, n) {
            for (var r = Object.keys(t), s = 0; s < r.length;)(!n || n && e[r[s]] === i) && (e[r[s]] = t[r[s]]), s++;
            return e
        }

        function l(e, t) {
            return a(e, t, !0)
        }

        function c(e, t, n) {
            var i, r = t.prototype;
            i = e.prototype = Object.create(r), i.constructor = e, i._super = r, n && a(i, n)
        }

        function u(e, t) {
            return function() {
                return e.apply(t, arguments)
            }
        }

        function h(e, t) {
            return typeof e == ut ? e.apply(t ? t[0] || i : i, t) : e
        }

        function d(e, t) {
            return e === i ? t : e
        }

        function p(e, t, n) {
            o(v(t), function(t) {
                e.addEventListener(t, n, !1)
            })
        }

        function f(e, t, n) {
            o(v(t), function(t) {
                e.removeEventListener(t, n, !1)
            })
        }

        function m(e, t) {
            for (; e;) {
                if (e == t) return !0;
                e = e.parentNode
            }
            return !1
        }

        function g(e, t) {
            return e.indexOf(t) > -1
        }

        function v(e) {
            return e.trim().split(/\s+/g)
        }

        function y(e, t, n) {
            if (e.indexOf && !n) return e.indexOf(t);
            for (var i = 0; i < e.length;) {
                if (n && e[i][n] == t || !n && e[i] === t) return i;
                i++
            }
            return -1
        }

        function w(e) {
            return Array.prototype.slice.call(e, 0)
        }

        function b(e, t, n) {
            for (var i = [], r = [], s = 0; s < e.length;) {
                var o = t ? e[s][t] : e[s];
                y(r, o) < 0 && i.push(e[s]), r[s] = o, s++
            }
            return n && (i = t ? i.sort(function(e, n) {
                return e[t] > n[t]
            }) : i.sort()), i
        }

        function _(e, t) {
            for (var n, r, s = t[0].toUpperCase() + t.slice(1), o = 0; o < lt.length;) {
                if (n = lt[o], r = n ? n + s : t, r in e) return r;
                o++
            }
            return i
        }

        function x() {
            return ft++
        }

        function S(e) {
            var t = e.ownerDocument;
            return t.defaultView || t.parentWindow
        }

        function k(e, t) {
            var n = this;
            this.manager = e, this.callback = t, this.element = e.element, this.target = e.options.inputTarget, this.domHandler = function(t) {
                h(e.options.enable, [e]) && n.handler(t)
            }, this.init()
        }

        function C(e) {
            var t, n = e.options.inputClass;
            return new(t = n ? n : vt ? M : yt ? q : gt ? B : N)(e, T)
        }

        function T(e, t, n) {
            var i = n.pointers.length,
                r = n.changedPointers.length,
                s = t & kt && i - r === 0,
                o = t & (Tt | Et) && i - r === 0;
            n.isFirst = !!s, n.isFinal = !!o, s && (e.session = {}), n.eventType = t, E(e, n), e.emit("hammer.input", n), e.recognize(n), e.session.prevInput = n
        }

        function E(e, t) {
            var n = e.session,
                i = t.pointers,
                r = i.length;
            n.firstInput || (n.firstInput = P(t)), r > 1 && !n.firstMultiple ? n.firstMultiple = P(t) : 1 === r && (n.firstMultiple = !1);
            var s = n.firstInput,
                o = n.firstMultiple,
                a = o ? o.center : s.center,
                l = t.center = j(i);
            t.timeStamp = pt(), t.deltaTime = t.timeStamp - s.timeStamp, t.angle = O(a, l), t.distance = H(a, l), $(n, t), t.offsetDirection = F(t.deltaX, t.deltaY), t.scale = o ? L(o.pointers, i) : 1, t.rotation = o ? D(o.pointers, i) : 0, A(n, t);
            var c = e.element;
            m(t.srcEvent.target, c) && (c = t.srcEvent.target), t.target = c
        }

        function $(e, t) {
            var n = t.center,
                i = e.offsetDelta || {},
                r = e.prevDelta || {},
                s = e.prevInput || {};
            (t.eventType === kt || s.eventType === Tt) && (r = e.prevDelta = {
                x: s.deltaX || 0,
                y: s.deltaY || 0
            }, i = e.offsetDelta = {
                x: n.x,
                y: n.y
            }), t.deltaX = r.x + (n.x - i.x), t.deltaY = r.y + (n.y - i.y)
        }

        function A(e, t) {
            var n, r, s, o, a = e.lastInterval || t,
                l = t.timeStamp - a.timeStamp;
            if (t.eventType != Et && (l > St || a.velocity === i)) {
                var c = a.deltaX - t.deltaX,
                    u = a.deltaY - t.deltaY,
                    h = I(l, c, u);
                r = h.x, s = h.y, n = dt(h.x) > dt(h.y) ? h.x : h.y, o = F(c, u), e.lastInterval = t
            } else n = a.velocity, r = a.velocityX, s = a.velocityY, o = a.direction;
            t.velocity = n, t.velocityX = r, t.velocityY = s, t.direction = o
        }

        function P(e) {
            for (var t = [], n = 0; n < e.pointers.length;) t[n] = {
                clientX: ht(e.pointers[n].clientX),
                clientY: ht(e.pointers[n].clientY)
            }, n++;
            return {
                timeStamp: pt(),
                pointers: t,
                center: j(t),
                deltaX: e.deltaX,
                deltaY: e.deltaY
            }
        }

        function j(e) {
            var t = e.length;
            if (1 === t) return {
                x: ht(e[0].clientX),
                y: ht(e[0].clientY)
            };
            for (var n = 0, i = 0, r = 0; t > r;) n += e[r].clientX, i += e[r].clientY, r++;
            return {
                x: ht(n / t),
                y: ht(i / t)
            }
        }

        function I(e, t, n) {
            return {
                x: t / e || 0,
                y: n / e || 0
            }
        }

        function F(e, t) {
            return e === t ? $t : dt(e) >= dt(t) ? e > 0 ? At : Pt : t > 0 ? jt : It
        }

        function H(e, t, n) {
            n || (n = Dt);
            var i = t[n[0]] - e[n[0]],
                r = t[n[1]] - e[n[1]];
            return Math.sqrt(i * i + r * r)
        }

        function O(e, t, n) {
            n || (n = Dt);
            var i = t[n[0]] - e[n[0]],
                r = t[n[1]] - e[n[1]];
            return 180 * Math.atan2(r, i) / Math.PI
        }

        function D(e, t) {
            return O(t[1], t[0], Lt) - O(e[1], e[0], Lt)
        }

        function L(e, t) {
            return H(t[0], t[1], Lt) / H(e[0], e[1], Lt)
        }

        function N() {
            this.evEl = Mt, this.evWin = Rt, this.allow = !0, this.pressed = !1, k.apply(this, arguments)
        }

        function M() {
            this.evEl = Wt, this.evWin = Bt, k.apply(this, arguments), this.store = this.manager.session.pointerEvents = []
        }

        function R() {
            this.evTarget = Vt, this.evWin = Xt, this.started = !1, k.apply(this, arguments)
        }

        function z(e, t) {
            var n = w(e.touches),
                i = w(e.changedTouches);
            return t & (Tt | Et) && (n = b(n.concat(i), "identifier", !0)), [n, i]
        }

        function q() {
            this.evTarget = Qt, this.targetIds = {}, k.apply(this, arguments)
        }

        function W(e, t) {
            var n = w(e.touches),
                i = this.targetIds;
            if (t & (kt | Ct) && 1 === n.length) return i[n[0].identifier] = !0, [n, n];
            var r, s, o = w(e.changedTouches),
                a = [],
                l = this.target;
            if (s = n.filter(function(e) {
                    return m(e.target, l)
                }), t === kt)
                for (r = 0; r < s.length;) i[s[r].identifier] = !0, r++;
            for (r = 0; r < o.length;) i[o[r].identifier] && a.push(o[r]), t & (Tt | Et) && delete i[o[r].identifier], r++;
            return a.length ? [b(s.concat(a), "identifier", !0), a] : void 0
        }

        function B() {
            k.apply(this, arguments);
            var e = u(this.handler, this);
            this.touch = new q(this.manager, e), this.mouse = new N(this.manager, e)
        }

        function U(e, t) {
            this.manager = e, this.set(t)
        }

        function V(e) {
            if (g(e, tn)) return tn;
            var t = g(e, nn),
                n = g(e, rn);
            return t && n ? nn + " " + rn : t || n ? t ? nn : rn : g(e, en) ? en : Jt
        }

        function X(e) {
            this.id = x(), this.manager = null, this.options = l(e || {}, this.defaults), this.options.enable = d(this.options.enable, !0), this.state = sn, this.simultaneous = {}, this.requireFail = []
        }

        function Y(e) {
            return e & un ? "cancel" : e & ln ? "end" : e & an ? "move" : e & on ? "start" : ""
        }

        function Q(e) {
            return e == It ? "down" : e == jt ? "up" : e == At ? "left" : e == Pt ? "right" : ""
        }

        function K(e, t) {
            var n = t.manager;
            return n ? n.get(e) : e
        }

        function G() {
            X.apply(this, arguments)
        }

        function Z() {
            G.apply(this, arguments), this.pX = null, this.pY = null
        }

        function J() {
            G.apply(this, arguments)
        }

        function et() {
            X.apply(this, arguments), this._timer = null, this._input = null
        }

        function tt() {
            G.apply(this, arguments)
        }

        function nt() {
            G.apply(this, arguments)
        }

        function it() {
            X.apply(this, arguments), this.pTime = !1, this.pCenter = !1, this._timer = null, this._input = null, this.count = 0
        }

        function rt(e, t) {
            return t = t || {}, t.recognizers = d(t.recognizers, rt.defaults.preset), new st(e, t)
        }

        function st(e, t) {
            t = t || {}, this.options = l(t, rt.defaults), this.options.inputTarget = this.options.inputTarget || e, this.handlers = {}, this.session = {}, this.recognizers = [], this.element = e, this.input = C(this), this.touchAction = new U(this, this.options.touchAction), ot(this, !0), o(t.recognizers, function(e) {
                var t = this.add(new e[0](e[1]));
                e[2] && t.recognizeWith(e[2]), e[3] && t.requireFailure(e[3])
            }, this)
        }

        function ot(e, t) {
            var n = e.element;
            o(e.options.cssProps, function(e, i) {
                n.style[_(n.style, i)] = t ? e : ""
            })
        }

        function at(e, n) {
            var i = t.createEvent("Event");
            i.initEvent(e, !0, !0), i.gesture = n, n.target.dispatchEvent(i)
        }
        var lt = ["", "webkit", "moz", "MS", "ms", "o"],
            ct = t.createElement("div"),
            ut = "function",
            ht = Math.round,
            dt = Math.abs,
            pt = Date.now,
            ft = 1,
            mt = /mobile|tablet|ip(ad|hone|od)|android/i,
            gt = "ontouchstart" in e,
            vt = _(e, "PointerEvent") !== i,
            yt = gt && mt.test(navigator.userAgent),
            wt = "touch",
            bt = "pen",
            _t = "mouse",
            xt = "kinect",
            St = 25,
            kt = 1,
            Ct = 2,
            Tt = 4,
            Et = 8,
            $t = 1,
            At = 2,
            Pt = 4,
            jt = 8,
            It = 16,
            Ft = At | Pt,
            Ht = jt | It,
            Ot = Ft | Ht,
            Dt = ["x", "y"],
            Lt = ["clientX", "clientY"];
        k.prototype = {
            handler: function() {},
            init: function() {
                this.evEl && p(this.element, this.evEl, this.domHandler), this.evTarget && p(this.target, this.evTarget, this.domHandler), this.evWin && p(S(this.element), this.evWin, this.domHandler)
            },
            destroy: function() {
                this.evEl && f(this.element, this.evEl, this.domHandler), this.evTarget && f(this.target, this.evTarget, this.domHandler), this.evWin && f(S(this.element), this.evWin, this.domHandler)
            }
        };
        var Nt = {
                mousedown: kt,
                mousemove: Ct,
                mouseup: Tt
            },
            Mt = "mousedown",
            Rt = "mousemove mouseup";
        c(N, k, {
            handler: function(e) {
                var t = Nt[e.type];
                t & kt && 0 === e.button && (this.pressed = !0), t & Ct && 1 !== e.which && (t = Tt), this.pressed && this.allow && (t & Tt && (this.pressed = !1), this.callback(this.manager, t, {
                    pointers: [e],
                    changedPointers: [e],
                    pointerType: _t,
                    srcEvent: e
                }))
            }
        });
        var zt = {
                pointerdown: kt,
                pointermove: Ct,
                pointerup: Tt,
                pointercancel: Et,
                pointerout: Et
            },
            qt = {
                2: wt,
                3: bt,
                4: _t,
                5: xt
            },
            Wt = "pointerdown",
            Bt = "pointermove pointerup pointercancel";
        e.MSPointerEvent && (Wt = "MSPointerDown", Bt = "MSPointerMove MSPointerUp MSPointerCancel"), c(M, k, {
            handler: function(e) {
                var t = this.store,
                    n = !1,
                    i = e.type.toLowerCase().replace("ms", ""),
                    r = zt[i],
                    s = qt[e.pointerType] || e.pointerType,
                    o = s == wt,
                    a = y(t, e.pointerId, "pointerId");
                r & kt && (0 === e.button || o) ? 0 > a && (t.push(e), a = t.length - 1) : r & (Tt | Et) && (n = !0), 0 > a || (t[a] = e, this.callback(this.manager, r, {
                    pointers: t,
                    changedPointers: [e],
                    pointerType: s,
                    srcEvent: e
                }), n && t.splice(a, 1))
            }
        });
        var Ut = {
                touchstart: kt,
                touchmove: Ct,
                touchend: Tt,
                touchcancel: Et
            },
            Vt = "touchstart",
            Xt = "touchstart touchmove touchend touchcancel";
        c(R, k, {
            handler: function(e) {
                var t = Ut[e.type];
                if (t === kt && (this.started = !0), this.started) {
                    var n = z.call(this, e, t);
                    t & (Tt | Et) && n[0].length - n[1].length === 0 && (this.started = !1), this.callback(this.manager, t, {
                        pointers: n[0],
                        changedPointers: n[1],
                        pointerType: wt,
                        srcEvent: e
                    })
                }
            }
        });
        var Yt = {
                touchstart: kt,
                touchmove: Ct,
                touchend: Tt,
                touchcancel: Et
            },
            Qt = "touchstart touchmove touchend touchcancel";
        c(q, k, {
            handler: function(e) {
                var t = Yt[e.type],
                    n = W.call(this, e, t);
                n && this.callback(this.manager, t, {
                    pointers: n[0],
                    changedPointers: n[1],
                    pointerType: wt,
                    srcEvent: e
                })
            }
        }), c(B, k, {
            handler: function(e, t, n) {
                var i = n.pointerType == wt,
                    r = n.pointerType == _t;
                if (i) this.mouse.allow = !1;
                else if (r && !this.mouse.allow) return;
                t & (Tt | Et) && (this.mouse.allow = !0), this.callback(e, t, n)
            },
            destroy: function() {
                this.touch.destroy(), this.mouse.destroy()
            }
        });
        var Kt = _(ct.style, "touchAction"),
            Gt = Kt !== i,
            Zt = "compute",
            Jt = "auto",
            en = "manipulation",
            tn = "none",
            nn = "pan-x",
            rn = "pan-y";
        U.prototype = {
            set: function(e) {
                e == Zt && (e = this.compute()), Gt && (this.manager.element.style[Kt] = e), this.actions = e.toLowerCase().trim()
            },
            update: function() {
                this.set(this.manager.options.touchAction)
            },
            compute: function() {
                var e = [];
                return o(this.manager.recognizers, function(t) {
                    h(t.options.enable, [t]) && (e = e.concat(t.getTouchAction()))
                }), V(e.join(" "))
            },
            preventDefaults: function(e) {
                if (!Gt) {
                    var t = e.srcEvent,
                        n = e.offsetDirection;
                    if (this.manager.session.prevented) return void t.preventDefault();
                    var i = this.actions,
                        r = g(i, tn),
                        s = g(i, rn),
                        o = g(i, nn);
                    return r || s && n & Ft || o && n & Ht ? this.preventSrc(t) : void 0
                }
            },
            preventSrc: function(e) {
                this.manager.session.prevented = !0, e.preventDefault()
            }
        };
        var sn = 1,
            on = 2,
            an = 4,
            ln = 8,
            cn = ln,
            un = 16,
            hn = 32;
        X.prototype = {
            defaults: {},
            set: function(e) {
                return a(this.options, e), this.manager && this.manager.touchAction.update(), this
            },
            recognizeWith: function(e) {
                if (s(e, "recognizeWith", this)) return this;
                var t = this.simultaneous;
                return e = K(e, this), t[e.id] || (t[e.id] = e, e.recognizeWith(this)), this
            },
            dropRecognizeWith: function(e) {
                return s(e, "dropRecognizeWith", this) ? this : (e = K(e, this), delete this.simultaneous[e.id], this)
            },
            requireFailure: function(e) {
                if (s(e, "requireFailure", this)) return this;
                var t = this.requireFail;
                return e = K(e, this), -1 === y(t, e) && (t.push(e), e.requireFailure(this)), this
            },
            dropRequireFailure: function(e) {
                if (s(e, "dropRequireFailure", this)) return this;
                e = K(e, this);
                var t = y(this.requireFail, e);
                return t > -1 && this.requireFail.splice(t, 1), this
            },
            hasRequireFailures: function() {
                return this.requireFail.length > 0
            },
            canRecognizeWith: function(e) {
                return !!this.simultaneous[e.id]
            },
            emit: function(e) {
                function t(t) {
                    n.manager.emit(n.options.event + (t ? Y(i) : ""), e)
                }
                var n = this,
                    i = this.state;
                ln > i && t(!0), t(), i >= ln && t(!0)
            },
            tryEmit: function(e) {
                return this.canEmit() ? this.emit(e) : void(this.state = hn)
            },
            canEmit: function() {
                for (var e = 0; e < this.requireFail.length;) {
                    if (!(this.requireFail[e].state & (hn | sn))) return !1;
                    e++
                }
                return !0
            },
            recognize: function(e) {
                var t = a({}, e);
                return h(this.options.enable, [this, t]) ? (this.state & (cn | un | hn) && (this.state = sn), this.state = this.process(t), void(this.state & (on | an | ln | un) && this.tryEmit(t))) : (this.reset(), void(this.state = hn))
            },
            process: function() {},
            getTouchAction: function() {},
            reset: function() {}
        }, c(G, X, {
            defaults: {
                pointers: 1
            },
            attrTest: function(e) {
                var t = this.options.pointers;
                return 0 === t || e.pointers.length === t
            },
            process: function(e) {
                var t = this.state,
                    n = e.eventType,
                    i = t & (on | an),
                    r = this.attrTest(e);
                return i && (n & Et || !r) ? t | un : i || r ? n & Tt ? t | ln : t & on ? t | an : on : hn
            }
        }), c(Z, G, {
            defaults: {
                event: "pan",
                threshold: 10,
                pointers: 1,
                direction: Ot
            },
            getTouchAction: function() {
                var e = this.options.direction,
                    t = [];
                return e & Ft && t.push(rn), e & Ht && t.push(nn), t
            },
            directionTest: function(e) {
                var t = this.options,
                    n = !0,
                    i = e.distance,
                    r = e.direction,
                    s = e.deltaX,
                    o = e.deltaY;
                return r & t.direction || (t.direction & Ft ? (r = 0 === s ? $t : 0 > s ? At : Pt, n = s != this.pX, i = Math.abs(e.deltaX)) : (r = 0 === o ? $t : 0 > o ? jt : It, n = o != this.pY, i = Math.abs(e.deltaY))), e.direction = r, n && i > t.threshold && r & t.direction
            },
            attrTest: function(e) {
                return G.prototype.attrTest.call(this, e) && (this.state & on || !(this.state & on) && this.directionTest(e))
            },
            emit: function(e) {
                this.pX = e.deltaX, this.pY = e.deltaY;
                var t = Q(e.direction);
                t && this.manager.emit(this.options.event + t, e), this._super.emit.call(this, e)
            }
        }), c(J, G, {
            defaults: {
                event: "pinch",
                threshold: 0,
                pointers: 2
            },
            getTouchAction: function() {
                return [tn]
            },
            attrTest: function(e) {
                return this._super.attrTest.call(this, e) && (Math.abs(e.scale - 1) > this.options.threshold || this.state & on)
            },
            emit: function(e) {
                if (this._super.emit.call(this, e), 1 !== e.scale) {
                    var t = e.scale < 1 ? "in" : "out";
                    this.manager.emit(this.options.event + t, e)
                }
            }
        }), c(et, X, {
            defaults: {
                event: "press",
                pointers: 1,
                time: 500,
                threshold: 5
            },
            getTouchAction: function() {
                return [Jt]
            },
            process: function(e) {
                var t = this.options,
                    n = e.pointers.length === t.pointers,
                    i = e.distance < t.threshold,
                    s = e.deltaTime > t.time;
                if (this._input = e, !i || !n || e.eventType & (Tt | Et) && !s) this.reset();
                else if (e.eventType & kt) this.reset(), this._timer = r(function() {
                    this.state = cn, this.tryEmit()
                }, t.time, this);
                else if (e.eventType & Tt) return cn;
                return hn
            },
            reset: function() {
                clearTimeout(this._timer)
            },
            emit: function(e) {
                this.state === cn && (e && e.eventType & Tt ? this.manager.emit(this.options.event + "up", e) : (this._input.timeStamp = pt(), this.manager.emit(this.options.event, this._input)))
            }
        }), c(tt, G, {
            defaults: {
                event: "rotate",
                threshold: 0,
                pointers: 2
            },
            getTouchAction: function() {
                return [tn]
            },
            attrTest: function(e) {
                return this._super.attrTest.call(this, e) && (Math.abs(e.rotation) > this.options.threshold || this.state & on)
            }
        }), c(nt, G, {
            defaults: {
                event: "swipe",
                threshold: 10,
                velocity: .65,
                direction: Ft | Ht,
                pointers: 1
            },
            getTouchAction: function() {
                return Z.prototype.getTouchAction.call(this)
            },
            attrTest: function(e) {
                var t, n = this.options.direction;
                return n & (Ft | Ht) ? t = e.velocity : n & Ft ? t = e.velocityX : n & Ht && (t = e.velocityY), this._super.attrTest.call(this, e) && n & e.direction && e.distance > this.options.threshold && dt(t) > this.options.velocity && e.eventType & Tt
            },
            emit: function(e) {
                var t = Q(e.direction);
                t && this.manager.emit(this.options.event + t, e), this.manager.emit(this.options.event, e)
            }
        }), c(it, X, {
            defaults: {
                event: "tap",
                pointers: 1,
                taps: 1,
                interval: 300,
                time: 250,
                threshold: 2,
                posThreshold: 10
            },
            getTouchAction: function() {
                return [en]
            },
            process: function(e) {
                var t = this.options,
                    n = e.pointers.length === t.pointers,
                    i = e.distance < t.threshold,
                    s = e.deltaTime < t.time;
                if (this.reset(), e.eventType & kt && 0 === this.count) return this.failTimeout();
                if (i && s && n) {
                    if (e.eventType != Tt) return this.failTimeout();
                    var o = this.pTime ? e.timeStamp - this.pTime < t.interval : !0,
                        a = !this.pCenter || H(this.pCenter, e.center) < t.posThreshold;
                    this.pTime = e.timeStamp, this.pCenter = e.center, a && o ? this.count += 1 : this.count = 1, this._input = e;
                    var l = this.count % t.taps;
                    if (0 === l) return this.hasRequireFailures() ? (this._timer = r(function() {
                        this.state = cn, this.tryEmit()
                    }, t.interval, this), on) : cn
                }
                return hn
            },
            failTimeout: function() {
                return this._timer = r(function() {
                    this.state = hn
                }, this.options.interval, this), hn
            },
            reset: function() {
                clearTimeout(this._timer)
            },
            emit: function() {
                this.state == cn && (this._input.tapCount = this.count, this.manager.emit(this.options.event, this._input))
            }
        }), rt.VERSION = "2.0.4", rt.defaults = {
            domEvents: !1,
            touchAction: Zt,
            enable: !0,
            inputTarget: null,
            inputClass: null,
            preset: [
                [tt, {
                    enable: !1
                }],
                [J, {
                        enable: !1
                    },
                    ["rotate"]
                ],
                [nt, {
                    direction: Ft
                }],
                [Z, {
                        direction: Ft
                    },
                    ["swipe"]
                ],
                [it],
                [it, {
                        event: "doubletap",
                        taps: 2
                    },
                    ["tap"]
                ],
                [et]
            ],
            cssProps: {
                userSelect: "none",
                touchSelect: "none",
                touchCallout: "none",
                contentZooming: "none",
                userDrag: "none",
                tapHighlightColor: "rgba(0,0,0,0)"
            }
        };
        var dn = 1,
            pn = 2;
        st.prototype = {
            set: function(e) {
                return a(this.options, e), e.touchAction && this.touchAction.update(), e.inputTarget && (this.input.destroy(), this.input.target = e.inputTarget, this.input.init()), this
            },
            stop: function(e) {
                this.session.stopped = e ? pn : dn
            },
            recognize: function(e) {
                var t = this.session;
                if (!t.stopped) {
                    this.touchAction.preventDefaults(e);
                    var n, i = this.recognizers,
                        r = t.curRecognizer;
                    (!r || r && r.state & cn) && (r = t.curRecognizer = null);
                    for (var s = 0; s < i.length;) n = i[s], t.stopped === pn || r && n != r && !n.canRecognizeWith(r) ? n.reset() : n.recognize(e), !r && n.state & (on | an | ln) && (r = t.curRecognizer = n), s++
                }
            },
            get: function(e) {
                if (e instanceof X) return e;
                for (var t = this.recognizers, n = 0; n < t.length; n++)
                    if (t[n].options.event == e) return t[n];
                return null
            },
            add: function(e) {
                if (s(e, "add", this)) return this;
                var t = this.get(e.options.event);
                return t && this.remove(t), this.recognizers.push(e), e.manager = this, this.touchAction.update(), e
            },
            remove: function(e) {
                if (s(e, "remove", this)) return this;
                var t = this.recognizers;
                return e = this.get(e), t.splice(y(t, e), 1), this.touchAction.update(), this
            },
            on: function(e, t) {
                var n = this.handlers;
                return o(v(e), function(e) {
                    n[e] = n[e] || [], n[e].push(t)
                }), this
            },
            off: function(e, t) {
                var n = this.handlers;
                return o(v(e), function(e) {
                    t ? n[e].splice(y(n[e], t), 1) : delete n[e]
                }), this
            },
            emit: function(e, t) {
                this.options.domEvents && at(e, t);
                var n = this.handlers[e] && this.handlers[e].slice();
                if (n && n.length) {
                    t.type = e, t.preventDefault = function() {
                        t.srcEvent.preventDefault()
                    };
                    for (var i = 0; i < n.length;) n[i](t), i++
                }
            },
            destroy: function() {
                this.element && ot(this, !1), this.handlers = {}, this.session = {}, this.input.destroy(), this.element = null
            }
        }, a(rt, {
            INPUT_START: kt,
            INPUT_MOVE: Ct,
            INPUT_END: Tt,
            INPUT_CANCEL: Et,
            STATE_POSSIBLE: sn,
            STATE_BEGAN: on,
            STATE_CHANGED: an,
            STATE_ENDED: ln,
            STATE_RECOGNIZED: cn,
            STATE_CANCELLED: un,
            STATE_FAILED: hn,
            DIRECTION_NONE: $t,
            DIRECTION_LEFT: At,
            DIRECTION_RIGHT: Pt,
            DIRECTION_UP: jt,
            DIRECTION_DOWN: It,
            DIRECTION_HORIZONTAL: Ft,
            DIRECTION_VERTICAL: Ht,
            DIRECTION_ALL: Ot,
            Manager: st,
            Input: k,
            TouchAction: U,
            TouchInput: q,
            MouseInput: N,
            PointerEventInput: M,
            TouchMouseInput: B,
            SingleTouchInput: R,
            Recognizer: X,
            AttrRecognizer: G,
            Tap: it,
            Pan: Z,
            Swipe: nt,
            Pinch: J,
            Rotate: tt,
            Press: et,
            on: p,
            off: f,
            each: o,
            merge: l,
            extend: a,
            inherit: c,
            bindFn: u,
            prefixed: _
        }), typeof define == ut && define.amd ? define("hammerjs", [], function() {
            return rt
        }) : "undefined" != typeof module && module.exports ? module.exports = rt : e[n] = rt
    }(window, document, "Hammer"),
    function(e) {
        "function" == typeof define && define.amd ? define("jquery-hammerjs", ["jquery", "hammerjs"], e) : "object" == typeof exports ? e(require("jquery"), require("hammerjs")) : e(jQuery, Hammer)
    }(function(e, t) {
        function n(n, i) {
            var r = e(n);
            r.data("hammer") || r.data("hammer", new t(r[0], i))
        }
        e.fn.hammer = function(e) {
            return this.each(function() {
                n(this, e)
            })
        }, t.Manager.prototype.emit = function(t) {
            return function(n, i) {
                t.call(this, n, i), e(this.element).trigger({
                    type: n,
                    gesture: i
                })
            }
        }(t.Manager.prototype.emit)
    }), define("carousel", ["jquery", "underscore", "modernizr", "jquery-hammerjs"], function(e, t, n) {
        var i = [],
            r = !1,
            s = function(t, r) {
                this.defaults = {
                    speed: 300,
                    easing: "linear",
                    alignRight: !1,
                    fitSlides: !1,
                    scaleSlides: !1,
                    scaleRatio: 1,
                    swipe: !0,
                    cssProp: n.csstransforms3d ? n.prefixed("transform") : "left",
                    cssAnimate: n.prefixed("transition"),
                    transProp: "",
                    alignLastSlideRight: !0,
                    pagination: !1,
                    loop: "startOver",
                    threshold: 80
                }, this.settings = e.extend({}, this.defaults, r), this.$wrapper = t, t.data("carousel", this), this.init(), i.push(this)
            };
        return s.prototype.init = function() {
            this.left = 0, this.endPos = 0, this.animationDisabled = !1, this.$wrapper = this.$wrapper, this.$carousel = e(".js-carousel__list", this.$wrapper), this.$parent = this.$carousel.parent(), this.$slides = this.$carousel.children(), this.$nav = e(".js-carousel__nav", this.$wrapper), this.$prev = e(".js-carousel__nav--prev", this.$wrapper), this.$next = e(".js-carousel__nav--next", this.$wrapper), this.$pagination = e(".js-carousel__pagination", this.$wrapper);
            var t = this.settings.easing && "linear" !== this.settings.easing ? " " + this.settings.easing : "",
                n = this.settings.cssProp.replace(/([A-Z])/g, function(e, t) {
                    return "-" + t.toLowerCase()
                }).replace(/^ms-/, "-ms-");
            this.settings.transProp = n + " " + this.settings.speed + "ms" + t + " 0ms", this.$parent.children().length > 1 && (this.$parent = this.$carousel.wrap("<div />").parent()), this.slideCount = this.$slides.filter(":not(.js-placeholder-slide)").length;
            for (var i = 0; i < this.slideCount;) {
                i++;
                var r = '<li class="js-carousel__pagination-item">' + i + "</li>";
                this.$pagination.append(r)
            }
            this.style().calibrate(), this.bindNav().bindTouch().bindPagination(), this.go(null, !1)
        }, s.prototype.calibrate = function() {
            function n(e) {
                return {
                    width: e.css("width").replace("px", "") - 0,
                    height: e.css("height").replace("px", "") - 0
                }
            }
            var i = this;
            if (!r) return i;
            var s = this.settings,
                o = i.$parent.width();
            if (s.dummySlide && e(".js-placeholder-slide", this.$carousel).width(this.$parent.width()), s.scaleSlides) {
                var a = 0,
                    l = 0,
                    c = i.$slides.filter(":not(.js-placeholder-slide)");
                c.width("auto").height("auto"), c.each(function() {
                    var n = t.isUndefined(e(this).data("slide-height")) ? e(this).height() : e(this).data("slide-height");
                    l = Math.max(l, n)
                }), l *= s.scaleRatio, c.each(function() {
                    var t = n(e(this)).width * l / n(e(this)).height;
                    a = Math.max(a, t), e(this).css({
                        width: t,
                        height: l
                    })
                }), s.fitSlides && a > o && c.each(function() {
                    var t = o / a * n(e(this)).width,
                        i = t * n(e(this)).height / n(e(this)).width;
                    e(this).css({
                        width: Math.floor(t),
                        height: Math.floor(i)
                    })
                })
            }
            i.$carousel.width(1e5).width(t.reduce(i.$slides, function(t, n) {
                return t + e(n).outerWidth(!0)
            }, 0)), i.startPos = s.alignRight ? o - i.$slides.first().outerWidth(!0) : 0, i.endPos = s.alignLastSlideRight ? -(i.$carousel.width() - o) : -(i.$carousel.width() - i.$slides.last().outerWidth(!0));
            var u = i.startPos;
            i.$slides.each(function() {
                var t = e(this).next();
                t.length || (u = i.endPos), u = Math.min(i.startPos, Math.max(i.endPos, u)), e(this).attr("data-carousel-left", u);
                var n = s.alignRight ? t : e(this);
                u -= n.outerWidth(!0)
            });
            var h = i.$slides.filter(".active");
            return h.length && (i.left = h.attr("data-carousel-left") - 0), i.$wrapper.trigger("calibrate"), i
        }, s.prototype.go = function(e, t) {
            var n = this;
            if (!r) return n;
            var i = n.settings,
                s = n.$slides.filter(".active");
            s.length || (s = n.$slides.first());
            var o = s;
            if (t = "undefined" == typeof t ? !0 : t, "prev" === e)
                if (s.prev().length) o = s.prev();
                else {
                    if ("startOver" !== i.loop) return n.activateSlide(s), n;
                    o = n.$slides.last()
                } else if ("next" === e)
                if (s.attr("data-carousel-left") - 0 === n.endPos) {
                    if ("startOver" !== i.loop) return n.activateSlide(s), n;
                    o = n.$slides.first()
                } else o = s.next();
            else o = "jquery" in Object(e) && n.$carousel.has(e) ? e : n.getClosest();
            for (; o.length && o.prev().attr("data-carousel-left") - 0 === o.attr("data-carousel-left") - 0;) o = o.prev();
            return n.activateSlide(o), n.left = o.attr("data-carousel-left") - 0, n.setPos(n.left, t), n
        }, s.prototype.getClosest = function() {
            var t = null,
                n = e(),
                i = this;
            return i.$slides.each(function() {
                var r = e(this).attr("data-carousel-left") - 0;
                (null === t || Math.abs(r - i.left) < Math.abs(t - i.left)) && (t = r, n = e(this))
            }), n
        }, s.prototype.setPos = function(e, t) {
            var i = this,
                s = i.settings;
            if (!r) return i;
            //if (e -= 0, isNaN(e)) throw new Error("setPos error: position passed is not a number");
            if (e -= 0, isNaN(e)) { return;};
            var o = i.animationDisabled,
                a = e;
            if (s.cssProp === n.prefixed("transform") && (a = "translate3d(" + a + "px, 0px, 0px)"), o || !t || s.cssAnimate) {
                var l = {};
                l[s.cssProp] = a;
                var c = !o && !t && s.cssAnimate;
                c && i.disableAnimation(), i.$carousel.css(l), c && i.enableAnimation()
            } else i.$carousel.animate({
                left: e
            }, {
                duration: s.speed,
                easing: s.easing ? s.easing : "linear"
            });
            return i
        }, s.prototype.disableAnimation = function() {
            var e = this,
                t = this.settings;
            if (!e.animationDisabled) {
                if (t.cssAnimate) {
                    var n = {};
                    n[t.cssAnimate] = "none", e.$carousel.css(n)
                }
                return e.animationDisabled = !0, this
            }
        }, s.prototype.enableAnimation = function() {
            return this.settings.cssAnimate && this.$carousel.css(this.settings.cssAnimate, this.settings.transProp), this.animationDisabled = !1, this
        }, s.prototype.bindTouch = function() {
            function t(e) {
                n.disableAnimation(), n.$wrapper.trigger("carousel-pan", e.gesture);
                var t = n.left;
                if (t += e.gesture.deltaX, t = Math.min(n.startPos, Math.max(t, n.endPos)), n.setPos(t), e.gesture.isFinal) {
                    n.left = t;
                    for (var s = n.getClosest(); s.length && s.prev().attr("data-carousel-left") - 0 === s.attr("data-carousel-left") - 0;) s = s.prev();
                    var o = null;
                    if (i.threshold) {
                        var a = n.$slides.filter(".active"),
                            l = Math.abs(e.gesture.deltaX);
                        s.index() === a.index() && l > i.threshold && (o = e.gesture.deltaX > 0 ? "prev" : "next", 0 === a.index() && "prev" === o ? o = null : a.index() === n.$slides.length - 1 && "next" === o && (o = null))
                    }
                    n.enableAnimation().go(o), setTimeout(function() {
                        r = 0
                    }, 50)
                }
                r = e.gesture.distance
            }
            var n = this,
                i = n.settings;
            if (!i.swipe) return n;
            var r = 0;
            return n.$carousel.hammer({
                preventDefault: !0
            }).on("pan", t), e("a, img", n.$carousel).on("dragstart", function(e) {
                e.preventDefault()
            }), e("a", n.$carousel).on("click", function(e) {
                r > 5 && (e.stopPropagation(), e.preventDefault(), r = 0)
            }), n
        }, s.prototype.style = function() {
            var e = this,
                t = e.settings,
                n = {};
            return "left" !== t.cssProp && t.cssAnimate || (n.position = "relative", "left" === t.cssProp && (n.left = e.left)), e.animationDisabled || e.enableAnimation(), e.$carousel.css(n), e.$parent.css("overflow", "hidden"), e
        }, s.prototype.bindNav = function() {
            var t = this;
            return e(t.$nav).on("click", function(n) {
                n.preventDefault();
                var i = e(this).hasClass("js-carousel__nav--prev") ? "prev" : "next";
                t.go(i)
            }), t
        }, s.prototype.activateSlide = function(e) {
            e.addClass("active").siblings().removeClass("active");
            var t = e.index(),
                n = 0 === t,
                i = t === this.$slides.length - 1;
            return this.$wrapper.toggleClass("js-carousel--first-slide", n), this.$wrapper.toggleClass("js-carousel--last-slide", i), this.$wrapper.trigger("slide-activate", this), this.$pagination.children().removeClass("active").eq(t).addClass("active"), this
        }, s.prototype.bindPagination = function() {
            var t = this;
            return t.$pagination.children().on("click", function(n) {
                if (n.preventDefault(), !e(this).hasClass("active")) {
                    var i = e(this).index();
                    t.go(t.$slides.eq(i))
                }
            }), t
        }, e(window).on("resize", function() {
            for (var e = 0; e < i.length; e++) i[e].calibrate(), i[e].go(null, !1)
        }), e(window).on("load", function() {
            r = !0;
            for (var e = 0; e < i.length; e++) i[e].calibrate(), i[e].go(i[e].$slides.first(), !1)
        }), s
    }), define("common/carousels", ["jquery", "carousel"], function(e, t) {
        e(".js-carousel").each(function() {
            new t(e(this))
        }), e(".pagination__item a").on("click", function(t) {
            t.preventDefault();
            var n = e(this).attr("href");
            document.cookie = "features_hash=" + n.substring(1), window.location.hash = n, window.location.reload()
        })
    }), define("modules/navigation", ["jquery"], function(e) {
        e(document).on("render", function(t, n) {
            var i = e(".site-nav-pane", n);
            e(".js-site-header-button--menu").on("click", function(t) {
                t.preventDefault(), i.fadeIn("fast"), e("body").addClass("is-nav--opened")
            }), e(".js-site-header-button--close").on("click", function(t) {
                t.preventDefault(), i.fadeOut("fast"), e("body").removeClass("is-nav--opened"), e(".site-header-newsletter__field", i).val("")
            })
        })
    }), define("modules/sub-navigation", ["jquery"], function(e) {
        function t(t) {
            t.preventDefault(), e(this).parent().toggleClass("active").find(".js-mod-sub-navigation__sub-wrap").toggle()
        }
        var n = e(".mod-sub-navigation"),
            i = e(".js-mod-sub-navigation__link--trigger", n);
        i.parent(".mod-sub-navigation__item.open").addClass("active"), i.on("click", t), e(document).on("render", function(t, n) {
            var i = e(".mod-sub-navigation__container", n);
            e(".js-mod-sub-navigation__open").on("click", function(t) {
                t.preventDefault(), i.fadeIn("fast"), e("body").addClass("is-nav--opened")
            }), e(".js-mod-sub-navigation__close").on("click", function(t) {
                t.preventDefault(), i.fadeOut("fast"), e("body").removeClass("is-nav--opened")
            }), e('a:contains("BACK TO TOP")').each(function() {
                e(this).click(function(t) {
                    t.preventDefault(), e("html, body").animate({
                        scrollTop: 0
                    }, "fast")
                })
            })
        })
    }), define("modules/dropdown", ["jquery"], function(e) {
        e(document).on("render", function() {
            e(".dropdown__sort").click(function(t) {
                t.stopPropagation(), e(this).toggleClass("open")
            }), e(document).click(function() {
                e(".dropdown__sort").removeClass("open")
            })
        })
    }), define("modules/tooltips", ["jquery", "modernizr"], function(e, t) {
        function n(e) {
            FB.XFBML.parse(e.get(0)), twttr.widgets.load(e.get(0)), e.siblings(".active").removeClass("active"), e.stop(!0, !0).addClass("active")
        }

        function i(t) {
            t.preventDefault();
            var n = 670,
                i = 370,
                r = e(t.target).closest("a"),
                s = window.screenLeft ? window.screenLeft : window.screenX,
                o = window.screenTop ? window.screenTop : window.screenY,
                a = o + e(window).height() / 2 - i / 2,
                l = s + e(window).width() / 2 - n / 2,
                c = "width=" + n + ", height=" + i + ", top=" + a + ", left=" + l,
                u = "Share";
            window.open(r.attr("href"), u, c)
        }

        function r(r) {
            if (t.touch) {
                var s = e(".tooltip-social.tooltip-social--touch", r);
                s.on("click", function(t) {
                    t.preventDefault(), n(e(this))
                }), e(document).on("touchend", function(e) {
                    s.hasClass("active") && (s.is(e.target) || 0 !== s.has(e.target).length || s.removeClass("active"))
                }), e(".article-share__item--tumblr .tooltip-social__content__inner .share-popup").on("click", i)
            } else {
                e("body").on("mouseenter", ".tooltip-social", function() {
                    n(e(this))
                }), e("body").on("mouseleave", ".tooltip-social", function() {
                    e(this).stop(!0, !0).delay(100).queue(function() {
                        e(this).removeClass("active")
                    })
                });
                var o = e(".tooltip", r),
                    a = "" !== o.attr("title");
                a && (t.touch || o.hover(function() {
                    var t = e(this).attr("title"),
                        n = e(this).offset().left,
                        i = e(this).offset().top,
                        r = 40,
                        s = 22;
                    e(this).data("tooltipText", t).removeAttr("title"), e('<div class="tooltip__content"></div>').text(t).prependTo("body").fadeIn(50), e(".tooltip__content").css("left", n - r), e(".tooltip__content").css("top", e(this).position().top + i - e(".tooltip__content").outerHeight() - s)
                }, function() {
                    e(this).attr("title", e(this).data("tooltipText")), e(".tooltip__content").stop(!0, !0).remove()
                })), e(".share-popup").on("click", i)
            }
        }
        var s = !1,
            o = !1;
        e.when(e.getScript("//apis.google.com/js/platform.js"), e.getScript("//platform.twitter.com/widgets.js")).done(function() {
            s = !0, o && r(document)
        }), e(document).on("render", function(e, t) {
            o = !0, s && r(t)
        })
    }), Unison = function() {
        var e, t = window,
            n = document,
            i = n.head,
            r = {},
            s = !1,
            o = {
                parseMQ: function(e) {
                    var t = this.getStyleProperty(e, "font-family");
                    return t.replace(/"/g, "").replace(/'/g, "")
                },
                getStyleProperty: function(e, n) {
                    return this.isUndefined(t.getComputedStyle) ? (n = n.replace(/-(.)/g, function(e, t) {
                        return t.toUpperCase()
                    }), e.currentStyle[n]) : t.getComputedStyle(e, null).getPropertyValue(n)
                },
                debounce: function(e, t, n) {
                    var i;
                    return function() {
                        var r = this,
                            s = arguments;
                        clearTimeout(i), i = setTimeout(function() {
                            i = null, n || e.apply(r, s)
                        }, t), n && !i && e.apply(r, s)
                    }
                },
                isObject: function(e) {
                    return "object" == typeof e
                },
                isUndefined: function(e) {
                    return "undefined" == typeof e
                }
            },
            a = {
                on: function(e, t) {
                    o.isObject(r[e]) || (r[e] = []), r[e].push(t)
                },
                emit: function(e, t) {
                    if (o.isObject(r[e]))
                        for (var n = r[e].slice(), i = 0; i < n.length; i++) n[i].call(this, t)
                }
            },
            l = {
                all: function() {
                    for (var e = {}, t = o.parseMQ(n.querySelector("title")).split(","), i = 0; i < t.length; i++) {
                        var r = t[i].trim().split(" ");
                        e[r[0]] = r[1]
                    }
                    return s ? e : null
                },
                now: function(e) {
                    var t = o.parseMQ(i).split(" "),
                        n = {
                            name: t[0],
                            width: t[1]
                        };
                    return s ? o.isUndefined(e) ? n : e(n) : null
                },
                update: function() {
                    l.now(function(t) {
                        t.name !== e && (a.emit(t.name), a.emit("change", t), e = t.name)
                    })
                }
            };
        return o.isUndefined(i) && (i = document.getElementsByTagName("head")[0]), t.onresize = o.debounce(l.update, 100), s = "none" !== o.getStyleProperty(i, "clear"), l.update(), {
            fetch: {
                all: l.all,
                now: l.now
            },
            on: a.on,
            emit: a.emit,
            util: {
                debounce: o.debounce,
                isObject: o.isObject
            }
        }
    }(), define("unison", function(e) {
        return function() {
            var t;
            return t || e.Unison
        }
    }(this)), define("modules/mod-gallery", ["jquery", "carousel", "unison"], function(e, t, n) {
        function i(i) {
            function r(t, n) {
                var i, r = l,
                    s = c,
                    o = r.left;
                if (s.disableAnimation(), o += n.deltaX, o <= r.endPos) i = s.$slides.last().attr("data-carousel-left") - 0;
                else if (o >= r.startPos) i = s.$slides.first().attr("data-carousel-left") - 0;
                else {
                    var a = r.$slides.first();
                    r.$slides.each(function() {
                        return e(this).attr("data-carousel-left") - 0 <= o ? (a = e(this), !1) : void 0
                    });
                    var u = a.outerWidth(!0),
                        h = s.$slides.eq(a.index() - 1).outerWidth(!0),
                        d = Math.round(n.deltaX * h / u);
                    i = Math.min(s.startPos, Math.max(s.left + d, s.endPos))
                }
                s.setPos(i), n.isFinal && (s.left = i, s.enableAnimation().go())
            }
            if (i.length && i.is(":visible")) {
                var s = e(".mod-full-gallery__carousel-wrapper", i);
                s.clone().insertAfter(i);
                var o = i.next();
                o.addClass("mod-full-gallery__carousel-wrapper--up-next"), e(".mod-full-gallery__item", o).first().remove();
                var a = '<li class="js-placeholder-slide mod-full-gallery__item" />';
                e(".mod-full-gallery__carousel", o).append(a);
                var l = new t(i, {
                        alignRight: !0,
                        fitSlides: !0,
                        scaleSlides: !0,
                        threshold: 0
                    }),
                    c = new t(o, {
                        alignLastSlideRight: !1,
                        swipe: !1,
                        scaleSlides: !0,
                        scaleRatio: "desktop" === n.fetch.now().name ? .74 : .47,
                        dummySlide: !0,
                        threshold: 0
                    });
                n.on("change", function(e) {
                    c.settings.scaleRatio = "desktop" === e.name ? .74 : .47, c.calibrate();
                    var t = l,
                        n = c;
                    n.$slides.filter(".active").length && n.go(n.$slides.eq(t.$slides.filter(".active").index()), !1)
                }), l.$nav.on("click", function() {
                    var t = e(this).hasClass("js-carousel__nav--prev") ? "prev" : "next";
                    c.go(t)
                }), l.$wrapper.on("slide-activate", function() {
                    var t = l.$slides.filter(".active"),
                        n = t.find(".mod-full-gallery__item-caption").html();
                    if (e(".mod-full-gallery__caption", this).toggle(!!n), n) {
                        var i = e(this).parents(".mod-full-gallery"),
                            r = e(".mod-full-gallery__caption__inner", i);
                        r.toggleClass("mod-full-gallery__caption__inner--active"), r.filter(".mod-full-gallery__caption__inner--active").html(n)
                    }
                }), l.$wrapper.on("calibrate", function() {
                    l.$slides.each(function(t) {
                        var n = e(this).outerHeight();
                        c.$slides.eq(t).data("slide-height", n)
                    })
                }), i.on("carousel-pan", r), l.calibrate(), c.calibrate();
                var u = l.$slides.filter(".active"),
                    h = u.find(".mod-full-gallery__item-caption").html();
                e(".mod-full-gallery__caption", l.$wrapper).toggle(!!h)
            }
        }
        e(document).on("render", function(t, n) {
            e(".js-carousel--gallery:visible:not(.initialized)", n).each(function() {
                e(this).addClass("initialized"), i(e(this))
            }), e(".mod-half-gallery__expand", n).on("click", function(t) {
                var n = e(this).parents(".article-aside");
                n.hasClass("article-aside--full") || (t.preventDefault(), n.removeClass("article-aside--right").addClass("article-aside--full"), e(this).parent().addClass("mod-half-gallery--full"), e(this).hide(), e(this).hasClass("initialized") || (e(this).addClass("initialized"), i(e(".js-carousel--gallery", this.parentNode))))
            }), e(".js-mod-full-gallery__close", n).on("click", function(t) {
                t.preventDefault(), e(this).parents(".article-aside").addClass("article-aside--right").removeClass("article-aside--full"), e(this).parents(".mod-half-gallery").removeClass("mod-half-gallery--full"), e(this).parents(".mod-half-gallery").find(".mod-half-gallery__expand").show()
            })
        })
    }), define("modules/mod-listicle", ["jquery", "unison"], function(e, t) {
        function n() {
            var name=t.fetch.now()!=null?t.fetch.now().name:'';
            p = name, d.removeClass("pinned scroll-complete").each(function() {
                var t = e(".mod-listicle__nav-outer", this),
                    n = e(".mod-listicle__nav-container", this),
                    i = e(this).offset().top + s,
                    r = d.offset().top + d.outerHeight();
                if (r -= t.outerHeight() + 60, "desktop" !== p) return void t.add(n).removeAttr("style");
                var o = Math.min(i + e(this).height(), r);
                e(this).data({
                    pinStart: i,
                    pinEnd: o
                }), l || (l = t.width(), c = t.height(), u = n.width(), h = n.height());
                var a = e(".mod-listicle__nav-wrap--outer", this).width();
                a -= e(".mod-listicle__content", this).outerWidth(!0), a += e(".mod-listicle__page-index", this).first().width();
                var f = a / l,
                    m = c * f;
                t.add(n).width(a).height(m)
            }), i()
        }

        function i() {
            d.length && "desktop" === p && (a = e(window).scrollTop(), d.each(function() {
                var t = e(this).data("pinStart"),
                    n = e(this).data("pinEnd"),
                    i = a > t,
                    r = n > a,
                    l = a >= n;
                e(this).toggleClass("pinned", i && r), e(this).toggleClass("scroll-complete", l);
                var c = e(".mod-listicle__item", this).removeClass("active"),
                    u = c.first();
                e(".mod-listicle__item", this).each(function() {
                    return e(this).offset().top + s - 1 <= a ? void(u = e(this)) : !1
                }), u.addClass("active");
                var h = e(".mod-listicle__pager--prev", this),
                    d = e(".mod-listicle__pager--next", this),
                    p = !u.prev().length;
                p = p && a < e(this).offset().top + o + s;
                var f = !u.next().length;
                h.toggleClass("mod-listicle__pager--disabled", p), d.toggleClass("mod-listicle__pager--disabled", f)
            }))
        }

        function r() {
            var e = window.requestAnimationFrame;
            return e ? e(i) : i()
        }
        var s = -100,
            o = 200,
            a = 0,
            l = 0,
            c = 0,
            u = 0,
            h = 0,
            d = e(),
            p = "desktop";
        e(document).on("render", function(t, i) {
            var r = e(".mod-listicle", i);
            d = d.add(r), n(), e(".mod-listicle__pager", r).on("click", function(t) {
                if (t.preventDefault(), !e(this).hasClass("mod-listicle__pager--disabled")) {
                    var n = e(this).parents(".mod-listicle"),
                        i = e(".mod-listicle__item.active", n),
                        r = e(this).hasClass("mod-listicle__pager--prev"),
                        l = e();
                    l = r && a >= i.offset().top + o + s ? i : r ? i.prev() : i.next(), e("html, body").animate({
                        scrollTop: l.offset().top + s
                    })
                }
            })
        }), e(window).on("resize", n), e(window).on("load", n), e(window).on("scroll", r)
    }),
    function(e) {
        e.fn.matchHeight = function(t) {
            if ("remove" === t) {
                var n = this;
                return this.css("height", ""), e.each(e.fn.matchHeight._groups, function(e, t) {
                    t.elements = t.elements.not(n)
                }), this
            }
            return 1 >= this.length ? this : (t = "undefined" != typeof t ? t : !0, e.fn.matchHeight._groups.push({
                elements: this,
                byRow: t
            }), e.fn.matchHeight._apply(this, t), this)
        }, e.fn.matchHeight._apply = function(t, n) {
            var s = e(t),
                o = [s];
            return n && (s.css({
                display: "block",
                "padding-top": "0",
                "padding-bottom": "0",
                "border-top": "0",
                "border-bottom": "0",
                height: "100px"
            }), o = i(s), s.css({
                display: "",
                "padding-top": "",
                "padding-bottom": "",
                "border-top": "",
                "border-bottom": "",
                height: ""
            })), e.each(o, function(t, n) {
                var i = e(n),
                    s = 0;
                i.each(function() {
                    var t = e(this);
                    t.css({
                        display: "block",
                        height: ""
                    }), t.outerHeight(!1) > s && (s = t.outerHeight(!1)), t.css({
                        display: ""
                    })
                }), i.each(function() {
                    var t = e(this),
                        n = 0;
                    "border-box" !== t.css("box-sizing") && (n += r(t.css("border-top-width")) + r(t.css("border-bottom-width")), n += r(t.css("padding-top")) + r(t.css("padding-bottom"))), t.css("height", s - n)
                })
            }), this
        }, e.fn.matchHeight._applyDataApi = function() {
            var t = {};
            e("[data-match-height], [data-mh]").each(function() {
                var n = e(this),
                    i = n.attr("data-match-height");
                t[i] = i in t ? t[i].add(n) : n
            }), e.each(t, function() {
                this.matchHeight(!0)
            })
        }, e.fn.matchHeight._groups = [], e.fn.matchHeight._throttle = 80;
        var t = -1,
            n = -1;
        e.fn.matchHeight._update = function(i) {
            if (i && "resize" === i.type) {
                if (i = e(window).width(), i === t) return;
                t = i
            } - 1 === n && (n = setTimeout(function() {
                e.each(e.fn.matchHeight._groups, function() {
                    e.fn.matchHeight._apply(this.elements, this.byRow)
                }), n = -1
            }, e.fn.matchHeight._throttle))
        }, e(e.fn.matchHeight._applyDataApi), e(window).bind("load resize orientationchange", e.fn.matchHeight._update);
        var i = function(t) {
                var n = null,
                    i = [];
                return e(t).each(function() {
                    var t = e(this),
                        s = t.offset().top - r(t.css("margin-top")),
                        o = 0 < i.length ? i[i.length - 1] : null;
                    null === o ? i.push(t) : 1 >= Math.floor(Math.abs(n - s)) ? i[i.length - 1] = o.add(t) : i.push(t), n = s
                }), i
            },
            r = function(e) {
                return parseFloat(e) || 0
            }
    }(jQuery), define("matchHeight", function() {}), define("modules/mod-winners", ["jquery", "unison", "matchHeight"], function(e, t) {
        function n() {
            var name=t.fetch.now()!=null?t.fetch.now().name:'';
            s = name, "mobile" === s ? r.matchHeight("remove") : "tablet" === s ? r.matchHeight() : r.matchHeight(!1)
        }
        var i = e(".mod-winners"),
            r = e(".mod-winners__text", i),
            s = "mobile";
        e(document).on("render", function() {
            n()
        })
    }), ! function(e) {
        "function" == typeof define && define.amd ? define("slick", ["jquery"], e) : e(jQuery)
    }(function(e) {
        var t = window.Slick || {};
        t = function() {
            function t(t, i) {
                var r, s, o = this;
                if (o.defaults = {
                        accessibility: !0,
                        appendArrows: e(t),
                        arrows: !0,
                        asNavFor: null,
                        prevArrow: '<button type="button" data-role="none" class="slick-prev">Previous</button>',
                        nextArrow: '<button type="button" data-role="none" class="slick-next">Next</button>',
                        autoplay: !1,
                        autoplaySpeed: 3e3,
                        centerMode: !1,
                        centerPadding: "50px",
                        cssEase: "ease",
                        customPaging: function(e, t) {
                            return '<button type="button" data-role="none">' + (t + 1) + "</button>"
                        },
                        dots: !1,
                        dotsClass: "slick-dots",
                        draggable: !0,
                        easing: "linear",
                        fade: !1,
                        focusOnSelect: !1,
                        infinite: !0,
                        lazyLoad: "ondemand",
                        onBeforeChange: null,
                        onAfterChange: null,
                        onInit: null,
                        onReInit: null,
                        pauseOnHover: !0,
                        pauseOnDotsHover: !1,
                        responsive: null,
                        rtl: !1,
                        slide: "div",
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        speed: 300,
                        swipe: !0,
                        touchMove: !0,
                        touchThreshold: 5,
                        useCSS: !0,
                        vertical: !1
                    }, o.initials = {
                        animating: !1,
                        dragging: !1,
                        autoPlayTimer: null,
                        currentSlide: 0,
                        currentLeft: null,
                        direction: 1,
                        $dots: null,
                        listWidth: null,
                        listHeight: null,
                        loadIndex: 0,
                        $nextArrow: null,
                        $prevArrow: null,
                        slideCount: null,
                        slideWidth: null,
                        $slideTrack: null,
                        $slides: null,
                        sliding: !1,
                        slideOffset: 0,
                        swipeLeft: null,
                        $list: null,
                        touchObject: {},
                        transformsEnabled: !1
                    }, e.extend(o, o.initials), o.activeBreakpoint = null, o.animType = null, o.animProp = null, o.breakpoints = [], o.breakpointSettings = [], o.cssTransitions = !1, o.paused = !1, o.positionProp = null, o.$slider = e(t), o.$slidesCache = null, o.transformType = null, o.transitionType = null, o.windowWidth = 0, o.windowTimer = null, o.options = e.extend({}, o.defaults, i), o.originalSettings = o.options, r = o.options.responsive || null, r && r.length > -1) {
                    for (s in r) r.hasOwnProperty(s) && (o.breakpoints.push(r[s].breakpoint), o.breakpointSettings[r[s].breakpoint] = r[s].settings);
                    o.breakpoints.sort(function(e, t) {
                        return t - e
                    })
                }
                o.autoPlay = e.proxy(o.autoPlay, o), o.autoPlayClear = e.proxy(o.autoPlayClear, o), o.changeSlide = e.proxy(o.changeSlide, o), o.selectHandler = e.proxy(o.selectHandler, o), o.setPosition = e.proxy(o.setPosition, o), o.swipeHandler = e.proxy(o.swipeHandler, o), o.dragHandler = e.proxy(o.dragHandler, o), o.keyHandler = e.proxy(o.keyHandler, o), o.autoPlayIterator = e.proxy(o.autoPlayIterator, o), o.instanceUid = n++, o.htmlExpr = /^(?:\s*(<[\w\W]+>)[^>]*)$/, o.init()
            }
            var n = 0;
            return t
        }(), t.prototype.addSlide = function(t, n, i) {
            var r = this;
            if ("boolean" == typeof n) i = n, n = null;
            else if (0 > n || n >= r.slideCount) return !1;
            r.unload(), "number" == typeof n ? 0 === n && 0 === r.$slides.length ? e(t).appendTo(r.$slideTrack) : i ? e(t).insertBefore(r.$slides.eq(n)) : e(t).insertAfter(r.$slides.eq(n)) : i === !0 ? e(t).prependTo(r.$slideTrack) : e(t).appendTo(r.$slideTrack), r.$slides = r.$slideTrack.children(this.options.slide), r.$slideTrack.children(this.options.slide).detach(), r.$slideTrack.append(r.$slides), r.$slides.each(function(t, n) {
                e(n).attr("index", t)
            }), r.$slidesCache = r.$slides, r.reinit()
        }, t.prototype.animateSlide = function(t, n) {
            var i = {},
                r = this;
            r.options.rtl === !0 && r.options.vertical === !1 && (t = -t), r.transformsEnabled === !1 ? r.options.vertical === !1 ? r.$slideTrack.animate({
                left: t
            }, r.options.speed, r.options.easing, n) : r.$slideTrack.animate({
                top: t
            }, r.options.speed, r.options.easing, n) : r.cssTransitions === !1 ? e({
                animStart: r.currentLeft
            }).animate({
                animStart: t
            }, {
                duration: r.options.speed,
                easing: r.options.easing,
                step: function(e) {
                    r.options.vertical === !1 ? (i[r.animType] = "translate(" + e + "px, 0px)", r.$slideTrack.css(i)) : (i[r.animType] = "translate(0px," + e + "px)", r.$slideTrack.css(i))
                },
                complete: function() {
                    n && n.call()
                }
            }) : (r.applyTransition(), i[r.animType] = r.options.vertical === !1 ? "translate3d(" + t + "px, 0px, 0px)" : "translate3d(0px," + t + "px, 0px)", r.$slideTrack.css(i), n && setTimeout(function() {
                r.disableTransition(), n.call()
            }, r.options.speed))
        }, t.prototype.applyTransition = function(e) {
            var t = this,
                n = {};
            n[t.transitionType] = t.options.fade === !1 ? t.transformType + " " + t.options.speed + "ms " + t.options.cssEase : "opacity " + t.options.speed + "ms " + t.options.cssEase, t.options.fade === !1 ? t.$slideTrack.css(n) : t.$slides.eq(e).css(n)
        }, t.prototype.autoPlay = function() {
            var e = this;
            e.autoPlayTimer && clearInterval(e.autoPlayTimer), e.slideCount > e.options.slidesToShow && e.paused !== !0 && (e.autoPlayTimer = setInterval(e.autoPlayIterator, e.options.autoplaySpeed))
        }, t.prototype.autoPlayClear = function() {
            var e = this;
            e.autoPlayTimer && clearInterval(e.autoPlayTimer)
        }, t.prototype.autoPlayIterator = function() {
            var t = this,
                n = null != t.options.asNavFor ? e(t.options.asNavFor).getSlick() : null;
            t.options.infinite === !1 ? 1 === t.direction ? (t.currentSlide + 1 === t.slideCount - 1 && (t.direction = 0), t.slideHandler(t.currentSlide + t.options.slidesToScroll), null != n && n.slideHandler(n.currentSlide + n.options.slidesToScroll)) : (0 === t.currentSlide - 1 && (t.direction = 1), t.slideHandler(t.currentSlide - t.options.slidesToScroll), null != n && n.slideHandler(n.currentSlide - n.options.slidesToScroll)) : (t.slideHandler(t.currentSlide + t.options.slidesToScroll), null != n && n.slideHandler(n.currentSlide + n.options.slidesToScroll))
        }, t.prototype.buildArrows = function() {
            var t = this;
            t.options.arrows === !0 && t.slideCount > t.options.slidesToShow && (t.$prevArrow = e(t.options.prevArrow), t.$nextArrow = e(t.options.nextArrow), t.htmlExpr.test(t.options.prevArrow) && t.$prevArrow.appendTo(t.options.appendArrows), t.htmlExpr.test(t.options.nextArrow) && t.$nextArrow.appendTo(t.options.appendArrows), t.options.infinite !== !0 && t.$prevArrow.addClass("slick-disabled"))
        }, t.prototype.buildDots = function() {
            var t, n, i = this;
            if (i.options.dots === !0 && i.slideCount > i.options.slidesToShow) {
                for (n = '<ul class="' + i.options.dotsClass + '">', t = 0; t <= i.getDotCount(); t += 1) n += "<li>" + i.options.customPaging.call(this, i, t) + "</li>";
                n += "</ul>", i.$dots = e(n).appendTo(i.$slider), i.$dots.find("li").first().addClass("slick-active")
            }
        }, t.prototype.buildOut = function() {
            var t = this;
            t.$slides = t.$slider.children(t.options.slide + ":not(.slick-cloned)").addClass("slick-slide"), t.slideCount = t.$slides.length, t.$slides.each(function(t, n) {
                e(n).attr("index", t)
            }), t.$slidesCache = t.$slides, t.$slider.addClass("slick-slider"), t.$slideTrack = 0 === t.slideCount ? e('<div class="slick-track"/>').appendTo(t.$slider) : t.$slides.wrapAll('<div class="slick-track"/>').parent(), t.$list = t.$slideTrack.wrap('<div class="slick-list"/>').parent(), t.$slideTrack.css("opacity", 0), t.options.centerMode === !0 && (t.options.slidesToScroll = 1, 0 === t.options.slidesToShow % 2 && (t.options.slidesToShow = 3)), e("img[data-lazy]", t.$slider).not("[src]").addClass("slick-loading"), t.setupInfinite(), t.buildArrows(), t.buildDots(), t.updateDots(), t.options.accessibility === !0 && t.$list.prop("tabIndex", 0), t.setSlideClasses("number" == typeof this.currentSlide ? this.currentSlide : 0), t.options.draggable === !0 && t.$list.addClass("draggable")
        }, t.prototype.checkResponsive = function() {
            var t, n, i = this;
            if (i.originalSettings.responsive && i.originalSettings.responsive.length > -1 && null !== i.originalSettings.responsive) {
                n = null;
                for (t in i.breakpoints) i.breakpoints.hasOwnProperty(t) && e(window).width() < i.breakpoints[t] && (n = i.breakpoints[t]);
                null !== n ? null !== i.activeBreakpoint ? n !== i.activeBreakpoint && (i.activeBreakpoint = n, i.options = e.extend({}, i.options, i.breakpointSettings[n]), i.refresh()) : (i.activeBreakpoint = n, i.options = e.extend({}, i.options, i.breakpointSettings[n]), i.refresh()) : null !== i.activeBreakpoint && (i.activeBreakpoint = null, i.options = e.extend({}, i.options, i.originalSettings), i.refresh())
            }
        }, t.prototype.changeSlide = function(t) {
            var n = this,
                i = e(t.target),
                r = null != n.options.asNavFor ? e(n.options.asNavFor).getSlick() : null;
            switch (i.is("a") && t.preventDefault(), t.data.message) {
                case "previous":
                    n.slideCount > n.options.slidesToShow && (n.slideHandler(n.currentSlide - n.options.slidesToScroll), null != r && r.slideHandler(r.currentSlide - r.options.slidesToScroll));
                    break;
                case "next":
                    n.slideCount > n.options.slidesToShow && (n.slideHandler(n.currentSlide + n.options.slidesToScroll), null != r && r.slideHandler(r.currentSlide + r.options.slidesToScroll));
                    break;
                case "index":
                    var s = e(t.target).parent().index() * n.options.slidesToScroll;
                    n.slideHandler(s), null != r && r.slideHandler(s);
                    break;
                default:
                    return !1
            }
        }, t.prototype.destroy = function() {
            var t = this;
            t.autoPlayClear(), t.touchObject = {}, e(".slick-cloned", t.$slider).remove(), t.$dots && t.$dots.remove(), t.$prevArrow && (t.$prevArrow.remove(), t.$nextArrow.remove()), t.$slides.parent().hasClass("slick-track") && t.$slides.unwrap().unwrap(), t.$slides.removeClass("slick-slide slick-active slick-visible").removeAttr("style"), t.$slider.removeClass("slick-slider"), t.$slider.removeClass("slick-initialized"), t.$list.off(".slick"), e(window).off(".slick-" + t.instanceUid), e(document).off(".slick-" + t.instanceUid)
        }, t.prototype.disableTransition = function(e) {
            var t = this,
                n = {};
            n[t.transitionType] = "", t.options.fade === !1 ? t.$slideTrack.css(n) : t.$slides.eq(e).css(n)
        }, t.prototype.fadeSlide = function(e, t) {
            var n = this;
            n.cssTransitions === !1 ? (n.$slides.eq(e).css({
                zIndex: 1e3
            }), n.$slides.eq(e).animate({
                opacity: 1
            }, n.options.speed, n.options.easing, t)) : (n.applyTransition(e), n.$slides.eq(e).css({
                opacity: 1,
                zIndex: 1e3
            }), t && setTimeout(function() {
                n.disableTransition(e), t.call()
            }, n.options.speed))
        }, t.prototype.filterSlides = function(e) {
            var t = this;
            null !== e && (t.unload(), t.$slideTrack.children(this.options.slide).detach(), t.$slidesCache.filter(e).appendTo(t.$slideTrack), t.reinit())
        }, t.prototype.getCurrent = function() {
            var e = this;
            return e.currentSlide
        }, t.prototype.getDotCount = function() {
            var e, t = this,
                n = 0,
                i = 0,
                r = 0;
            for (e = t.options.infinite === !0 ? t.slideCount + t.options.slidesToShow - t.options.slidesToScroll : t.slideCount; e > n;) r++, i += t.options.slidesToScroll, n = i + t.options.slidesToShow;
            return r
        }, t.prototype.getLeft = function(e) {
            var t, n, i = this,
                r = 0;
            return i.slideOffset = 0, n = i.$slides.first().outerHeight(), i.options.infinite === !0 ? (i.slideCount > i.options.slidesToShow && (i.slideOffset = -1 * i.slideWidth * i.options.slidesToShow, r = -1 * n * i.options.slidesToShow), 0 !== i.slideCount % i.options.slidesToScroll && e + i.options.slidesToScroll > i.slideCount && i.slideCount > i.options.slidesToShow && (i.slideOffset = -1 * i.slideCount % i.options.slidesToShow * i.slideWidth, r = -1 * i.slideCount % i.options.slidesToShow * n)) : 0 !== i.slideCount % i.options.slidesToShow && e + i.options.slidesToScroll > i.slideCount && i.slideCount > i.options.slidesToShow && (i.slideOffset = i.options.slidesToShow * i.slideWidth - i.slideCount % i.options.slidesToShow * i.slideWidth, r = i.slideCount % i.options.slidesToShow * n), i.options.centerMode === !0 && i.options.infinite === !0 ? i.slideOffset += i.slideWidth * Math.floor(i.options.slidesToShow / 2) - i.slideWidth : i.options.centerMode === !0 && (i.slideOffset += i.slideWidth * Math.floor(i.options.slidesToShow / 2)), t = i.options.vertical === !1 ? -1 * e * i.slideWidth + i.slideOffset : -1 * e * n + r
        }, t.prototype.init = function() {
            var t = this;
            e(t.$slider).hasClass("slick-initialized") || (e(t.$slider).addClass("slick-initialized"), t.buildOut(), t.setProps(), t.startLoad(), t.loadSlider(), t.initializeEvents(), t.checkResponsive()), null !== t.options.onInit && t.options.onInit.call(this, t)
        }, t.prototype.initArrowEvents = function() {
            var e = this;
            e.options.arrows === !0 && e.slideCount > e.options.slidesToShow && (e.$prevArrow.on("click.slick", {
                message: "previous"
            }, e.changeSlide), e.$nextArrow.on("click.slick", {
                message: "next"
            }, e.changeSlide))
        }, t.prototype.initDotEvents = function() {
            var t = this;
            t.options.dots === !0 && t.slideCount > t.options.slidesToShow && e("li", t.$dots).on("click.slick", {
                message: "index"
            }, t.changeSlide), t.options.dots === !0 && t.options.pauseOnDotsHover === !0 && t.options.autoplay === !0 && e("li", t.$dots).on("mouseenter.slick", t.autoPlayClear).on("mouseleave.slick", t.autoPlay)
        }, t.prototype.initializeEvents = function() {
            var t = this;
            t.initArrowEvents(), t.initDotEvents(), t.$list.on("touchstart.slick mousedown.slick", {
                action: "start"
            }, t.swipeHandler), t.$list.on("touchmove.slick mousemove.slick", {
                action: "move"
            }, t.swipeHandler), t.$list.on("touchend.slick mouseup.slick", {
                action: "end"
            }, t.swipeHandler), t.$list.on("touchcancel.slick mouseleave.slick", {
                action: "end"
            }, t.swipeHandler), t.options.pauseOnHover === !0 && t.options.autoplay === !0 && (t.$list.on("mouseenter.slick", t.autoPlayClear), t.$list.on("mouseleave.slick", t.autoPlay)), t.options.accessibility === !0 && t.$list.on("keydown.slick", t.keyHandler), t.options.focusOnSelect === !0 && e(t.options.slide, t.$slideTrack).on("click.slick", t.selectHandler), e(window).on("orientationchange.slick.slick-" + t.instanceUid, function() {
                t.checkResponsive(), t.setPosition()
            }), e(window).on("resize.slick.slick-" + t.instanceUid, function() {
                e(window).width() !== t.windowWidth && (clearTimeout(t.windowDelay), t.windowDelay = window.setTimeout(function() {
                    t.windowWidth = e(window).width(), t.checkResponsive(), t.setPosition()
                }, 50))
            }), e(window).on("load.slick.slick-" + t.instanceUid, t.setPosition), e(document).on("ready.slick.slick-" + t.instanceUid, t.setPosition)
        }, t.prototype.initUI = function() {
            var e = this;
            e.options.arrows === !0 && e.slideCount > e.options.slidesToShow && (e.$prevArrow.show(), e.$nextArrow.show()), e.options.dots === !0 && e.slideCount > e.options.slidesToShow && e.$dots.show(), e.options.autoplay === !0 && e.autoPlay()
        }, t.prototype.keyHandler = function(e) {
            var t = this;
            37 === e.keyCode ? t.changeSlide({
                data: {
                    message: "previous"
                }
            }) : 39 === e.keyCode && t.changeSlide({
                data: {
                    message: "next"
                }
            })
        }, t.prototype.lazyLoad = function() {
            function t(t) {
                e("img[data-lazy]", t).each(function() {
                    var t = e(this),
                        n = e(this).attr("data-lazy") + "?" + (new Date).getTime();
                    t.load(function() {
                        t.animate({
                            opacity: 1
                        }, 200)
                    }).css({
                        opacity: 0
                    }).attr("src", n).removeAttr("data-lazy").removeClass("slick-loading")
                })
            }
            var n, i, r, s, o = this;
            o.options.centerMode === !0 || o.options.fade === !0 ? (r = o.options.slidesToShow + o.currentSlide - 1, s = r + o.options.slidesToShow + 2) : (r = o.options.infinite ? o.options.slidesToShow + o.currentSlide : o.currentSlide, s = r + o.options.slidesToShow), n = o.$slider.find(".slick-slide").slice(r, s), t(n), 1 == o.slideCount ? (i = o.$slider.find(".slick-slide"), t(i)) : o.currentSlide >= o.slideCount - o.options.slidesToShow ? (i = o.$slider.find(".slick-cloned").slice(0, o.options.slidesToShow), t(i)) : 0 === o.currentSlide && (i = o.$slider.find(".slick-cloned").slice(-1 * o.options.slidesToShow), t(i))
        }, t.prototype.loadSlider = function() {
            var e = this;
            e.setPosition(), e.$slideTrack.css({
                opacity: 1
            }), e.$slider.removeClass("slick-loading"), e.initUI(), "progressive" === e.options.lazyLoad && e.progressiveLazyLoad()
        }, t.prototype.postSlide = function(e) {
            var t = this;
            null !== t.options.onAfterChange && t.options.onAfterChange.call(this, t, e), t.animating = !1, t.setPosition(), t.swipeLeft = null, t.options.autoplay === !0 && t.paused === !1 && t.autoPlay()
        }, t.prototype.progressiveLazyLoad = function() {
            var t, n, i = this;
            t = e("img[data-lazy]").length, t > 0 && (n = e("img[data-lazy]", i.$slider).first(), n.attr("src", n.attr("data-lazy")).removeClass("slick-loading").load(function() {
                n.removeAttr("data-lazy"), i.progressiveLazyLoad()
            }))
        }, t.prototype.refresh = function() {
            var t = this,
                n = t.currentSlide;
            t.destroy(), e.extend(t, t.initials), t.currentSlide = n, t.init()
        }, t.prototype.reinit = function() {
            var t = this;
            t.$slides = t.$slideTrack.children(t.options.slide).addClass("slick-slide"), t.slideCount = t.$slides.length, t.currentSlide >= t.slideCount && 0 !== t.currentSlide && (t.currentSlide = t.currentSlide - t.options.slidesToScroll), t.setProps(), t.setupInfinite(), t.buildArrows(), t.updateArrows(), t.initArrowEvents(), t.buildDots(), t.updateDots(), t.initDotEvents(), t.options.focusOnSelect === !0 && e(t.options.slide, t.$slideTrack).on("click.slick", t.selectHandler), t.setSlideClasses(0), t.setPosition(), null !== t.options.onReInit && t.options.onReInit.call(this, t)
        }, t.prototype.removeSlide = function(e, t) {
            var n = this;
            return "boolean" == typeof e ? (t = e, e = t === !0 ? 0 : n.slideCount - 1) : e = t === !0 ? --e : e, n.slideCount < 1 || 0 > e || e > n.slideCount - 1 ? !1 : (n.unload(), n.$slideTrack.children(this.options.slide).eq(e).remove(), n.$slides = n.$slideTrack.children(this.options.slide), n.$slideTrack.children(this.options.slide).detach(), n.$slideTrack.append(n.$slides), n.$slidesCache = n.$slides, void n.reinit())
        }, t.prototype.setCSS = function(e) {
            var t, n, i = this,
                r = {};
            i.options.rtl === !0 && (e = -e), t = "left" == i.positionProp ? e + "px" : "0px", n = "top" == i.positionProp ? e + "px" : "0px", r[i.positionProp] = e, i.transformsEnabled === !1 ? i.$slideTrack.css(r) : (r = {}, i.cssTransitions === !1 ? (r[i.animType] = "translate(" + t + ", " + n + ")", i.$slideTrack.css(r)) : (r[i.animType] = "translate3d(" + t + ", " + n + ", 0px)", i.$slideTrack.css(r)))
        }, t.prototype.setDimensions = function() {
            var e = this;
            e.options.vertical === !1 ? e.options.centerMode === !0 && e.$list.css({
                padding: "0px " + e.options.centerPadding
            }) : (e.$list.height(e.$slides.first().outerHeight(!0) * e.options.slidesToShow), e.options.centerMode === !0 && e.$list.css({
                padding: e.options.centerPadding + " 0px"
            })), e.listWidth = e.$list.width(), e.listHeight = e.$list.height(), e.options.vertical === !1 ? (e.slideWidth = Math.ceil(e.listWidth / e.options.slidesToShow), e.$slideTrack.width(Math.ceil(e.slideWidth * e.$slideTrack.children(".slick-slide").length))) : (e.slideWidth = Math.ceil(e.listWidth), e.$slideTrack.height(Math.ceil(e.$slides.first().outerHeight(!0) * e.$slideTrack.children(".slick-slide").length)));
            var t = e.$slides.first().outerWidth(!0) - e.$slides.first().width();
            e.$slideTrack.children(".slick-slide").width(e.slideWidth - t)
        }, t.prototype.setFade = function() {
            var t, n = this;
            n.$slides.each(function(i, r) {
                t = -1 * n.slideWidth * i, e(r).css({
                    position: "relative",
                    left: t,
                    top: 0,
                    zIndex: 800,
                    opacity: 0
                })
            }), n.$slides.eq(n.currentSlide).css({
                zIndex: 900,
                opacity: 1
            })
        }, t.prototype.setPosition = function() {
            var e = this;
            e.setDimensions(), e.options.fade === !1 ? e.setCSS(e.getLeft(e.currentSlide)) : e.setFade()
        }, t.prototype.setProps = function() {
            var e = this;
            e.positionProp = e.options.vertical === !0 ? "top" : "left", "top" === e.positionProp ? e.$slider.addClass("slick-vertical") : e.$slider.removeClass("slick-vertical"), (void 0 !== document.body.style.WebkitTransition || void 0 !== document.body.style.MozTransition || void 0 !== document.body.style.msTransition) && e.options.useCSS === !0 && (e.cssTransitions = !0), void 0 !== document.body.style.MozTransform && (e.animType = "MozTransform", e.transformType = "-moz-transform", e.transitionType = "MozTransition"), void 0 !== document.body.style.webkitTransform && (e.animType = "webkitTransform", e.transformType = "-webkit-transform", e.transitionType = "webkitTransition"), void 0 !== document.body.style.msTransform && (e.animType = "msTransform", e.transformType = "-ms-transform", e.transitionType = "msTransition"), void 0 !== document.body.style.transform && (e.animType = "transform", e.transformType = "transform", e.transitionType = "transition"), e.transformsEnabled = null !== e.animType
        }, t.prototype.setSlideClasses = function(e) {
            var t, n, i, r, s = this;
            s.$slider.find(".slick-slide").removeClass("slick-active").removeClass("slick-center"), n = s.$slider.find(".slick-slide"), s.options.centerMode === !0 ? (t = Math.floor(s.options.slidesToShow / 2), s.options.infinite === !0 && (e >= t && e <= s.slideCount - 1 - t ? s.$slides.slice(e - t, e + t + 1).addClass("slick-active") : (i = s.options.slidesToShow + e, n.slice(i - t + 1, i + t + 2).addClass("slick-active")), 0 === e ? n.eq(n.length - 1 - s.options.slidesToShow).addClass("slick-center") : e === s.slideCount - 1 && n.eq(s.options.slidesToShow).addClass("slick-center")), s.$slides.eq(e).addClass("slick-center")) : e >= 0 && e <= s.slideCount - s.options.slidesToShow ? s.$slides.slice(e, e + s.options.slidesToShow).addClass("slick-active") : n.length <= s.options.slidesToShow ? n.addClass("slick-active") : (r = s.slideCount % s.options.slidesToShow, i = s.options.infinite === !0 ? s.options.slidesToShow + e : e, s.options.slidesToShow == s.options.slidesToScroll && s.slideCount - e < s.options.slidesToShow ? n.slice(i - (s.options.slidesToShow - r), i + r).addClass("slick-active") : n.slice(i, i + s.options.slidesToShow).addClass("slick-active")), "ondemand" === s.options.lazyLoad && s.lazyLoad()
        }, t.prototype.setupInfinite = function() {
            var t, n, i, r = this;
            if ((r.options.fade === !0 || r.options.vertical === !0) && (r.options.centerMode = !1), r.options.infinite === !0 && r.options.fade === !1 && (n = null, r.slideCount > r.options.slidesToShow)) {
                for (i = r.options.centerMode === !0 ? r.options.slidesToShow + 1 : r.options.slidesToShow, t = r.slideCount; t > r.slideCount - i; t -= 1) n = t - 1, e(r.$slides[n]).clone(!0).attr("id", "").prependTo(r.$slideTrack).addClass("slick-cloned");
                for (t = 0; i > t; t += 1) n = t, e(r.$slides[n]).clone(!0).attr("id", "").appendTo(r.$slideTrack).addClass("slick-cloned");
                r.$slideTrack.find(".slick-cloned").find("[id]").each(function() {
                    e(this).attr("id", "")
                })
            }
        }, t.prototype.selectHandler = function(t) {
            var n = this,
                i = null != n.options.asNavFor ? e(n.options.asNavFor).getSlick() : null,
                r = parseInt(e(t.target).parent().attr("index"));
            if (r || (r = 0), !(n.slideCount <= n.options.slidesToShow) && (n.slideHandler(r), null != i)) {
                if (i.slideCount <= i.options.slidesToShow) return;
                i.slideHandler(r)
            }
        }, t.prototype.slideHandler = function(e) {
            var t, n, i, r, s = null,
                o = this;
            return o.animating === !0 ? !1 : (t = e, s = o.getLeft(t), i = o.getLeft(o.currentSlide), r = 0 !== o.slideCount % o.options.slidesToScroll ? o.options.slidesToScroll : 0, o.currentLeft = null === o.swipeLeft ? i : o.swipeLeft, o.options.infinite === !1 && o.options.centerMode === !1 && (0 > e || e > o.slideCount - o.options.slidesToShow + r) ? (o.options.fade === !1 && (t = o.currentSlide, o.animateSlide(i, function() {
                o.postSlide(t)
            })), !1) : o.options.infinite === !1 && o.options.centerMode === !0 && (0 > e || e > o.slideCount - o.options.slidesToScroll) ? (o.options.fade === !1 && (t = o.currentSlide, o.animateSlide(i, function() {
                o.postSlide(t)
            })), !1) : (o.options.autoplay === !0 && clearInterval(o.autoPlayTimer), n = 0 > t ? 0 !== o.slideCount % o.options.slidesToScroll ? o.slideCount - o.slideCount % o.options.slidesToScroll : o.slideCount - o.options.slidesToScroll : t > o.slideCount - 1 ? 0 : t, o.animating = !0, null !== o.options.onBeforeChange && e !== o.currentSlide && o.options.onBeforeChange.call(this, o, o.currentSlide, n), o.currentSlide = n, o.setSlideClasses(o.currentSlide), o.updateDots(), o.updateArrows(), o.options.fade === !0 ? (o.fadeSlide(n, function() {
                o.postSlide(n)
            }), !1) : void o.animateSlide(s, function() {
                o.postSlide(n)
            })))
        }, t.prototype.startLoad = function() {
            var e = this;
            e.options.arrows === !0 && e.slideCount > e.options.slidesToShow && (e.$prevArrow.hide(), e.$nextArrow.hide()), e.options.dots === !0 && e.slideCount > e.options.slidesToShow && e.$dots.hide(), e.$slider.addClass("slick-loading")
        }, t.prototype.swipeDirection = function() {
            var e, t, n, i, r = this;
            return e = r.touchObject.startX - r.touchObject.curX, t = r.touchObject.startY - r.touchObject.curY, n = Math.atan2(t, e), i = Math.round(180 * n / Math.PI), 0 > i && (i = 360 - Math.abs(i)), 45 >= i && i >= 0 ? "left" : 360 >= i && i >= 315 ? "left" : i >= 135 && 225 >= i ? "right" : "vertical"
        }, t.prototype.swipeEnd = function(t) {
            var n = this,
                i = null != n.options.asNavFor ? e(n.options.asNavFor).getSlick() : null;
            if (n.dragging = !1, void 0 === n.touchObject.curX) return !1;
            if (n.touchObject.swipeLength >= n.touchObject.minSwipe) switch (e(t.target).on("click.slick", function(t) {
                t.stopImmediatePropagation(), t.stopPropagation(), t.preventDefault(), e(t.target).off("click.slick")
            }), n.swipeDirection()) {
                case "left":
                    n.slideHandler(n.currentSlide + n.options.slidesToScroll), null != i && i.slideHandler(i.currentSlide + i.options.slidesToScroll), n.touchObject = {};
                    break;
                case "right":
                    n.slideHandler(n.currentSlide - n.options.slidesToScroll), null != i && i.slideHandler(i.currentSlide - i.options.slidesToScroll), n.touchObject = {}
            } else n.touchObject.startX !== n.touchObject.curX && (n.slideHandler(n.currentSlide), null != i && i.slideHandler(i.currentSlide), n.touchObject = {})
        }, t.prototype.swipeHandler = function(e) {
            var t = this;
            if (!(t.options.swipe === !1 || "ontouchend" in document && t.options.swipe === !1 || t.options.draggable === !1 || t.options.draggable === !1 && !e.originalEvent.touches)) switch (t.touchObject.fingerCount = e.originalEvent && void 0 !== e.originalEvent.touches ? e.originalEvent.touches.length : 1, t.touchObject.minSwipe = t.listWidth / t.options.touchThreshold, e.data.action) {
                case "start":
                    t.swipeStart(e);
                    break;
                case "move":
                    t.swipeMove(e);
                    break;
                case "end":
                    t.swipeEnd(e)
            }
        }, t.prototype.swipeMove = function(e) {
            var t, n, i, r, s = this;
            return r = void 0 !== e.originalEvent ? e.originalEvent.touches : null, t = s.getLeft(s.currentSlide), !s.dragging || r && 1 !== r.length ? !1 : (s.touchObject.curX = void 0 !== r ? r[0].pageX : e.clientX, s.touchObject.curY = void 0 !== r ? r[0].pageY : e.clientY, s.touchObject.swipeLength = Math.round(Math.sqrt(Math.pow(s.touchObject.curX - s.touchObject.startX, 2))), n = s.swipeDirection(), "vertical" !== n ? (void 0 !== e.originalEvent && s.touchObject.swipeLength > 4 && e.preventDefault(), i = s.touchObject.curX > s.touchObject.startX ? 1 : -1, s.swipeLeft = s.options.vertical === !1 ? t + s.touchObject.swipeLength * i : t + s.touchObject.swipeLength * (s.$list.height() / s.listWidth) * i, s.options.fade === !0 || s.options.touchMove === !1 ? !1 : s.animating === !0 ? (s.swipeLeft = null, !1) : void s.setCSS(s.swipeLeft)) : void 0)
        }, t.prototype.swipeStart = function(e) {
            var t, n = this;
            return 1 !== n.touchObject.fingerCount || n.slideCount <= n.options.slidesToShow ? (n.touchObject = {}, !1) : (void 0 !== e.originalEvent && void 0 !== e.originalEvent.touches && (t = e.originalEvent.touches[0]), n.touchObject.startX = n.touchObject.curX = void 0 !== t ? t.pageX : e.clientX, n.touchObject.startY = n.touchObject.curY = void 0 !== t ? t.pageY : e.clientY, void(n.dragging = !0))
        }, t.prototype.unfilterSlides = function() {
            var e = this;
            null !== e.$slidesCache && (e.unload(), e.$slideTrack.children(this.options.slide).detach(), e.$slidesCache.appendTo(e.$slideTrack), e.reinit())
        }, t.prototype.unload = function() {
            var t = this;
            e(".slick-cloned", t.$slider).remove(), t.$dots && t.$dots.remove(), t.$prevArrow && (t.$prevArrow.remove(), t.$nextArrow.remove()), t.$slides.removeClass("slick-slide slick-active slick-visible").removeAttr("style")
        }, t.prototype.updateArrows = function() {
            var e = this;
            e.options.arrows === !0 && e.options.infinite !== !0 && e.slideCount > e.options.slidesToShow && (e.$prevArrow.removeClass("slick-disabled"), e.$nextArrow.removeClass("slick-disabled"), 0 === e.currentSlide ? (e.$prevArrow.addClass("slick-disabled"), e.$nextArrow.removeClass("slick-disabled")) : e.currentSlide >= e.slideCount - e.options.slidesToShow && (e.$nextArrow.addClass("slick-disabled"), e.$prevArrow.removeClass("slick-disabled")))
        }, t.prototype.updateDots = function() {
            var e = this;
            null !== e.$dots && (e.$dots.find("li").removeClass("slick-active"), e.$dots.find("li").eq(Math.floor(e.currentSlide / e.options.slidesToScroll)).addClass("slick-active"))
        }, e.fn.slick = function(e) {
            var n = this;
            return n.each(function(n, i) {
                i.slick = new t(i, e)
            })
        }, e.fn.slickAdd = function(e, t, n) {
            var i = this;
            return i.each(function(i, r) {
                r.slick.addSlide(e, t, n)
            })
        }, e.fn.slickCurrentSlide = function() {
            var e = this;
            return e.get(0).slick.getCurrent()
        }, e.fn.slickFilter = function(e) {
            var t = this;
            return t.each(function(t, n) {
                n.slick.filterSlides(e)
            })
        }, e.fn.slickGoTo = function(t) {
            var n = this;
            return n.each(function(n, i) {
                var r = null != i.slick.options.asNavFor ? e(i.slick.options.asNavFor) : null;
                null != r && r.slickGoTo(t), i.slick.slideHandler(t)
            })
        }, e.fn.slickNext = function() {
            var e = this;
            return e.each(function(e, t) {
                t.slick.changeSlide({
                    data: {
                        message: "next"
                    }
                })
            })
        }, e.fn.slickPause = function() {
            var e = this;
            return e.each(function(e, t) {
                t.slick.autoPlayClear(), t.slick.paused = !0
            })
        }, e.fn.slickPlay = function() {
            var e = this;
            return e.each(function(e, t) {
                t.slick.paused = !1, t.slick.autoPlay()
            })
        }, e.fn.slickPrev = function() {
            var e = this;
            return e.each(function(e, t) {
                t.slick.changeSlide({
                    data: {
                        message: "previous"
                    }
                })
            })
        }, e.fn.slickRemove = function(e, t) {
            var n = this;
            return n.each(function(n, i) {
                i.slick.removeSlide(e, t)
            })
        }, e.fn.slickGetOption = function(e) {
            var t = this;
            return t.get(0).slick.options[e]
        }, e.fn.slickSetOption = function(e, t, n) {
            var i = this;
            return i.each(function(i, r) {
                r.slick.options[e] = t, n === !0 && (r.slick.unload(), r.slick.reinit())
            })
        }, e.fn.slickUnfilter = function() {
            var e = this;
            return e.each(function(e, t) {
                t.slick.unfilterSlides()
            })
        }, e.fn.unslick = function() {
            var e = this;
            return e.each(function(e, t) {
                t.slick && t.slick.destroy()
            })
        }, e.fn.getSlick = function() {
            var e = null,
                t = this;
            return t.each(function(t, n) {
                e = n.slick
            }), e
        }
    }), define("modules/mod-article-5up", ["jquery", "unison", "slick"], function(e, t) {
        function n() {
            var name=t.fetch.now()!=null?t.fetch.now().name:'';
            s = name, "mobile" === s ? (r.hasClass("slick-initialized") && r.unslick(), r.slick({
                arrows: !1,
                dots: !0,
                speed: 500
            })) : r.unslick()
        }
        var i = e(".mod-article-5up"),
            r = e(".mod-article-5up__container", i),
            s = "mobile";
        e(document).on("render", function() {
            n()
        }), e(window).on("resize", n)
    }), define("modules/mod-article-3up", ["jquery", "unison", "slick"], function(e, t) {
        function n() {
            var name=t.fetch.now()!=null?t.fetch.now().name:'';
            s = name, "mobile" === s ? (r.hasClass("slick-initialized") && r.unslick(), r.slick({
                arrows: !1,
                dots: !0,
                speed: 500,
                adaptiveHeight: !0,
                onAfterChange: function() {
                    console.log(r)
                }
            })) : r.unslick()
        }
        var i = e(".mod-article-3up"),
            r = e(".mod-article-3up__container", i),
            s = "mobile";
        e(document).on("render", function() {
            n()
        }), e(window).on("resize", n)
    }), define("modules/mod-gallery-5up", ["jquery", "unison", "slick"], function(e, t) {
        function n() {
            var name=t.fetch.now()!=null?t.fetch.now().name:'';
            s = name, "mobile" === s ? (r.hasClass("slick-initialized") && r.unslick(), r.slick({
                arrows: !1,
                dots: !0,
                speed: 500,
                adaptiveHeight: !0
            })) : r.unslick()
        }
        var i = e(".mod-gallery-5up"),
            r = e(".mod-gallery-5up__container", i),
            s = "mobile";
        e(window).on("resize", n)
    }),
    function(e) {
        "function" == typeof define && define.amd ? define("jquery-validation", ["jquery"], e) : e(jQuery)
    }(function(e) {
        
        var t, n = {};
        e.ajaxPrefilter ? e.ajaxPrefilter(function(e, t, i) {
            var r = e.port;
            "abort" === e.mode && (n[r] && n[r].abort(), n[r] = i)
        }) : (t = e.ajax, e.ajax = function(i) {
            var r = ("mode" in i ? i : e.ajaxSettings).mode,
                s = ("port" in i ? i : e.ajaxSettings).port;
            return "abort" === r ? (n[s] && n[s].abort(), n[s] = t.apply(this, arguments), n[s]) : t.apply(this, arguments)
        }), e.extend(e.fn, {
            validateDelegate: function(t, n, i) {
                return this.bind(n, function(n) {
                    var r = e(n.target);
                    return r.is(t) ? i.apply(r, arguments) : void 0
                })
            }
        })
    }),
    function(e, t, n) {
        function i(e) {
            var t = {},
                i = /^jQuery\d+$/;
            return n.each(e.attributes, function(e, n) {
                n.specified && !i.test(n.name) && (t[n.name] = n.value)
            }), t
        }

        function r(e, t) {
            var i = this,
                r = n(i);
            if (i.value == r.attr("placeholder") && r.hasClass("placeholder"))
                if (r.data("placeholder-password")) {
                    if (r = r.hide().next().show().attr("id", r.removeAttr("id").data("placeholder-id")), e === !0) return r[0].value = t;
                    r.focus()
                } else i.value = "", r.removeClass("placeholder"), i == o() && i.select()
        }

        function s() {
            var e, t = this,
                s = n(t),
                o = this.id;
            if ("" == t.value) {
                if ("password" == t.type) {
                    if (!s.data("placeholder-textinput")) {
                        try {
                            e = s.clone().attr({
                                type: "text"
                            })
                        } catch (a) {
                            e = n("<input>").attr(n.extend(i(this), {
                                type: "text"
                            }))
                        }
                        e.removeAttr("name").data({
                            "placeholder-password": s,
                            "placeholder-id": o
                        }).bind("focus.placeholder", r), s.data({
                            "placeholder-textinput": e,
                            "placeholder-id": o
                        }).before(e)
                    }
                    s = s.removeAttr("id").hide().prev().attr("id", o).show()
                }
                s.addClass("placeholder"), s[0].value = s.attr("placeholder")
            } else s.removeClass("placeholder")
        }

        function o() {
            try {
                return t.activeElement
            } catch (e) {}
        }
        var a, l, c = "[object OperaMini]" == Object.prototype.toString.call(e.operamini),
            u = "placeholder" in t.createElement("input") && !c,
            h = "placeholder" in t.createElement("textarea") && !c,
            d = n.fn,
            p = n.valHooks,
            f = n.propHooks;
        u && h ? (l = d.placeholder = function() {
            return this
        }, l.input = l.textarea = !0) : (l = d.placeholder = function() {
            var e = this;
            return e.filter((u ? "textarea" : ":input") + "[placeholder]").not(".placeholder").bind({
                "focus.placeholder": r,
                "blur.placeholder": s
            }).data("placeholder-enabled", !0).trigger("blur.placeholder"), e
        }, l.input = u, l.textarea = h, a = {
            get: function(e) {
                var t = n(e),
                    i = t.data("placeholder-password");
                return i ? i[0].value : t.data("placeholder-enabled") && t.hasClass("placeholder") ? "" : e.value
            },
            set: function(e, t) {
                var i = n(e),
                    a = i.data("placeholder-password");
                return a ? a[0].value = t : i.data("placeholder-enabled") ? ("" == t ? (e.value = t, e != o() && s.call(e)) : i.hasClass("placeholder") ? r.call(e, !0, t) || (e.value = t) : e.value = t, i) : e.value = t
            }
        }, u || (p.input = a, f.value = a), h || (p.textarea = a, f.value = a), n(function() {
            n(t).delegate("form", "submit.placeholder", function() {
                var e = n(".placeholder", this).each(r);
                setTimeout(function() {
                    e.each(s)
                }, 10)
            })
        }), n(e).bind("beforeunload.placeholder", function() {
            n(".placeholder").each(function() {
                this.value = ""
            })
        }))
    }(this, document, jQuery), define("jquery-placeholder", function() {}), define("modules/mod-forms", ["jquery", "jquery-validation", "jquery-placeholder"], function(e) {
        }), define("modules/mod-netted", ["jquery", "jquery-validation", "jquery-placeholder"], function(e) {
        var t = e(".mod-netted"),
            n = e(".mod-netted__link", t),
            i = e(".mod-netted__meta", t),
            r = e(".mod-netted__newsletter", t),
            s = e(".mod-netted__email", t),
            o = e(".mod-netted__success", t);
        n.on("click", function() {
            i.removeClass("active"), r.addClass("active"), s.focus()
        }), e(document).on("render", function() {
            
        })
    }), define("modules/mod-netted-1up", ["jquery", "jquery-validation", "jquery-placeholder"], function(e) {
        var t = e(".mod-netted-1up"),
            n = e(".mod-netted-1up__primary", t),
            i = e(".mod-netted-1up__link", t),
            r = e(".mod-netted-1up__meta", t),
            s = e(".mod-netted-1up__newsletter", t),
            o = e(".mod-netted-1up__email", t),
            a = e(".mod-netted-1up__success", t);
        i.on("click", function() {
            r.removeClass("active"), n.addClass("active"), s.addClass("active"), o.focus()
        }), e(document).on("render", function() {
            
        })
    }),
    function(e, t) {
        "function" == typeof define && define.amd ? define("handlebars", [], t) : "object" == typeof exports ? module.exports = t() : e.Handlebars = e.Handlebars || t()
    }(this, function() {
        var e = function() {
                function e(e) {
                    this.string = e
                }
                var t;
                return e.prototype.toString = function() {
                    return "" + this.string
                }, t = e
            }(),
            t = function(e) {
                function t(e) {
                    return l[e]
                }

                function n(e) {
                    for (var t = 1; t < arguments.length; t++)
                        for (var n in arguments[t]) Object.prototype.hasOwnProperty.call(arguments[t], n) && (e[n] = arguments[t][n]);
                    return e
                }

                function i(e) {
                    return e instanceof a ? e.toString() : null == e ? "" : e ? (e = "" + e, u.test(e) ? e.replace(c, t) : e) : e + ""
                }

                function r(e) {
                    return e || 0 === e ? p(e) && 0 === e.length ? !0 : !1 : !0
                }

                function s(e, t) {
                    return (e ? e + "." : "") + t
                }
                var o = {},
                    a = e,
                    l = {
                        "&": "&amp;",
                        "<": "&lt;",
                        ">": "&gt;",
                        '"': "&quot;",
                        "'": "&#x27;",
                        "`": "&#x60;"
                    },
                    c = /[&<>"'`]/g,
                    u = /[&<>"'`]/;
                o.extend = n;
                var h = Object.prototype.toString;
                o.toString = h;
                var d = function(e) {
                    return "function" == typeof e
                };
                d(/x/) && (d = function(e) {
                    return "function" == typeof e && "[object Function]" === h.call(e)
                });
                var d;
                o.isFunction = d;
                var p = Array.isArray || function(e) {
                    return e && "object" == typeof e ? "[object Array]" === h.call(e) : !1
                };
                return o.isArray = p, o.escapeExpression = i, o.isEmpty = r, o.appendContextPath = s, o
            }(e),
            n = function() {
                function e(e, t) {
                    var i;
                    t && t.firstLine && (i = t.firstLine, e += " - " + i + ":" + t.firstColumn);
                    for (var r = Error.prototype.constructor.call(this, e), s = 0; s < n.length; s++) this[n[s]] = r[n[s]];
                    i && (this.lineNumber = i, this.column = t.firstColumn)
                }
                var t, n = ["description", "fileName", "lineNumber", "message", "name", "number", "stack"];
                return e.prototype = new Error, t = e
            }(),
            i = function(e, t) {
                function n(e, t) {
                    this.helpers = e || {}, this.partials = t || {}, i(this)
                }

                function i(e) {
                    e.registerHelper("helperMissing", function() {
                        if (1 === arguments.length) return void 0;
                        throw new o("Missing helper: '" + arguments[arguments.length - 1].name + "'")
                    }), e.registerHelper("blockHelperMissing", function(t, n) {
                        var i = n.inverse,
                            r = n.fn;
                        if (t === !0) return r(this);
                        if (t === !1 || null == t) return i(this);
                        if (u(t)) return t.length > 0 ? (n.ids && (n.ids = [n.name]), e.helpers.each(t, n)) : i(this);
                        if (n.data && n.ids) {
                            var o = g(n.data);
                            o.contextPath = s.appendContextPath(n.data.contextPath, n.name), n = {
                                data: o
                            }
                        }
                        return r(t, n)
                    }), e.registerHelper("each", function(e, t) {
                        if (!t) throw new o("Must pass iterator to #each");
                        var n, i, r = t.fn,
                            a = t.inverse,
                            l = 0,
                            c = "";
                        if (t.data && t.ids && (i = s.appendContextPath(t.data.contextPath, t.ids[0]) + "."), h(e) && (e = e.call(this)), t.data && (n = g(t.data)), e && "object" == typeof e)
                            if (u(e))
                                for (var d = e.length; d > l; l++) n && (n.index = l, n.first = 0 === l, n.last = l === e.length - 1, i && (n.contextPath = i + l)), c += r(e[l], {
                                    data: n
                                });
                            else
                                for (var p in e) e.hasOwnProperty(p) && (n && (n.key = p, n.index = l, n.first = 0 === l, i && (n.contextPath = i + p)), c += r(e[p], {
                                    data: n
                                }), l++);
                        return 0 === l && (c = a(this)), c
                    }), e.registerHelper("if", function(e, t) {
                        return h(e) && (e = e.call(this)), !t.hash.includeZero && !e || s.isEmpty(e) ? t.inverse(this) : t.fn(this)
                    }), e.registerHelper("unless", function(t, n) {
                        return e.helpers["if"].call(this, t, {
                            fn: n.inverse,
                            inverse: n.fn,
                            hash: n.hash
                        })
                    }), e.registerHelper("with", function(e, t) {
                        h(e) && (e = e.call(this));
                        var n = t.fn;
                        if (s.isEmpty(e)) return t.inverse(this);
                        if (t.data && t.ids) {
                            var i = g(t.data);
                            i.contextPath = s.appendContextPath(t.data.contextPath, t.ids[0]), t = {
                                data: i
                            }
                        }
                        return n(e, t)
                    }), e.registerHelper("log", function(t, n) {
                        var i = n.data && null != n.data.level ? parseInt(n.data.level, 10) : 1;
                        e.log(i, t)
                    }), e.registerHelper("lookup", function(e, t) {
                        return e && e[t]
                    })
                }
                var r = {},

                    s = e,
                    o = t,
                    a = "2.0.0";
                r.VERSION = a;
                var l = 6;
                r.COMPILER_REVISION = l;
                var c = {
                    1: "<= 1.0.rc.2",
                    2: "== 1.0.0-rc.3",
                    3: "== 1.0.0-rc.4",
                    4: "== 1.x.x",
                    5: "== 2.0.0-alpha.x",
                    6: ">= 2.0.0-beta.1"
                };
                r.REVISION_CHANGES = c;
                var u = s.isArray,
                    h = s.isFunction,
                    d = s.toString,
                    p = "[object Object]";
                r.HandlebarsEnvironment = n, n.prototype = {
                    constructor: n,
                    logger: f,
                    log: m,
                    registerHelper: function(e, t) {
                        if (d.call(e) === p) {
                            if (t) throw new o("Arg not supported with multiple helpers");
                            s.extend(this.helpers, e)
                        } else this.helpers[e] = t
                    },
                    unregisterHelper: function(e) {
                        delete this.helpers[e]
                    },
                    registerPartial: function(e, t) {
                        d.call(e) === p ? s.extend(this.partials, e) : this.partials[e] = t
                    },
                    unregisterPartial: function(e) {
                        delete this.partials[e]
                    }
                };
                var f = {
                    methodMap: {
                        0: "debug",
                        1: "info",
                        2: "warn",
                        3: "error"
                    },
                    DEBUG: 0,
                    INFO: 1,
                    WARN: 2,
                    ERROR: 3,
                    level: 3,
                    log: function(e, t) {
                        if (f.level <= e) {
                            var n = f.methodMap[e];
                            "undefined" != typeof console && console[n] && console[n].call(console, t)
                        }
                    }
                };
                r.logger = f;
                var m = f.log;
                r.log = m;
                var g = function(e) {
                    var t = s.extend({}, e);
                    return t._parent = e, t
                };
                return r.createFrame = g, r
            }(t, n),
            r = function(e, t, n) {
                function i(e) {
                    var t = e && e[0] || 1,
                        n = d;
                    if (t !== n) {
                        if (n > t) {
                            var i = p[n],
                                r = p[t];
                            throw new h("Template was precompiled with an older version of Handlebars than the current runtime. Please update your precompiler to a newer version (" + i + ") or downgrade your runtime to an older version (" + r + ").")
                        }
                        throw new h("Template was precompiled with a newer version of Handlebars than the current runtime. Please update your runtime to a newer version (" + e[1] + ").")
                    }
                }

                function r(e, t) {
                    if (!t) throw new h("No environment passed to template");
                    if (!e || !e.main) throw new h("Unknown template object: " + typeof e);
                    t.VM.checkRevision(e.compiler);
                    var n = function(n, i, r, s, o, a, l, c, d) {
                            o && (s = u.extend({}, s, o));
                            var p = t.VM.invokePartial.call(this, n, r, s, a, l, c, d);
                            if (null == p && t.compile) {
                                var f = {
                                    helpers: a,
                                    partials: l,
                                    data: c,
                                    depths: d
                                };
                                l[r] = t.compile(n, {
                                    data: void 0 !== c,
                                    compat: e.compat
                                }, t), p = l[r](s, f)
                            }
                            if (null != p) {
                                if (i) {
                                    for (var m = p.split("\n"), g = 0, v = m.length; v > g && (m[g] || g + 1 !== v); g++) m[g] = i + m[g];
                                    p = m.join("\n")
                                }
                                return p
                            }
                            throw new h("The partial " + r + " could not be compiled when running in runtime-only mode")
                        },
                        i = {
                            lookup: function(e, t) {
                                for (var n = e.length, i = 0; n > i; i++)
                                    if (e[i] && null != e[i][t]) return e[i][t]
                            },
                            lambda: function(e, t) {
                                return "function" == typeof e ? e.call(t) : e
                            },
                            escapeExpression: u.escapeExpression,
                            invokePartial: n,
                            fn: function(t) {
                                return e[t]
                            },
                            programs: [],
                            program: function(e, t, n) {
                                var i = this.programs[e],
                                    r = this.fn(e);
                                return t || n ? i = s(this, e, r, t, n) : i || (i = this.programs[e] = s(this, e, r)), i
                            },
                            data: function(e, t) {
                                for (; e && t--;) e = e._parent;
                                return e
                            },
                            merge: function(e, t) {
                                var n = e || t;
                                return e && t && e !== t && (n = u.extend({}, t, e)), n
                            },
                            noop: t.VM.noop,
                            compilerInfo: e.compiler
                        },
                        r = function(t, n) {
                            n = n || {};
                            var s = n.data;
                            r._setup(n), !n.partial && e.useData && (s = l(t, s));
                            var o;
                            return e.useDepths && (o = n.depths ? [t].concat(n.depths) : [t]), e.main.call(i, t, i.helpers, i.partials, s, o)
                        };
                    return r.isTop = !0, r._setup = function(n) {
                        n.partial ? (i.helpers = n.helpers, i.partials = n.partials) : (i.helpers = i.merge(n.helpers, t.helpers), e.usePartial && (i.partials = i.merge(n.partials, t.partials)))
                    }, r._child = function(t, n, r) {
                        if (e.useDepths && !r) throw new h("must pass parent depths");
                        return s(i, t, e[t], n, r)
                    }, r
                }

                function s(e, t, n, i, r) {
                    var s = function(t, s) {
                        return s = s || {}, n.call(e, t, e.helpers, e.partials, s.data || i, r && [t].concat(r))
                    };
                    return s.program = t, s.depth = r ? r.length : 0, s
                }

                function o(e, t, n, i, r, s, o) {
                    var a = {
                        partial: !0,
                        helpers: i,
                        partials: r,
                        data: s,
                        depths: o
                    };
                    if (void 0 === e) throw new h("The partial " + t + " could not be found");
                    return e instanceof Function ? e(n, a) : void 0
                }

                function a() {
                    return ""
                }

                function l(e, t) {
                    return t && "root" in t || (t = t ? f(t) : {}, t.root = e), t
                }
                var c = {},
                    u = e,
                    h = t,
                    d = n.COMPILER_REVISION,
                    p = n.REVISION_CHANGES,
                    f = n.createFrame;
                return c.checkRevision = i, c.template = r, c.program = s, c.invokePartial = o, c.noop = a, c
            }(t, n, i),
            s = function(e, t, n, i, r) {
                var s, o = e,
                    a = t,
                    l = n,
                    c = i,
                    u = r,
                    h = function() {
                        var e = new o.HandlebarsEnvironment;
                        return c.extend(e, o), e.SafeString = a, e.Exception = l, e.Utils = c, e.escapeExpression = c.escapeExpression, e.VM = u, e.template = function(t) {
                            return u.template(t, e)
                        }, e
                    },
                    d = h();
                return d.create = h, d["default"] = d, s = d
            }(i, e, n, t, r),
            o = function(e) {
                function t(e) {
                    e = e || {}, this.firstLine = e.first_line, this.firstColumn = e.first_column, this.lastColumn = e.last_column, this.lastLine = e.last_line
                }
                var n, i = e,
                    r = {
                        ProgramNode: function(e, n, i) {
                            t.call(this, i), this.type = "program", this.statements = e, this.strip = n
                        },
                        MustacheNode: function(e, n, i, s, o) {
                            if (t.call(this, o), this.type = "mustache", this.strip = s, null != i && i.charAt) {
                                var a = i.charAt(3) || i.charAt(2);
                                this.escaped = "{" !== a && "&" !== a
                            } else this.escaped = !!i;
                            this.sexpr = e instanceof r.SexprNode ? e : new r.SexprNode(e, n), this.id = this.sexpr.id, this.params = this.sexpr.params, this.hash = this.sexpr.hash, this.eligibleHelper = this.sexpr.eligibleHelper, this.isHelper = this.sexpr.isHelper
                        },
                        SexprNode: function(e, n, i) {
                            t.call(this, i), this.type = "sexpr", this.hash = n;
                            var r = this.id = e[0],
                                s = this.params = e.slice(1);
                            this.isHelper = !(!s.length && !n), this.eligibleHelper = this.isHelper || r.isSimple
                        },
                        PartialNode: function(e, n, i, r, s) {
                            t.call(this, s), this.type = "partial", this.partialName = e, this.context = n, this.hash = i, this.strip = r, this.strip.inlineStandalone = !0
                        },
                        BlockNode: function(e, n, i, r, s) {
                            t.call(this, s), this.type = "block", this.mustache = e, this.program = n, this.inverse = i, this.strip = r, i && !n && (this.isInverse = !0)
                        },
                        RawBlockNode: function(e, n, s, o) {
                            if (t.call(this, o), e.sexpr.id.original !== s) throw new i(e.sexpr.id.original + " doesn't match " + s, this);
                            n = new r.ContentNode(n, o), this.type = "block", this.mustache = e, this.program = new r.ProgramNode([n], {}, o)
                        },
                        ContentNode: function(e, n) {
                            t.call(this, n), this.type = "content", this.original = this.string = e
                        },
                        HashNode: function(e, n) {
                            t.call(this, n), this.type = "hash", this.pairs = e
                        },
                        IdNode: function(e, n) {
                            t.call(this, n), this.type = "ID";
                            for (var r = "", s = [], o = 0, a = "", l = 0, c = e.length; c > l; l++) {
                                var u = e[l].part;
                                if (r += (e[l].separator || "") + u, ".." === u || "." === u || "this" === u) {
                                    if (s.length > 0) throw new i("Invalid path: " + r, this);
                                    ".." === u ? (o++, a += "../") : this.isScoped = !0
                                } else s.push(u)
                            }
                            this.original = r, this.parts = s, this.string = s.join("."), this.depth = o, this.idName = a + this.string, this.isSimple = 1 === e.length && !this.isScoped && 0 === o, this.stringModeValue = this.string
                        },
                        PartialNameNode: function(e, n) {
                            t.call(this, n), this.type = "PARTIAL_NAME", this.name = e.original
                        },
                        DataNode: function(e, n) {
                            t.call(this, n), this.type = "DATA", this.id = e, this.stringModeValue = e.stringModeValue, this.idName = "@" + e.stringModeValue
                        },
                        StringNode: function(e, n) {
                            t.call(this, n), this.type = "STRING", this.original = this.string = this.stringModeValue = e
                        },
                        NumberNode: function(e, n) {
                            t.call(this, n), this.type = "NUMBER", this.original = this.number = e, this.stringModeValue = Number(e)
                        },
                        BooleanNode: function(e, n) {
                            t.call(this, n), this.type = "BOOLEAN", this.bool = e, this.stringModeValue = "true" === e
                        },
                        CommentNode: function(e, n) {
                            t.call(this, n), this.type = "comment", this.comment = e, this.strip = {
                                inlineStandalone: !0
                            }
                        }
                    };
                return n = r
            }(n),
            a = function() {
                var e, t = function() {
                    function e() {
                        this.yy = {}
                    }
                    var t = {
                            trace: function() {},
                            yy: {},
                            symbols_: {
                                error: 2,
                                root: 3,
                                program: 4,
                                EOF: 5,
                                program_repetition0: 6,
                                statement: 7,
                                mustache: 8,
                                block: 9,
                                rawBlock: 10,
                                partial: 11,
                                CONTENT: 12,
                                COMMENT: 13,
                                openRawBlock: 14,
                                END_RAW_BLOCK: 15,
                                OPEN_RAW_BLOCK: 16,
                                sexpr: 17,
                                CLOSE_RAW_BLOCK: 18,
                                openBlock: 19,
                                block_option0: 20,
                                closeBlock: 21,
                                openInverse: 22,
                                block_option1: 23,
                                OPEN_BLOCK: 24,
                                CLOSE: 25,
                                OPEN_INVERSE: 26,
                                inverseAndProgram: 27,
                                INVERSE: 28,
                                OPEN_ENDBLOCK: 29,
                                path: 30,
                                OPEN: 31,
                                OPEN_UNESCAPED: 32,
                                CLOSE_UNESCAPED: 33,
                                OPEN_PARTIAL: 34,
                                partialName: 35,
                                param: 36,
                                partial_option0: 37,
                                partial_option1: 38,
                                sexpr_repetition0: 39,
                                sexpr_option0: 40,
                                dataName: 41,
                                STRING: 42,
                                NUMBER: 43,
                                BOOLEAN: 44,
                                OPEN_SEXPR: 45,
                                CLOSE_SEXPR: 46,
                                hash: 47,
                                hash_repetition_plus0: 48,
                                hashSegment: 49,
                                ID: 50,
                                EQUALS: 51,
                                DATA: 52,
                                pathSegments: 53,
                                SEP: 54,
                                $accept: 0,
                                $end: 1
                            },
                            terminals_: {
                                2: "error",
                                5: "EOF",
                                12: "CONTENT",
                                13: "COMMENT",
                                15: "END_RAW_BLOCK",
                                16: "OPEN_RAW_BLOCK",
                                18: "CLOSE_RAW_BLOCK",
                                24: "OPEN_BLOCK",
                                25: "CLOSE",
                                26: "OPEN_INVERSE",
                                28: "INVERSE",
                                29: "OPEN_ENDBLOCK",
                                31: "OPEN",
                                32: "OPEN_UNESCAPED",
                                33: "CLOSE_UNESCAPED",
                                34: "OPEN_PARTIAL",
                                42: "STRING",
                                43: "NUMBER",
                                44: "BOOLEAN",
                                45: "OPEN_SEXPR",
                                46: "CLOSE_SEXPR",
                                50: "ID",
                                51: "EQUALS",
                                52: "DATA",
                                54: "SEP"
                            },
                            productions_: [0, [3, 2],
                                [4, 1],
                                [7, 1],
                                [7, 1],
                                [7, 1],
                                [7, 1],
                                [7, 1],
                                [7, 1],
                                [10, 3],
                                [14, 3],
                                [9, 4],
                                [9, 4],
                                [19, 3],
                                [22, 3],
                                [27, 2],
                                [21, 3],
                                [8, 3],
                                [8, 3],
                                [11, 5],
                                [11, 4],
                                [17, 3],
                                [17, 1],
                                [36, 1],
                                [36, 1],
                                [36, 1],
                                [36, 1],
                                [36, 1],
                                [36, 3],
                                [47, 1],
                                [49, 3],
                                [35, 1],
                                [35, 1],
                                [35, 1],
                                [41, 2],
                                [30, 1],
                                [53, 3],
                                [53, 1],
                                [6, 0],
                                [6, 2],
                                [20, 0],
                                [20, 1],
                                [23, 0],
                                [23, 1],
                                [37, 0],
                                [37, 1],
                                [38, 0],
                                [38, 1],
                                [39, 0],
                                [39, 2],
                                [40, 0],
                                [40, 1],
                                [48, 1],
                                [48, 2]
                            ],
                            performAction: function(e, t, n, i, r, s) {
                                var o = s.length - 1;
                                switch (r) {
                                    case 1:
                                        return i.prepareProgram(s[o - 1].statements, !0), s[o - 1];
                                    case 2:
                                        this.$ = new i.ProgramNode(i.prepareProgram(s[o]), {}, this._$);
                                        break;
                                    case 3:
                                        this.$ = s[o];
                                        break;
                                    case 4:
                                        this.$ = s[o];
                                        break;
                                    case 5:
                                        this.$ = s[o];
                                        break;
                                    case 6:
                                        this.$ = s[o];
                                        break;
                                    case 7:
                                        this.$ = new i.ContentNode(s[o], this._$);
                                        break;
                                    case 8:
                                        this.$ = new i.CommentNode(s[o], this._$);
                                        break;
                                    case 9:
                                        this.$ = new i.RawBlockNode(s[o - 2], s[o - 1], s[o], this._$);
                                        break;
                                    case 10:
                                        this.$ = new i.MustacheNode(s[o - 1], null, "", "", this._$);
                                        break;
                                    case 11:
                                        this.$ = i.prepareBlock(s[o - 3], s[o - 2], s[o - 1], s[o], !1, this._$);
                                        break;
                                    case 12:
                                        this.$ = i.prepareBlock(s[o - 3], s[o - 2], s[o - 1], s[o], !0, this._$);
                                        break;
                                    case 13:
                                        this.$ = new i.MustacheNode(s[o - 1], null, s[o - 2], i.stripFlags(s[o - 2], s[o]), this._$);
                                        break;
                                    case 14:
                                        this.$ = new i.MustacheNode(s[o - 1], null, s[o - 2], i.stripFlags(s[o - 2], s[o]), this._$);
                                        break;
                                    case 15:
                                        this.$ = {
                                            strip: i.stripFlags(s[o - 1], s[o - 1]),
                                            program: s[o]
                                        };
                                        break;
                                    case 16:
                                        this.$ = {
                                            path: s[o - 1],
                                            strip: i.stripFlags(s[o - 2], s[o])
                                        };
                                        break;
                                    case 17:
                                        this.$ = new i.MustacheNode(s[o - 1], null, s[o - 2], i.stripFlags(s[o - 2], s[o]), this._$);
                                        break;
                                    case 18:
                                        this.$ = new i.MustacheNode(s[o - 1], null, s[o - 2], i.stripFlags(s[o - 2], s[o]), this._$);
                                        break;
                                    case 19:
                                        this.$ = new i.PartialNode(s[o - 3], s[o - 2], s[o - 1], i.stripFlags(s[o - 4], s[o]), this._$);
                                        break;
                                    case 20:
                                        this.$ = new i.PartialNode(s[o - 2], void 0, s[o - 1], i.stripFlags(s[o - 3], s[o]), this._$);
                                        break;
                                    case 21:
                                        this.$ = new i.SexprNode([s[o - 2]].concat(s[o - 1]), s[o], this._$);
                                        break;
                                    case 22:
                                        this.$ = new i.SexprNode([s[o]], null, this._$);
                                        break;
                                    case 23:
                                        this.$ = s[o];
                                        break;
                                    case 24:
                                        this.$ = new i.StringNode(s[o], this._$);
                                        break;
                                    case 25:
                                        this.$ = new i.NumberNode(s[o], this._$);
                                        break;
                                    case 26:
                                        this.$ = new i.BooleanNode(s[o], this._$);
                                        break;
                                    case 27:
                                        this.$ = s[o];
                                        break;
                                    case 28:
                                        s[o - 1].isHelper = !0, this.$ = s[o - 1];
                                        break;
                                    case 29:
                                        this.$ = new i.HashNode(s[o], this._$);
                                        break;
                                    case 30:
                                        this.$ = [s[o - 2], s[o]];
                                        break;
                                    case 31:
                                        this.$ = new i.PartialNameNode(s[o], this._$);
                                        break;
                                    case 32:
                                        this.$ = new i.PartialNameNode(new i.StringNode(s[o], this._$), this._$);
                                        break;
                                    case 33:
                                        this.$ = new i.PartialNameNode(new i.NumberNode(s[o], this._$));
                                        break;
                                    case 34:
                                        this.$ = new i.DataNode(s[o], this._$);
                                        break;
                                    case 35:
                                        this.$ = new i.IdNode(s[o], this._$);
                                        break;
                                    case 36:
                                        s[o - 2].push({
                                            part: s[o],
                                            separator: s[o - 1]
                                        }), this.$ = s[o - 2];
                                        break;
                                    case 37:

                                        this.$ = [{
                                            part: s[o]
                                        }];
                                        break;
                                    case 38:
                                        this.$ = [];
                                        break;
                                    case 39:
                                        s[o - 1].push(s[o]);
                                        break;
                                    case 48:
                                        this.$ = [];
                                        break;
                                    case 49:
                                        s[o - 1].push(s[o]);
                                        break;
                                    case 52:
                                        this.$ = [s[o]];
                                        break;
                                    case 53:
                                        s[o - 1].push(s[o])
                                }
                            },
                            table: [{
                                3: 1,
                                4: 2,
                                5: [2, 38],
                                6: 3,
                                12: [2, 38],
                                13: [2, 38],
                                16: [2, 38],
                                24: [2, 38],
                                26: [2, 38],
                                31: [2, 38],
                                32: [2, 38],
                                34: [2, 38]
                            }, {
                                1: [3]
                            }, {
                                5: [1, 4]
                            }, {
                                5: [2, 2],
                                7: 5,
                                8: 6,
                                9: 7,
                                10: 8,
                                11: 9,
                                12: [1, 10],
                                13: [1, 11],
                                14: 16,
                                16: [1, 20],
                                19: 14,
                                22: 15,
                                24: [1, 18],
                                26: [1, 19],
                                28: [2, 2],
                                29: [2, 2],
                                31: [1, 12],
                                32: [1, 13],
                                34: [1, 17]
                            }, {
                                1: [2, 1]
                            }, {
                                5: [2, 39],
                                12: [2, 39],
                                13: [2, 39],
                                16: [2, 39],
                                24: [2, 39],
                                26: [2, 39],
                                28: [2, 39],
                                29: [2, 39],
                                31: [2, 39],
                                32: [2, 39],
                                34: [2, 39]
                            }, {
                                5: [2, 3],
                                12: [2, 3],
                                13: [2, 3],
                                16: [2, 3],
                                24: [2, 3],
                                26: [2, 3],
                                28: [2, 3],
                                29: [2, 3],
                                31: [2, 3],
                                32: [2, 3],
                                34: [2, 3]
                            }, {
                                5: [2, 4],
                                12: [2, 4],
                                13: [2, 4],
                                16: [2, 4],
                                24: [2, 4],
                                26: [2, 4],
                                28: [2, 4],
                                29: [2, 4],
                                31: [2, 4],
                                32: [2, 4],
                                34: [2, 4]
                            }, {
                                5: [2, 5],
                                12: [2, 5],
                                13: [2, 5],
                                16: [2, 5],
                                24: [2, 5],
                                26: [2, 5],
                                28: [2, 5],
                                29: [2, 5],
                                31: [2, 5],
                                32: [2, 5],
                                34: [2, 5]
                            }, {
                                5: [2, 6],
                                12: [2, 6],
                                13: [2, 6],
                                16: [2, 6],
                                24: [2, 6],
                                26: [2, 6],
                                28: [2, 6],
                                29: [2, 6],
                                31: [2, 6],
                                32: [2, 6],
                                34: [2, 6]
                            }, {
                                5: [2, 7],
                                12: [2, 7],
                                13: [2, 7],
                                16: [2, 7],
                                24: [2, 7],
                                26: [2, 7],
                                28: [2, 7],
                                29: [2, 7],
                                31: [2, 7],
                                32: [2, 7],
                                34: [2, 7]
                            }, {
                                5: [2, 8],
                                12: [2, 8],
                                13: [2, 8],
                                16: [2, 8],
                                24: [2, 8],
                                26: [2, 8],
                                28: [2, 8],
                                29: [2, 8],
                                31: [2, 8],
                                32: [2, 8],
                                34: [2, 8]
                            }, {
                                17: 21,
                                30: 22,
                                41: 23,
                                50: [1, 26],
                                52: [1, 25],
                                53: 24
                            }, {
                                17: 27,
                                30: 22,
                                41: 23,
                                50: [1, 26],
                                52: [1, 25],
                                53: 24
                            }, {
                                4: 28,
                                6: 3,
                                12: [2, 38],
                                13: [2, 38],
                                16: [2, 38],
                                24: [2, 38],
                                26: [2, 38],
                                28: [2, 38],
                                29: [2, 38],
                                31: [2, 38],
                                32: [2, 38],
                                34: [2, 38]
                            }, {
                                4: 29,
                                6: 3,
                                12: [2, 38],
                                13: [2, 38],
                                16: [2, 38],
                                24: [2, 38],
                                26: [2, 38],
                                28: [2, 38],
                                29: [2, 38],
                                31: [2, 38],
                                32: [2, 38],
                                34: [2, 38]
                            }, {
                                12: [1, 30]
                            }, {
                                30: 32,
                                35: 31,
                                42: [1, 33],
                                43: [1, 34],
                                50: [1, 26],
                                53: 24
                            }, {
                                17: 35,
                                30: 22,
                                41: 23,
                                50: [1, 26],
                                52: [1, 25],
                                53: 24
                            }, {
                                17: 36,
                                30: 22,
                                41: 23,
                                50: [1, 26],
                                52: [1, 25],
                                53: 24
                            }, {
                                17: 37,
                                30: 22,
                                41: 23,
                                50: [1, 26],
                                52: [1, 25],
                                53: 24
                            }, {
                                25: [1, 38]
                            }, {
                                18: [2, 48],
                                25: [2, 48],
                                33: [2, 48],
                                39: 39,
                                42: [2, 48],
                                43: [2, 48],
                                44: [2, 48],
                                45: [2, 48],
                                46: [2, 48],
                                50: [2, 48],
                                52: [2, 48]
                            }, {
                                18: [2, 22],
                                25: [2, 22],
                                33: [2, 22],
                                46: [2, 22]
                            }, {
                                18: [2, 35],
                                25: [2, 35],
                                33: [2, 35],
                                42: [2, 35],
                                43: [2, 35],
                                44: [2, 35],
                                45: [2, 35],
                                46: [2, 35],
                                50: [2, 35],
                                52: [2, 35],
                                54: [1, 40]
                            }, {
                                30: 41,
                                50: [1, 26],
                                53: 24
                            }, {
                                18: [2, 37],
                                25: [2, 37],
                                33: [2, 37],
                                42: [2, 37],
                                43: [2, 37],
                                44: [2, 37],
                                45: [2, 37],
                                46: [2, 37],
                                50: [2, 37],
                                52: [2, 37],
                                54: [2, 37]
                            }, {
                                33: [1, 42]
                            }, {
                                20: 43,
                                27: 44,
                                28: [1, 45],
                                29: [2, 40]
                            }, {
                                23: 46,
                                27: 47,
                                28: [1, 45],
                                29: [2, 42]
                            }, {
                                15: [1, 48]
                            }, {
                                25: [2, 46],
                                30: 51,
                                36: 49,
                                38: 50,
                                41: 55,
                                42: [1, 52],
                                43: [1, 53],
                                44: [1, 54],
                                45: [1, 56],
                                47: 57,
                                48: 58,
                                49: 60,
                                50: [1, 59],
                                52: [1, 25],
                                53: 24
                            }, {
                                25: [2, 31],
                                42: [2, 31],
                                43: [2, 31],
                                44: [2, 31],
                                45: [2, 31],
                                50: [2, 31],
                                52: [2, 31]
                            }, {
                                25: [2, 32],
                                42: [2, 32],
                                43: [2, 32],
                                44: [2, 32],
                                45: [2, 32],
                                50: [2, 32],
                                52: [2, 32]
                            }, {
                                25: [2, 33],
                                42: [2, 33],
                                43: [2, 33],
                                44: [2, 33],
                                45: [2, 33],
                                50: [2, 33],
                                52: [2, 33]
                            }, {
                                25: [1, 61]
                            }, {
                                25: [1, 62]
                            }, {
                                18: [1, 63]
                            }, {
                                5: [2, 17],
                                12: [2, 17],
                                13: [2, 17],
                                16: [2, 17],
                                24: [2, 17],
                                26: [2, 17],
                                28: [2, 17],
                                29: [2, 17],
                                31: [2, 17],
                                32: [2, 17],
                                34: [2, 17]
                            }, {
                                18: [2, 50],
                                25: [2, 50],
                                30: 51,
                                33: [2, 50],
                                36: 65,
                                40: 64,
                                41: 55,
                                42: [1, 52],
                                43: [1, 53],
                                44: [1, 54],
                                45: [1, 56],
                                46: [2, 50],
                                47: 66,
                                48: 58,
                                49: 60,
                                50: [1, 59],
                                52: [1, 25],
                                53: 24
                            }, {
                                50: [1, 67]
                            }, {
                                18: [2, 34],
                                25: [2, 34],
                                33: [2, 34],
                                42: [2, 34],
                                43: [2, 34],
                                44: [2, 34],
                                45: [2, 34],
                                46: [2, 34],
                                50: [2, 34],
                                52: [2, 34]
                            }, {
                                5: [2, 18],
                                12: [2, 18],
                                13: [2, 18],
                                16: [2, 18],
                                24: [2, 18],
                                26: [2, 18],
                                28: [2, 18],
                                29: [2, 18],
                                31: [2, 18],
                                32: [2, 18],
                                34: [2, 18]
                            }, {
                                21: 68,
                                29: [1, 69]
                            }, {
                                29: [2, 41]
                            }, {
                                4: 70,
                                6: 3,
                                12: [2, 38],
                                13: [2, 38],
                                16: [2, 38],
                                24: [2, 38],
                                26: [2, 38],
                                29: [2, 38],
                                31: [2, 38],
                                32: [2, 38],
                                34: [2, 38]
                            }, {
                                21: 71,
                                29: [1, 69]
                            }, {
                                29: [2, 43]
                            }, {
                                5: [2, 9],
                                12: [2, 9],
                                13: [2, 9],
                                16: [2, 9],
                                24: [2, 9],
                                26: [2, 9],
                                28: [2, 9],
                                29: [2, 9],
                                31: [2, 9],
                                32: [2, 9],
                                34: [2, 9]
                            }, {
                                25: [2, 44],
                                37: 72,
                                47: 73,
                                48: 58,
                                49: 60,
                                50: [1, 74]
                            }, {
                                25: [1, 75]
                            }, {
                                18: [2, 23],
                                25: [2, 23],
                                33: [2, 23],
                                42: [2, 23],
                                43: [2, 23],
                                44: [2, 23],
                                45: [2, 23],
                                46: [2, 23],
                                50: [2, 23],
                                52: [2, 23]
                            }, {
                                18: [2, 24],
                                25: [2, 24],
                                33: [2, 24],
                                42: [2, 24],
                                43: [2, 24],
                                44: [2, 24],
                                45: [2, 24],
                                46: [2, 24],
                                50: [2, 24],
                                52: [2, 24]
                            }, {
                                18: [2, 25],
                                25: [2, 25],
                                33: [2, 25],
                                42: [2, 25],
                                43: [2, 25],
                                44: [2, 25],
                                45: [2, 25],
                                46: [2, 25],
                                50: [2, 25],
                                52: [2, 25]
                            }, {
                                18: [2, 26],
                                25: [2, 26],
                                33: [2, 26],
                                42: [2, 26],
                                43: [2, 26],
                                44: [2, 26],
                                45: [2, 26],
                                46: [2, 26],
                                50: [2, 26],
                                52: [2, 26]
                            }, {
                                18: [2, 27],
                                25: [2, 27],
                                33: [2, 27],
                                42: [2, 27],
                                43: [2, 27],
                                44: [2, 27],
                                45: [2, 27],
                                46: [2, 27],
                                50: [2, 27],
                                52: [2, 27]
                            }, {
                                17: 76,
                                30: 22,
                                41: 23,
                                50: [1, 26],
                                52: [1, 25],
                                53: 24
                            }, {
                                25: [2, 47]
                            }, {
                                18: [2, 29],
                                25: [2, 29],
                                33: [2, 29],
                                46: [2, 29],
                                49: 77,
                                50: [1, 74]
                            }, {
                                18: [2, 37],
                                25: [2, 37],
                                33: [2, 37],
                                42: [2, 37],
                                43: [2, 37],
                                44: [2, 37],
                                45: [2, 37],
                                46: [2, 37],
                                50: [2, 37],
                                51: [1, 78],
                                52: [2, 37],
                                54: [2, 37]
                            }, {
                                18: [2, 52],
                                25: [2, 52],
                                33: [2, 52],
                                46: [2, 52],
                                50: [2, 52]
                            }, {
                                12: [2, 13],
                                13: [2, 13],
                                16: [2, 13],
                                24: [2, 13],
                                26: [2, 13],
                                28: [2, 13],
                                29: [2, 13],
                                31: [2, 13],
                                32: [2, 13],
                                34: [2, 13]
                            }, {
                                12: [2, 14],
                                13: [2, 14],
                                16: [2, 14],
                                24: [2, 14],
                                26: [2, 14],
                                28: [2, 14],
                                29: [2, 14],
                                31: [2, 14],
                                32: [2, 14],
                                34: [2, 14]
                            }, {
                                12: [2, 10]
                            }, {
                                18: [2, 21],
                                25: [2, 21],
                                33: [2, 21],
                                46: [2, 21]
                            }, {
                                18: [2, 49],
                                25: [2, 49],
                                33: [2, 49],
                                42: [2, 49],
                                43: [2, 49],
                                44: [2, 49],
                                45: [2, 49],
                                46: [2, 49],
                                50: [2, 49],
                                52: [2, 49]
                            }, {
                                18: [2, 51],
                                25: [2, 51],
                                33: [2, 51],
                                46: [2, 51]
                            }, {
                                18: [2, 36],
                                25: [2, 36],
                                33: [2, 36],
                                42: [2, 36],
                                43: [2, 36],
                                44: [2, 36],
                                45: [2, 36],
                                46: [2, 36],
                                50: [2, 36],
                                52: [2, 36],
                                54: [2, 36]
                            }, {
                                5: [2, 11],
                                12: [2, 11],
                                13: [2, 11],
                                16: [2, 11],
                                24: [2, 11],
                                26: [2, 11],
                                28: [2, 11],
                                29: [2, 11],
                                31: [2, 11],
                                32: [2, 11],
                                34: [2, 11]
                            }, {
                                30: 79,
                                50: [1, 26],
                                53: 24
                            }, {
                                29: [2, 15]
                            }, {
                                5: [2, 12],
                                12: [2, 12],
                                13: [2, 12],
                                16: [2, 12],
                                24: [2, 12],
                                26: [2, 12],
                                28: [2, 12],
                                29: [2, 12],
                                31: [2, 12],
                                32: [2, 12],
                                34: [2, 12]
                            }, {
                                25: [1, 80]
                            }, {
                                25: [2, 45]
                            }, {
                                51: [1, 78]
                            }, {
                                5: [2, 20],
                                12: [2, 20],
                                13: [2, 20],
                                16: [2, 20],
                                24: [2, 20],
                                26: [2, 20],
                                28: [2, 20],
                                29: [2, 20],
                                31: [2, 20],
                                32: [2, 20],
                                34: [2, 20]
                            }, {
                                46: [1, 81]
                            }, {
                                18: [2, 53],
                                25: [2, 53],
                                33: [2, 53],
                                46: [2, 53],
                                50: [2, 53]
                            }, {
                                30: 51,
                                36: 82,
                                41: 55,
                                42: [1, 52],
                                43: [1, 53],
                                44: [1, 54],
                                45: [1, 56],
                                50: [1, 26],
                                52: [1, 25],
                                53: 24
                            }, {
                                25: [1, 83]
                            }, {
                                5: [2, 19],
                                12: [2, 19],
                                13: [2, 19],
                                16: [2, 19],
                                24: [2, 19],
                                26: [2, 19],
                                28: [2, 19],
                                29: [2, 19],
                                31: [2, 19],
                                32: [2, 19],
                                34: [2, 19]
                            }, {
                                18: [2, 28],
                                25: [2, 28],
                                33: [2, 28],
                                42: [2, 28],
                                43: [2, 28],
                                44: [2, 28],
                                45: [2, 28],
                                46: [2, 28],
                                50: [2, 28],
                                52: [2, 28]
                            }, {
                                18: [2, 30],
                                25: [2, 30],
                                33: [2, 30],
                                46: [2, 30],
                                50: [2, 30]
                            }, {
                                5: [2, 16],
                                12: [2, 16],
                                13: [2, 16],
                                16: [2, 16],
                                24: [2, 16],
                                26: [2, 16],
                                28: [2, 16],
                                29: [2, 16],
                                31: [2, 16],
                                32: [2, 16],
                                34: [2, 16]
                            }],
                            defaultActions: {
                                4: [2, 1],
                                44: [2, 41],
                                47: [2, 43],
                                57: [2, 47],
                                63: [2, 10],
                                70: [2, 15],
                                73: [2, 45]
                            },
                            parseError: function(e) {
                                throw new Error(e)
                            },
                            parse: function(e) {
                                function t() {
                                    var e;
                                    return e = n.lexer.lex() || 1, "number" != typeof e && (e = n.symbols_[e] || e), e
                                }
                                var n = this,
                                    i = [0],
                                    r = [null],
                                    s = [],
                                    o = this.table,
                                    a = "",
                                    l = 0,
                                    c = 0,
                                    u = 0;
                                this.lexer.setInput(e), this.lexer.yy = this.yy, this.yy.lexer = this.lexer, this.yy.parser = this, "undefined" == typeof this.lexer.yylloc && (this.lexer.yylloc = {});
                                var h = this.lexer.yylloc;
                                s.push(h);
                                var d = this.lexer.options && this.lexer.options.ranges;
                                "function" == typeof this.yy.parseError && (this.parseError = this.yy.parseError);
                                for (var p, f, m, g, v, y, w, b, _, x = {};;) {
                                    if (m = i[i.length - 1], this.defaultActions[m] ? g = this.defaultActions[m] : ((null === p || "undefined" == typeof p) && (p = t()), g = o[m] && o[m][p]), "undefined" == typeof g || !g.length || !g[0]) {
                                        var S = "";
                                        if (!u) {
                                            _ = [];
                                            for (y in o[m]) this.terminals_[y] && y > 2 && _.push("'" + this.terminals_[y] + "'");
                                            S = this.lexer.showPosition ? "Parse error on line " + (l + 1) + ":\n" + this.lexer.showPosition() + "\nExpecting " + _.join(", ") + ", got '" + (this.terminals_[p] || p) + "'" : "Parse error on line " + (l + 1) + ": Unexpected " + (1 == p ? "end of input" : "'" + (this.terminals_[p] || p) + "'"), this.parseError(S, {
                                                text: this.lexer.match,
                                                token: this.terminals_[p] || p,
                                                line: this.lexer.yylineno,
                                                loc: h,
                                                expected: _
                                            })
                                        }
                                    }
                                    if (g[0] instanceof Array && g.length > 1) throw new Error("Parse Error: multiple actions possible at state: " + m + ", token: " + p);
                                    switch (g[0]) {
                                        case 1:
                                            i.push(p), r.push(this.lexer.yytext), s.push(this.lexer.yylloc), i.push(g[1]), p = null, f ? (p = f, f = null) : (c = this.lexer.yyleng, a = this.lexer.yytext, l = this.lexer.yylineno, h = this.lexer.yylloc, u > 0 && u--);
                                            break;
                                        case 2:
                                            if (w = this.productions_[g[1]][1], x.$ = r[r.length - w], x._$ = {
                                                    first_line: s[s.length - (w || 1)].first_line,
                                                    last_line: s[s.length - 1].last_line,
                                                    first_column: s[s.length - (w || 1)].first_column,
                                                    last_column: s[s.length - 1].last_column
                                                }, d && (x._$.range = [s[s.length - (w || 1)].range[0], s[s.length - 1].range[1]]), v = this.performAction.call(x, a, c, l, this.yy, g[1], r, s), "undefined" != typeof v) return v;
                                            w && (i = i.slice(0, -1 * w * 2), r = r.slice(0, -1 * w), s = s.slice(0, -1 * w)), i.push(this.productions_[g[1]][0]), r.push(x.$), s.push(x._$), b = o[i[i.length - 2]][i[i.length - 1]], i.push(b);
                                            break;
                                        case 3:
                                            return !0
                                    }
                                }
                                return !0
                            }
                        },
                        n = function() {
                            var e = {
                                EOF: 1,
                                parseError: function(e, t) {
                                    if (!this.yy.parser) throw new Error(e);
                                    this.yy.parser.parseError(e, t)
                                },
                                setInput: function(e) {
                                    return this._input = e, this._more = this._less = this.done = !1, this.yylineno = this.yyleng = 0, this.yytext = this.matched = this.match = "", this.conditionStack = ["INITIAL"], this.yylloc = {
                                        first_line: 1,
                                        first_column: 0,
                                        last_line: 1,
                                        last_column: 0
                                    }, this.options.ranges && (this.yylloc.range = [0, 0]), this.offset = 0, this
                                },
                                input: function() {
                                    var e = this._input[0];
                                    this.yytext += e, this.yyleng++, this.offset++, this.match += e, this.matched += e;
                                    var t = e.match(/(?:\r\n?|\n).*/g);
                                    return t ? (this.yylineno++, this.yylloc.last_line++) : this.yylloc.last_column++, this.options.ranges && this.yylloc.range[1] ++, this._input = this._input.slice(1), e
                                },
                                unput: function(e) {
                                    var t = e.length,
                                        n = e.split(/(?:\r\n?|\n)/g);
                                    this._input = e + this._input, this.yytext = this.yytext.substr(0, this.yytext.length - t - 1), this.offset -= t;
                                    var i = this.match.split(/(?:\r\n?|\n)/g);
                                    this.match = this.match.substr(0, this.match.length - 1), this.matched = this.matched.substr(0, this.matched.length - 1), n.length - 1 && (this.yylineno -= n.length - 1);
                                    var r = this.yylloc.range;
                                    return this.yylloc = {
                                        first_line: this.yylloc.first_line,
                                        last_line: this.yylineno + 1,
                                        first_column: this.yylloc.first_column,
                                        last_column: n ? (n.length === i.length ? this.yylloc.first_column : 0) + i[i.length - n.length].length - n[0].length : this.yylloc.first_column - t
                                    }, this.options.ranges && (this.yylloc.range = [r[0], r[0] + this.yyleng - t]), this
                                },
                                more: function() {
                                    return this._more = !0, this
                                },
                                less: function(e) {
                                    this.unput(this.match.slice(e))
                                },
                                pastInput: function() {
                                    var e = this.matched.substr(0, this.matched.length - this.match.length);
                                    return (e.length > 20 ? "..." : "") + e.substr(-20).replace(/\n/g, "")
                                },
                                upcomingInput: function() {
                                    var e = this.match;
                                    return e.length < 20 && (e += this._input.substr(0, 20 - e.length)), (e.substr(0, 20) + (e.length > 20 ? "..." : "")).replace(/\n/g, "")
                                },
                                showPosition: function() {
                                    var e = this.pastInput(),
                                        t = new Array(e.length + 1).join("-");
                                    return e + this.upcomingInput() + "\n" + t + "^"
                                },
                                next: function() {
                                    if (this.done) return this.EOF;
                                    this._input || (this.done = !0);
                                    var e, t, n, i, r;
                                    this._more || (this.yytext = "", this.match = "");
                                    for (var s = this._currentRules(), o = 0; o < s.length && (n = this._input.match(this.rules[s[o]]), !n || t && !(n[0].length > t[0].length) || (t = n, i = o, this.options.flex)); o++);
                                    return t ? (r = t[0].match(/(?:\r\n?|\n).*/g), r && (this.yylineno += r.length), this.yylloc = {
                                        first_line: this.yylloc.last_line,
                                        last_line: this.yylineno + 1,
                                        first_column: this.yylloc.last_column,
                                        last_column: r ? r[r.length - 1].length - r[r.length - 1].match(/\r?\n?/)[0].length : this.yylloc.last_column + t[0].length
                                    }, this.yytext += t[0], this.match += t[0], this.matches = t, this.yyleng = this.yytext.length, this.options.ranges && (this.yylloc.range = [this.offset, this.offset += this.yyleng]), this._more = !1, this._input = this._input.slice(t[0].length), this.matched += t[0], e = this.performAction.call(this, this.yy, this, s[i], this.conditionStack[this.conditionStack.length - 1]), this.done && this._input && (this.done = !1), e ? e : void 0) : "" === this._input ? this.EOF : this.parseError("Lexical error on line " + (this.yylineno + 1) + ". Unrecognized text.\n" + this.showPosition(), {
                                        text: "",
                                        token: null,
                                        line: this.yylineno
                                    })
                                },
                                lex: function() {
                                    var e = this.next();
                                    return "undefined" != typeof e ? e : this.lex()
                                },
                                begin: function(e) {
                                    this.conditionStack.push(e)
                                },
                                popState: function() {
                                    return this.conditionStack.pop()
                                },
                                _currentRules: function() {
                                    return this.conditions[this.conditionStack[this.conditionStack.length - 1]].rules
                                },
                                topState: function() {
                                    return this.conditionStack[this.conditionStack.length - 2]
                                },
                                pushState: function(e) {
                                    this.begin(e)
                                }
                            };
                            return e.options = {}, e.performAction = function(e, t, n, i) {
                                function r(e, n) {
                                    return t.yytext = t.yytext.substr(e, t.yyleng - n)
                                }
                                switch (n) {
                                    case 0:
                                        if ("\\\\" === t.yytext.slice(-2) ? (r(0, 1), this.begin("mu")) : "\\" === t.yytext.slice(-1) ? (r(0, 1), this.begin("emu")) : this.begin("mu"), t.yytext) return 12;
                                        break;
                                    case 1:
                                        return 12;
                                    case 2:
                                        return this.popState(), 12;
                                    case 3:
                                        return t.yytext = t.yytext.substr(5, t.yyleng - 9), this.popState(), 15;
                                    case 4:
                                        return 12;
                                    case 5:
                                        return r(0, 4), this.popState(), 13;
                                    case 6:
                                        return 45;
                                    case 7:
                                        return 46;
                                    case 8:
                                        return 16;
                                    case 9:
                                        return this.popState(), this.begin("raw"), 18;
                                    case 10:
                                        return 34;
                                    case 11:
                                        return 24;
                                    case 12:
                                        return 29;
                                    case 13:
                                        return this.popState(), 28;
                                    case 14:
                                        return this.popState(), 28;
                                    case 15:
                                        return 26;
                                    case 16:
                                        return 26;
                                    case 17:
                                        return 32;
                                    case 18:
                                        return 31;
                                    case 19:
                                        this.popState(), this.begin("com");
                                        break;
                                    case 20:
                                        return r(3, 5), this.popState(), 13;
                                    case 21:
                                        return 31;
                                    case 22:
                                        return 51;
                                    case 23:
                                        return 50;
                                    case 24:
                                        return 50;
                                    case 25:
                                        return 54;
                                    case 26:
                                        break;
                                    case 27:
                                        return this.popState(), 33;
                                    case 28:
                                        return this.popState(), 25;
                                    case 29:
                                        return t.yytext = r(1, 2).replace(/\\"/g, '"'), 42;
                                    case 30:
                                        return t.yytext = r(1, 2).replace(/\\'/g, "'"), 42;
                                    case 31:
                                        return 52;
                                    case 32:
                                        return 44;
                                    case 33:
                                        return 44;
                                    case 34:
                                        return 43;
                                    case 35:
                                        return 50;
                                    case 36:
                                        return t.yytext = r(1, 2), 50;
                                    case 37:
                                        return "INVALID";
                                    case 38:
                                        return 5
                                }
                            }, e.rules = [/^(?:[^\x00]*?(?=(\{\{)))/, /^(?:[^\x00]+)/, /^(?:[^\x00]{2,}?(?=(\{\{|\\\{\{|\\\\\{\{|$)))/, /^(?:\{\{\{\{\/[^\s!"#%-,\.\/;->@\[-\^`\{-~]+(?=[=}\s\/.])\}\}\}\})/, /^(?:[^\x00]*?(?=(\{\{\{\{\/)))/, /^(?:[\s\S]*?--\}\})/, /^(?:\()/, /^(?:\))/, /^(?:\{\{\{\{)/, /^(?:\}\}\}\})/, /^(?:\{\{(~)?>)/, /^(?:\{\{(~)?#)/, /^(?:\{\{(~)?\/)/, /^(?:\{\{(~)?\^\s*(~)?\}\})/, /^(?:\{\{(~)?\s*else\s*(~)?\}\})/, /^(?:\{\{(~)?\^)/, /^(?:\{\{(~)?\s*else\b)/, /^(?:\{\{(~)?\{)/, /^(?:\{\{(~)?&)/, /^(?:\{\{!--)/, /^(?:\{\{![\s\S]*?\}\})/, /^(?:\{\{(~)?)/, /^(?:=)/, /^(?:\.\.)/, /^(?:\.(?=([=~}\s\/.)])))/, /^(?:[\/.])/, /^(?:\s+)/, /^(?:\}(~)?\}\})/, /^(?:(~)?\}\})/, /^(?:"(\\["]|[^"])*")/, /^(?:'(\\[']|[^'])*')/, /^(?:@)/, /^(?:true(?=([~}\s)])))/, /^(?:false(?=([~}\s)])))/, /^(?:-?[0-9]+(?:\.[0-9]+)?(?=([~}\s)])))/, /^(?:([^\s!"#%-,\.\/;->@\[-\^`\{-~]+(?=([=~}\s\/.)]))))/, /^(?:\[[^\]]*\])/, /^(?:.)/, /^(?:$)/], e.conditions = {
                                mu: {
                                    rules: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38],
                                    inclusive: !1
                                },
                                emu: {
                                    rules: [2],
                                    inclusive: !1
                                },
                                com: {
                                    rules: [5],
                                    inclusive: !1
                                },
                                raw: {
                                    rules: [3, 4],
                                    inclusive: !1
                                },
                                INITIAL: {
                                    rules: [0, 1, 38],
                                    inclusive: !0
                                }
                            }, e
                        }();
                    return t.lexer = n, e.prototype = t, t.Parser = e, new e
                }();
                return e = t
            }(),
            l = function(e) {

                function t(e, t) {
                    return {
                        left: "~" === e.charAt(2),
                        right: "~" === t.charAt(t.length - 3)
                    }
                }

                function n(e, t, n, i, l, u) {
                    if (e.sexpr.id.original !== i.path.original) throw new c(e.sexpr.id.original + " doesn't match " + i.path.original, e);
                    var h = n && n.program,
                        d = {
                            left: e.strip.left,
                            right: i.strip.right,
                            openStandalone: s(t.statements),
                            closeStandalone: r((h || t).statements)
                        };
                    if (e.strip.right && o(t.statements, null, !0), h) {
                        var p = n.strip;
                        p.left && a(t.statements, null, !0), p.right && o(h.statements, null, !0), i.strip.left && a(h.statements, null, !0), r(t.statements) && s(h.statements) && (a(t.statements), o(h.statements))
                    } else i.strip.left && a(t.statements, null, !0);
                    return l ? new this.BlockNode(e, h, t, d, u) : new this.BlockNode(e, t, h, d, u)
                }

                function i(e, t) {
                    for (var n = 0, i = e.length; i > n; n++) {
                        var l = e[n],
                            c = l.strip;
                        if (c) {
                            var u = r(e, n, t, "partial" === l.type),
                                h = s(e, n, t),
                                d = c.openStandalone && u,
                                p = c.closeStandalone && h,
                                f = c.inlineStandalone && u && h;
                            c.right && o(e, n, !0), c.left && a(e, n, !0), f && (o(e, n), a(e, n) && "partial" === l.type && (l.indent = /([ \t]+$)/.exec(e[n - 1].original) ? RegExp.$1 : "")), d && (o((l.program || l.inverse).statements), a(e, n)), p && (o(e, n), a((l.inverse || l.program).statements))
                        }
                    }
                    return e
                }

                function r(e, t, n) {
                    void 0 === t && (t = e.length);
                    var i = e[t - 1],
                        r = e[t - 2];
                    return i ? "content" === i.type ? (r || !n ? /\r?\n\s*?$/ : /(^|\r?\n)\s*?$/).test(i.original) : void 0 : n
                }

                function s(e, t, n) {
                    void 0 === t && (t = -1);
                    var i = e[t + 1],
                        r = e[t + 2];
                    return i ? "content" === i.type ? (r || !n ? /^\s*?\r?\n/ : /^\s*?(\r?\n|$)/).test(i.original) : void 0 : n
                }

                function o(e, t, n) {
                    var i = e[null == t ? 0 : t + 1];
                    if (i && "content" === i.type && (n || !i.rightStripped)) {
                        var r = i.string;
                        i.string = i.string.replace(n ? /^\s+/ : /^[ \t]*\r?\n?/, ""), i.rightStripped = i.string !== r
                    }
                }

                function a(e, t, n) {
                    var i = e[null == t ? e.length - 1 : t - 1];
                    if (i && "content" === i.type && (n || !i.leftStripped)) {
                        var r = i.string;
                        return i.string = i.string.replace(n ? /\s+$/ : /[ \t]+$/, ""), i.leftStripped = i.string !== r, i.leftStripped
                    }
                }
                var l = {},
                    c = e;
                return l.stripFlags = t, l.prepareBlock = n, l.prepareProgram = i, l
            }(n),
            c = function(e, t, n, i) {
                function r(e) {
                    return e.constructor === a.ProgramNode ? e : (o.yy = u, o.parse(e))
                }
                var s = {},
                    o = e,
                    a = t,
                    l = n,
                    c = i.extend;
                s.parser = o;
                var u = {};
                return c(u, l, a), s.parse = r, s
            }(a, o, l, t),
            u = function(e, t) {
                function n() {}

                function i(e, t, n) {
                    if (null == e || "string" != typeof e && e.constructor !== n.AST.ProgramNode) throw new a("You must pass a string or Handlebars AST to Handlebars.precompile. You passed " + e);
                    t = t || {}, "data" in t || (t.data = !0), t.compat && (t.useDepths = !0);
                    var i = n.parse(e),
                        r = (new n.Compiler).compile(i, t);
                    return (new n.JavaScriptCompiler).compile(r, t)
                }

                function r(e, t, n) {
                    function i() {
                        var i = n.parse(e),
                            r = (new n.Compiler).compile(i, t),
                            s = (new n.JavaScriptCompiler).compile(r, t, void 0, !0);
                        return n.template(s)
                    }
                    if (null == e || "string" != typeof e && e.constructor !== n.AST.ProgramNode) throw new a("You must pass a string or Handlebars AST to Handlebars.compile. You passed " + e);
                    t = t || {}, "data" in t || (t.data = !0), t.compat && (t.useDepths = !0);
                    var r, s = function(e, t) {
                        return r || (r = i()), r.call(this, e, t)
                    };
                    return s._setup = function(e) {
                        return r || (r = i()), r._setup(e)
                    }, s._child = function(e, t, n) {
                        return r || (r = i()), r._child(e, t, n)
                    }, s
                }

                function s(e, t) {
                    if (e === t) return !0;
                    if (l(e) && l(t) && e.length === t.length) {
                        for (var n = 0; n < e.length; n++)
                            if (!s(e[n], t[n])) return !1;
                        return !0
                    }
                }
                var o = {},
                    a = e,
                    l = t.isArray,
                    c = [].slice;
                return o.Compiler = n, n.prototype = {
                    compiler: n,
                    equals: function(e) {
                        var t = this.opcodes.length;
                        if (e.opcodes.length !== t) return !1;
                        for (var n = 0; t > n; n++) {
                            var i = this.opcodes[n],
                                r = e.opcodes[n];
                            if (i.opcode !== r.opcode || !s(i.args, r.args)) return !1
                        }
                        for (t = this.children.length, n = 0; t > n; n++)
                            if (!this.children[n].equals(e.children[n])) return !1;
                        return !0
                    },
                    guid: 0,
                    compile: function(e, t) {
                        this.opcodes = [], this.children = [], this.depths = {
                            list: []
                        }, this.options = t, this.stringParams = t.stringParams, this.trackIds = t.trackIds;
                        var n = this.options.knownHelpers;
                        if (this.options.knownHelpers = {
                                helperMissing: !0,
                                blockHelperMissing: !0,
                                each: !0,
                                "if": !0,
                                unless: !0,
                                "with": !0,
                                log: !0,
                                lookup: !0
                            }, n)
                            for (var i in n) this.options.knownHelpers[i] = n[i];
                        return this.accept(e)
                    },
                    accept: function(e) {
                        return this[e.type](e)
                    },
                    program: function(e) {
                        for (var t = e.statements, n = 0, i = t.length; i > n; n++) this.accept(t[n]);
                        return this.isSimple = 1 === i, this.depths.list = this.depths.list.sort(function(e, t) {
                            return e - t
                        }), this
                    },
                    compileProgram: function(e) {
                        var t, n = (new this.compiler).compile(e, this.options),
                            i = this.guid++;
                        this.usePartial = this.usePartial || n.usePartial, this.children[i] = n;
                        for (var r = 0, s = n.depths.list.length; s > r; r++) t = n.depths.list[r], 2 > t || this.addDepth(t - 1);
                        return i
                    },
                    block: function(e) {
                        var t = e.mustache,
                            n = e.program,
                            i = e.inverse;
                        n && (n = this.compileProgram(n)), i && (i = this.compileProgram(i));
                        var r = t.sexpr,
                            s = this.classifySexpr(r);
                        "helper" === s ? this.helperSexpr(r, n, i) : "simple" === s ? (this.simpleSexpr(r), this.opcode("pushProgram", n), this.opcode("pushProgram", i), this.opcode("emptyHash"), this.opcode("blockValue", r.id.original)) : (this.ambiguousSexpr(r, n, i), this.opcode("pushProgram", n), this.opcode("pushProgram", i), this.opcode("emptyHash"), this.opcode("ambiguousBlockValue")), this.opcode("append")
                    },
                    hash: function(e) {
                        var t, n, i = e.pairs;
                        for (this.opcode("pushHash"), t = 0, n = i.length; n > t; t++) this.pushParam(i[t][1]);
                        for (; t--;) this.opcode("assignToHash", i[t][0]);
                        this.opcode("popHash")
                    },
                    partial: function(e) {
                        var t = e.partialName;
                        this.usePartial = !0, e.hash ? this.accept(e.hash) : this.opcode("push", "undefined"), e.context ? this.accept(e.context) : (this.opcode("getContext", 0), this.opcode("pushContext")), this.opcode("invokePartial", t.name, e.indent || ""), this.opcode("append")
                    },
                    content: function(e) {
                        e.string && this.opcode("appendContent", e.string)
                    },
                    mustache: function(e) {
                        this.sexpr(e.sexpr), this.opcode(e.escaped && !this.options.noEscape ? "appendEscaped" : "append")
                    },
                    ambiguousSexpr: function(e, t, n) {
                        var i = e.id,
                            r = i.parts[0],
                            s = null != t || null != n;
                        this.opcode("getContext", i.depth), this.opcode("pushProgram", t), this.opcode("pushProgram", n), this.ID(i), this.opcode("invokeAmbiguous", r, s)
                    },
                    simpleSexpr: function(e) {
                        var t = e.id;
                        "DATA" === t.type ? this.DATA(t) : t.parts.length ? this.ID(t) : (this.addDepth(t.depth), this.opcode("getContext", t.depth), this.opcode("pushContext")), this.opcode("resolvePossibleLambda")
                    },
                    helperSexpr: function(e, t, n) {
                        var i = this.setupFullMustacheParams(e, t, n),
                            r = e.id,
                            s = r.parts[0];
                        if (this.options.knownHelpers[s]) this.opcode("invokeKnownHelper", i.length, s);
                        else {
                            if (this.options.knownHelpersOnly) throw new a("You specified knownHelpersOnly, but used the unknown helper " + s, e);
                            r.falsy = !0, this.ID(r), this.opcode("invokeHelper", i.length, r.original, r.isSimple)
                        }
                    },
                    sexpr: function(e) {
                        var t = this.classifySexpr(e);
                        "simple" === t ? this.simpleSexpr(e) : "helper" === t ? this.helperSexpr(e) : this.ambiguousSexpr(e)
                    },
                    ID: function(e) {
                        this.addDepth(e.depth), this.opcode("getContext", e.depth);
                        var t = e.parts[0];
                        t ? this.opcode("lookupOnContext", e.parts, e.falsy, e.isScoped) : this.opcode("pushContext")
                    },
                    DATA: function(e) {
                        this.options.data = !0, this.opcode("lookupData", e.id.depth, e.id.parts)
                    },
                    STRING: function(e) {
                        this.opcode("pushString", e.string)
                    },
                    NUMBER: function(e) {
                        this.opcode("pushLiteral", e.number)
                    },
                    BOOLEAN: function(e) {
                        this.opcode("pushLiteral", e.bool)
                    },
                    comment: function() {},
                    opcode: function(e) {
                        this.opcodes.push({
                            opcode: e,
                            args: c.call(arguments, 1)
                        })
                    },
                    addDepth: function(e) {
                        0 !== e && (this.depths[e] || (this.depths[e] = !0, this.depths.list.push(e)))
                    },
                    classifySexpr: function(e) {
                        var t = e.isHelper,
                            n = e.eligibleHelper,
                            i = this.options;
                        if (n && !t) {
                            var r = e.id.parts[0];
                            i.knownHelpers[r] ? t = !0 : i.knownHelpersOnly && (n = !1)
                        }
                        return t ? "helper" : n ? "ambiguous" : "simple"
                    },
                    pushParams: function(e) {
                        for (var t = 0, n = e.length; n > t; t++) this.pushParam(e[t])
                    },
                    pushParam: function(e) {
                        this.stringParams ? (e.depth && this.addDepth(e.depth), this.opcode("getContext", e.depth || 0), this.opcode("pushStringParam", e.stringModeValue, e.type), "sexpr" === e.type && this.sexpr(e)) : (this.trackIds && this.opcode("pushId", e.type, e.idName || e.stringModeValue), this.accept(e))
                    },
                    setupFullMustacheParams: function(e, t, n) {
                        var i = e.params;
                        return this.pushParams(i), this.opcode("pushProgram", t), this.opcode("pushProgram", n), e.hash ? this.hash(e.hash) : this.opcode("emptyHash"), i
                    }
                }, o.precompile = i, o.compile = r, o
            }(n, t),
            h = function(e, t) {
                function n(e) {
                    this.value = e
                }

                function i() {}
                var r, s = e.COMPILER_REVISION,
                    o = e.REVISION_CHANGES,
                    a = t;
                i.prototype = {
                    nameLookup: function(e, t) {
                        return i.isValidJavaScriptVariableName(t) ? e + "." + t : e + "['" + t + "']"
                    },
                    depthedLookup: function(e) {
                        return this.aliases.lookup = "this.lookup", 'lookup(depths, "' + e + '")'
                    },
                    compilerInfo: function() {
                        var e = s,
                            t = o[e];
                        return [e, t]
                    },
                    appendToBuffer: function(e) {
                        return this.environment.isSimple ? "return " + e + ";" : {
                            appendToBuffer: !0,
                            content: e,
                            toString: function() {
                                return "buffer += " + e + ";"
                            }
                        }
                    },
                    initializeBuffer: function() {
                        return this.quotedString("")
                    },
                    namespace: "Handlebars",
                    compile: function(e, t, n, i) {
                        this.environment = e, this.options = t, this.stringParams = this.options.stringParams, this.trackIds = this.options.trackIds, this.precompile = !i, this.name = this.environment.name, this.isChild = !!n, this.context = n || {
                            programs: [],
                            environments: []
                        }, this.preamble(), this.stackSlot = 0, this.stackVars = [], this.aliases = {}, this.registers = {
                            list: []
                        }, this.hashes = [], this.compileStack = [], this.inlineStack = [], this.compileChildren(e, t), this.useDepths = this.useDepths || e.depths.list.length || this.options.compat;
                        var r, s, o, l = e.opcodes;
                        for (s = 0, o = l.length; o > s; s++) r = l[s], this[r.opcode].apply(this, r.args);
                        if (this.pushSource(""), this.stackSlot || this.inlineStack.length || this.compileStack.length) throw new a("Compile completed with content left on stack");
                        var c = this.createFunctionContext(i);
                        if (this.isChild) return c;
                        var u = {
                                compiler: this.compilerInfo(),
                                main: c
                            },
                            h = this.context.programs;
                        for (s = 0, o = h.length; o > s; s++) h[s] && (u[s] = h[s]);
                        return this.environment.usePartial && (u.usePartial = !0), this.options.data && (u.useData = !0), this.useDepths && (u.useDepths = !0), this.options.compat && (u.compat = !0), i || (u.compiler = JSON.stringify(u.compiler), u = this.objectLiteral(u)), u
                    },
                    preamble: function() {
                        this.lastContext = 0, this.source = []
                    },
                    createFunctionContext: function(e) {
                        var t = "",
                            n = this.stackVars.concat(this.registers.list);
                        n.length > 0 && (t += ", " + n.join(", "));
                        for (var i in this.aliases) this.aliases.hasOwnProperty(i) && (t += ", " + i + "=" + this.aliases[i]);
                        var r = ["depth0", "helpers", "partials", "data"];
                        this.useDepths && r.push("depths");
                        var s = this.mergeSource(t);
                        return e ? (r.push(s), Function.apply(this, r)) : "function(" + r.join(",") + ") {\n  " + s + "}"
                    },
                    mergeSource: function(e) {
                        for (var t, n, i = "", r = !this.forceBuffer, s = 0, o = this.source.length; o > s; s++) {
                            var a = this.source[s];
                            a.appendToBuffer ? t = t ? t + "\n    + " + a.content : a.content : (t && (i ? i += "buffer += " + t + ";\n  " : (n = !0, i = t + ";\n  "), t = void 0), i += a + "\n  ", this.environment.isSimple || (r = !1))
                        }
                        return r ? (t || !i) && (i += "return " + (t || '""') + ";\n") : (e += ", buffer = " + (n ? "" : this.initializeBuffer()), i += t ? "return buffer + " + t + ";\n" : "return buffer;\n"), e && (i = "var " + e.substring(2) + (n ? "" : ";\n  ") + i), i
                    },
                    blockValue: function(e) {
                        this.aliases.blockHelperMissing = "helpers.blockHelperMissing";
                        var t = [this.contextName(0)];
                        this.setupParams(e, 0, t);
                        var n = this.popStack();
                        t.splice(1, 0, n), this.push("blockHelperMissing.call(" + t.join(", ") + ")")
                    },
                    ambiguousBlockValue: function() {
                        this.aliases.blockHelperMissing = "helpers.blockHelperMissing";
                        var e = [this.contextName(0)];
                        this.setupParams("", 0, e, !0), this.flushInline();
                        var t = this.topStack();
                        e.splice(1, 0, t), this.pushSource("if (!" + this.lastHelper + ") { " + t + " = blockHelperMissing.call(" + e.join(", ") + "); }")
                    },
                    appendContent: function(e) {
                        this.pendingContent && (e = this.pendingContent + e), this.pendingContent = e
                    },
                    append: function() {
                        this.flushInline();
                        var e = this.popStack();
                        this.pushSource("if (" + e + " != null) { " + this.appendToBuffer(e) + " }"), this.environment.isSimple && this.pushSource("else { " + this.appendToBuffer("''") + " }")
                    },
                    appendEscaped: function() {
                        this.aliases.escapeExpression = "this.escapeExpression", this.pushSource(this.appendToBuffer("escapeExpression(" + this.popStack() + ")"))
                    },
                    getContext: function(e) {
                        this.lastContext = e
                    },
                    pushContext: function() {
                        this.pushStackLiteral(this.contextName(this.lastContext))
                    },
                    lookupOnContext: function(e, t, n) {
                        var i = 0,
                            r = e.length;
                        for (n || !this.options.compat || this.lastContext ? this.pushContext() : this.push(this.depthedLookup(e[i++])); r > i; i++) this.replaceStack(function(n) {
                            var r = this.nameLookup(n, e[i], "context");
                            return t ? " && " + r : " != null ? " + r + " : " + n
                        })
                    },
                    lookupData: function(e, t) {
                        this.pushStackLiteral(e ? "this.data(data, " + e + ")" : "data");
                        for (var n = t.length, i = 0; n > i; i++) this.replaceStack(function(e) {
                            return " && " + this.nameLookup(e, t[i], "data")
                        })
                    },
                    resolvePossibleLambda: function() {
                        this.aliases.lambda = "this.lambda", this.push("lambda(" + this.popStack() + ", " + this.contextName(0) + ")")
                    },
                    pushStringParam: function(e, t) {
                        this.pushContext(), this.pushString(t), "sexpr" !== t && ("string" == typeof e ? this.pushString(e) : this.pushStackLiteral(e))
                    },
                    emptyHash: function() {
                        this.pushStackLiteral("{}"), this.trackIds && this.push("{}"), this.stringParams && (this.push("{}"), this.push("{}"))
                    },
                    pushHash: function() {
                        this.hash && this.hashes.push(this.hash), this.hash = {
                            values: [],
                            types: [],
                            contexts: [],
                            ids: []
                        }
                    },
                    popHash: function() {
                        var e = this.hash;
                        this.hash = this.hashes.pop(), this.trackIds && this.push("{" + e.ids.join(",") + "}"), this.stringParams && (this.push("{" + e.contexts.join(",") + "}"), this.push("{" + e.types.join(",") + "}")), this.push("{\n    " + e.values.join(",\n    ") + "\n  }")
                    },
                    pushString: function(e) {
                        this.pushStackLiteral(this.quotedString(e))
                    },
                    push: function(e) {
                        return this.inlineStack.push(e), e
                    },
                    pushLiteral: function(e) {
                        this.pushStackLiteral(e)
                    },
                    pushProgram: function(e) {
                        this.pushStackLiteral(null != e ? this.programExpression(e) : null)
                    },
                    invokeHelper: function(e, t, n) {
                        this.aliases.helperMissing = "helpers.helperMissing";
                        var i = this.popStack(),
                            r = this.setupHelper(e, t),
                            s = (n ? r.name + " || " : "") + i + " || helperMissing";
                        this.push("((" + s + ").call(" + r.callParams + "))")
                    },
                    invokeKnownHelper: function(e, t) {
                        var n = this.setupHelper(e, t);
                        this.push(n.name + ".call(" + n.callParams + ")")
                    },
                    invokeAmbiguous: function(e, t) {
                        this.aliases.functionType = '"function"', this.aliases.helperMissing = "helpers.helperMissing", this.useRegister("helper");
                        var n = this.popStack();
                        this.emptyHash();
                        var i = this.setupHelper(0, e, t),
                            r = this.lastHelper = this.nameLookup("helpers", e, "helper");
                        this.push("((helper = (helper = " + r + " || " + n + ") != null ? helper : helperMissing" + (i.paramsInit ? "),(" + i.paramsInit : "") + "),(typeof helper === functionType ? helper.call(" + i.callParams + ") : helper))")
                    },
                    invokePartial: function(e, t) {
                        var n = [this.nameLookup("partials", e, "partial"), "'" + t + "'", "'" + e + "'", this.popStack(), this.popStack(), "helpers", "partials"];
                        this.options.data ? n.push("data") : this.options.compat && n.push("undefined"), this.options.compat && n.push("depths"), this.push("this.invokePartial(" + n.join(", ") + ")")
                    },
                    assignToHash: function(e) {
                        var t, n, i, r = this.popStack();
                        this.trackIds && (i = this.popStack()), this.stringParams && (n = this.popStack(), t = this.popStack());
                        var s = this.hash;
                        t && s.contexts.push("'" + e + "': " + t), n && s.types.push("'" + e + "': " + n), i && s.ids.push("'" + e + "': " + i), s.values.push("'" + e + "': (" + r + ")")
                    },
                    pushId: function(e, t) {
                        "ID" === e || "DATA" === e ? this.pushString(t) : this.pushStackLiteral("sexpr" === e ? "true" : "null")
                    },
                    compiler: i,
                    compileChildren: function(e, t) {
                        for (var n, i, r = e.children, s = 0, o = r.length; o > s; s++) {
                            n = r[s], i = new this.compiler;
                            var a = this.matchExistingProgram(n);
                            null == a ? (this.context.programs.push(""), a = this.context.programs.length, n.index = a, n.name = "program" + a, this.context.programs[a] = i.compile(n, t, this.context, !this.precompile), this.context.environments[a] = n, this.useDepths = this.useDepths || i.useDepths) : (n.index = a, n.name = "program" + a)
                        }
                    },
                    matchExistingProgram: function(e) {
                        for (var t = 0, n = this.context.environments.length; n > t; t++) {
                            var i = this.context.environments[t];
                            if (i && i.equals(e)) return t
                        }
                    },
                    programExpression: function(e) {
                        var t = this.environment.children[e],
                            n = (t.depths.list, this.useDepths),
                            i = [t.index, "data"];
                        return n && i.push("depths"), "this.program(" + i.join(", ") + ")"
                    },
                    useRegister: function(e) {
                        this.registers[e] || (this.registers[e] = !0, this.registers.list.push(e))
                    },
                    pushStackLiteral: function(e) {
                        return this.push(new n(e))
                    },
                    pushSource: function(e) {
                        this.pendingContent && (this.source.push(this.appendToBuffer(this.quotedString(this.pendingContent))), this.pendingContent = void 0), e && this.source.push(e)
                    },
                    pushStack: function(e) {
                        this.flushInline();
                        var t = this.incrStack();
                        return this.pushSource(t + " = " + e + ";"), this.compileStack.push(t), t
                    },
                    replaceStack: function(e) {
                        {
                            var t, i, r, s = "";
                            this.isInline()
                        }
                        if (!this.isInline()) throw new a("replaceStack on non-inline");
                        var o = this.popStack(!0);
                        if (o instanceof n) s = t = o.value, r = !0;
                        else {
                            i = !this.stackSlot;
                            var l = i ? this.incrStack() : this.topStackName();
                            s = "(" + this.push(l) + " = " + o + ")", t = this.topStack()
                        }
                        var c = e.call(this, t);
                        r || this.popStack(), i && this.stackSlot--, this.push("(" + s + c + ")")
                    },
                    incrStack: function() {
                        return this.stackSlot++, this.stackSlot > this.stackVars.length && this.stackVars.push("stack" + this.stackSlot), this.topStackName()
                    },
                    topStackName: function() {
                        return "stack" + this.stackSlot
                    },
                    flushInline: function() {
                        var e = this.inlineStack;
                        if (e.length) {
                            this.inlineStack = [];
                            for (var t = 0, i = e.length; i > t; t++) {
                                var r = e[t];
                                r instanceof n ? this.compileStack.push(r) : this.pushStack(r)
                            }
                        }
                    },
                    isInline: function() {
                        return this.inlineStack.length
                    },
                    popStack: function(e) {
                        var t = this.isInline(),
                            i = (t ? this.inlineStack : this.compileStack).pop();
                        if (!e && i instanceof n) return i.value;
                        if (!t) {
                            if (!this.stackSlot) throw new a("Invalid stack pop");
                            this.stackSlot--
                        }
                        return i
                    },
                    topStack: function() {
                        var e = this.isInline() ? this.inlineStack : this.compileStack,
                            t = e[e.length - 1];
                        return t instanceof n ? t.value : t
                    },
                    contextName: function(e) {
                        return this.useDepths && e ? "depths[" + e + "]" : "depth" + e
                    },
                    quotedString: function(e) {
                        return '"' + e.replace(/\\/g, "\\\\").replace(/"/g, '\\"').replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\u2028/g, "\\u2028").replace(/\u2029/g, "\\u2029") + '"'
                    },
                    objectLiteral: function(e) {
                        var t = [];
                        for (var n in e) e.hasOwnProperty(n) && t.push(this.quotedString(n) + ":" + e[n]);
                        return "{" + t.join(",") + "}"
                    },
                    setupHelper: function(e, t, n) {
                        var i = [],
                            r = this.setupParams(t, e, i, n),
                            s = this.nameLookup("helpers", t, "helper");
                        return {
                            params: i,
                            paramsInit: r,
                            name: s,
                            callParams: [this.contextName(0)].concat(i).join(", ")
                        }
                    },
                    setupOptions: function(e, t, n) {
                        var i, r, s, o = {},
                            a = [],
                            l = [],
                            c = [];
                        o.name = this.quotedString(e), o.hash = this.popStack(), this.trackIds && (o.hashIds = this.popStack()), this.stringParams && (o.hashTypes = this.popStack(), o.hashContexts = this.popStack()), r = this.popStack(), s = this.popStack(), (s || r) && (s || (s = "this.noop"), r || (r = "this.noop"), o.fn = s, o.inverse = r);
                        for (var u = t; u--;) i = this.popStack(), n[u] = i, this.trackIds && (c[u] = this.popStack()), this.stringParams && (l[u] = this.popStack(), a[u] = this.popStack());
                        return this.trackIds && (o.ids = "[" + c.join(",") + "]"), this.stringParams && (o.types = "[" + l.join(",") + "]", o.contexts = "[" + a.join(",") + "]"), this.options.data && (o.data = "data"), o
                    },
                    setupParams: function(e, t, n, i) {
                        var r = this.objectLiteral(this.setupOptions(e, t, n));
                        return i ? (this.useRegister("options"), n.push("options"), "options=" + r) : (n.push(r), "")
                    }
                };
                for (var l = "break else new var case finally return void catch for switch while continue function this with default if throw delete in try do instanceof typeof abstract enum int short boolean export interface static byte extends long super char final native synchronized class float package throws const goto private transient implements protected volatile double import public let yield".split(" "), c = i.RESERVED_WORDS = {}, u = 0, h = l.length; h > u; u++) c[l[u]] = !0;
                return i.isValidJavaScriptVariableName = function(e) {
                    return !i.RESERVED_WORDS[e] && /^[a-zA-Z_$][0-9a-zA-Z_$]*$/.test(e)
                }, r = i
            }(i, n),
            d = function(e, t, n, i, r) {
                var s, o = e,
                    a = t,
                    l = n.parser,
                    c = n.parse,
                    u = i.Compiler,
                    h = i.compile,
                    d = i.precompile,
                    p = r,
                    f = o.create,
                    m = function() {
                        var e = f();
                        return e.compile = function(t, n) {
                            return h(t, n, e)
                        }, e.precompile = function(t, n) {
                            return d(t, n, e)
                        }, e.AST = a, e.Compiler = u, e.JavaScriptCompiler = p, e.Parser = l, e.parse = c, e
                    };
                return o = m(), o.create = m, o["default"] = o, s = o
            }(s, o, c, u, h);
        return d
    }), define("hbs.compiled", ["handlebars"], function(e) {
        return this.JST = this.JST || {}, e.registerPartial("search-result", this.JST["search-result"] = e.template({
            1: function() {
                return " search-result--winner"
            },
            3: function() {
                return " search-result--featured"
            },
            5: function() {
                return "search-result__image--placeholder"
            },
            7: function() {
                return "search-result__image--video"
            },
            9: function(e, t, n, i) {
                var r, s, o = "function",
                    a = t.helperMissing,
                    l = this.escapeExpression,
                    c = '    <a href="';
                return r = t["if"].call(e, null != e ? e.video : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(10, i),
                    inverse: this.program(12, i),
                    data: i
                }), null != r && (c += r), c += '" ', r = t["if"].call(e, null != e ? e.video : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(14, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += ' alt="' + l((s = null != (s = t.title || (null != e ? e.title : e)) ? s : a, typeof s === o ? s.call(e, {
                    name: "title",
                    hash: {},
                    data: i
                }) : s)) + '" target="_blank" rel="no_follow" style="background-image: url(\'' + l((s = null != (s = t.image || (null != e ? e.image : e)) ? s : a, typeof s === o ? s.call(e, {
                    name: "image",
                    hash: {},
                    data: i
                }) : s)) + "');\">\n      ", r = t["if"].call(e, null != e ? e.video : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(16, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c + '\n  <div class="search-preloader"><img src="/wp-content/themes/roots-sass-master/assets/images/loader-black.gif"></div>\n  <img src="' + l((s = null != (s = t.image || (null != e ? e.image : e)) ? s : a, typeof s === o ? s.call(e, {
                    name: "image",
                    hash: {},
                    data: i
                }) : s)) + '"> \n    </a>\n'
            },
            10: function(e, t, n, i) {
                var r, s = "function",
                    o = t.helperMissing,
                    a = this.escapeExpression;
                return a((r = null != (r = t.video || (null != e ? e.video : e)) ? r : o, typeof r === s ? r.call(e, {
                    name: "video",
                    hash: {},
                    data: i
                }) : r))
            },
            12: function(e, t, n, i) {
                var r, s = "function",
                    o = t.helperMissing,
                    a = this.escapeExpression;
                return a((r = null != (r = t.url || (null != e ? e.url : e)) ? r : o, typeof r === s ? r.call(e, {
                    name: "url",
                    hash: {},
                    data: i
                }) : r))
            },
            14: function() {
                return 'class="has-video js-has-video"'
            },
            16: function() {
                return '<span class="icon icon-video"></span>'
            },
            18: function(e, t, n, i) {
                var r, s = "function",
                    o = t.helperMissing,
                    a = this.escapeExpression;
                return '    <a href="' + a((r = null != (r = t.url || (null != e ? e.url : e)) ? r : o, typeof r === s ? r.call(e, {
                    name: "url",
                    hash: {},
                    data: i
                }) : r)) + '" alt="' + a((r = null != (r = t.title || (null != e ? e.title : e)) ? r : o, typeof r === s ? r.call(e, {
                    name: "title",
                    hash: {},
                    data: i
                }) : r)) + '" target="_blank" rel="no_follow" style="background-image: url(\'/wp-content/themes/roots-sass-master/assets/images/search-no-image.jpg\');"></a>\n'
            },
            20: function(e, t, n, i) {
                var r, s = "function",
                    o = t.helperMissing,
                    a = this.escapeExpression;
                return '    <div class="search-result__year">' + a((r = null != (r = t.year || (null != e ? e.year : e)) ? r : o, typeof r === s ? r.call(e, {
                    name: "year",
                    hash: {},
                    data: i
                }) : r)) + " Webby Winners</div>\n"
            },
            22: function() {
                return "search-result__category"
            },
            24: function(e, t, n, i) {
                var r, s = "function",
                    o = t.helperMissing,
                    a = this.escapeExpression;
                return '    <div class="search-result__date">' + a((r = null != (r = t.date || (null != e ? e.date : e)) ? r : o, typeof r === s ? r.call(e, {
                    name: "date",
                    hash: {},
                    data: i
                }) : r)) + "</div>\n"
            },
            26: function() {
                return "search-result__headline--winner"
            },
            28: function() {
                return "search-result__headline--featured"
            },
            30: function(e, t, n, i) {
                var r, s = "function",
                    o = t.helperMissing,
                    a = this.escapeExpression;
                return '    <div class="search-result__organization">' + a((r = null != (r = t.agency || (null != e ? e.agency : e)) ? r : o, typeof r === s ? r.call(e, {
                    name: "agency",
                    hash: {},
                    data: i
                }) : r)) + "</div>\n"
            },
            32: function(e, t, n, i) {
                var r, s = '    <div class="search-result__category-list">\n      <ul>\n';
                return r = t.each.call(e, null != e ? e.categories : e, {
                    name: "each",
                    hash: {},
                    fn: this.program(33, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), s + "      </ul>\n    </div>\n"
            },
            33: function(e, t, n, i) {
                var r, s, o = "function",
                    a = t.helperMissing,
                    l = this.escapeExpression,
                    c = '        <li class="search-result__category-item">\n          <div class="search-result__category-title"><a href="' + l((s = null != (s = t.url || (null != e ? e.url : e)) ? s : a, typeof s === o ? s.call(e, {
                        name: "url",
                        hash: {},
                        data: i
                    }) : s)) + '">' + l((s = null != (s = t.title || (null != e ? e.title : e)) ? s : a, typeof s === o ? s.call(e, {
                        name: "title",
                        hash: {},
                        data: i
                    }) : s)) + '</a></div>\n          <div class="search-result__category-class">\n            ';
                return r = t["if"].call(e, null != e ? e.honoree : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(34, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += "\n            ", r = t["if"].call(e, null != e ? e.nominee : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(36, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += "\n            ", r = t["if"].call(e, null != e ? e.peoplesVoice : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(38, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += "\n            ", r = t["if"].call(e, null != e ? e.winner : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(40, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c + "\n          </div>\n        </li>\n"
            },
            34: function() {
                return "<span>Honoree</span>"
            },
            36: function() {
                return "<span>Nominee</span>"
            },
            38: function() {
                return "<span>People's Voice</span>"
            },
            40: function() {
                return "<span>Webby Winner</span>"
            },
            compiler: [6, ">= 2.0.0-beta.1"],
            main: function(e, t, n, i) {
                var r, s, o = "function",
                    a = t.helperMissing,
                    l = this.escapeExpression,
                    c = '<li class="search-result';
                return r = t["if"].call(e, null != e ? e.is_winner : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(1, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), r = t["if"].call(e, null != e ? e.is_featured : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(3, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += '">\n\n  <div class="search-result__image ', r = t.unless.call(e, null != e ? e.image : e, {
                    name: "unless",
                    hash: {},
                    fn: this.program(5, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += " ", r = t["if"].call(e, null != e ? e.video : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(7, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += '">\n', r = t["if"].call(e, null != e ? e.image : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(9, i),
                    inverse: this.program(18, i),
                    data: i
                }), null != r && (c += r), c += '  </div>\n\n  <div class="search-result__text">\n\n', r = t["if"].call(e, null != e ? e.is_winner : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(20, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += '\n    <div class="search-result__eyebrow ', r = t["if"].call(e, null != e ? e.is_winner : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(22, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += '">' + l((s = null != (s = t.type || (null != e ? e.type : e)) ? s : a, typeof s === o ? s.call(e, {
                    name: "type",
                    hash: {},
                    data: i
                }) : s)) + "</div>\n\n", r = t["if"].call(e, null != e ? e.is_featured : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(24, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += '\n    <h3 class="search-result__headline ', r = t["if"].call(e, null != e ? e.is_winner : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(26, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += " ", r = t["if"].call(e, null != e ? e.is_featured : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(28, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += '"><a href="' + l((s = null != (s = t.url || (null != e ? e.url : e)) ? s : a, typeof s === o ? s.call(e, {
                    name: "url",
                    hash: {},
                    data: i
                }) : s)) + '">' + l((s = null != (s = t.title || (null != e ? e.title : e)) ? s : a, typeof s === o ? s.call(e, {
                    name: "title",
                    hash: {},
                    data: i
                }) : s)) + "</a></h3>\n\n", r = t["if"].call(e, null != e ? e.is_winner : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(30, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += "\n", r = t["if"].call(e, null != e ? e.is_winner : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(32, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c + "\n  </div>\n\n</li>\n"
            },
            useData: !0
        })), this.JST["search-results/search-results"] = e.template({
            1: function(e, t, n, i) {
                var r, s, o = "function",
                    a = t.helperMissing,
                    l = this.escapeExpression,
                    c = "    ";
                return r = t["if"].call(e, null != e ? e.error : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(2, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c += l((s = null != (s = t.message || (null != e ? e.message : e)) ? s : a, typeof s === o ? s.call(e, {
                    name: "message",
                    hash: {},
                    data: i
                }) : s)), r = t["if"].call(e, null != e ? e.error : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(4, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c + "\n"
            },
            2: function() {
                return '<div class="mod-search-results__error">'
            },
            4: function() {
                return "</div>"
            },
            6: function(e, t, n, i) {
                var r, s = "";
                return r = this.invokePartial(n["search-result"], "    ", "search-result", e, void 0, t, n, i), null != r && (s += r), s
            },
            8: function(e, t, n, i) {
                var r, s = '  <ul class="pagination pagination--search-results">\n';
                return r = t["if"].call(e, null != e ? e.show_prev : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(9, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), r = t.each.call(e, null != (r = null != e ? e.pagination : e) ? r.pages : r, {
                    name: "each",
                    hash: {},
                    fn: this.program(11, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), r = t["if"].call(e, null != e ? e.show_dots : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(15, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), r = t.each.call(e, null != (r = null != e ? e.pagination : e) ? r.pages : r, {
                    name: "each",
                    hash: {},
                    fn: this.program(17, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), r = t["if"].call(e, null != e ? e.show_next : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(20, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), s + "  </ul>\n"
            },
            9: function() {
                return '    <li class="pagination__item pagination__item--arrow pagination__item--left"><a href="#" class="js-pagination-direction"><span class="icon icon-arrow-left"></span></a></li>\n'
            },
            11: function(e, t, n, i) {
                var r, s = "";
                return r = t.unless.call(e, null != e ? e.last : e, {
                    name: "unless",
                    hash: {},
                    fn: this.program(12, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), s
            },
            12: function(e, t, n, i) {
                var r, s, o = "function",
                    a = t.helperMissing,
                    l = this.escapeExpression,
                    c = '        <li class="pagination__item pagination__item--' + l((s = null != (s = t.page || (null != e ? e.page : e)) ? s : a, typeof s === o ? s.call(e, {
                        name: "page",
                        hash: {},
                        data: i
                    }) : s));
                return r = t["if"].call(e, null != e ? e.active : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(13, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c + '"><a href="#" class="js-pagination-page">' + l((s = null != (s = t.page || (null != e ? e.page : e)) ? s : a, typeof s === o ? s.call(e, {
                    name: "page",
                    hash: {},
                    data: i
                }) : s)) + "</a></li>\n"
            },
            13: function() {
                return " pagination__item--active"
            },
            15: function() {
                return '    <li class="pagination__item pagination__item--spacer">&hellip;</li>\n'
            },
            17: function(e, t, n, i) {
                var r, s = "";
                return r = t["if"].call(e, null != e ? e.last : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(18, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), s
            },
            18: function(e, t, n, i) {
                var r, s, o = "function",
                    a = t.helperMissing,
                    l = this.escapeExpression,
                    c = '        <li class="pagination__item pagination__item--skip';
                return r = t["if"].call(e, null != e ? e.active : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(13, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (c += r), c + '"><a href="#" class="js-pagination-page">' + l((s = null != (s = t.page || (null != e ? e.page : e)) ? s : a, typeof s === o ? s.call(e, {
                    name: "page",
                    hash: {},
                    data: i
                }) : s)) + "</a></li>\n"
            },
            20: function() {
                return '    <li class="pagination__item pagination__item--arrow pagination__item--right"><a href="#" class="js-pagination-direction"><span class="icon icon-arrow-right"></span></a></li>\n'
            },
            compiler: [6, ">= 2.0.0-beta.1"],
            main: function(e, t, n, i) {
                var r, s = '<ul class="mod-search-results__results-list">\n';
                return r = t["if"].call(e, null != e ? e.message : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(1, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), r = t.each.call(e, null != e ? e.search_results : e, {
                    name: "each",
                    hash: {},
                    fn: this.program(6, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), s += "</ul>\n", r = t["if"].call(e, null != e ? e.pagination : e, {
                    name: "if",
                    hash: {},
                    fn: this.program(8, i),
                    inverse: this.noop,
                    data: i
                }), null != r && (s += r), s
            },
            usePartial: !0,
            useData: !0
        }), this.JST
    }),
    function(e, t) {
        var n = e.History = e.History || {},
            i = e.jQuery;
        //if ("undefined" != typeof n.Adapter) throw new Error("History.js Adapter has already been loaded...");
        n.Adapter = {
            bind: function(e, t, n) {
                i(e).bind(t, n)
            },
            trigger: function(e, t, n) {
                i(e).trigger(t, n)
            },
            extractEventData: function(e, n, i) {
                var r = n && n.originalEvent && n.originalEvent[e] || i && i[e] || t;
                return r
            },
            onDomLoad: function(e) {
                i(e)
            }
        }, "undefined" != typeof n.init && n.init()
    }(window),
    function(e, t) {
        var n = e.console || t,
            i = e.document,
            r = e.navigator,
            s = false,
            o = e.setTimeout,
            a = e.clearTimeout,
            l = e.setInterval,
            c = e.clearInterval,
            u = e.JSON,
            h = e.alert,
            d = e.History = e.History || {},
            p = e.history;
        try {
            s.setItem("TEST", "1"), s.removeItem("TEST")
        } catch (f) {
            s = !1
        }
        //if (u.stringify = u.stringify || u.encode, u.parse = u.parse || u.decode, "undefined" != typeof d.init) throw new Error("History.js Core has already been loaded...");
        d.init = function() {
            return "undefined" == typeof d.Adapter ? !1 : ("undefined" != typeof d.initCore && d.initCore(), "undefined" != typeof d.initHtml4 && d.initHtml4(), !0)
        }, d.initCore = function() {
            if ("undefined" != typeof d.initCore.initialized) return !1;
            if (d.initCore.initialized = !0, d.options = d.options || {}, d.options.hashChangeInterval = d.options.hashChangeInterval || 100, d.options.safariPollInterval = d.options.safariPollInterval || 500, d.options.doubleCheckInterval = d.options.doubleCheckInterval || 500, d.options.disableSuid = d.options.disableSuid || !1, d.options.storeInterval = d.options.storeInterval || 1e3, d.options.busyDelay = d.options.busyDelay || 250, d.options.debug = d.options.debug || !1, d.options.initialTitle = d.options.initialTitle || i.title, d.options.html4Mode = d.options.html4Mode || !1, d.options.delayInit = d.options.delayInit || !1, d.intervalList = [], d.clearAllIntervals = function() {
                    var e, t = d.intervalList;
                    if ("undefined" != typeof t && null !== t) {
                        for (e = 0; e < t.length; e++) c(t[e]);
                        d.intervalList = null
                    }
                }, d.debug = function() {
                    d.options.debug && d.log.apply(d, arguments)
                }, d.log = function() {
                    var e, t, r, s, o, a = !("undefined" == typeof n || "undefined" == typeof n.log || "undefined" == typeof n.log.apply),
                        l = i.getElementById("log");
                    for (a ? (s = Array.prototype.slice.call(arguments), e = s.shift(), "undefined" != typeof n.debug ? n.debug.apply(n, [e, s]) : n.log.apply(n, [e, s])) : e = "\n" + arguments[0] + "\n", t = 1, r = arguments.length; r > t; ++t) {
                        if (o = arguments[t], "object" == typeof o && "undefined" != typeof u) try {
                            o = u.stringify(o)
                        } catch (c) {}
                        e += "\n" + o + "\n"
                    }
                    return l ? (l.value += e + "\n-----\n", l.scrollTop = l.scrollHeight - l.clientHeight) : a || h(e), !0
                }, d.getInternetExplorerMajorVersion = function() {
                    var e = d.getInternetExplorerMajorVersion.cached = "undefined" != typeof d.getInternetExplorerMajorVersion.cached ? d.getInternetExplorerMajorVersion.cached : function() {
                        for (var e = 3, t = i.createElement("div"), n = t.getElementsByTagName("i");
                            (t.innerHTML = "<!--[if gt IE " + ++e + "]><i></i><![endif]-->") && n[0];);
                        return e > 4 ? e : !1
                    }();
                    return e
                }, d.isInternetExplorer = function() {
                    var e = d.isInternetExplorer.cached = "undefined" != typeof d.isInternetExplorer.cached ? d.isInternetExplorer.cached : Boolean(d.getInternetExplorerMajorVersion());
                    return e
                }, d.emulated = d.options.html4Mode ? {
                    pushState: !0,
                    hashChange: !0
                } : {
                    pushState: !Boolean(e.history && e.history.pushState && e.history.replaceState && !(/ Mobile\/([1-7][a-z]|(8([abcde]|f(1[0-8]))))/i.test(r.userAgent) || /AppleWebKit\/5([0-2]|3[0-2])/i.test(r.userAgent))),
                    hashChange: Boolean(!("onhashchange" in e || "onhashchange" in i) || d.isInternetExplorer() && d.getInternetExplorerMajorVersion() < 8)
                }, d.enabled = !d.emulated.pushState, d.bugs = {
                    setHash: Boolean(!d.emulated.pushState && "Apple Computer, Inc." === r.vendor && /AppleWebKit\/5([0-2]|3[0-3])/.test(r.userAgent)),
                    safariPoll: Boolean(!d.emulated.pushState && "Apple Computer, Inc." === r.vendor && /AppleWebKit\/5([0-2]|3[0-3])/.test(r.userAgent)),
                    ieDoubleCheck: Boolean(d.isInternetExplorer() && d.getInternetExplorerMajorVersion() < 8),
                    hashEscape: Boolean(d.isInternetExplorer() && d.getInternetExplorerMajorVersion() < 7)
                }, d.isEmptyObject = function(e) {
                    for (var t in e)
                        if (e.hasOwnProperty(t)) return !1;
                    return !0
                }, d.cloneObject = function(e) {
                    var t, n;
                    return e ? (t = u.stringify(e), n = u.parse(t)) : n = {}, n
                }, d.getRootUrl = function() {
                    var e = i.location.protocol + "//" + (i.location.hostname || i.location.host);
                    return i.location.port && (e += ":" + i.location.port), e += "/"
                }, d.getBaseHref = function() {
                    var e = i.getElementsByTagName("base"),
                        t = null,
                        n = "";
                    return 1 === e.length && (t = e[0], n = t.href.replace(/[^\/]+$/, "")), n = n.replace(/\/+$/, ""), n && (n += "/"), n
                }, d.getBaseUrl = function() {
                    var e = d.getBaseHref() || d.getBasePageUrl() || d.getRootUrl();
                    return e
                }, d.getPageUrl = function() {
                    var e, t = d.getState(!1, !1),
                        n = (t || {}).url || d.getLocationHref();
                    return e = n.replace(/\/+$/, "").replace(/[^\/]+$/, function(e) {
                        return /\./.test(e) ? e : e + "/"
                    })
                }, d.getBasePageUrl = function() {
                    var e = d.getLocationHref().replace(/[#\?].*/, "").replace(/[^\/]+$/, function(e) {
                        return /[^\/]$/.test(e) ? "" : e
                    }).replace(/\/+$/, "") + "/";
                    return e
                }, d.getFullUrl = function(e, t) {
                    var n = e,
                        i = e.substring(0, 1);
                    return t = "undefined" == typeof t ? !0 : t, /[a-z]+\:\/\//.test(e) || (n = "/" === i ? d.getRootUrl() + e.replace(/^\/+/, "") : "#" === i ? d.getPageUrl().replace(/#.*/, "") + e : "?" === i ? d.getPageUrl().replace(/[\?#].*/, "") + e : t ? d.getBaseUrl() + e.replace(/^(\.\/)+/, "") : d.getBasePageUrl() + e.replace(/^(\.\/)+/, "")), n.replace(/\#$/, "")
                }, d.getShortUrl = function(e) {
                    var t = e,
                        n = d.getBaseUrl(),
                        i = d.getRootUrl();
                    return d.emulated.pushState && (t = t.replace(n, "")), t = t.replace(i, "/"), d.isTraditionalAnchor(t) && (t = "./" + t), t = t.replace(/^(\.\/)+/g, "./").replace(/\#$/, "")
                }, d.getLocationHref = function(e) {
                    return e = e || i, e.URL === e.location.href ? e.location.href : e.location.href === decodeURIComponent(e.URL) ? e.URL : e.location.hash && decodeURIComponent(e.location.href.replace(/^[^#]+/, "")) === e.location.hash ? e.location.href : -1 == e.URL.indexOf("#") && -1 != e.location.href.indexOf("#") ? e.location.href : e.URL || e.location.href
                }, d.store = {}, d.idToState = d.idToState || {}, d.stateToId = d.stateToId || {}, d.urlToId = d.urlToId || {}, d.storedStates = d.storedStates || [], d.savedStates = d.savedStates || [], d.normalizeStore = function() {
                    d.store.idToState = d.store.idToState || {}, d.store.urlToId = d.store.urlToId || {}, d.store.stateToId = d.store.stateToId || {}
                }, d.getState = function(e, t) {
                    "undefined" == typeof e && (e = !0), "undefined" == typeof t && (t = !0);
                    var n = d.getLastSavedState();
                    return !n && t && (n = d.createStateObject()), e && (n = d.cloneObject(n), n.url = n.cleanUrl || n.url), n
                }, d.getIdByState = function(e) {
                    var t, n = d.extractId(e.url);
                    if (!n)
                        if (t = d.getStateString(e), "undefined" != typeof d.stateToId[t]) n = d.stateToId[t];
                        else if ("undefined" != typeof d.store.stateToId[t]) n = d.store.stateToId[t];
                    else {
                        for (;;)
                            if (n = (new Date).getTime() + String(Math.random()).replace(/\D/g, ""), "undefined" == typeof d.idToState[n] && "undefined" == typeof d.store.idToState[n]) break;
                        d.stateToId[t] = n, d.idToState[n] = e
                    }
                    return n
                }, d.normalizeState = function(e) {
                    var t, n;
                    return e && "object" == typeof e || (e = {}), "undefined" != typeof e.normalized ? e : (e.data && "object" == typeof e.data || (e.data = {}), t = {}, t.normalized = !0, t.title = e.title || "", t.url = d.getFullUrl(e.url ? e.url : d.getLocationHref()), t.hash = d.getShortUrl(t.url), t.data = d.cloneObject(e.data), t.id = d.getIdByState(t), t.cleanUrl = t.url.replace(/\??\&_suid.*/, ""), t.url = t.cleanUrl, n = !d.isEmptyObject(t.data), (t.title || n) && d.options.disableSuid !== !0 && (t.hash = d.getShortUrl(t.url).replace(/\??\&_suid.*/, ""), /\?/.test(t.hash) || (t.hash += "?"), t.hash += "&_suid=" + t.id), t.hashedUrl = d.getFullUrl(t.hash), (d.emulated.pushState || d.bugs.safariPoll) && d.hasUrlDuplicate(t) && (t.url = t.hashedUrl), t)
                }, d.createStateObject = function(e, t, n) {
                    var i = {
                        data: e,
                        title: t,
                        url: n
                    };
                    return i = d.normalizeState(i)
                }, d.getStateById = function(e) {
                    e = String(e);
                    var n = d.idToState[e] || d.store.idToState[e] || t;
                    return n
                }, d.getStateString = function(e) {
                    var t, n, i;
                    return t = d.normalizeState(e), n = {
                        data: t.data,
                        title: e.title,
                        url: e.url
                    }, i = u.stringify(n)
                }, d.getStateId = function(e) {
                    var t, n;
                    return t = d.normalizeState(e), n = t.id
                }, d.getHashByState = function(e) {
                    var t, n;
                    return t = d.normalizeState(e), n = t.hash
                }, d.extractId = function(e) {
                    var t, n, i, r;
                    return r = -1 != e.indexOf("#") ? e.split("#")[0] : e, n = /(.*)\&_suid=([0-9]+)$/.exec(r), i = n ? n[1] || e : e, t = n ? String(n[2] || "") : "", t || !1
                }, d.isTraditionalAnchor = function(e) {
                    var t = !/[\/\?\.]/.test(e);
                    return t
                }, d.extractState = function(e, t) {
                    var n, i, r = null;
                    return t = t || !1, n = d.extractId(e), n && (r = d.getStateById(n)), r || (i = d.getFullUrl(e), n = d.getIdByUrl(i) || !1, n && (r = d.getStateById(n)), r || !t || d.isTraditionalAnchor(e) || (r = d.createStateObject(null, null, i))), r
                }, d.getIdByUrl = function(e) {
                    var n = d.urlToId[e] || d.store.urlToId[e] || t;
                    return n
                }, d.getLastSavedState = function() {
                    return d.savedStates[d.savedStates.length - 1] || t
                }, d.getLastStoredState = function() {
                    return d.storedStates[d.storedStates.length - 1] || t
                }, d.hasUrlDuplicate = function(e) {
                    var t, n = !1;
                    return t = d.extractState(e.url), n = t && t.id !== e.id
                }, d.storeState = function(e) {
                    return d.urlToId[e.url] = e.id, d.storedStates.push(d.cloneObject(e)), e
                }, d.isLastSavedState = function(e) {
                    var t, n, i, r = !1;
                    return d.savedStates.length && (t = e.id, n = d.getLastSavedState(), i = n.id, r = t === i), r
                }, d.saveState = function(e) {
                    return d.isLastSavedState(e) ? !1 : (d.savedStates.push(d.cloneObject(e)), !0)
                }, d.getStateByIndex = function(e) {
                    var t = null;
                    return t = "undefined" == typeof e ? d.savedStates[d.savedStates.length - 1] : 0 > e ? d.savedStates[d.savedStates.length + e] : d.savedStates[e]
                }, d.getCurrentIndex = function() {
                    var e = null;
                    return e = d.savedStates.length < 1 ? 0 : d.savedStates.length - 1
                }, d.getHash = function(e) {
                    var t, n = d.getLocationHref(e);
                    return t = d.getHashByUrl(n)
                }, d.unescapeHash = function(e) {
                    var t = d.normalizeHash(e);
                    return t = decodeURIComponent(t)
                }, d.normalizeHash = function(e) {
                    var t = e.replace(/[^#]*#/, "").replace(/#.*/, "");
                    return t
                }, d.setHash = function(e, t) {
                    var n, r;
                    return t !== !1 && d.busy() ? (d.pushQueue({
                        scope: d,
                        callback: d.setHash,
                        args: arguments,
                        queue: t
                    }), !1) : (d.busy(!0), n = d.extractState(e, !0), n && !d.emulated.pushState ? d.pushState(n.data, n.title, n.url, !1) : d.getHash() !== e && (d.bugs.setHash ? (r = d.getPageUrl(), d.pushState(null, null, r + "#" + e, !1)) : i.location.hash = e), d)
                }, d.escapeHash = function(t) {
                    var n = d.normalizeHash(t);
                    return n = e.encodeURIComponent(n), d.bugs.hashEscape || (n = n.replace(/\%21/g, "!").replace(/\%26/g, "&").replace(/\%3D/g, "=").replace(/\%3F/g, "?")), n
                }, d.getHashByUrl = function(e) {
                    var t = String(e).replace(/([^#]*)#?([^#]*)#?(.*)/, "$2");
                    return t = d.unescapeHash(t)
                }, d.setTitle = function(e) {
                    var t, n = e.title;
                    n || (t = d.getStateByIndex(0), t && t.url === e.url && (n = t.title || d.options.initialTitle));
                    try {
                        i.getElementsByTagName("title")[0].innerHTML = n.replace("<", "&lt;").replace(">", "&gt;").replace(" & ", " &amp; ")
                    } catch (r) {}
                    return i.title = n, d
                }, d.queues = [], d.busy = function(e) {
                    if ("undefined" != typeof e ? d.busy.flag = e : "undefined" == typeof d.busy.flag && (d.busy.flag = !1), !d.busy.flag) {
                        a(d.busy.timeout);
                        var t = function() {
                            var e, n, i;
                            if (!d.busy.flag)
                                for (e = d.queues.length - 1; e >= 0; --e) n = d.queues[e], 0 !== n.length && (i = n.shift(), d.fireQueueItem(i), d.busy.timeout = o(t, d.options.busyDelay))
                        };
                        d.busy.timeout = o(t, d.options.busyDelay)
                    }
                    return d.busy.flag
                }, d.busy.flag = !1, d.fireQueueItem = function(e) {
                    return e.callback.apply(e.scope || d, e.args || [])
                }, d.pushQueue = function(e) {
                    return d.queues[e.queue || 0] = d.queues[e.queue || 0] || [], d.queues[e.queue || 0].push(e), d
                }, d.queue = function(e, t) {
                    return "function" == typeof e && (e = {
                        callback: e
                    }), "undefined" != typeof t && (e.queue = t), d.busy() ? d.pushQueue(e) : d.fireQueueItem(e), d
                }, d.clearQueue = function() {
                    return d.busy.flag = !1, d.queues = [], d
                }, d.stateChanged = !1, d.doubleChecker = !1, d.doubleCheckComplete = function() {
                    return d.stateChanged = !0, d.doubleCheckClear(), d
                }, d.doubleCheckClear = function() {
                    return d.doubleChecker && (a(d.doubleChecker), d.doubleChecker = !1), d
                }, d.doubleCheck = function(e) {
                    return d.stateChanged = !1, d.doubleCheckClear(), d.bugs.ieDoubleCheck && (d.doubleChecker = o(function() {
                        return d.doubleCheckClear(), d.stateChanged || e(), !0
                    }, d.options.doubleCheckInterval)), d
                }, d.safariStatePoll = function() {
                    var t, n = d.extractState(d.getLocationHref());
                    if (!d.isLastSavedState(n)) return t = n, t || (t = d.createStateObject()), d.Adapter.trigger(e, "popstate"), d
                }, d.back = function(e) {
                    return e !== !1 && d.busy() ? (d.pushQueue({
                        scope: d,
                        callback: d.back,
                        args: arguments,
                        queue: e
                    }), !1) : (d.busy(!0), d.doubleCheck(function() {
                        d.back(!1)
                    }), p.go(-1), !0)
                }, d.forward = function(e) {
                    return e !== !1 && d.busy() ? (d.pushQueue({
                        scope: d,
                        callback: d.forward,
                        args: arguments,
                        queue: e
                    }), !1) : (d.busy(!0), d.doubleCheck(function() {
                        d.forward(!1)
                    }), p.go(1), !0)
                }, d.go = function(e, t) {
                    var n;
                    if (e > 0)
                        for (n = 1; e >= n; ++n) d.forward(t);
                    else {
                        if (!(0 > e)) throw new Error("History.go: History.go requires a positive or negative integer passed.");
                        for (n = -1; n >= e; --n) d.back(t)
                    }
                    return d
                }, d.emulated.pushState) {
                var f = function() {};
                d.pushState = d.pushState || f, d.replaceState = d.replaceState || f
            } else d.onPopState = function(t, n) {
                var i, r, s = !1,
                    o = !1;
                return d.doubleCheckComplete(), (i = d.getHash()) ? (r = d.extractState(i || d.getLocationHref(), !0), r ? d.replaceState(r.data, r.title, r.url, !1) : (d.Adapter.trigger(e, "anchorchange"), d.busy(!1)), d.expectedStateId = !1, !1) : (s = d.Adapter.extractEventData("state", t, n) || !1, o = s ? d.getStateById(s) : d.expectedStateId ? d.getStateById(d.expectedStateId) : d.extractState(d.getLocationHref()), o || (o = d.createStateObject(null, null, d.getLocationHref())), d.expectedStateId = !1, d.isLastSavedState(o) ? (d.busy(!1), !1) : (d.storeState(o), d.saveState(o), d.setTitle(o), d.Adapter.trigger(e, "statechange"), d.busy(!1), !0))
            }, d.Adapter.bind(e, "popstate", d.onPopState), d.pushState = function(t, n, i, r) {
                //if (d.getHashByUrl(i) && d.emulated.pushState) throw new Error("History.js does not support states with fragement-identifiers (hashes/anchors).");
                if (d.getHashByUrl(i) && d.emulated.pushState) { return;};
                if (r !== !1 && d.busy()) return d.pushQueue({
                    scope: d,
                    callback: d.pushState,
                    args: arguments,
                    queue: r
                }), !1;
                d.busy(!0);
                var s = d.createStateObject(t, n, i);
                return d.isLastSavedState(s) ? d.busy(!1) : (d.storeState(s), d.expectedStateId = s.id, p.pushState(s.id, s.title, s.url), d.Adapter.trigger(e, "popstate")), !0
            }, d.replaceState = function(t, n, i, r) {
               // if (d.getHashByUrl(i) && d.emulated.pushState) throw new Error("History.js does not support states with fragement-identifiers (hashes/anchors).");
               if (d.getHashByUrl(i) && d.emulated.pushState) { return;};
                if (r !== !1 && d.busy()) return d.pushQueue({
                    scope: d,
                    callback: d.replaceState,
                    args: arguments,
                    queue: r
                }), !1;
                d.busy(!0);
                var s = d.createStateObject(t, n, i);
                return d.isLastSavedState(s) ? d.busy(!1) : (d.storeState(s), d.expectedStateId = s.id, p.replaceState(s.id, s.title, s.url), d.Adapter.trigger(e, "popstate")), !0
            };
            if (s) {
                try {
                    d.store = u.parse(s.getItem("History.store")) || {}
                } catch (m) {
                    d.store = {}
                }
                d.normalizeStore()
            } else d.store = {}, d.normalizeStore();
            d.Adapter.bind(e, "unload", d.clearAllIntervals), d.saveState(d.storeState(d.extractState(d.getLocationHref(), !0))), s && (d.onUnload = function() {
                var e, t, n;
                try {
                    e = u.parse(s.getItem("History.store")) || {}
                } catch (i) {
                    e = {}
                }
                e.idToState = e.idToState || {}, e.urlToId = e.urlToId || {}, e.stateToId = e.stateToId || {};
                for (t in d.idToState) d.idToState.hasOwnProperty(t) && (e.idToState[t] = d.idToState[t]);
                for (t in d.urlToId) d.urlToId.hasOwnProperty(t) && (e.urlToId[t] = d.urlToId[t]);
                for (t in d.stateToId) d.stateToId.hasOwnProperty(t) && (e.stateToId[t] = d.stateToId[t]);
                d.store = e, d.normalizeStore(), n = u.stringify(e);
                try {
                    s.setItem("History.store", n)
                } catch (r) {
                    if (r.code !== DOMException.QUOTA_EXCEEDED_ERR) throw r;
                    s.length && (s.removeItem("History.store"), s.setItem("History.store", n))
                }
            }, d.intervalList.push(l(d.onUnload, d.options.storeInterval)), d.Adapter.bind(e, "beforeunload", d.onUnload), d.Adapter.bind(e, "unload", d.onUnload)), d.emulated.pushState || (d.bugs.safariPoll && d.intervalList.push(l(d.safariStatePoll, d.options.safariPollInterval)), ("Apple Computer, Inc." === r.vendor || "Mozilla" === (r.appCodeName || "")) && (d.Adapter.bind(e, "hashchange", function() {
                d.Adapter.trigger(e, "popstate")
            }), d.getHash() && d.Adapter.onDomLoad(function() {
                d.Adapter.trigger(e, "hashchange")
            })))
        }, d.options && d.options.delayInit || d.init()
    }(window), define("history", ["jquery"], function(e) {
        return function() {
            var t;
            return t || e.History
        }
    }(this)), define("modules/mod-search-results", ["jquery", "underscore", "hbs.compiled", "modernizr", "history"], function(e, t, n, i, r) {
        function s() {
            var e = g("keyword");
            "true" === g("search") && e && (o(!0), A.keyword = e, j.val(e), A.pagenumber = g("pagenumber") ? parseInt(g("pagenumber"), 10) : A.pagenumber, A.filter = g("filter") ? g("filter") : A.filter, A.sort = g("sort") ? g("sort") : A.sort, m(), a())
        }

        function o(t) {
            "undefined" == typeof t ? e("html, body").toggleClass("js-active-search") : e("html, body").toggleClass("js-active-search", t), e("body").hasClass("js-active-search") ? (x = !0, j.focus(), 0 === O.filter(".active").length && "all" === S && O.first().addClass("active")) : (l(), x = !1, j.blur())
        }

        function a() {
            var t = "/wp-content/themes/roots-sass-master/search-results.php",
                n = !1;
            v && 4 !== v.readystate && (v.abort(), n = !0), R.show(), z.hide(), q.hide(), v = e.ajax({
                url: t,
                method: "GET",
                dataType: "json",
                data: A,
                success: function(t) {
                    return C = t, 0 === C.total_count ? (R.hide(), z.hide(), q.show(), l(), q.html("No results found"), void q.addClass("error")) : ("features" === A.filter ? (H.text(C.features_count), T = C.features_count) : "winners" === A.filter ? (H.text(C.winners_count), T = C.winners_count) : (H.text(C.total_count), T = C.total_count), h(), d(), c(), p(A.filter), f(A.sort), r.replaceState(null, null, "?search=true&" + e.param(A)), R.hide(), z.show(), void q.hide())
                },
                error: function(e) {
                    R.hide(), z.hide(), q.show(), n !== !0 && (console.log(e), C.error = !0, C.message = "", c())
                }
            })
        }

        function l() {
            C = {}, _ = 1, A.pagenumber = _, H.text("0"), O.filter(".disabled").removeClass("disabled"), p(S), f(k), c(), r.replaceState(null, null, y), q.html("Type to search what you're looking for"), q.removeClass("error"), q.show(), R.hide(), z.hide()
        }

        function c() {
            var t = E(C);
            F.toggleClass("active"), F.filter(".active").html(t), u(), e(".search-result__text", P).matchHeight(), "undefined" == typeof C.total_count || 0 === C.total_count ? P.removeClass("has-results") : (P.addClass("has-results"), e(".search-result__image > a > img").on("load", function() {
                e(this).parents(".search-result__image > a").children(".search-preloader").remove()
            }))
        }

        function u() {
            F.css({
                "max-height": ""
            });
            var e = F.filter(".active").outerHeight();
            F.css({
                "max-height": e
            })
        }

        function h() {
            if (!(C.total_count <= w)) {
                var e = Math.ceil(T / w),
                    t = 1,
                    n = 5;
                _ = A.pagenumber, _ > 1 && (C.show_prev = !0), e > _ && (C.show_next = !0), e - _ >= 5 && (C.show_dots = !0, n = _ + 2), e > 5 && e - _ >= 5 ? t = _ : (t = e - 4, 1 > t && (t = 1), n = e), C.pagination = {
                    pages: []
                };
                for (var i = 1; e >= i; i++)
                    if (i === e || !(t > i || i > n)) {
                        var r = [];
                        _ === i && (r.active = !0), i === e && (r.last = !0), r.page = i, C.pagination.pages.push(r)
                    }
            }
        }

        function d() {
            var t = C.unique_types;
            O.each(function() {
                var n = e(this).data("filter"),
                    i = !0;
                if ("all" !== n) {
                    for (var r = 0; r < t.length; r++) n === t[r] && (i = !1, r = t.length);
                    i ? (e(this).addClass("disabled"), e(this).hasClass("active") && (e(this).removeClass("active"), p(S), a())) : e(this).removeClass("disabled")
                }
            })
        }

        function p(e) {
            var t = O.filter("[data-filter=" + e + "]");
            t && !t.hasClass("disabled") && (O.filter(".active").removeClass("active"), t.addClass("active"), A.filter = e)
        }

        function f(e) {
            var t = M.filter("[data-sort=" + e + "]");
            t.length && !t.hasClass("active") && (M.filter(".active").removeClass("active"), t.addClass("active"), A.sort = e, m())
        }

        function m() {
            var e = M.filter("[data-sort=" + A.sort + "]");
            D.removeClass("open"), e.length && N.text(e.text())
        }

        function g(e) {
            e = e.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var t = new RegExp("[\\?&]" + e + "=([^&#]*)"),
                n = t.exec(location.search);
            return null === n ? "" : decodeURIComponent(n[1].replace(/\+/g, " "))
        }
        var v, y = window.location.pathname,
            w = 56,
            b = 150,
            _ = 1,
            x = !1,
            S = "all",
            k = "date",
            C = {},
            T = 0,
            E = n["search-results/search-results"],
            $ = t.debounce(a, b),
            A = {
                keyword: "",
                pagenumber: 1,
                filter: S,
                sort: k
            },
            P = e(".mod-search-results"),
            j = e(".mod-search-results__field", P),
            I = e(".mod-search-results__form", P),
            F = e(".mod-search-results__results-container", P),
            H = e(".mod-search-results__count", P),
            O = e(".mod-search-results__filter", P),
            D = e(".mod-search-results__sort", P),
            L = e(".mod-search-results__sort-selection", P),
            N = e(".mod-search-results__sort-selection__selected", L),
            M = e(".mod-search-results__sort-item", P),
            R = e(".mod-search-loading", P),
            z = e(".mod-search-results__filters", P),
            q = e(".mod-search-results__info", P);
        s(), e(document).on("render", function(t, n) {
            var i = e(".js-toggle-search", n);
            i = i, e(document).on("click", ".js-toggle-search", function(e) {
                e.preventDefault(), j.val(""), l(), o()
            }), O.on("click", function(t) {
                return e(this).hasClass("active") || e(this).hasClass("disabled") ? !1 : (t.preventDefault(), p(e(this).data("filter")), A.pagenumber = 1, void a())
            }), L.on("click", function(e) {
                e.preventDefault(), D.toggleClass("open")
            }), M.on("click", function(t) {
                return e(this).hasClass("active") ? void m() : (t.preventDefault(), f(e(this).data("sort")), A.pagenumber = 1, void a())
            }), P.on("click", ".js-pagination-page", function(t) {
                e(this).parent().hasClass("pagination__item--active") || (t.preventDefault(), _ = parseInt(e(this).text(), 10), A.pagenumber = _, P.animate({
                    scrollTop: 0
                }, 300), a())
            }), P.on("click", ".js-pagination-direction", function(t) {
                t.preventDefault(), P.animate({
                    scrollTop: 0
                }, 300), e(this).parent().hasClass("pagination__item--left") ? (A.pagenumber = --_, a()) : (A.pagenumber = ++_, a())
            })
        }), e(document).on("keydown", function(e) {
            i.touch || 27 === e.which && o(!1)
        }), e(document).on("keypress", function(t) {
            if (!i.touch) {
                if (x && 27 === t.which) return void o(!1);
                if (!(x || e(t.target).is("input, textarea, select, option") || t.metaKey)) {
                    var n = new RegExp("^[a-zA-Z0-9]+$"),
                        r = String.fromCharCode(t.charCode ? t.charCode : t.which);
                    n.test(r) && (j.val(r), o(!0), t.preventDefault())
                }
            }
        }), e(document).on("keyup", function() {
            if (!i.touch) {
                var e = j.val();
                if (A.keyword !== e && A.keyword !== undefined) return A.keyword = e, A.pagenumber = 1, (A.keyword!=undefined && A.keyword.length) < 3 ? void l() : void $()
            }
        }), I.on("submit", function(e) {
            if (e.preventDefault(), i.touch) {
                var t = j.val();
                j.blur(), A.keyword !== t && (A.keyword = t, a())
            }
        }), e(window).on("resize.search-container", u)
    }), define("modules/mod-special-achievement", ["jquery", "matchHeight"], function(e) {
        e(document).on("render", function(t, n) {
            e(".mod-special-achievements__details", n).matchHeight()
        })
    }),
    function() {
        var e = [].indexOf || function(e) {
                for (var t = 0, n = this.length; n > t; t++)
                    if (t in this && this[t] === e) return t;
                return -1
            },
            t = [].slice;
        ! function(e, t) {
            return "function" == typeof define && define.amd ? define("waypoints", ["jquery"], function(n) {
                return t(n, e)
            }) : t(e.jQuery, e)
        }(window, function(n, i) {
            var r, s, o, a, l, c, u, h, d, p, f, m, g, v, y, w;
            return r = n(i), h = e.call(i, "ontouchstart") >= 0, a = {
                horizontal: {},
                vertical: {}
            }, l = 1, u = {}, c = "waypoints-context-id", f = "resize.waypoints", m = "scroll.waypoints", g = 1, v = "waypoints-waypoint-ids", y = "waypoint", w = "waypoints", s = function() {
                function e(e) {
                    var t = this;
                    this.$element = e, this.element = e[0], this.didResize = !1, this.didScroll = !1, this.id = "context" + l++, this.oldScroll = {
                        x: e.scrollLeft(),
                        y: e.scrollTop()
                    }, this.waypoints = {
                        horizontal: {},
                        vertical: {}
                    }, this.element[c] = this.id, u[this.id] = this, e.bind(m, function() {
                        var e;
                        return t.didScroll || h ? void 0 : (t.didScroll = !0, e = function() {
                            return t.doScroll(), t.didScroll = !1
                        }, i.setTimeout(e, n[w].settings.scrollThrottle))
                    }), e.bind(f, function() {
                        var e;
                        return t.didResize ? void 0 : (t.didResize = !0, e = function() {
                            return n[w]("refresh"), t.didResize = !1
                        }, i.setTimeout(e, n[w].settings.resizeThrottle))
                    })
                }
                return e.prototype.doScroll = function() {
                    var e, t = this;
                    return e = {
                        horizontal: {
                            newScroll: this.$element.scrollLeft(),
                            oldScroll: this.oldScroll.x,
                            forward: "right",
                            backward: "left"
                        },
                        vertical: {
                            newScroll: this.$element.scrollTop(),
                            oldScroll: this.oldScroll.y,
                            forward: "down",
                            backward: "up"
                        }
                    }, !h || e.vertical.oldScroll && e.vertical.newScroll || n[w]("refresh"), n.each(e, function(e, i) {
                        var r, s, o;
                        return o = [], s = i.newScroll > i.oldScroll, r = s ? i.forward : i.backward, n.each(t.waypoints[e], function(e, t) {
                            var n, r;
                            return i.oldScroll < (n = t.offset) && n <= i.newScroll ? o.push(t) : i.newScroll < (r = t.offset) && r <= i.oldScroll ? o.push(t) : void 0
                        }), o.sort(function(e, t) {
                            return e.offset - t.offset
                        }), s || o.reverse(), n.each(o, function(e, t) {
                            return t.options.continuous || e === o.length - 1 ? t.trigger([r]) : void 0
                        })
                    }), this.oldScroll = {
                        x: e.horizontal.newScroll,
                        y: e.vertical.newScroll
                    }
                }, e.prototype.refresh = function() {
                    var e, t, i, r = this;
                    return i = n.isWindow(this.element), t = this.$element.offset(), this.doScroll(), e = {
                        horizontal: {
                            contextOffset: i ? 0 : t.left,
                            contextScroll: i ? 0 : this.oldScroll.x,
                            contextDimension: this.$element.width(),
                            oldScroll: this.oldScroll.x,
                            forward: "right",
                            backward: "left",
                            offsetProp: "left"
                        },
                        vertical: {
                            contextOffset: i ? 0 : t.top,
                            contextScroll: i ? 0 : this.oldScroll.y,
                            contextDimension: i ? n[w]("viewportHeight") : this.$element.height(),
                            oldScroll: this.oldScroll.y,
                            forward: "down",
                            backward: "up",
                            offsetProp: "top"
                        }
                    }, n.each(e, function(e, t) {
                        return n.each(r.waypoints[e], function(e, i) {
                            var r, s, o, a, l;
                            return r = i.options.offset, o = i.offset, s = n.isWindow(i.element) ? 0 : i.$element.offset()[t.offsetProp], n.isFunction(r) ? r = r.apply(i.element) : "string" == typeof r && (r = parseFloat(r), i.options.offset.indexOf("%") > -1 && (r = Math.ceil(t.contextDimension * r / 100))), i.offset = s - t.contextOffset + t.contextScroll - r, i.options.onlyOnScroll && null != o || !i.enabled ? void 0 : null !== o && o < (a = t.oldScroll) && a <= i.offset ? i.trigger([t.backward]) : null !== o && o > (l = t.oldScroll) && l >= i.offset ? i.trigger([t.forward]) : null === o && t.oldScroll >= i.offset ? i.trigger([t.forward]) : void 0
                        })
                    })
                }, e.prototype.checkEmpty = function() {
                    return n.isEmptyObject(this.waypoints.horizontal) && n.isEmptyObject(this.waypoints.vertical) ? (this.$element.unbind([f, m].join(" ")), delete u[this.id]) : void 0
                }, e
            }(), o = function() {
                function e(e, t, i) {
                    var r, s;
                    "bottom-in-view" === i.offset && (i.offset = function() {
                        var e;
                        return e = n[w]("viewportHeight"), n.isWindow(t.element) || (e = t.$element.height()), e - n(this).outerHeight()
                    }), this.$element = e, this.element = e[0], this.axis = i.horizontal ? "horizontal" : "vertical", this.callback = i.handler, this.context = t, this.enabled = i.enabled, this.id = "waypoints" + g++, this.offset = null, this.options = i, t.waypoints[this.axis][this.id] = this, a[this.axis][this.id] = this, r = null != (s = this.element[v]) ? s : [], r.push(this.id), this.element[v] = r
                }
                return e.prototype.trigger = function(e) {
                    return this.enabled ? (null != this.callback && this.callback.apply(this.element, e), this.options.triggerOnce ? this.destroy() : void 0) : void 0
                }, e.prototype.disable = function() {
                    return this.enabled = !1
                }, e.prototype.enable = function() {
                    return this.context.refresh(), this.enabled = !0
                }, e.prototype.destroy = function() {
                    return delete a[this.axis][this.id], delete this.context.waypoints[this.axis][this.id], this.context.checkEmpty()
                }, e.getWaypointsByElement = function(e) {
                    var t, i;
                    return (i = e[v]) ? (t = n.extend({}, a.horizontal, a.vertical), n.map(i, function(e) {
                        return t[e]
                    })) : []
                }, e
            }(), p = {
                init: function(e, t) {
                    var i;
                    return t = n.extend({}, n.fn[y].defaults, t), null == (i = t.handler) && (t.handler = e), this.each(function() {
                        var e, i, r, a;
                        return e = n(this), r = null != (a = t.context) ? a : n.fn[y].defaults.context, n.isWindow(r) || (r = e.closest(r)), r = n(r), i = u[r[0][c]], i || (i = new s(r)), new o(e, i, t)
                    }), n[w]("refresh"), this
                },
                disable: function() {
                    return p._invoke.call(this, "disable")
                },
                enable: function() {
                    return p._invoke.call(this, "enable")
                },
                destroy: function() {
                    return p._invoke.call(this, "destroy")
                },
                prev: function(e, t) {
                    return p._traverse.call(this, e, t, function(e, t, n) {
                        return t > 0 ? e.push(n[t - 1]) : void 0
                    })
                },
                next: function(e, t) {
                    return p._traverse.call(this, e, t, function(e, t, n) {
                        return t < n.length - 1 ? e.push(n[t + 1]) : void 0
                    })
                },
                _traverse: function(e, t, r) {
                    var s, o;
                    return null == e && (e = "vertical"), null == t && (t = i), o = d.aggregate(t), s = [], this.each(function() {
                        var t;
                        return t = n.inArray(this, o[e]), r(s, t, o[e])
                    }), this.pushStack(s)
                },
                _invoke: function(e) {
                    return this.each(function() {
                        var t;
                        return t = o.getWaypointsByElement(this), n.each(t, function(t, n) {
                            return n[e](), !0
                        })
                    }), this
                }
            }, n.fn[y] = function() {
                var e, i;
                return i = arguments[0], e = 2 <= arguments.length ? t.call(arguments, 1) : [], p[i] ? p[i].apply(this, e) : n.isFunction(i) ? p.init.apply(this, arguments) : n.isPlainObject(i) ? p.init.apply(this, [null, i]) : n.error(i ? "The " + i + " method does not exist in jQuery Waypoints." : "jQuery Waypoints needs a callback function or handler option.")
            }, n.fn[y].defaults = {
                context: i,
                continuous: !0,
                enabled: !0,
                horizontal: !1,
                offset: 0,
                triggerOnce: !1
            }, d = {
                refresh: function() {
                    return n.each(u, function(e, t) {
                        return t.refresh()
                    })
                },
                viewportHeight: function() {
                    var e;
                    return null != (e = i.innerHeight) ? e : r.height()
                },
                aggregate: function(e) {
                    var t, i, r;
                    return t = a, e && (t = null != (r = u[n(e)[0][c]]) ? r.waypoints : void 0), t ? (i = {
                        horizontal: [],
                        vertical: []
                    }, n.each(i, function(e, r) {
                        return n.each(t[e], function(e, t) {
                            return r.push(t)
                        }), r.sort(function(e, t) {
                            return e.offset - t.offset
                        }), i[e] = n.map(r, function(e) {
                            return e.element
                        }), i[e] = n.unique(i[e])
                    }), i) : []
                },
                above: function(e) {
                    return null == e && (e = i), d._filter(e, "vertical", function(e, t) {
                        return t.offset <= e.oldScroll.y
                    })
                },
                below: function(e) {
                    return null == e && (e = i), d._filter(e, "vertical", function(e, t) {
                        return t.offset > e.oldScroll.y
                    })
                },
                left: function(e) {
                    return null == e && (e = i), d._filter(e, "horizontal", function(e, t) {
                        return t.offset <= e.oldScroll.x
                    })
                },
                right: function(e) {
                    return null == e && (e = i), d._filter(e, "horizontal", function(e, t) {
                        return t.offset > e.oldScroll.x
                    })
                },
                enable: function() {
                    return d._invoke("enable")
                },
                disable: function() {
                    return d._invoke("disable")
                },
                destroy: function() {
                    return d._invoke("destroy")
                },
                extendFn: function(e, t) {
                    return p[e] = t
                },
                _invoke: function(e) {
                    var t;
                    return t = n.extend({}, a.vertical, a.horizontal), n.each(t, function(t, n) {
                        return n[e](), !0
                    })
                },
                _filter: function(e, t, i) {
                    var r, s;
                    return (r = u[n(e)[0][c]]) ? (s = [], n.each(r.waypoints[t], function(e, t) {
                        return i(r, t) ? s.push(t) : void 0
                    }), s.sort(function(e, t) {
                        return e.offset - t.offset
                    }), n.map(s, function(e) {
                        return e.element
                    })) : []
                }
            }, n[w] = function() {
                var e, n;
                return n = arguments[0], e = 2 <= arguments.length ? t.call(arguments, 1) : [], d[n] ? d[n].apply(null, e) : d.aggregate.call(null, n)
            }, n[w].settings = {
                resizeThrottle: 100,
                scrollThrottle: 30
            }, r.on("load.waypoints", function() {
                return n[w]("refresh")
            })
        })
    }.call(this), define("modules/mod-winners-nav", ["jquery", "underscore", "modernizr", "unison", "waypoints"], function(e, t, n, i) {
        {
            var r = ({
                    "w-year": e.trim(e(".mod-winners-nav__selected").text()),
                    "w-category": "all"
                }, {
                    year: {},
                    category: {}
                }),
                s = [".mod-winners-nav", ".onScrollContainer"],
                o = ["year", "category"];
            e(".mod-winners-nav__results")
        }
        e(".mod-winners-nav__list").each(function() {
            var t = e(this).hasClass("mod-winners-nav__list--year") ? "year" : "category";
            r[t] = {}, e("[data-" + t + "]", this).each(function() {
                var n = e(this).data(t) + "";
                if (r[t][n] = {}, e(this).parents(".mod-winners-nav__submenu").length) {
                    var i = e(this).parents(".mod-winners-nav__item--category"),
                        s = i.find(".mod-winners-nav__link--dropdown");
                    r[t][n].parentCategory = s.text(), r[t][n].value = e(this).text()
                } else r[t][n].value = e(this).text(), r[t][n].parentCategory = ""
            })
        }), t.each(s, function(r) {
            function s() {
                e(r + "__item, " + r + "__overlay").removeClass("active")
            }
            var a = e(r),
                l = e(r + "__overlay");
            e(r + "__overlay-close").on("click", function(e) {
                e.preventDefault(), s()
            }), e(r + " .mod-winners-nav__link").on("click", function() {
                e(this).hasClass("mod-winners-nav__link--dropdown") || s()
            }), e(document).on("keyup", function(e) {
                27 === e.keyCode && "desktop" !== i.fetch.now().name && s()
            }), e(r + "__link--dropdown").on("click", function(t) {
                t.preventDefault();
                var n = e(this).parents(r + "__item");
                n.toggleClass("active").siblings().removeClass("active"), e(r + "__overlay").scrollTop(n.position().top + e(r + "__overlay.active").scrollTop() - 10)
            }), t.each(o, function(t) {
                function s(t) {
                    if (i.fetch.now()!=null && (clearTimeout(o), "desktop" === i.fetch.now().name)) {
                        var n = t.toElement || t.relatedTarget,
                            s = e(n);
                        s.closest(r).length ? o = setTimeout(function() {
                            d.removeClass("active")
                        }, 500) : d.removeClass("active")
                    }
                }
                var o, c = r + "__filter--" + t,
                    u = r + "__overlay--" + t,
                    h = e(c),
                    d = e(u);
                n.touch || (h.on("mouseover", function() {
                    clearTimeout(o), "desktop" === i.fetch.now().name && (l.removeClass("active"), d.addClass("active"))
                }).on("mouseleave", s), d.on("mouseover", function() {
                    clearTimeout(o)
                }).on("mouseleave", s), a.on("mouseleave", s)), h.on("click", function(n) {
                    n.preventDefault(), "desktop" === i.fetch.now().name ? d.addClass("active") : e(".mod-winners-nav__overlay--" + t).addClass("active")
                }), e(r + "__link[data-" + t + "]").on("click", function(e) {})
            })
        }), e(".mod-winners-nav").waypoint(function(t) {
            e(".onScrollContainer").toggleClass("onScrollContainer--active", "down" === t)
        }, {
            offset: function() {
                return -e(this).height() + 38
            }
        }), e("body.single-winner .site-header").waypoint(function(t) {
            e(".onScrollContainer").toggleClass("onScrollContainer--active", "down" === t)
        }, {
            offset: function() {
                return -e(this).height()
            }
        })
    }), define("modules/mod-5-word-speech", ["jquery"], function(e) {
        var t = e(".mod-5-word-speeches__refresh"),
            n = t.data("id"),
            i = t.data("year");
        e(".mod-5-word-speeches__refresh").click(function(r) {
            r.preventDefault(), e.ajax({
                url: "/wp-admin/admin-ajax.php",
                method: "GET",
                data: "action=ajax_5_word_speeches&currentId=" + n + "&pageId=" + t.data("page-id") + "&year=" + i,
                dataType: "json",
                success: function(i) {
                    t.attr("data-id", i.id), e(".mod-5-word-speeches__headline").html(i.content), e(".mod-5-word-speeches__author a").text(i.author), e(".mod-5-word-speeches__author a").attr("href", i.author_url), e(".mod-5-word-speeches__category a").text(i.category), e(".mod-5-word-speeches__category a").attr("href", i.category_url), n = i.id
                }
            })
        })
    }), define("modules/social-feed", ["jquery", "carousel"], function(e, t) {
        SocialFeedWidget = {
            results: "",
            fbloaded: !1,
            twloaded: !1,
            instaloaded: !1,
            init: function() {
                var t = this;
                e(window).load(function() {
                    t.onLoad()
                })
            },
            onLoad: function() {
                this.loadFeed("feed_twitter"), this.loadFeed("feed_instagram"), this.loadFeed("feed_facebook")
            },
            renderFeed: function() {
                if (this.fbloaded && this.twloaded && this.instaloaded) {
                    e(".mod-social-feed .mod-social-feed__content ul.mod-social-feed__carousel").append(this.results);
                    var n = e(".mod-social-feed ul.mod-social-feed__carousel"),
                        i = n.children("li");
                    i.sort(function(t, n) {
                        return e(n).data("sort") - e(t).data("sort")
                    }), i.detach().appendTo(n), e(".mod-social-feed").addClass("js-carousel"), new t(e(".mod-social-feed"))
                }
            },
            loadFeed: function(t) {
                e.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    data: "action=" + t,
                    type: "GET",
                    success: function(e) {
                        switch (SocialFeedWidget.results += e, t) {
                            case "feed_facebook":
                                SocialFeedWidget.fbloaded = !0;
                                break;
                            case "feed_twitter":
                                SocialFeedWidget.twloaded = !0;
                                break;
                            case "feed_instagram":
                                SocialFeedWidget.instaloaded = !0
                        }
                        SocialFeedWidget.renderFeed()
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            }
        }, SocialFeedWidget.init()
    }), define("modules/mod-winners-gallery", ["jquery", "underscore", "unison"], function(e, t, n) {
        function i() {
            var t = e(window).scrollTop(),
                n = e(window).height(),
                i = 0,
                o = "";
            if (e(".onScrollContainer").length && (i = e(".onScrollContainer").height()), n += n / 2, e(".mod-winners-gallery").each(function() {
                    if (e(this).offset().top < t + n && e(this).offset().top > t - n || e(this).offset().bottom > t + (i + 100) && e(this).offset().bottom < t - (i + 100)) {
                        if (o = "/wp-admin/admin-ajax.php?action=ajax_winners&slug=" + e(this).data("slug") + "&year=" + e(this).data("year"), !e(this).hasClass("fetching") && !e(this).hasClass("loading") && !e(this).hasClass("loaded")) {
                            e(this).addClass("fetching");
                            var l = e(this);
                            e.getJSON(o, function(t) {
                                var n, i = t.winner.length,
                                    o = t.nominee.length,
                                    a = t.honoree.length,
                                    c = t.facebook_url,
                                    u = t.twitter_url,
                                    h = "",
                                    d = "",
                                    p = "";
                                if (l.removeClass("fetching"), l.addClass("loading"), i > 0) {
                                    for (n = 0; i > n; n++) {
                                        var f = e(".mod-winners-gallery__winner-list", l).children().eq(n),
                                            m = t.winner[n];
                                        m.ww && !m.pv ? (h = "Webby Winner", d = "webby-winner") : !m.ww && m.pv ? (h = "People's Voice", d = "peoples-voice") : (h = "Webby Winner + People's Voice", d = "webby-winner-peoples-voice"), e(".badge", f).addClass("badge--" + d), e("." + d + "-" + l.data("year")).length && (p = e("." + d + "-" + l.data("year")).val(), e(".badge", f).css("background-image", 'url("' + p + '")')), e(".mod-winners-gallery__subhead", f).text(h), "Entrant" !== m.organization && "" !== m.organization ? e(".mod-winners-gallery__credits", f).text(m.organization) : e(".mod-winners-gallery__credits", f).remove(), e(".mod-winners-gallery__title > a", f).attr("href", m.permalink).text(m.name), null === m.image && (m.image = "/wp-content/themes/roots-sass-master/assets/images/temp/winners-detail-no-image_620x317.jpg"), e(".mod-winners-gallery__image > a", f).attr("href", m.permalink), e(".mod-winners-gallery__image > a", f).css("background-image", "url(" + m.image + ")"), e(".mod-winners-gallery__image > a > img", f).attr("src", m.image).load(r)
                                    }
                                    e(".mod-winners-gallery__winner-list", l).removeClass("preloader"), e(".mod-winners-gallery__winner-list", l).children(".preloader-overlay").remove()
                                }
                                if (o > 0) {
                                    var g = o;
                                    for (i > 0 && (g = 4), i > 1 && (g = 3), n = 0; g > n && o > n; n++) {
                                        var v = e(".mod-winners-gallery__nominee-list", l).children().eq(n),
                                            y = t.nominee[n];
                                        "Entrant" !== y.organization && "" !== y.organization ? e(".mod-winners-gallery__credits", v).text(y.organization) : e(".mod-winners-gallery__credits", v).remove(), e(".mod-winners-gallery__title > a", v).attr("href", y.permalink).text(y.name), null === y.image && (y.image = "/wp-content/themes/roots-sass-master/assets/images/temp/winners-detail-no-image_620x317.jpg"), e(".mod-winners-gallery__image > a", v).attr("href", y.permalink), e(".mod-winners-gallery__image > a", v).css("background-image", "url(" + y.image + ")"), e(".mod-winners-gallery__image > a > img", v).attr("src", y.image).load(s)
                                    }
                                    e(".mod-winners-gallery__nominee-list", l).removeClass("preloader"), e(".mod-winners-gallery__nominee-list", l).children(".preloader-overlay").remove()
                                }
                                if (a > 0)
                                    for (n = 0; a > n; n++) {
                                        var w = t.honoree[n];
                                        e(".mod-winners-gallery__honorees-list", l).append('<li class="mod-winners-gallery__honorees-item"><h4 class="mod-winners-gallery__honorees-name"><a href="' + w.permalink + '">' + w.name + '</a></h4><div class="mod-winners-gallery__honorees-organization"><a href="' + w.permalink + '">' + w.organization + "</a></div></li>")
                                    }
                                e(".group-share__item--facebook", l).html('<div class="fb-share-button" data-href="' + c + '" data-layout="button"></div>'), e(".fb-share-popup", l).attr("href", "https://www.facebook.com/sharer.php?u=" + c), l.find(".share-popup").eq(0).attr("href", "https://www.facebook.com/sharer.php?u=" + c), e(".group-share__item--twitter", l).html(""), e(".group-share__item--twitter", l).html('<a href="https://twitter.com/share?url=' + u + '&via=TheWebbyAwards" class="twitter-share-button" data-count="none">Tweet</a>'), e(".twitter-share-popup", l).attr("href", "https://twitter.com/share?url=" + u + "&via=TheWebbyAwards"), l.find(".share-popup").eq(1).attr("href", "https://twitter.com/share?url=" + u + "&via=TheWebbyAwards"), l.removeClass("loading"), l.addClass("loaded")
                            })
                        }
                        if ("all" !== a && e(this).offset().top < t + i && e(this).offset().top > t - i) {
                            var c = e(location).attr("href"),
                                u = e(this).data("url"),
                                h = e(this).data("slug");
                            if (c !== u && c !== u + "/") {
                                var d = document.title.split("|"),
                                    p = e(".mod-winners-gallery__sub-category", e(this)).text() + " | " + d[1];
                                document.title = p, window.history.pushState("", p, u), e(".mod-winners-nav__link-all").attr("href", "/winners/all/" + h.replace(/\./g, "/") + "/");
                                var f = e(".mod-winners-gallery__category", e(this)).text();
                                "Web" === f && (f = "Websites"), e(".onScrollContainer__category").text(f), e(".onScrollContainer__selected--category span").text(e(".mod-winners-gallery__sub-category", e(this)).text())
                            }
                        }
                    }
                }), "all" !== a && e("body").hasClass("post-type-archive-winner")) {
                var l = "",
                    c = "",
                    u = "",
                    h = e(location).attr("href"),
                    d = e("#special-achievements");
                d.length && d.offset().top < t + i && d.offset().top > t - i && (l = d.data("url"), h !== l && (u = document.title.split("|"), c = d.data("title") + " | " + u[1], document.title = c, window.history.pushState("", c, l), e(".mod-winners-nav__link-all").attr("href", "/winners/all/special-achievement/"), e(".onScrollContainer__category").text(""), e(".onScrollContainer__category").text(""), e(".onScrollContainer__selected--category span").text("Special Achievement"), e(".onScrollContainer__selected--category span").text("Special Achievement")));
                var p = e("#all-categories");
                p.length && p.offset().top >= t - i && (l = p.data("url"), h !== l && (u = document.title.split("|"), c = p.data("title") + " | " + u[1], document.title = c, window.history.pushState("", c, l), e(".onScrollContainer__selected--category span").text("All")))
            }
        }

        function r() {
            e(this).parents(".mod-winners-gallery__image").removeClass("preloader"), e(this).parents(".mod-winners-gallery__image").children("preloader-overlay").remove(), c === e(window).scrollTop() && "Special Achievement" !== e(".onScrollContainer__selected--category span").text() && (e(window).scrollTop(e('.mod-winners-gallery[data-slug="' + l + '"]').position().top - (e(".onScrollContainer").height() - 5)), c = e(window).scrollTop())
        }

        function s() {
            e(this).parents(".mod-winners-gallery__image").removeClass("preloader"), e(this).parents(".mod-winners-gallery__image").children(".preloader-overlay").remove(), c === e(window).scrollTop() && "Special Achievement" !== e(".onScrollContainer__selected--category span").text() && (e(window).scrollTop(e('.mod-winners-gallery[data-slug="' + l + '"]').position().top - (e(".onScrollContainer").height() - 5)), c = e(window).scrollTop())
        }

        function o() {
            var t = 0,
                n = e(location).attr("href"),
                i = 0;
            6 === n.split("/").length && (e(".onScrollContainer").length && (t = e(".onScrollContainer").height(), t -= 5), e(".mod-year-overview__info").length ? (i = e(".mod-year-overview").offset().top, i > 0 && (e(window).scrollTop(i - t), e(".onScrollContainer").addClass("onScrollContainer--active"))) : (i = e(".mod-winners-nav").offset().top, i > 0 && (e(window).scrollTop(i), e(".mod-year-overview").addClass("hidden"))))
        }
        if (e(".mod-winners-nav").length || e(".onScrollContainer").length) {
            e(".mod-winners-nav__results").on("click", ".js-mod-winners-gallery__view-all", function(t) {
                t.preventDefault(), e(this).toggleClass("active"), e(this).parents(".mod-winners-gallery").find(".mod-winners-gallery__honorees").fadeToggle("fast")
            }), e(".mod-winners-gallery__close").on("click", function(t) {
                t.preventDefault(), e(".mod-winners-gallery__honorees").fadeOut("fast")
            });
            var a = e(".onScrollContainer__selected--year").text(),
                l = "",
                c = 0,
                u = t.debounce(i, 150),
                h = {
                    init: function() {
                        var t = 0,
                            n = e(location).attr("href");
                        if (6 === n.split("/").length) setTimeout(function() {
                            o()
                        }, 250);
                        else {
                            var i = 0;
                            if (e(".onScrollContainer").length && (t = e(".onScrollContainer").height(), t -= 5), "all" === a) {
                                var r = e(".onScrollContainer__list--category .active-media-type").text();
                                "Special Achievement" === r ? (e(".onScrollContainer__selected--category span").text(e(".onScrollContainer__list--category .active-media-type").text()), e(".onScrollContainer__selected--category span").text(e(".onScrollContainer__list--category .active-media-type").text())) : (e(".onScrollContainer__category").text(e(".onScrollContainer__list--category .active-media-type").text()), e(".onScrollContainer__category").text(e(".onScrollContainer__list--category .active-media-type").text())), i = e(".mod-winners-nav__results").first(".mod-winners-gallery").offset().top, e(window).scrollTop(i - t)
                            } else e('.mod-winners-gallery[data-url="' + n + '"]').length ? (i = e('.mod-winners-gallery[data-url="' + n + '"]').offset().top, e(".onScrollContainer__overlay--category").removeClass("active"), e(window).scrollTop(i - t)) : e('#special-achievements[data-url="' + n + '"]').length && (i = e('#special-achievements[data-url="' + n + '"]').offset().top, e(window).scrollTop(i - t))
                        }
                    }
                };
            e(window).on("scroll", function() {
                if (u(), e(".mod-winners-nav__results.ajaxload:not(.loading,.done)").length > 0) {
                    var t = e(".mod-winners-nav__results.ajaxload"),
                        n = e(window).scrollTop(),
                        i = e(document).height() - 4e3,
                        r = Number(e("input.yearLoaded").val()) - 1;
                    n >= i && (t.addClass("loading"), e.ajax({
                        url: "/wp-admin/admin-ajax.php?action=ajax_fetch_winners_all&media_type=" + e("input.mediaType").val() + "&category=all&year=" + r,
                        type: "GET",
                        dataType: "json"
                    }).done(function(n) {
                        t.removeClass("loading"), t.append(n.html), e("input.yearLoaded").val(r), "" === n.html && e(".mod-winners-nav__results.ajaxload").addClass("done")
                    }))
                }
            }), e("body").hasClass("post-type-archive-winner") ? e(".mod-winners-nav__list--category .mod-winners-nav__link, .onScrollContainer__overlay--category .onScrollContainer__link").on("click", function(t) {
                if ("desktop" === n.fetch.now().name || !e(this).hasClass("onScrollContainer__link--dropdown") && !e(this).hasClass("mod-winners-nav__link--dropdown")) {
                    if ("desktop" !== n.fetch.now().name && "all" === a) return e(".mod-winners-nav__overlay--category").addClass("active"), e(this).parents(".mod-winners-nav__item--category").addClass("active"), void(location.href = e(this).attr("href"));
                    if ("all" === a) return void(location.href = e(this).attr("href"));
                    t.preventDefault();
                    var i = e(this).data("category"),
                        r = 0,
                        s = 0;
                    e(".onScrollContainer").length && (s = e(".onScrollContainer").height()), s -= 5, "all" === i ? r = e("#all-categories").offset().top + s : "special" === i ? e("#special-achievements").length && (r = e("#special-achievements").offset().top) : (r = e('.mod-winners-gallery[data-slug="' + i + '"]').position().top, l = i), r > 0 && (e(window).scrollTop(r - s), c = e(window).scrollTop()), e(".onScrollContainer__overlay--category").removeClass("active"), e(".mod-winners-nav__overlay--category").removeClass("active")
                }
            }) : e("body").hasClass("single-winner") && e(".mod-winners-nav__list--category .mod-winners-nav__link, .onScrollContainer__overlay--category .onScrollContainer__link").on("click", function(t) {
                t.preventDefault(), ("desktop" === n.fetch.now().name || !e(this).hasClass("onScrollContainer__link--dropdown") && !e(this).hasClass("mod-winners-nav__link--dropdown")) && ("desktop" !== n.fetch.now().name && (e(".mod-winners-nav__overlay--category").addClass("active"), e(this).parents(".mod-winners-nav__item--category").addClass("active")), location.href = e(this).attr("href"))
            }), e(document).ready(function() {
                h.init()
            }), e(window).unload(function() {
                o()
            }), e(document).on("mouseup", function(t) {
                var n = e(t.target);
                n.hasClass("mod-winners-gallery__honorees") || n.hasClass("mod-winners-gallery__view-all") || n.parents("div.mod-winners-gallery__honorees").length || e(".mod-winners-gallery__honorees").fadeOut("fast")
            }), o()
        }
    }),
    function() {
        "function" == typeof jQuery && define("jquery", function() {
            return jQuery
        }), "object" == typeof Modernizr && define("modernizr", function() {
            return Modernizr
        }), require(["jquery", "common/fancybox", "common/carousels", "modules/navigation", "modules/sub-navigation", "modules/dropdown", "modules/tooltips", "modules/mod-gallery", "modules/mod-listicle", "modules/mod-winners", "modules/mod-article-5up", "modules/mod-article-3up", "modules/mod-gallery-5up", "modules/mod-forms", "modules/mod-netted", "modules/mod-netted-1up", "modules/mod-search-results", "modules/mod-special-achievement", "modules/mod-winners-nav", "modules/mod-5-word-speech", "modules/social-feed", "modules/mod-winners-gallery"], function(e) {
            e(document).trigger("render", document)
        })
    }(), define("main", function() {});