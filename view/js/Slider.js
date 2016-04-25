(function($) {
    $.fn.slider = function(options)
    {
        if(typeof(options) == "undefined" || typeof(options) != "object")
            options = {};
        return new Slider(options, this);
    };

    var Slider = function(options, block){
        this.block = block;
        this.list = new List();
        this.first = null;
        this.last = null;
        this.view = null;
        this.nav = null;
        this.active = null;
        this.buttonPrev = null;
        this.buttonNext = null;
        this.session = 5;
        this.settings = {
            time : 3000,
            speed : 1000,
            type : 1,
            animat: 'scroll'
        };

        // Merge the users options with our defaults
        $.extend(this.settings, options);
        this.init();
        this.start(this.session);

    };

    Slider.prototype = {
        init: function() {
            var obj = this;
            var _prev = null;
            $(obj.block).find("div.sliderItem").each(function(i, v) {
                var id = "slide_"+i;
                if(obj.first == null)
                    obj.first = id;
                obj.last = id;
                if(_prev != null) {
                    var edit_prev =  obj.list.get(_prev);
                    edit_prev.next = id;
                    obj.list.edit(_prev, edit_prev);
                }
                obj.list.add(id,
                    {
                        id: id,
                        html_view: v.outerHTML,
                        prev: _prev,
                        next: null
                    }
                );
                _prev = id;
            });

            var edit_first =  obj.list.get(obj.first);
            if(edit_first != null)
            {
                edit_first.prev = obj.last;
                obj.list.edit(obj.first, edit_first);

                var edit_last =  obj.list.get(obj.last);
                edit_last.next = obj.first;
                obj.list.edit(obj.last, edit_last);

                obj.greatView();
            }
        },
        greatView: function() {
            var obj = this;
            $(obj.block).children().hide();

            $(obj.block).append("<div id='slider_view'></div>");
            $(obj.block).append("<div id='slider_nav'></div>");
            $(obj.block).append("<div id='sliderNavPrev'></div>");
            $(obj.block).append("<div id='sliderNavNext'></div>");

            obj.view = $(obj.block).find("div#slider_view");
            obj.nav = $(obj.block).find("div#slider_nav");
            obj.buttonPrev = $(obj.block).find("div#sliderNavPrev");
            obj.buttonNext = $(obj.block).find("div#sliderNavNext");

            obj.view.html(obj.list.get(obj.first).html_view);

            var count = 0;
            obj.list.foreach(function(id)
            {
                ++count;
                $(obj.nav).append('<div id="slider_nav_' + id + '" class="slider_nav_item"></div>');
            });

            obj.active = obj.first;
            $(obj.nav).find("#slider_nav_" + obj.first).addClass("active");
            obj.nav.css('width', count * 35);
            // obj.nav.css('left', ($(obj.block).width() - count * 35) / 2);
            // obj.nav.css('top', $(obj.block).height() - 25);
            obj.nav.css('height', 25);
            obj.initButton();
        },
        initButton: function() {
            var obj = this;

            (obj.buttonPrev).on("click", function() {
                obj.prev(obj.session++);
            });
            (obj.buttonNext).on("click", function() {
                obj.next(obj.session++);
            });
            $("div[id^='slider_nav_']").click(function()
            {
                if(!$(this).hasClass('active'))
                {
                    var button_id = $(this).attr("id").replace("slider_nav_", "");
                    var new_elem = obj.list.get(button_id);
                    obj.step(obj.active, new_elem.id, obj.session++);
                }
            });
        },
        start: function(id_session) {
            var obj = this;
            setTimeout(function()
            {
                if(id_session == obj.session)
                {
                    obj.next(id_session);
                    obj.start(id_session);
                }

            }, obj.settings.time);
        },
        next: function(id_session) {
            var obj = this;
            var new_elem = obj.list.get(obj.active);
            if(new_elem != null)
                obj.step(obj.active, new_elem.next, id_session, 1);
        },
        prev: function(id_session) {
            var obj = this;
            var new_elem = obj.list.get(obj.active);
            obj.step(obj.active, new_elem.prev, id_session, 0);

        },
        step: function(id_old, id_new, id_session, next)
        {
            next = next == undefined ? (id_new > id_old ? 1 : 0) : next;
            var obj = this;
            var new_elem = obj.list.get(id_new);

            obj.animation(new_elem.html_view, next);

            $(obj.nav).find("#slider_nav_" + id_old).removeClass("active");
            $(obj.nav).find("#slider_nav_" + new_elem.id).addClass("active");
            obj.active = new_elem.id;

            if(id_session != obj.session)
            {
                ++obj.session;
                obj.start(obj.session);
            }
        },
        animation: function(new_html, next)
        {
            var obj = this;
            var i = obj.session;
            obj.view.children('div').addClass('old').addClass('old_'+i);
            obj.view.append(new_html);


            var anim = {left: (next ? "-100%" : "100%")};
            if(obj.settings.animat == 'opacity')
            {
                anim = { opacity : 0}
            }

            obj.view.children('div.old_'+i).animate(anim, {
                queue: false,
                duration: obj.settings.speed,
                complete: function() {
                    obj.view.children('div.old_'+i).remove();
                }
            });


        }
    }


}) (jQuery);