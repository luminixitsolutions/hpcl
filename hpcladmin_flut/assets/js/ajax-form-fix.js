/**
 * Workaround for hosts where POST bodies to ajax_files/ are not received by PHP.
 * Converts non-file POST requests to GET query strings (server bootstrap maps $_GET -> $_POST).
 */
(function ($) {
    'use strict';

    function isAjaxFilesUrl(url) {
        return url && String(url).indexOf('ajax_files/') !== -1;
    }

    function toQueryString(data) {
        if (typeof data === 'string') {
            return data;
        }
        if ($.isPlainObject(data)) {
            return $.param(data, true);
        }
        return '';
    }

    function formDataToQuery(formData) {
        var parts = [];
        var hasFile = false;

        if (typeof FormData !== 'undefined' && formData instanceof FormData) {
            formData.forEach(function (value, key) {
                if (value instanceof File) {
                    if (value.size > 0) {
                        hasFile = true;
                    }
                    return;
                }
                if (Object.prototype.toString.call(value) === '[object FileList]') {
                    if (value.length > 0) {
                        hasFile = true;
                    }
                    return;
                }
                parts.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
            });
            return { query: parts.join('&'), hasFile: hasFile };
        }

        return { query: toQueryString(formData), hasFile: false };
    }

    $.ajaxPrefilter(function (options) {
        var url = options.url || '';
        if (!isAjaxFilesUrl(url)) {
            return;
        }

        var method = (options.type || options.method || 'GET').toUpperCase();
        if (method !== 'POST') {
            return;
        }

        var converted = formDataToQuery(options.data);
        if (converted.hasFile) {
            return;
        }

        if (!converted.query) {
            return;
        }

        options.url = url + (url.indexOf('?') >= 0 ? '&' : '?') + converted.query;
        options.type = 'GET';
        options.method = 'GET';
        options.data = undefined;
        options.contentType = undefined;
        options.processData = undefined;
    });
})(jQuery);
