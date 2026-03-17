/**
 * York Admin — Meta Box JS
 * Tabs, Repeater, Image Picker
 *
 * @package YorkAlumni
 */
jQuery(function ($) {
    'use strict';

    /* ── TABS ── */
    $(document).on('click', '.york-tabs-nav button', function () {
        var tab  = $(this).data('tab');
        var $box = $(this).closest('.york-tabs-wrap');
        $box.find('.york-tabs-nav button').removeClass('is-active');
        $box.find('.york-tab-panel').removeClass('is-active');
        $(this).addClass('is-active');
        $box.find('.york-tab-panel[data-tab="' + tab + '"]').addClass('is-active');
    });

    /* ── REPEATER TOGGLE ── */
    $(document).on('click', '.york-repeater-header', function () {
        $(this).closest('.york-repeater-item').toggleClass('is-open');
    });

    /* ── REPEATER ADD ── */
    $(document).on('click', '.york-add-repeater-item', function () {
        var $wrap  = $(this).closest('.york-repeater-wrap');
        var tpl    = $wrap.find('.york-repeater-template').html();
        var count  = $wrap.find('.york-repeater-list .york-repeater-item').length;
        var html   = tpl.replace(/__INDEX__/g, count);
        var $item  = $(html);
        $wrap.find('.york-repeater-list').append($item);
        $item.addClass('is-open');
        bindImagePickers($item);
        updateLabels($wrap);
    });

    /* ── REPEATER REMOVE ── */
    $(document).on('click', '.york-repeater-remove', function (e) {
        e.stopPropagation();
        if (!confirm('Remove this item?')) { return; }
        var $wrap = $(this).closest('.york-repeater-wrap');
        $(this).closest('.york-repeater-item').remove();
        reindexRepeater($wrap);
        updateLabels($wrap);
    });

    function reindexRepeater($wrap) {
        $wrap.find('.york-repeater-list .york-repeater-item').each(function (i) {
            $(this).find('input, textarea, select').each(function () {
                var name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + i + ']'));
                }
            });
        });
    }

    function updateLabels($wrap) {
        var prefix = $wrap.data('label') || 'Item';
        $wrap.find('.york-repeater-list .york-repeater-item').each(function (i) {
            $(this).find('.york-repeater-label').first().text(prefix + ' ' + (i + 1));
        });
    }

    /* ── IMAGE PICKER ── */
    function bindImagePickers($ctx) {
        $ctx.find('.york-select-image').off('click.york').on('click.york', function (e) {
            e.preventDefault();
            var $btn     = $(this);
            var $field   = $btn.closest('.york-image-field');
            var $input   = $field.find('.york-image-id');
            var $preview = $field.find('.york-image-preview img');
            var $remove  = $field.find('.york-remove-image');

            var frame = wp.media({
                title: 'Select Image',
                button: { text: 'Use Image' },
                multiple: false
            });

            frame.on('select', function () {
                var att = frame.state().get('selection').first().toJSON();
                var url = (att.sizes && att.sizes.thumbnail) ? att.sizes.thumbnail.url : att.url;
                $input.val(att.id);
                $preview.attr('src', url).show();
                $remove.show();
            });

            frame.open();
        });

        $ctx.find('.york-remove-image').off('click.york').on('click.york', function (e) {
            e.preventDefault();
            var $field = $(this).closest('.york-image-field');
            $field.find('.york-image-id').val('');
            $field.find('.york-image-preview img').attr('src', '').hide();
            $(this).hide();
        });
    }

    /* Bind on page load */
    bindImagePickers($(document));

    /* Open first repeater items by default */
    $('.york-repeater-item:first-child').addClass('is-open');
});
