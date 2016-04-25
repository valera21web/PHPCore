var System = {

    string: {
        empty: function(pString) {
            if(pString == undefined || pString.length == 0) {
                return true;
            } else {
                pString = pString.trim();
                if(pString.length == 0) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        isset: function() {

        },
        utf8_encode: function(argString)
        {
            if (argString === null || typeof argString === 'undefined') { return ''; }
            var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
            var utftext = '', start, end, stringl = 0;
            start = end = 0; stringl = string.length;
            for (var n = 0; n < stringl; n++) {
                var c1 = string.charCodeAt(n), enc = null;
                if (c1 < 128) { end++;
                } else if (c1 > 127 && c1 < 2048) {
                    enc = String.fromCharCode((c1 >> 6) | 192, (c1 & 63) | 128);
                } else if ((c1 & 0xF800) != 0xD800) {
                    enc = String.fromCharCode((c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128);
                } else { // surrogate pairs
                    if ((c1 & 0xFC00) != 0xD800) {throw new RangeError('Unmatched trail surrogate at ' + n);}
                    var c2 = string.charCodeAt(++n);
                    if ((c2 & 0xFC00) != 0xDC00) {throw new RangeError('Unmatched lead surrogate at ' + (n - 1));}
                    c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
                    enc = String.fromCharCode((c1 >> 18) | 240, ((c1 >> 12) & 63) | 128, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128);
                }
                if (enc !== null) {
                    if (end > start) {utftext += string.slice(start, end);} utftext += enc;start = end = n + 1;
                }
            }
            if (end > start) { utftext += string.slice(start, stringl); }
            return utftext;
        }
    },
    location: {
        _GET: function(pId) {
            var get_string = window.location.search.trim();
            if(!System.string.empty(get_string)) {
                var listGET = new List();
                get_string = get_string.substring(1);
                var paths = get_string.split("&");
                for(var i in paths) {
                    var path = paths[i].split("=");
                    listGET.set(path['0'], path['1']);
                }
                if(pId != undefined) {
                    return listGET.get(pId);
                } else {
                    return listGET;
                }
            } else {
                return null;
            }
        }
    },
    out: {
        print: function(elem, text) {

        },
        /**
         *
         * @param text - string
         */
        console: function(text) {
            this.out.console(text, "info");
        },
        /**
         *
         * @param text - string
         * @param type - type of print console
         */
        console: function(text, type) {
            if(window.console === undefined) {
                alert(text);
            } else {
                if(type === undefined) {
                    window.console.info(text);
                } else {
                    switch (type) {
                        case "info":
                            window.console.info(text);
                            break;
                        case "error":
                            window.console.error(text);
                            break;
                        case "log":
                            window.console.log(text);
                            break;

                        default:
                            window.console.info(text);
                            break;
                    }
                }
            }
            return null;
        }
    },
    security:
    {
        sha1: function(str)
        {
            var rotate_left = function(n, s) { var t4 = (n << s) | (n >>> (32 - s)); return t4; };
            var cvt_hex = function(val) {
                var str = '', i,v; for (i = 7; i >= 0; i--) { v = (val >>> (i * 4)) & 0x0f; str += v.toString(16); }
                return str;
            };
            var blockstart, i, j,W = new Array(80);
            var H0 = 0x67452301, H1 = 0xEFCDAB89, H2 = 0x98BADCFE, H3 = 0x10325476, H4 = 0xC3D2E1F0;
            var A, B, C, D, E, temp; str = System.string.utf8_encode(str);
            var str_len = str.length; var word_array = [];
            for (i = 0; i < str_len - 3; i += 4) {
                j = str.charCodeAt(i) << 24 | str.charCodeAt(i + 1) << 16 | str.charCodeAt(i + 2) << 8 | str.charCodeAt(i + 3);
                word_array.push(j);
            }
            switch (str_len % 4) {
                case 0: i = 0x080000000; break;
                case 1: i = str.charCodeAt(str_len - 1) << 24 | 0x0800000; break;
                case 2: i = str.charCodeAt(str_len - 2) << 24 | str.charCodeAt(str_len - 1) << 16 | 0x08000; break;
                case 3: i = str.charCodeAt(str_len - 3) << 24 | str.charCodeAt(str_len - 2) << 16 | str.charCodeAt(str_len - 1) << 8 | 0x80;  break;
            }
            word_array.push(i);
            while ((word_array.length % 16) != 14) { word_array.push(0); }
            word_array.push(str_len >>> 29);
            word_array.push((str_len << 3) & 0x0ffffffff);
            for (blockstart = 0; blockstart < word_array.length; blockstart += 16) {
                for (i = 0; i < 16; i++) { W[i] = word_array[blockstart + i]; }
                for (i = 16; i <= 79; i++) { W[i] = rotate_left(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1); }
                A = H0,B = H1,C = H2,D = H3,E = H4;
                for (i = 0; i <= 19; i++) {
                    temp = (rotate_left(A, 5) + ((B & C) | (~B & D)) + E + W[i] + 0x5A827999) & 0x0ffffffff;
                    E = D; D = C; C = rotate_left(B, 30); B = A; A = temp;
                }
                for (i = 20; i <= 39; i++) {
                    temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff;
                    E = D; D = C; C = rotate_left(B, 30); B = A; A = temp;
                }
                for (i = 40; i <= 59; i++) {
                    temp = (rotate_left(A, 5) + ((B & C) | (B & D) | (C & D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff;
                    E = D; D = C; C = rotate_left(B, 30); B = A; A = temp;
                }
                for (i = 60; i <= 79; i++) {
                    temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff;
                    E = D; D = C; C = rotate_left(B, 30); B = A; A = temp;
                }
                H0 = (H0 + A) & 0x0ffffffff; H1 = (H1 + B) & 0x0ffffffff; H2 = (H2 + C) & 0x0ffffffff;
                H3 = (H3 + D) & 0x0ffffffff; H4 = (H4 + E) & 0x0ffffffff;
            }
            temp = cvt_hex(H0) + cvt_hex(H1) + cvt_hex(H2) + cvt_hex(H3) + cvt_hex(H4);
            return temp.toLowerCase();
        }
    }
};

var List = function(val)
{
    if(val == undefined)
    {
        this.id = 0;
        this.keyIdToIndex = new Object();
        this.data = new Array();
        this.count = 0;
    }
    else
    {
        this.id = val.id;
        this.keyIdToIndex = val.keyIdToIndex;
        this.data = val.data;
        this.count = val.count;
    }

};

List.prototype = {
    add: function(pId, pData) {
        if(pData == undefined) {
            pData = pId;
            pId = this.id;
        }
        if(this.is_has(pId)) {
            this.edit(pId, pData);
        } else {
            this.data[this.id] = {
                id : pId,
                value: pData
            };
            this.keyIdToIndex[pId] = this.id;
            ++this.id;
            ++this.count;
        }
        return null;
    },
    get: function(pId, pSubValue) {
        if(this.keyIdToIndex[pId] != undefined) {
            if(pSubValue != undefined && pSubValue != null) {
                return this.data[this.keyIdToIndex[pId]]['value'][pSubValue];
            } else {
                return this.data[this.keyIdToIndex[pId]]['value'];
            }
        }
        return null;
    },
    edit: function(pId, pData) {
        if(this.keyIdToIndex[pId] != undefined) {
            this.data[this.keyIdToIndex[pId]]['value'] = $.extend(true, this.data[this.keyIdToIndex[pId]]['value'], pData);
            return true;
        }
        return false;
    },
    delete: function(pId) {
        if(this.keyIdToIndex[pId] != undefined) {
            delete this.data[this.keyIdToIndex[pId]];
            delete this.keyIdToIndex[pId];
            --this.count;
            return true;
        }
        return false;
    },
    foreach: function(fun) {
        for(var index in this.data)
        {
            if(this.data[index] != undefined && this.data[index] != null)
                fun(this.data[index]['id'], this.data[index]['value'], index);
        }

        return null;
    },
    empty: function() {
        return !this.count;
    },
    size: function() {
        return this.count;
    },
    is_has: function(pId) {
        return this.is_has(pId, "bool");
    },
    is_has: function(pId, pReturnType) {
        if(this.keyIdToIndex[pId] != undefined) {
            var row = this.data[this.keyIdToIndex[pId]];
            pReturnType = pReturnType != undefined ? pReturnType : "bool";
            switch (pReturnType) {
                case "bool":
                    return true;
                    break;
                case "object":
                    return row;
                    break;
                case "object-value":
                    return row['value'];
                    break;

                default:
                    return row;
                    break;
            }
        }
        return false;
    }
};



/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD (Register as an anonymous module)
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {

    var pluses = /\+/g;

    function encode(s) {
        return config.raw ? s : encodeURIComponent(s);
    }

    function decode(s) {
        return config.raw ? s : decodeURIComponent(s);
    }

    function stringifyCookieValue(value) {
        return encode(config.json ? JSON.stringify(value) : String(value));
    }

    function parseCookieValue(s) {
        if (s.indexOf('"') === 0) {
            // This is a quoted cookie as according to RFC2068, unescape...
            s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
        }

        try {
            // Replace server-side written pluses with spaces.
            // If we can't decode the cookie, ignore it, it's unusable.
            // If we can't parse the cookie, ignore it, it's unusable.
            s = decodeURIComponent(s.replace(pluses, ' '));
            return config.json ? JSON.parse(s) : s;
        } catch(e) {}
    }

    function read(s, converter) {
        var value = config.raw ? s : parseCookieValue(s);
        return $.isFunction(converter) ? converter(value) : value;
    }

    var config = $.cookie = function (key, value, options) {

        // Write

        if (arguments.length > 1 && !$.isFunction(value)) {
            options = $.extend({}, config.defaults, options);

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setMilliseconds(t.getMilliseconds() + days * 864e+5);
            }

            return (document.cookie = [
                encode(key), '=', stringifyCookieValue(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path    ? '; path=' + options.path : '',
                options.domain  ? '; domain=' + options.domain : '',
                options.secure  ? '; secure' : ''
            ].join(''));
        }

        // Read

        var result = key ? undefined : {},
        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all. Also prevents odd result when
        // calling $.cookie().
            cookies = document.cookie ? document.cookie.split('; ') : [],
            i = 0,
            l = cookies.length;

        for (; i < l; i++) {
            var parts = cookies[i].split('='),
                name = decode(parts.shift()),
                cookie = parts.join('=');

            if (key === name) {
                // If second argument (value) is a function it's a converter...
                result = read(cookie, value);
                break;
            }

            // Prevent storing a cookie that we couldn't decode.
            if (!key && (cookie = read(cookie)) !== undefined) {
                result[name] = cookie;
            }
        }

        return result;
    };

    config.defaults = {};

    $.removeCookie = function (key, options) {
        // Must not alter options, thus extending a fresh object...
        $.cookie(key, '', $.extend({}, options, { expires: -1 }));
        return !$.cookie(key);
    };

}));