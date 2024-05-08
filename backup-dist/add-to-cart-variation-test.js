! function (t, a, i, e) {
    var r = function (t) {
        var a = this;
        a.$form = t, a.$attributeFields = t.find(".variations select"), a.$singleVariation = t.find(".single_variation"), a.$singleVariationWrap = t.find(".single_variation_wrap"), a.$resetVariations = t.find(".reset_variations"), a.$product = t.closest(".product"), a.variationData = t.data("product_variations"), a.useAjax = !1 === a.variationData, a.xhr = !1, a.loading = !0, a.$singleVariationWrap.show(), a.$form.off(".wc-variation-form"), a.getChosenAttributes = a.getChosenAttributes.bind(a), a.findMatchingVariations = a.findMatchingVariations.bind(a), a.isMatch = a.isMatch.bind(a), a.toggleResetLink = a.toggleResetLink.bind(a), t.on("click.wc-variation-form", ".reset_variations", {
            variationForm: a
        }, a.onReset), t.on("reload_product_variations", {
            variationForm: a
        }, a.onReload), t.on("hide_variation", {
            variationForm: a
        }, a.onHide), t.on("show_variation", {
            variationForm: a
        }, a.onShow), t.on("click", ".single_add_to_cart_button", {
            variationForm: a
        }, a.onAddToCart), t.on("reset_data", {
            variationForm: a
        }, a.onResetDisplayedVariation), t.on("reset_image", {
            variationForm: a
        }, a.onResetImage), t.on("change.wc-variation-form", ".variations select", {
            variationForm: a
        }, a.onChange), t.on("found_variation.wc-variation-form", {
            variationForm: a
        }, a.onFoundVariation), t.on("check_variations.wc-variation-form", {
            variationForm: a
        }, a.onFindVariation), t.on("update_variation_values.wc-variation-form", {
            variationForm: a
        }, a.onUpdateAttributes), setTimeout(function () {
            t.trigger("check_variations"), t.trigger("wc_variation_form", a), a.loading = !1
        }, 100)
    };
    r.prototype.onReset = function (t) {
        t.preventDefault(), t.data.variationForm.$attributeFields.val("").trigger("change"), t.data.variationForm.$form.trigger("reset_data")
    }, r.prototype.onReload = function (t) {
        var a = t.data.variationForm;
        a.variationData = a.$form.data("product_variations"), a.useAjax = !1 === a.variationData, a.$form.trigger("check_variations")
    }, r.prototype.onHide = function (t) {
        t.preventDefault(), t.data.variationForm.$form.find(".single_add_to_cart_button").removeClass("wc-variation-is-unavailable").addClass("disabled wc-variation-selection-needed"), t.data.variationForm.$form.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-enabled").addClass("woocommerce-variation-add-to-cart-disabled")
    }, r.prototype.onShow = function (a, i, e) {
        a.preventDefault(), e ? (a.data.variationForm.$form.find(".single_add_to_cart_button").removeClass("disabled wc-variation-selection-needed wc-variation-is-unavailable"), a.data.variationForm.$form.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-disabled").addClass("woocommerce-variation-add-to-cart-enabled")) : (a.data.variationForm.$form.find(".single_add_to_cart_button").removeClass("wc-variation-selection-needed").addClass("disabled wc-variation-is-unavailable"), a.data.variationForm.$form.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-enabled").addClass("woocommerce-variation-add-to-cart-disabled")), wp.mediaelement && a.data.variationForm.$form.find(".wp-audio-shortcode, .wp-video-shortcode").not(".mejs-container").filter(function () {
            return !t(this).parent().hasClass("mejs-mediaelement")
        }).mediaelementplayer(wp.mediaelement.settings)
    }, r.prototype.onAddToCart = function (i) {
        t(this).is(".disabled") && (i.preventDefault(), t(this).is(".wc-variation-is-unavailable") ? a.alert(wc_add_to_cart_variation_params.i18n_unavailable_text) : t(this).is(".wc-variation-selection-needed") && a.alert(wc_add_to_cart_variation_params.i18n_make_a_selection_text))
    }, r.prototype.onResetDisplayedVariation = function (t) {
        var a = t.data.variationForm;
        a.$product.find(".product_meta").find(".sku").wc_reset_content(), a.$product.find(".product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value").wc_reset_content(), a.$product.find(".product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value").wc_reset_content(), a.$form.trigger("reset_image"), a.$singleVariation.slideUp(200).trigger("hide_variation")
    }, r.prototype.onResetImage = function (t) {
        t.data.variationForm.$form.wc_variations_image_update(!1)
    }, r.prototype.onFindVariation = function (a, i) {
        var e = a.data.variationForm,
            r = void 0 !== i ? i : e.getChosenAttributes(),
            o = r.data;
        if (r.count && r.count === r.chosenCount)
            if (e.useAjax) e.xhr && e.xhr.abort(), e.$form.block({
                message: null,
                overlayCSS: {
                    background: "#fff",
                    opacity: .6
                }
            }), o.product_id = parseInt(e.$form.data("product_id"), 10), o.custom_data = e.$form.data("custom_data"), e.xhr = t.ajax({
                url: wc_add_to_cart_variation_params.wc_ajax_url.toString().replace("%%endpoint%%", "get_variation"),
                type: "POST",
                data: o,
                success: function (t) {
                    t ? e.$form.trigger("found_variation", [t]) : (e.$form.trigger("reset_data"), r.chosenCount = 0, e.loading || (e.$form.find(".single_variation").after('<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + "</p>"), e.$form.find(".wc-no-matching-variations").slideDown(200)))
                },
                complete: function () {
                    e.$form.unblock()
                }
            });
            else {
                e.$form.trigger("update_variation_values");
                var n = e.findMatchingVariations(e.variationData, o).shift();
                n ? e.$form.trigger("found_variation", [n]) : (e.$form.trigger("reset_data"), r.chosenCount = 0, e.loading || (e.$form.find(".single_variation").after('<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + "</p>"), e.$form.find(".wc-no-matching-variations").slideDown(200)))
            }
        else e.$form.trigger("update_variation_values"), e.$form.trigger("reset_data");
        e.toggleResetLink(r.chosenCount > 0)
    }, r.prototype.onFoundVariation = function (a, i) {
        var e = a.data.variationForm,
            r = e.$product.find(".product_meta").find(".sku"),
            n = e.$product.find(".product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value"),
            s = e.$product.find(".product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value"),
            c = e.$singleVariationWrap.find('.quantity input.qty[name="quantity"]'),
            _ = c.closest(".quantity"),
            d = !0,
            m = !1,
            v = "";
        if (i.sku ? r.wc_set_content(i.sku) : r.wc_reset_content(), i.weight ? n.wc_set_content(i.weight_html) : n.wc_reset_content(), i.dimensions ? s.wc_set_content(t.parseHTML(i.dimensions_html)[0].data) : s.wc_reset_content(), e.$form.wc_variations_image_update(i), i.variation_is_visible ? (m = o("variation-template"), i.variation_id) : m = o("unavailable-variation-template"), v = (v = (v = m({
                variation: i
            })).replace("/*<![CDATA[*/", "")).replace("/*]]>*/", ""), e.$singleVariation.html(v), e.$form.find('input[name="variation_id"], input.variation_id').val(i.variation_id).trigger("change"), "yes" === i.is_sold_individually) c.val("1").attr("min", "1").attr("max", "").trigger("change"), _.hide();
        else {
            var l = parseFloat(c.val());
            l = isNaN(l) ? i.min_qty : (l = l > parseFloat(i.max_qty) ? i.max_qty : l) < parseFloat(i.min_qty) ? i.min_qty : l, c.attr("min", i.min_qty).attr("max", i.max_qty).val(l).trigger("change"), _.show()
        }
        i.is_purchasable && i.is_in_stock && i.variation_is_visible || (d = !1), e.$singleVariation.text().trim() ? e.$singleVariation.slideDown(200).trigger("show_variation", [i, d]) : e.$singleVariation.show().trigger("show_variation", [i, d])
    }, r.prototype.onChange = function (t) {
        var a = t.data.variationForm;
        a.$form.find('input[name="variation_id"], input.variation_id').val("").trigger("change"), a.$form.find(".wc-no-matching-variations").remove(), a.useAjax ? a.$form.trigger("check_variations") : (a.$form.trigger("woocommerce_variation_select_change"), a.$form.trigger("check_variations")), a.$form.trigger("woocommerce_variation_has_changed")
    }, r.prototype.addSlashes = function (t) {
        return t = (t = t.replace(/'/g, "\\'")).replace(/"/g, '\\"')
    }, r.prototype.onUpdateAttributes = function (a) {
        var i = a.data.variationForm,
            e = i.getChosenAttributes().data;
        i.useAjax || (i.$attributeFields.each(function (a, r) {
            var o, n = t(r),
                s = n.data("attribute_name") || n.attr("name"),
                c = t(r).data("show_option_none"),
                _ = ":gt(0)",
                d = t("<select/>"),
                m = n.val() || "",
                v = !0;
            if (!n.data("attribute_html")) {
                var l = n.clone();
                l.find("option").removeAttr("attached").prop("disabled", !1).prop("selected", !1), n.data("attribute_options", l.find("option" + _).get()), n.data("attribute_html", l.html())
            }
            d.html(n.data("attribute_html"));
            var g = t.extend(!0, {}, e);
            g[s] = "";
            var f = i.findMatchingVariations(i.variationData, g);
            for (var u in f)
                if ("undefined" != typeof f[u]) {
                    var h = f[u].attributes;
                    for (var p in h)
                        if (h.hasOwnProperty(p)) {
                            var w = h[p],
                                b = "";
                            if (p === s)
                                if (f[u].variation_is_active && (b = "enabled"), w) {
                                    w = t("<div/>").html(w).text();
                                    var $ = d.find("option");
                                    if ($.length)
                                        for (var y = 0, F = $.length; y < F; y++) {
                                            var C = t($[y]);
                                            if (w === C.val()) {
                                                C.addClass("attached " + b);
                                                break
                                            }
                                        }
                                } else d.find("option:gt(0)").addClass("attached " + b)
                        }
                } o = d.find("option.attached").length, m && (v = !1, 0 !== o && d.find("option.attached.enabled").each(function () {
                var a = t(this).val();
                if (m === a) return v = !0, !1
            })), o > 0 && m && v && "no" === c && (d.find("option:first").remove(), _ = ""), d.find("option" + _ + ":not(.attached)").remove(), n.html(d.html()), n.find("option" + _ + ":not(.enabled)").prop("disabled", !0), m ? v ? n.val(m) : n.val("").trigger("change") : n.val("")
        }), i.$form.trigger("woocommerce_update_variation_values"))
    }, r.prototype.getChosenAttributes = function () {
        var a = {},
            i = 0,
            e = 0;
        return this.$attributeFields.each(function () {
            var r = t(this).data("attribute_name") || t(this).attr("name"),
                o = t(this).val() || "";
            o.length > 0 && e++, i++, a[r] = o
        }), {
            count: i,
            chosenCount: e,
            data: a
        }
    }, r.prototype.findMatchingVariations = function (t, a) {
        for (var i = [], e = 0; e < t.length; e++) {
            var r = t[e];
            this.isMatch(r.attributes, a) && i.push(r)
        }
        return i
    }, r.prototype.isMatch = function (t, a) {
        var i = !0;
        for (var e in t)
            if (t.hasOwnProperty(e)) {
                var r = t[e],
                    o = a[e];
                void 0 !== r && void 0 !== o && 0 !== r.length && 0 !== o.length && r !== o && (i = !1)
            } return i
    }, r.prototype.toggleResetLink = function (t) {
        t ? "hidden" === this.$resetVariations.css("visibility") && this.$resetVariations.css("visibility", "visible").hide().fadeIn() : this.$resetVariations.css("visibility", "hidden")
    }, t.fn.wc_variation_form = function () {
        return new r(this), this
    }, t.fn.wc_set_content = function (t) {
        void 0 === this.attr("data-o_content") && this.attr("data-o_content", this.text()), this.text(t)
    }, t.fn.wc_reset_content = function () {
        void 0 !== this.attr("data-o_content") && this.text(this.attr("data-o_content"))
    }, t.fn.wc_set_variation_attr = function (t, a) {
        void 0 === this.attr("data-o_" + t) && this.attr("data-o_" + t, this.attr(t) ? this.attr(t) : ""), !1 === a ? this.removeAttr(t) : this.attr(t, a)
    }, t.fn.wc_reset_variation_attr = function (t) {
        void 0 !== this.attr("data-o_" + t) && this.attr(t, this.attr("data-o_" + t))
    }, t.fn.wc_maybe_trigger_slide_position_reset = function (a) {
        var i = t(this),
            e = i.closest(".product").find(".images"),
            r = !1,
            o = a && a.image_id ? a.image_id : "";
        i.attr("current-image") !== o && (r = !0), i.attr("current-image", o), r && e.trigger("woocommerce_gallery_reset_slide_position")
    }, t.fn.wc_variations_image_update = function (i) {
        var e = this,
            r = e.closest(".product"),
            o = r.find(".images"),
            n = r.find(".flex-control-nav"),
            s = n.find("li:eq(0) img"),
            c = o.find(".woocommerce-product-gallery__image[data-slick-index='0'], .woocommerce-product-gallery__image--placeholder").eq(0),
            _ = c.find(".wp-post-image"),
            d = c.find("a").eq(0);
        if (i && i.image && i.image.src && i.image.src.length > 1) {
            n.find('li img[data-o_src="' + i.image.gallery_thumbnail_src + '"]').length > 0 && e.wc_variations_image_reset();
            var m = n.find('li img[src="' + i.image.gallery_thumbnail_src + '"]');
            if (m.length > 0) return m.trigger("click"), e.attr("current-image", i.image_id), void a.setTimeout(function () {
                t(a).trigger("resize"), o.trigger("woocommerce_gallery_init_zoom")
            }, 20);
            _.wc_set_variation_attr("src", i.image.src), _.wc_set_variation_attr("height", i.image.src_h), _.wc_set_variation_attr("width", i.image.src_w), _.wc_set_variation_attr("srcset", i.image.srcset), _.wc_set_variation_attr("sizes", i.image.sizes), _.wc_set_variation_attr("title", i.image.title), _.wc_set_variation_attr("data-caption", i.image.caption), _.wc_set_variation_attr("alt", i.image.alt), _.wc_set_variation_attr("data-src", i.image.full_src), _.wc_set_variation_attr("data-large_image", i.image.full_src), _.wc_set_variation_attr("data-large_image_width", i.image.full_src_w), _.wc_set_variation_attr("data-large_image_height", i.image.full_src_h), c.wc_set_variation_attr("data-thumb", i.image.src), s.wc_set_variation_attr("src", i.image.gallery_thumbnail_src), d.wc_set_variation_attr("href", i.image.full_src)
        } else e.wc_variations_image_reset();
        a.setTimeout(function () {
            t(a).trigger("resize"), e.wc_maybe_trigger_slide_position_reset(i), o.trigger("woocommerce_gallery_init_zoom")
        }, 20)
    }, t.fn.wc_variations_image_reset = function () {
        var t = this.closest(".product"),
            a = t.find(".images"),
            i = t.find(".flex-control-nav").find("li:eq(0) img"),
            e = a.find(".woocommerce-product-gallery__image[data-slick-index='0'], .woocommerce-product-gallery__image--placeholder").eq(0),
            r = e.find(".wp-post-image"),
            o = e.find("a").eq(0);
        r.wc_reset_variation_attr("src"), r.wc_reset_variation_attr("width"), r.wc_reset_variation_attr("height"), r.wc_reset_variation_attr("srcset"), r.wc_reset_variation_attr("sizes"), r.wc_reset_variation_attr("title"), r.wc_reset_variation_attr("data-caption"), r.wc_reset_variation_attr("alt"), r.wc_reset_variation_attr("data-src"), r.wc_reset_variation_attr("data-large_image"), r.wc_reset_variation_attr("data-large_image_width"), r.wc_reset_variation_attr("data-large_image_height"), e.wc_reset_variation_attr("data-thumb"), i.wc_reset_variation_attr("src"), o.wc_reset_variation_attr("href")
    }, t(function () {
        "undefined" != typeof wc_add_to_cart_variation_params && t(".variations_form").each(function () {
            t(this).wc_variation_form()
        })
    });
}(jQuery, window, document);