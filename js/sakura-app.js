/*！
 * Sakura application bundle theme ver 3.2.0
 * @author Mashiro
 * @url https://2heng.xin
 * @date 2019.8.3
 */
mashiro_global.variables = new function () {
    this.has_bot_ui = false;
    this.isNight = false;
    this.skinSecter = false;
}
mashiro_global.ini = new function () {
    this.normalize = function () {
        lazyload();
        social_share();
        mashiro_global.post_list_show_animation.ini();
        copy_code_block();
        coverVideoIni();
    }
    this.pjax = function () {
        pjaxInit();
        social_share();
        mashiro_global.post_list_show_animation.ini();
        copy_code_block();
        coverVideoIni();
    }
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + mashiro_option.cookie_version_control + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + mashiro_option.cookie_version_control + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function removeCookie(name) {
    document.cookie = name + mashiro_option.cookie_version_control + '=; Max-Age=-99999999;';
}

function imgError(ele, type) {
    switch (type) {
        case 1:
            ele.src = 'https://view.moezx.cc/images/2017/12/30/Transparent_Akkarin.th.jpg';
            break;
        case 2:
            ele.src = 'https://gravatar.shino.cc/avatar/?s=80&d=mm&r=g';
            break;
        default:
            ele.src = 'https://view.moezx.cc/images/2018/05/13/image-404.png';
    }
} 

mashiro_global.post_list_show_animation = new function () {
    this.ini = function (ajax) {
        $("article.post-list-thumb").each(function (i) {
            if (ajax) {
                var window_height = $(window).height();
            } else {
                if ($(".headertop").hasClass("headertop-bar")) {
                    var window_height = 0;
                } else {
                    if (mashiro_option.land_at_home) {
                        var window_height = $(window).height() - 300;
                    } else {
                        var window_height = $(window).height();
                    }
                }
            }
            var article_height = $("article.post-list-thumb").eq(i).offset().top;
            if ($(window).height() + $(window).scrollTop() >= article_height)
                $("article.post-list-thumb").eq(i).addClass('post-list-show');
            $(window).scroll(function () {
                var scrolltop = $(window).scrollTop();
                if (scrolltop + window_height >= article_height && scrolltop)
                    $("article.post-list-thumb").eq(i).addClass("post-list-show");
            });
        });
    }
}
mashiro_global.font_control = new function () {
    this.change_font = function () {
        if ($("body").hasClass("serif")) {
            $("body").removeClass("serif");
            $(".control-btn-serif").removeClass("selected");
            $(".control-btn-sans-serif").addClass("selected");
            setCookie("font_family", "sans-serif", 30);
        } else {
            $("body").addClass("serif");
            $(".control-btn-serif").addClass("selected");
            $(".control-btn-sans-serif").removeClass("selected");
            setCookie("font_family", "serif", 30);
            if (document.body.clientWidth <= 860) {
                addComment.createButterbar("将从网络加载字体，流量请注意");
            }
        }
    }
    this.ini = function () {
        if (document.body.clientWidth > 860) {
            if (!getCookie("font_family") || getCookie("font_family") == "serif")
                $("body").addClass("serif");
        }
        if (getCookie("font_family") == "sans-serif") {
            $("body").removeClass("sans-serif");
            $(".control-btn-serif").removeClass("selected");
            $(".control-btn-sans-serif").addClass("selected");
        }
    }
}
mashiro_global.font_control.ini();

function code_highlight_style() {
    function gen_top_bar(i) {
        var attributes = {
            'autocomplete': 'off',
            'autocorrect': 'off',
            'autocapitalize': 'off',
            'spellcheck': 'false',
            'contenteditable': 'false',
            'design': 'by Mashiro'
        }
        var ele_name = $('pre:eq(' + i + ')')[0].children[0].className;
        var lang = ele_name.substr(0, ele_name.indexOf(" ")).replace('language-', '');
        if (lang.toLowerCase() == "hljs") var lang = "text";
        $('pre:eq(' + i + ')').addClass('highlight-wrap');
        for (var t in attributes) {
            $('pre:eq(' + i + ')').attr(t, attributes[t]);
        }
        $('pre:eq(' + i + ') code').attr('data-rel', lang.toUpperCase());
    }
    $('pre code').each(function (i, block) {
        hljs.highlightBlock(block);
    });
    for (var i = 0; i < $('pre').length; i++) {
        gen_top_bar(i);
    }
    hljs.initLineNumbersOnLoad();
    $('pre').on('click', function (e) {
        if (e.target !== this) return;
        $(this).toggleClass('code-block-fullscreen');
        $('html').toggleClass('code-block-fullscreen-html-scroll');
    });
}
try {
    code_highlight_style();
} catch (e) {}

if (Poi.reply_link_version == 'new'){
    $('body').on('click','.comment-reply-link',function(){
        addComment.moveForm( "comment-"+$(this).attr('data-commentid'), $(this).attr('data-commentid'), "respond", $(this).attr('data-postid') );
        return false;
    });
}

function attach_image() {
    $('#upload-img-file').change(function () {
        if (this.files.length > 10) {
            addComment.createButterbar("每次上传上限为10张.<br>10 files max per request.");
            return 0;
        }
        for (i = 0; i < this.files.length; i++) {
            if (this.files[i].size >= 5242880) {
                alert('图片上传大小限制为5 MB.\n5 MB max per file.\n\n「' + this.files[i].name + '」\n\n这张图太大啦~\nThis image is too large~');
            }
        }
        for (var i = 0; i < this.files.length; i++) {
            var f = this.files[i];
            var formData = new FormData();
            formData.append('smfile', f);
            $.ajax({
                url: 'https://sm.ms/api/upload',
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                beforeSend: function (xhr) {
                    $('.insert-image-tips').html('<i class="fa fa-spinner rotating" aria-hidden="true"></i>');
                    addComment.createButterbar("上传中...<br>Uploading...");
                },
                success: function (res) {
                    $('.insert-image-tips').html('<i class="fa fa-check" aria-hidden="true"></i>');
                    setTimeout(function () {
                        $('.insert-image-tips').html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
                    }, 1000);
                    var get_the_url = res.data.url;
                    $('#upload-img-show').append('<img class="lazyload upload-image-preview" src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.2/img/svg/loader/trans.ajax-spinner-preloader.svg" data-src="' + get_the_url + '" onclick="window.open(\'' + get_the_url + '\')" onerror="imgError(this)" />');
                    lazyload();
                    addComment.createButterbar("图片上传成功~<br>Uploaded successfully~");
                    grin(res.data.url.replace('https://i.loli.net/', '{UPLOAD}'), type = 'Img');
                },
                error: function () {
                    $('.insert-image-tips').html('<i class="fa fa-times" aria-hidden="true" style="color:red"></i>');
                    alert("上传失败，请重试.\nUpload failed, please try again.");
                    setTimeout(function () {
                        $('.insert-image-tips').html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
                    }, 1000);
                }
            })
        }
    });
}

function clean_upload_images() {
    $('#upload-img-show').html('');
}

function add_upload_tips() {
    $('<div class="insert-image-tips popup"><i class="fa fa-picture-o" aria-hidden="true"></i><span class="insert-img-popuptext" id="uploadTipPopup">上传图片</span></div><input id="upload-img-file" type="file" accept="image/*" multiple="multiple" class="insert-image-button">').insertAfter($(".form-submit #submit"));
    attach_image();
    $("#upload-img-file").hover(function () {
        $(".insert-image-tips").addClass("insert-image-tips-hover");
        $("#uploadTipPopup").addClass("show");
    }, function () {
        $(".insert-image-tips").removeClass("insert-image-tips-hover");
        $("#uploadTipPopup").removeClass("show");
    });
}

function click_to_view_image() {
    $(".comment_inline_img").click(function () {
        var temp_url = $(this).attr('src');
        window.open(temp_url);
    });
}
click_to_view_image();

function original_emoji_click() {
    $(".emoji-item").click(function () {
        grin($(this).text(), type = "custom", before = "`", after = "` ");
    });
}
original_emoji_click();

function showPopup(ele) {
    var popup = ele.querySelector("#thePopup");
    popup.classList.toggle("show");
}

function cmt_showPopup(ele) {
    var popup = $(ele).find("#thePopup");
    popup.addClass("show");
    $(ele).find("input").blur(function () {
        popup.removeClass("show");
    });
}

function scrollBar() {
    if (document.body.clientWidth > 860) {
        $(window).scroll(function () {
            var s = $(window).scrollTop();
            var a = $(document).height();
            var b = $(window).height();
            var result = parseInt(s / (a - b) * 100);
            $("#bar").css("width", result + "%");
            if (false) {
                if (result >= 0 && result <= 19)
                    $("#bar").css("background", "#cccccc");
                if (result >= 20 && result <= 39)
                    $("#bar").css("background", "#50bcb6");
                if (result >= 40 && result <= 59)
                    $("#bar").css("background", "#85c440");
                if (result >= 60 && result <= 79)
                    $("#bar").css("background", "#f2b63c");
                if (result >= 80 && result <= 99)
                    $("#bar").css("background", "#FF0000");
                if (result == 100)
                    $("#bar").css("background", "#5aaadb");
            } else {
                $("#bar").css("background", "orange");
            }
            $(".toc-container").css("height", $(".site-content").outerHeight());
            $(".skin-menu").removeClass('show');
        });
    }
}
scrollBar();

function checkBgImgCookie() {
    var bgurl = getCookie("bgImgSetting");
    if (bgurl != "") {
        if (bgurl == "https://view.moezx.cc/images/2018/01/03/sakura.png" || bgurl == "https://view.moezx.cc/images/2018/01/03/plaid2dbf8.jpg" || bgurl == "https://view.moezx.cc/images/2018/01/10/star02.png" || bgurl == "https://view.moezx.cc/images/2018/01/24/kyotoanimation.png" || bgurl == "https://view.moezx.cc/images/2018/03/27/dot_orange.gif") {
            mashiro_global.variables.skinSecter = true;
            mashiro_global.variables.isNight = false;
            $("#night-mode-cover").css("visibility", "hidden");
            $("body").css("background-image", "url(" + bgurl + ")");
            $(".blank").css("background-color", "rgba(255,255,255,1)");
            $(".pattern-center").removeClass('pattern-center').addClass('pattern-center-sakura');
            $(".headertop-bar").removeClass('headertop-bar').addClass('headertop-bar-sakura');
        } else if (bgurl == "https://api.shino.cc/bing/") {
            mashiro_global.variables.skinSecter = true;
            mashiro_global.variables.isNight = true;
            $("#night-mode-cover").css("visibility", "hidden");
            $("body").css("background-image", "url(" + bgurl + ")");
            $(".blank").css("background-color", "rgba(255,255,255,1)");
            $(".pattern-center").removeClass('pattern-center').addClass('pattern-center-sakura');
            $(".headertop-bar").removeClass('headertop-bar').addClass('headertop-bar-sakura');
        } else {}
    } else {}
}
if (document.body.clientWidth > 860) {
    checkBgImgCookie();
}

function no_right_click() {
    $('.post-thumb img').bind('contextmenu', function (e) {
        return false;
    });
}
no_right_click();
if (mashiro_global.variables.isNight) {
    $(".changeSkin-gear, .toc").css("background", "rgba(255,255,255,0.8)");
} else {
    $(".changeSkin-gear, .toc").css("background", "none");
}
$(document).ready(function () {
    function changeBG(tagid, url) {
        $(".skin-menu " + tagid).click(function () {
            mashiro_global.variables.skinSecter = true;
            mashiro_global.variables.isNight = false;
            $("#night-mode-cover").css("visibility", "hidden");
            $("body").css("background-image", "url(" + url + ")");
            $(".blank").css("background-color", "rgba(255,255,255,1)");
            $(".pattern-center").removeClass('pattern-center').addClass('pattern-center-sakura');
            $(".headertop-bar").removeClass('headertop-bar').addClass('headertop-bar-sakura');
            closeSkinMenu();
            setCookie("bgImgSetting", url, 30);
        });
    }

    function changeBGnoTrans(tagid, url) {
        $(".skin-menu " + tagid).click(function () {
            mashiro_global.variables.skinSecter = true;
            mashiro_global.variables.isNight = true;
            $("#night-mode-cover").css("visibility", "hidden");
            $("body").css("background-image", "url(" + url + ")");
            $(".blank").css("background-color", "rgba(255,255,255,1)");
            $(".pattern-center").removeClass('pattern-center').addClass('pattern-center-sakura');
            $(".headertop-bar").removeClass('headertop-bar').addClass('headertop-bar-sakura');
            closeSkinMenu();
            setCookie("bgImgSetting", url, 30);
        });
    }
    changeBG("#sakura-bg", "https://view.moezx.cc/images/2018/01/03/sakura.png");
    changeBG("#gribs-bg", "https://view.moezx.cc/images/2018/01/03/plaid2dbf8.jpg");
    changeBG("#pixiv-bg", "https://view.moezx.cc/images/2018/01/10/star02.png");
    changeBG("#KAdots-bg", "https://view.moezx.cc/images/2018/01/24/kyotoanimation.png");
    changeBG("#totem-bg", "https://view.moezx.cc/images/2018/03/27/dot_orange.gif");
    changeBGnoTrans("#bing-bg", "https://api.shino.cc/bing/");
    $(".skin-menu #white-bg").click(function () {
        mashiro_global.variables.skinSecter = false;
        mashiro_global.variables.isNight = false;
        $("#night-mode-cover").css("visibility", "hidden");
        $("body").css("background-image", "none");
        $(".blank").css("background-color", "rgba(255,255,255,.0)");
        $(".pattern-center-sakura").removeClass('pattern-center-sakura').addClass('pattern-center');
        $(".headertop-bar-sakura").removeClass('headertop-bar-sakura').addClass('headertop-bar');
        closeSkinMenu();
        setCookie("bgImgSetting", "Hello World!", 30);
    });
    $(".skin-menu #dark-bg").click(function () {
        mashiro_global.variables.skinSecter = true;
        mashiro_global.variables.isNight = true;
        $("body").css("background-image", "url(https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.2/other-sites/api-index/images/me.png)");
        $(".blank").css("background-color", "rgba(255,255,255,.8)");
        $("#night-mode-cover").css("visibility", "visible");
        $(".pattern-center").removeClass('pattern-center').addClass('pattern-center-sakura');
        $(".headertop-bar").removeClass('headertop-bar').addClass('headertop-bar-sakura');
        closeSkinMenu();
    });

    function closeSkinMenu() {
        $(".skin-menu").removeClass('show');
        setTimeout(function () {
            $(".changeSkin-gear").css("visibility", "visible");
        }, 300);
        if (mashiro_global.variables.isNight) {
            $(".changeSkin-gear, .toc").css("background", "rgba(255,255,255,0.8)");
        } else {
            $(".changeSkin-gear, .toc").css("background", "none");
        }
    }
    $(".changeSkin-gear").click(function () {
        $(".skin-menu").toggleClass('show');
        if (mashiro_global.variables.isNight) {
            $(".changeSkin").css("background", "rgba(255,255,255,0.8)");
        } else {
            $(".changeSkin").css("background", "none");
        }
    })
    $(".skin-menu #close-skinMenu").click(function () {
        closeSkinMenu();
    });
    add_upload_tips();
});
var bgn = 1;

function nextBG() {
    $(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + "?" + bgn + ")");
    bgn = bgn + 1;
}

function preBG() {
    bgn = bgn - 1;
    $(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + "?" + bgn + ")");
}
$(document).ready(function () {
    $("#bg-next").click(function () {
        nextBG();
    });
    $("#bg-pre").click(function () {
        preBG();
    });
});
if (document.body.clientWidth <= 860) {
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("moblieGoTop").style.display = "block";
        } else {
            document.getElementById("moblieGoTop").style.display = "none";
        }
    }

    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
}

function timeSeriesReload(flag) {
    if (flag == true) {
        $('#archives span.al_mon').click(function () {
            $(this).next().slideToggle(400);
            return false;
        });
        lazyload();
    } else {
        (function () {
            $('#al_expand_collapse,#archives span.al_mon').css({
                cursor: "s-resize"
            });
            $('#archives span.al_mon').each(function () {
                var num = $(this).next().children('li').length;
                $(this).children('#post-num').text(num);
            });
            var $al_post_list = $('#archives ul.al_post_list'),
                $al_post_list_f = $('#archives ul.al_post_list:first');
            $al_post_list.hide(1, function () {
                $al_post_list_f.show();
            });
            $('#archives span.al_mon').click(function () {
                $(this).next().slideToggle(400);
                return false;
            });
            if (document.body.clientWidth > 860) {
                $('#archives li.al_li').mouseover(function () {
                    $(this).children('.al_post_list').show(400);
                    return false;
                });
                if (false) {
                    $('#archives li.al_li').mouseout(function () {
                        $(this).children('.al_post_list').hide(400);
                        return false;
                    });
                }
            }
            var al_expand_collapse_click = 0;
            $('#al_expand_collapse').click(function () {
                if (al_expand_collapse_click == 0) {
                    $al_post_list.show();
                    al_expand_collapse_click++;
                } else if (al_expand_collapse_click == 1) {
                    $al_post_list.hide();
                    al_expand_collapse_click--;
                }
            });
        })();
    }
}
timeSeriesReload();

/*视频feature*/
function coverVideo() {
    var video = document.getElementById("coverVideo");
    var btn = document.getElementById("coverVideo-btn");

    if (video.paused) {
        video.play();
        try {
            btn.innerHTML = '<i class="fa fa-pause" aria-hidden="true"></i>';
        } catch (e) {};
        //console.info('play:coverVideo()');
    } else {
        video.pause();
        try {
            btn.innerHTML = '<i class="fa fa-play" aria-hidden="true"></i>';
        } catch (e) {};
        //console.info('pause:coverVideo()');
    }
}

function killCoverVideo() {
    var video = document.getElementById("coverVideo");
    var btn = document.getElementById("coverVideo-btn");

    if (video.paused) {
        //console.info('none:killCoverVideo()');
    } else {
        video.pause();
        try {
            btn.innerHTML = '<i class="fa fa-play" aria-hidden="true"></i>';
        } catch (e) {};
        //console.info('pause:killCoverVideo()');
    }
}

function coverVideoIni() {
    if ($('video').hasClass('hls')) {
        var video = document.getElementById('coverVideo');
        var video_src = $('#coverVideo').attr('data-src');
        if(Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource(video_src);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED,function() {
                video.play();
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = video_src;
            video.addEventListener('loadedmetadata',function() {
                video.play();
            });
        }
        //console.info('ini:coverVideoIni()');
    }
}

function copy_code_block() {
    $('pre code').each(function (i, block) {
        $(block).attr({ id: 'hljs-' + i });
        $(this).after('<a class="copy-code" href="javascript:" data-clipboard-target="#hljs-' + i + '" title="拷贝代码"><i class="fa fa-clipboard" aria-hidden="true"></i></a>');
    });
    var clipboard = new ClipboardJS('.copy-code');
}

function tableOfContentScroll(flag) {
    if (document.body.clientWidth <= 1200) {
        return;
    } else if ($("div").hasClass("have-toc") == false && $("div").hasClass("has-toc") == false) {
        $(".toc-container").remove();
    } else {
        $(document).ready(function () {
            if ($("div").hasClass("toc")) {
                $(".toc-container").css("height", $(".site-content").outerHeight());
                setTimeout(function () {
                    $(".toc-container").css("height", $(".site-content").outerHeight());
                }, 1000);
                setTimeout(function () {
                    $(".toc-container").css("height", $(".site-content").outerHeight());
                }, 6000);
            }
        });
        if (flag) {
            var id = 1;
            $(".entry-content , .links").children("h1,h2,h3,h4,h5").each(function () {
                var hyphenated = "toc-head-" + id;
                $(this).attr('id', hyphenated);
                id++;
            });
            tocbot.init({
                tocSelector: '.toc',
                contentSelector: ['.entry-content', '.links'],
                headingSelector: 'h1, h2, h3, h4, h5',
            });
        }
    }
}
tableOfContentScroll(flag = true);
var pjaxInit = function () {
    add_upload_tips();
    no_right_click();
    click_to_view_image();
    original_emoji_click();
    mashiro_global.font_control.ini();
    $("p").remove(".head-copyright");
    try {
        code_highlight_style();
    } catch (e) {};
    try {
        getqqinfo();
    } catch (e) {};
    lazyload();
    $("#to-load-aplayer").click(function () {
        try {
            reloadHermit();
        } catch (e) {};
        $("div").remove(".load-aplayer");
    });
    if ($("div").hasClass("aplayer")) {
        try {
            reloadHermit();
        } catch (e) {};
    }
    if (mashiro_global.variables.skinSecter === true) {
        $(".pattern-center").removeClass('pattern-center').addClass('pattern-center-sakura');
        $(".headertop-bar").removeClass('headertop-bar').addClass('headertop-bar-sakura');
        if (mashiro_global.variables.isNight) {
            $(".blank").css("background-color", "rgba(255,255,255,1)");
            $(".toc").css("background-color", "rgba(255,255,255,0.8)");
        }
    }
    $('.iconflat').css('width', '50px').css('height', '50px');
    $('.openNav').css('height', '50px');
    $("#bg-next").click(function () {
        nextBG();
    });
    $("#bg-pre").click(function () {
        preBG();
    });
    smileBoxToggle();
    timeSeriesReload();
    add_copyright();
    tableOfContentScroll(flag = true);
    console.log($("#myscript").text());
}
$(document).on("click", ".sm", function () {
    var msg = "您真的要设为私密吗？";
    if (confirm(msg) == true) {
        $(this).commentPrivate();
    } else {
        aler("已取消");
    }
});
$.fn.commentPrivate = function () {
    if ($(this).hasClass('private_now')) {
        alert('您之前已设过私密评论');
        return false;
    } else {
        $(this).addClass('private_now');
        var idp = $(this).data('idp'),
            actionp = $(this).data('actionp'),
            rateHolderp = $(this).children('.has_set_private');
        var ajax_data = {
            action: "siren_private",
            p_id: idp,
            p_action: actionp
        };
        $.post("/wp-admin/admin-ajax.php", ajax_data, function (data) {
            $(rateHolderp).html(data);
        });
        return false;
    }
};

POWERMODE.colorful = true;
POWERMODE.shake = false;
document.body.addEventListener('input', POWERMODE);

function motionSwitch(ele) {
    var motionEles = [".bili", ".menhera", ".tieba"];
    for (var i in motionEles) {
        $(motionEles[i] + '-bar').removeClass("on-hover");
        $(motionEles[i] + '-container').css("display", "none");
    }
    $(ele + '-bar').addClass("on-hover");
    $(ele + '-container').css("display", "block");
}
$('.comt-addsmilies').click(function () {
    $('.comt-smilies').toggle();
})
$('.comt-smilies a').click(function () {
    $(this).parent().hide();
})

function smileBoxToggle() {
    $(document).ready(function () {
        $("#emotion-toggle").click(function () {
            $(".emotion-toggle-off").toggle(0);
            $(".emotion-toggle-on").toggle(0);
            $(".emotion-box").toggle(160);
        });
    });
}
smileBoxToggle();

function grin(tag, type, before, after) {
    var myField;
    if (type == "custom") {
        tag = before + tag + after;
    } else if (type == "Img") {
        tag = '[img]' + tag + '[/img]';
    } else if (type == "Math") {
        tag = ' {{' + tag + '}} ';
    } else {
        tag = ' :' + tag + ': ';
    }
    if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
        myField = document.getElementById('comment');
    } else {
        return false;
    }
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = tag;
        myField.focus();
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        var cursorPos = endPos;
        myField.value = myField.value.substring(0, startPos) + tag + myField.value.substring(endPos, myField.value.length);
        cursorPos += tag.length;
        myField.focus();
        myField.selectionStart = cursorPos;
        myField.selectionEnd = cursorPos;
    } else {
        myField.value += tag;
        myField.focus();
    }
}

function add_copyright() {
    document.body.addEventListener("copy", function (e) {
        if (window.getSelection().toString().length > 30 && mashiro_option.clipboardCopyright) {
            setClipboardText(e);
        }
        addComment.createButterbar("复制成功！<br>Copied to clipboard successfully!", 1000);
    });

    function setClipboardText(event) {
        event.preventDefault();
        var htmlData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。<br>" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.<br>" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)<br>" + "# 作者(Author)：" + mashiro_option.author_name + "<br>" + "# 链接(URL)：" + window.location.href + "<br>" + "# 来源(Source)：" + mashiro_option.site_name + "<br><br>" + window.getSelection().toString().replace(/\r\n/g, "<br>");;
        var textData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。\n" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.\n" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)\n" + "# 作者(Author)：" + mashiro_option.author_name + "\n" + "# 链接(URL)：" + window.location.href + "\n" + "# 来源(Source)：" + mashiro_option.site_name + "\n\n" + window.getSelection().toString().replace(/\r\n/g, "\n");
        if (event.clipboardData) {
            event.clipboardData.setData("text/html", htmlData);
            event.clipboardData.setData("text/plain", textData);
        } else if (window.clipboardData) {
            return window.clipboardData.setData("text", textData);
        }
    }
}
add_copyright();
$(function () {
    getqqinfo();
});

if(mashiro_option.float_player_on) {
    function aplayerF() {
        'use strict';
        var aplayers = [],
            loadMeting = function () {
                function a(a, b) {
                    var c = {
                        container: a,
                        audio: b,
                        mini: null,
                        fixed: null,
                        autoplay: !1,
                        mutex: !0,
                        lrcType: 3,
                        listFolded: !1,
                        preload: 'auto',
                        theme: '#2980b9',
                        loop: 'all',
                        order: 'list',
                        volume: null,
                        listMaxHeight: null,
                        customAudioType: null,
                        storageName: 'metingjs'
                    };
                    if (b.length) {
                        b[0].lrc || (c.lrcType = 0);
                        var d = {};
                        for (var e in c) {
                            var f = e.toLowerCase();
                            (a.dataset.hasOwnProperty(f) || a.dataset.hasOwnProperty(e) || null !== c[e]) && (d[e] = a.dataset[f] || a.dataset[e] || c[e], ('true' === d[e] || 'false' === d[e]) && (d[e] = 'true' == d[e]))
                        }
                        aplayers.push(new APlayer(d))
                    }
                    for (var f = 0; f < aplayers.length; f++) try {
                        aplayers[f].lrc.hide();
                    } catch (a) {
                        console.log(a)
                    }
                    var lrcTag = 1;
                    $(".aplayer.aplayer-fixed").click(function(){
                        if (lrcTag == 1) {
                            for (var f = 0; f < aplayers.length; f++) try {
                                aplayers[f].lrc.show();
                            } catch (a) {
                                console.log(a)
                            }
                        }
                        lrcTag = 2;
                    });
                    var apSwitchTag = 0;
                    $(".aplayer.aplayer-fixed .aplayer-body").addClass("ap-hover");
                    $(".aplayer-miniswitcher").click(function(){
                        if (apSwitchTag == 0) {
                            $(".aplayer.aplayer-fixed .aplayer-body").removeClass( "ap-hover" );
                            apSwitchTag = 1;
                        } else {
                            $(".aplayer.aplayer-fixed .aplayer-body").addClass( "ap-hover" );
                            apSwitchTag =0;
                        }
                    });
                }
                var b = 'https://api.i-meto.com/meting/api?server=:server&type=:type&id=:id&r=:r';
                'undefined' != typeof meting_api && (b = meting_api);
                for (var f = 0; f < aplayers.length; f++) try {
                    aplayers[f].destroy()
                } catch (a) {
                    console.log(a)
                }
                aplayers = [];
                for (var c = document.querySelectorAll('.aplayer'), d = function () {
                        var d = c[e],
                            f = d.dataset.id;
                        if (f) {
                            var g = d.dataset.api || b;
                            g = g.replace(':server', d.dataset.server), g = g.replace(':type', d.dataset.type), g = g.replace(':id', d.dataset.id), g = g.replace(':auth', d.dataset.auth), g = g.replace(':r', Math.random());
                            var h = new XMLHttpRequest;
                            h.onreadystatechange = function () {
                                if (4 === h.readyState && (200 <= h.status && 300 > h.status || 304 === h.status)) {
                                    var b = JSON.parse(h.responseText);
                                    a(d, b)
                                }
                            }, h.open('get', g, !0), h.send(null)
                        } else if (d.dataset.url) {
                            var i = [{
                                name: d.dataset.name || d.dataset.title || 'Audio name',
                                artist: d.dataset.artist || d.dataset.author || 'Audio artist',
                                url: d.dataset.url,
                                cover: d.dataset.cover || d.dataset.pic,
                                lrc: d.dataset.lrc,
                                type: d.dataset.type || 'auto'
                            }];
                            a(d, i)
                        }
                    }, e = 0; e < c.length; e++) d()
            };
        document.addEventListener('DOMContentLoaded', loadMeting, !1);
    }
    if (document.body.clientWidth > 860) {
        aplayerF ();
    }
}

function getqqinfo() {
    var is_get_by_qq = false;
    if (!getCookie('user_qq') && !getCookie('user_qq_email') && !getCookie('user_author')) {
        $('input#qq,input#author,input#email,input#url').val('');
    }
    if (getCookie('user_avatar') && getCookie('user_qq') && getCookie('user_qq_email')) {
        $('div.comment-user-avatar img').attr('src', getCookie('user_avatar'));
        $('input#author').val(getCookie('user_author'));
        $('input#email').val(getCookie('user_qq') + '@qq.com');
        $('input#qq').val(getCookie('user_qq'));
        if (mashiro_option.qzone_autocomplete) {
            $('input#url').val('https://user.qzone.qq.com/' + getCookie('user_qq'));
        }
        if ($('input#qq').val()) {
            $('.qq-check').css('display', 'block');
            $('.gravatar-check').css('display', 'none');
        }
    }
    var emailAddressFlag = $('input#email').val();
    $('input#author').on('blur', function () {
        var qq = $('input#author').val();
        $.ajax({
            type: 'get',
            url: mashiro_option.qq_api_url + '?type=getqqnickname&qq=' + qq,
            dataType: 'jsonp',
            jsonp: 'callback',
            jsonpCallback: 'portraitCallBack',
            success: function (data) {
                $('input#author').val(data[qq][6]);
                $('input#email').val($.trim(qq) + '@qq.com');
                if (mashiro_option.qzone_autocomplete) {
                    $('input#url').val('https://user.qzone.qq.com/' + $.trim(qq));
                }
                $('div.comment-user-avatar img').attr('src', 'https://q2.qlogo.cn/headimg_dl?dst_uin=' + qq + '&spec=100');
                is_get_by_qq = true;
                $('input#qq').val($.trim(qq));
                if ($('input#qq').val()) {
                    $('.qq-check').css('display', 'block');
                    $('.gravatar-check').css('display', 'none');
                }
                setCookie('user_author', data[qq][6], 30);
                setCookie('user_qq', qq, 30);
                setCookie('is_user_qq', 'yes', 30);
                setCookie('user_qq_email', qq + '@qq.com', 30);
                setCookie('user_email', qq + '@qq.com', 30);
                emailAddressFlag = $('input#email').val();
            },
            error: function () {
                $('input#qq').val('');
                $('.qq-check').css('display', 'none');
                $('.gravatar-check').css('display', 'block');
                $('div.comment-user-avatar img').attr('src', get_gravatar($('input#email').val(), 80));
                setCookie('user_qq', '', 30);
                setCookie('user_email', $('input#email').val(), 30);
                setCookie('user_avatar', get_gravatar($('input#email').val(), 80), 30);
            }
        });
        $.ajax({
            type: 'get',
            url: mashiro_option.qq_avatar_api_url + '?type=getqqavatar&qq=' + qq,
            dataType: 'jsonp',
            jsonp: 'callback',
            jsonpCallback: 'qqavatarCallBack',
            success: function (data) {
                $('div.comment-user-avatar img').attr('src', data[qq]);
                setCookie('user_avatar', data[qq], 30);
            },
            error: function () {
                $('input#qq', 'input#email', 'input#url').val('');
                if (!$('input#qq').val()) {
                    $('.qq-check').css('display', 'none');
                    $('.gravatar-check').css('display', 'block');
                    setCookie('user_qq', '', 30);
                    $('div.comment-user-avatar img').attr('src', get_gravatar($('input#email').val(), 80));
                    setCookie('user_avatar', get_gravatar($('input#email').val(), 80), 30);
                }
            }
        });
    });
    if (getCookie('user_avatar') && getCookie('user_email') && getCookie('is_user_qq') == 'no' && !getCookie('user_qq_email')) {
        $('div.comment-user-avatar img').attr('src', getCookie('user_avatar'));
        $('input#email').val(getCookie('user_email'));
        $('input#qq').val('');
        if (!$('input#qq').val()) {
            $('.qq-check').css('display', 'none');
            $('.gravatar-check').css('display', 'block');
        }
    }
    $('input#email').on('blur', function () {
        var emailAddress = $('input#email').val();
        if (is_get_by_qq == false || emailAddressFlag != emailAddress) {
            $('div.comment-user-avatar img').attr('src', get_gravatar(emailAddress, 80));
            setCookie('user_avatar', get_gravatar(emailAddress, 80), 30);
            setCookie('user_email', emailAddress, 30);
            setCookie('user_qq_email', '', 30);
            setCookie('is_user_qq', 'no', 30);
            $('input#qq').val('');
            if (!$('input#qq').val()) {
                $('.qq-check').css('display', 'none');
                $('.gravatar-check').css('display', 'block');
            }
        }
    });
    if (getCookie('user_url')) {
        $('input#url').val(getCookie('user_url'));
    }
    $('input#url').on('blur', function () {
        var URL_Address = $('input#url').val();
        $('input#url').val(URL_Address);
        setCookie('user_url', URL_Address, 30);
    });
    if (getCookie('user_author')) {
        $('input#author').val(getCookie('user_author'));
    }
    $('input#author').on('blur', function () {
        var user_name = $('input#author').val();
        $('input#author').val(user_name);
        setCookie('user_author', user_name, 30);
    });
}

function mail_me() {
    var mail = "mailto:" + mashiro_option.email_name + "@" + mashiro_option.email_domain;
    window.open(mail);
}

mashiro_global.ini.normalize();
loadCSS(mashiro_option.jsdelivr_css_src);
loadCSS(mashiro_option.entry_content_theme_src);
loadCSS("https://at.alicdn.com/t/font_679578_qyt5qzzavdo39pb9.css");
loadCSS("https://cdn.jsdelivr.net/npm/aplayer@1.10.1/dist/APlayer.min.css");
loadCSS("https://fonts.googleapis.com/css?family=Noto+SerifMerriweather|Merriweather+Sans|Source+Code+Pro|Ubuntu:400,700|Noto+Serif+SC");
(function webpackUniversalModuleDefinition(b, a) {
    if (typeof exports === "object" && typeof module === "object") {
        module.exports = a()
    } else {
        if (typeof define === "function" && define.amd) {
            define([], a)
        } else {
            if (typeof exports === "object") {
                exports.POWERMODE = a()
            } else {
                b.POWERMODE = a()
            }
        }
    }
})(this, function () {
    return (function (c) {
        var b = {};

        function a(e) {
            if (b[e]) {
                return b[e].exports
            }
            var d = b[e] = {
                exports: {},
                id: e,
                loaded: false
            };
            c[e].call(d.exports, d, d.exports, a);
            d.loaded = true;
            return d.exports
        }
        a.m = c;
        a.c = b;
        a.p = "";
        return a(0)
    })([function (j, e, a) {
        var b = document.createElement("canvas");
        b.width = window.innerWidth;
        b.height = window.innerHeight;
        b.style.cssText = "position:fixed;top:0;left:0;pointer-events:none;z-index:999999";
        window.addEventListener("resize", function () {
            b.width = window.innerWidth;
            b.height = window.innerHeight
        });
        document.body.appendChild(b);
        var c = b.getContext("2d");
        var l = [];
        var k = 0;
        m.shake = true;

        function h(o, n) {
            return Math.random() * (n - o) + o
        }

        function g(n) {
            if (m.colorful) {
                var o = h(0, 360);
                return "hsla(" + h(o - 10, o + 10) + ", 100%, " + h(50, 80) + "%, " + 1 + ")"
            } else {
                return window.getComputedStyle(n).color
            }
        }

        function f() {
            var o = document.activeElement;
            var n;
            if (o.tagName === "TEXTAREA" || (o.tagName === "INPUT" && o.getAttribute("type") === "text")) {
                var p = a(1)(o, o.selectionStart);
                n = o.getBoundingClientRect();
                return {
                    x: p.left + n.left,
                    y: p.top + n.top,
                    color: g(o)
                }
            }
            var r = window.getSelection();
            if (r.rangeCount) {
                var q = r.getRangeAt(0);
                var s = q.startContainer;
                if (s.nodeType === document.TEXT_NODE) {
                    s = s.parentNode
                }
                n = q.getBoundingClientRect();
                return {
                    x: n.left,
                    y: n.top,
                    color: g(s)
                }
            }
            return {
                x: 0,
                y: 0,
                color: "transparent"
            }
        }

        function d(o, p, n) {
            return {
                x: o,
                y: p,
                alpha: 1,
                color: n,
                velocity: {
                    x: -1 + Math.random() * 2,
                    y: -3.5 + Math.random() * 2
                }
            }
        }

        function m() {
            var n = f();
            var p = 5 + Math.round(Math.random() * 10);
            while (p--) {
                l[k] = d(n.x, n.y, n.color);
                k = (k + 1) % 500
            }
            if (m.shake) {
                var o = 1 + 2 * Math.random();
                var q = o * (Math.random() > 0.5 ? -1 : 1);
                var r = o * (Math.random() > 0.5 ? -1 : 1);
                document.body.style.marginLeft = q + "px";
                document.body.style.marginTop = r + "px";
                setTimeout(function () {
                    document.body.style.marginLeft = "";
                    document.body.style.marginTop = ""
                }, 75)
            }
        }
        m.colorful = false;

        function i() {
            requestAnimationFrame(i);
            c.clearRect(0, 0, b.width, b.height);
            for (var n = 0; n < l.length; ++n) {
                var o = l[n];
                if (o.alpha <= 0.1) {
                    continue
                }
                o.velocity.y += 0.075;
                o.x += o.velocity.x;
                o.y += o.velocity.y;
                o.alpha *= 0.96;
                c.globalAlpha = o.alpha;
                c.fillStyle = o.color;
                c.fillRect(Math.round(o.x - 1.5), Math.round(o.y - 1.5), 3, 3)
            }
        }
        requestAnimationFrame(i);
        j.exports = m
    }, function (b, a) {
        (function () {
            var e = ["direction", "boxSizing", "width", "height", "overflowX", "overflowY", "borderTopWidth", "borderRightWidth", "borderBottomWidth", "borderLeftWidth", "borderStyle", "paddingTop", "paddingRight", "paddingBottom", "paddingLeft", "fontStyle", "fontVariant", "fontWeight", "fontStretch", "fontSize", "fontSizeAdjust", "lineHeight", "fontFamily", "textAlign", "textTransform", "textIndent", "textDecoration", "letterSpacing", "wordSpacing", "tabSize", "MozTabSize"];
            var d = window.mozInnerScreenX != null;

            function c(k, m, l) {
                var h = l && l.debug || false;
                if (h) {
                    var j = document.querySelector("#input-textarea-caret-position-mirror-div");
                    if (j) {
                        j.parentNode.removeChild(j)
                    }
                }
                var i = document.createElement("div");
                i.id = "input-textarea-caret-position-mirror-div";
                document.body.appendChild(i);
                var o = i.style;
                var f = window.getComputedStyle ? getComputedStyle(k) : k.currentStyle;
                o.whiteSpace = "pre-wrap";
                if (k.nodeName !== "INPUT") {
                    o.wordWrap = "break-word"
                }
                o.position = "absolute";
                if (!h) {
                    o.visibility = "hidden"
                }
                e.forEach(function (p) {
                    o[p] = f[p]
                });
                if (d) {
                    if (k.scrollHeight > parseInt(f.height)) {
                        o.overflowY = "scroll"
                    }
                } else {
                    o.overflow = "hidden"
                }
                i.textContent = k.value.substring(0, m);
                if (k.nodeName === "INPUT") {
                    i.textContent = i.textContent.replace(/\s/g, "\u00a0")
                }
                var n = document.createElement("span");
                n.textContent = k.value.substring(m) || ".";
                i.appendChild(n);
                var g = {
                    top: n.offsetTop + parseInt(f.borderTopWidth),
                    left: n.offsetLeft + parseInt(f.borderLeftWidth)
                };
                if (h) {
                    n.style.backgroundColor = "#aaa"
                } else {
                    document.body.removeChild(i)
                }
                return g
            }
            if (typeof b != "undefined" && typeof b.exports != "undefined") {
                b.exports = c
            } else {
                window.getCaretCoordinates = c
            }
        }())
    }])
});

var home = location.href,
    s = $('#bgvideo')[0],
    Siren = {
        MN: function () {
            $('.iconflat').on('click', function () {
                if ($("#main-container").hasClass("open")) {
                    $('.iconflat').css('width', '50px').css('height', '50px');
                    $('.openNav').css('height', '50px');
                } else {
                    $('.iconflat').css('width', '100%').css('height', '100%');
                    $('.openNav').css('height', '100%');
                }
                $('body').toggleClass('navOpen');
                $('#main-container,#mo-nav,.openNav').toggleClass('open');
            });
        },
        MNH: function () {
            if ($('body').hasClass('navOpen')) {
                $('body').toggleClass('navOpen');
                $('#main-container,#mo-nav,.openNav').toggleClass('open');
            }
        },
        splay: function () {
            $('#video-btn').addClass('video-pause').removeClass('video-play').show();
            $('.video-stu').css({
                "bottom": "-100px"
            });
            $('.focusinfo').css({
                "top": "-999px"
            });
            try {
                for (var i = 0; i < ap.length; i++) {
                    try {
                        ap[i].destroy()
                    } catch (e) {}
                }
            } catch (e) {}
            try {
                hermitInit()
            } catch (e) {}
            s.play();
        },
        spause: function () {
            $('#video-btn').addClass('video-play').removeClass('video-pause');
            $('.focusinfo').css({
                "top": "49.3%"
            });
            s.pause();
        },
        liveplay: function () {
            if (s.oncanplay != undefined && $('.haslive').length > 0) {
                if ($('.videolive').length > 0) {
                    Siren.splay();
                }
            }
        },
        livepause: function () {
            if (s.oncanplay != undefined && $('.haslive').length > 0) {
                Siren.spause();
                $('.video-stu').css({
                    "bottom": "0px"
                }).html('已暂停 ...');
            }
        },
        addsource: function () {
            $('.video-stu').html('正在载入视频 ...').css({
                "bottom": "0px"
            });
            var t = Poi.movies.name.split(","),
                _t = t[Math.floor(Math.random() * t.length)];
            $('#bgvideo').attr('src', Poi.movies.url + '/' + _t + '.mp4');
            $('#bgvideo').attr('video-name', _t);
        },
        LV: function () {
            var _btn = $('#video-btn');
            _btn.on('click', function () {
                if ($(this).hasClass('loadvideo')) {
                    $(this).addClass('video-pause').removeClass('loadvideo').hide();
                    Siren.addsource();
                    s.oncanplay = function () {
                        Siren.splay();
                        $('#video-add').show();
                        _btn.addClass('videolive');
                        _btn.addClass('haslive');
                    }
                } else {
                    if ($(this).hasClass('video-pause')) {
                        Siren.spause();
                        _btn.removeClass('videolive');
                        $('.video-stu').css({
                            "bottom": "0px"
                        }).html('已暂停 ...');
                    } else {
                        Siren.splay();
                        _btn.addClass('videolive');
                    }
                }
                s.onended = function () {
                    $('#bgvideo').attr('src', '');
                    $('#video-add').hide();
                    _btn.addClass('loadvideo').removeClass('video-pause');
                    _btn.removeClass('videolive');
                    _btn.removeClass('haslive');
                    $('.focusinfo').css({
                        "top": "49.3%"
                    });
                }
            });
            $('#video-add').on('click', function () {
                Siren.addsource();
            });
        },
        AH: function () {
            if (Poi.windowheight == 'auto') {
                if ($('h1.main-title').length > 0) {
                    var _height = $(window).height();
                    $('#centerbg').css({
                        'height': _height
                    });
                    $('#bgvideo').css({
                        'min-height': _height
                    });
                    window.resizeFlag= null;
                    $(window).resize(function () {
                        //直接resize性能爆炸，改成延时
                        if(resizeFlag!=null){
                            clearTimeout(resizeFlag);
                        }
                        resizeFlag = setTimeout(function(){ Siren.AH();resizeFlag=null }, 1000);
                    })
                }
            } else {
                $('.headertop').addClass('headertop-bar');
            }
        },
        PE: function () {
            if ($('.headertop').length > 0) {
                if ($('h1.main-title').length > 0) {
                    $('.blank').css({
                        "padding-top": "0px"
                    });
                    $('.headertop').css({
                        "height": "auto"
                    }).show();
                    if (Poi.movies.live == 'open') Siren.liveplay();
                } else {
                    $('.blank').css({
                        "padding-top": "75px"
                    });
                    $('.headertop').css({
                        "height": "0px"
                    }).hide();
                    Siren.livepause();
                }
            }
        },
        CE: function () {
            $('.comments-hidden').show();
            $('.comments-main').hide();
            $('.comments-hidden').click(function () {
                $('.comments-main').slideDown(500);
                $('.comments-hidden').hide();
            });
            $('.archives').hide();
            $('.archives:first').show();
            $('#archives-temp h3').click(function () {
                $(this).next().slideToggle('fast');
                return false;
            });
            if (mashiro_option.baguetteBoxON) {
                baguetteBox.run('.entry-content', {
                    captions: function (element) {
                        return element.getElementsByTagName('img')[0].alt;
                    },
                    ignoreClass: 'fancybox',
                });
            }
            $('.js-toggle-search').on('click', function () {
                $('.js-toggle-search').toggleClass('is-active');
                $('.js-search').toggleClass('is-visible');
            });
            $('.search_close').on('click', function () {
                if ($('.js-search').hasClass('is-visible')) {
                    $('.js-toggle-search').toggleClass('is-active');
                    $('.js-search').toggleClass('is-visible');
                }
            });
            $('#show-nav').on('click', function () {
                if ($('#show-nav').hasClass('showNav')) {
                    $('#show-nav').removeClass('showNav').addClass('hideNav');
                    $('.site-top .lower nav').addClass('navbar');
                } else {
                    $('#show-nav').removeClass('hideNav').addClass('showNav');
                    $('.site-top .lower nav').removeClass('navbar');
                }
            });
            $("#loading").click(function () {
                $("#loading").fadeOut(500);
            });
        },
        NH: function () {
            var h1 = 0,
                h2 = 50,
                ss = $(document).scrollTop();
            $(window).scroll(function () {
                var s = $(document).scrollTop();
                if (s == h1) {
                    $('.site-header').removeClass('yya');
                }
                if (s > h1) {
                    $('.site-header').addClass('yya');
                }
                if (s > h2) {
                    $('.site-header').addClass('gizle');
                    if (s > ss) {
                        $('.site-header').removeClass('sabit');
                    } else {
                        $('.site-header').addClass('sabit');
                    }
                    ss = s;
                }
            });
        },
        XLS: function () {
            $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            $('body').on('click', '#pagination a', function () {
                $(this).addClass("loading").text("");
                $.ajax({
                    type: "POST",
                    url: $(this).attr("href") + "#main",
                    success: function (data) {
                        result = $(data).find("#main .post");
                        nextHref = $(data).find("#pagination a").attr("href");
                        $("#main").append(result.fadeIn(500));
                        $("#pagination a").removeClass("loading").text("Previous");
                        lazyload();
                        mashiro_global.post_list_show_animation.ini(50);
                        if (nextHref != undefined) {
                            $("#pagination a").attr("href", nextHref);
                        } else {
                            $("#pagination").html("<span>很高兴你翻到这里，但是真的没有了...</span>");
                        }
                    }
                });
                return false;
            });
        },
        XCS: function () {
            var __cancel = jQuery('#cancel-comment-reply-link'),
                __cancel_text = __cancel.text(),
                __list = 'commentwrap';
            jQuery(document).on("submit", "#commentform", function () {
                jQuery.ajax({
                    url: Poi.ajaxurl,
                    data: jQuery(this).serialize() + "&action=ajax_comment",
                    type: jQuery(this).attr('method'),
                    beforeSend: addComment.createButterbar("提交中(Commiting)...."),
                    error: function (request) {
                        var t = addComment;
                        t.createButterbar(request.responseText);
                    },
                    success: function (data) {
                        jQuery('textarea').each(function () {
                            this.value = ''
                        });
                        var t = addComment,
                            cancel = t.I('cancel-comment-reply-link'),
                            temp = t.I('wp-temp-form-div'),
                            respond = t.I(t.respondId),
                            post = t.I('comment_post_ID').value,
                            parent = t.I('comment_parent').value;
                        if (parent != '0') {
                            jQuery('#respond').before('<ol class="children">' + data + '</ol>');
                        } else if (!jQuery('.' + __list).length) {
                            if (Poi.formpostion == 'bottom') {
                                jQuery('#respond').before('<ol class="' + __list + '">' + data + '</ol>');
                            } else {
                                jQuery('#respond').after('<ol class="' + __list + '">' + data + '</ol>');
                            }
                        } else {
                            if (Poi.order == 'asc') {
                                jQuery('.' + __list).append(data);
                            } else {
                                jQuery('.' + __list).prepend(data);
                            }
                        }
                        t.createButterbar("提交成功(Succeed)");
                        lazyload();
                        code_highlight_style();
                        click_to_view_image();
                        clean_upload_images();
                        cancel.style.display = 'none';
                        cancel.onclick = null;
                        t.I('comment_parent').value = '0';
                        if (temp && respond) {
                            temp.parentNode.insertBefore(respond, temp);
                            temp.parentNode.removeChild(temp)
                        }
                    }
                });
                return false;
            });
            addComment = {
                moveForm: function (commId, parentId, respondId) {
                    var t = this,
                        div, comm = t.I(commId),
                        respond = t.I(respondId),
                        cancel = t.I('cancel-comment-reply-link'),
                        parent = t.I('comment_parent'),
                        post = t.I('comment_post_ID');
                    __cancel.text(__cancel_text);
                    t.respondId = respondId;
                    if (!t.I('wp-temp-form-div')) {
                        div = document.createElement('div');
                        div.id = 'wp-temp-form-div';
                        div.style.display = 'none';
                        respond.parentNode.insertBefore(div, respond)
                    }!comm ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
                    jQuery("body").animate({
                        scrollTop: jQuery('#respond').offset().top - 180
                    }, 400);
                    parent.value = parentId;
                    cancel.style.display = '';
                    cancel.onclick = function () {
                        var t = addComment,
                            temp = t.I('wp-temp-form-div'),
                            respond = t.I(t.respondId);
                        t.I('comment_parent').value = '0';
                        if (temp && respond) {
                            temp.parentNode.insertBefore(respond, temp);
                            temp.parentNode.removeChild(temp);
                        }
                        this.style.display = 'none';
                        this.onclick = null;
                        return false;
                    };
                    try {
                        t.I('comment').focus();
                    } catch (e) {}
                    return false;
                },
                I: function (e) {
                    return document.getElementById(e);
                },
                clearButterbar: function (e) {
                    if (jQuery(".butterBar").length > 0) {
                        jQuery(".butterBar").remove();
                    }
                },
                createButterbar: function (message, showtime) {
                    var t = this;
                    t.clearButterbar();
                    jQuery("body").append('<div class="butterBar butterBar--center"><p class="butterBar-message">' + message + '</p></div>');
                    if (showtime > 0) {
                        setTimeout("jQuery('.butterBar').remove()", showtime);
                    } else {
                        setTimeout("jQuery('.butterBar').remove()", 6000);
                    }
                }
            };
        },
        XCP: function () {
            $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            $('body').on('click', '#comments-navi a', function (e) {
                e.preventDefault();
                $.ajax({
                    type: "GET",
                    url: $(this).attr('href'),
                    beforeSend: function () {
                        $('#comments-navi').remove();
                        $('ul.commentwrap').remove();
                        $('#loading-comments').slideDown();
                        $body.animate({
                            scrollTop: $('#comments-list-title').offset().top - 65
                        }, 800);
                    },
                    dataType: "html",
                    success: function (out) {
                        result = $(out).find('ul.commentwrap');
                        nextlink = $(out).find('#comments-navi');
                        $('#loading-comments').slideUp('fast');
                        $('#loading-comments').after(result.fadeIn(500));
                        $('ul.commentwrap').after(nextlink);
                        lazyload();
                        code_highlight_style();
                        click_to_view_image();
                    }
                });
            });
        },
        IA: function () {
            POWERMODE.colorful = true;
            POWERMODE.shake = false;
            document.body.addEventListener('input', POWERMODE)
        },
        GT: function () {
            var offset = 100,
                offset_opacity = 1200,
                scroll_top_duration = 700,
                $back_to_top = $('.cd-top');
            $(window).scroll(function () {
                if ($(this).scrollTop() > offset) {
                    $back_to_top.addClass('cd-is-visible');
                    $(".changeSkin-gear").css("bottom", "0");
                    if ($(window).height() > 950) {
                        $(".cd-top.cd-is-visible").css("top", "0");
                    } else {
                        $(".cd-top.cd-is-visible").css("top", ($(window).height() - 950) + "px");
                    }
                } else {
                    $(".changeSkin-gear").css("bottom", "-999px");
                    $(".cd-top.cd-is-visible").css("top", "-900px");
                    $back_to_top.removeClass('cd-is-visible cd-fade-out');
                }
                if ($(this).scrollTop() > offset_opacity) {
                    $back_to_top.addClass('cd-fade-out');
                }
            });
            //smooth scroll to top
            $back_to_top.on('click', function (event) {
                event.preventDefault();
                $('body,html').animate({
                    scrollTop: 0,
                }, scroll_top_duration);
            });
        }
    }
$(function () {
    Siren.AH();
    Siren.PE();
    Siren.NH();
    Siren.GT();
    Siren.XLS();
    Siren.XCS();
    Siren.XCP();
    Siren.CE();
    Siren.MN();
    Siren.IA();
    Siren.LV();
    if (Poi.pjax) {
        $(document).pjax('a[target!=_top]', '#page', {
            fragment: '#page',
            timeout: 8000,
        }).on('pjax:beforeSend', () => {  //离开页面停止播放
            $('.normal-cover-video').each(function() {
                this.pause();
                this.src = '';
                this.load = '';
            });
        }).on('pjax:send', function () {
            $("#bar").css("width", "0%");
            if (mashiro_option.NProgressON) NProgress.start();
            Siren.MNH();
        }).on('pjax:complete', function () {
            Siren.AH();
            Siren.PE();
            Siren.CE();
            if (mashiro_option.NProgressON) NProgress.done();
            mashiro_global.ini.pjax();
            $("#loading").fadeOut(500);
            if (Poi.codelamp == 'open') {
                self.Prism.highlightAll(event)
            };
            if ($('.ds-thread').length > 0) {
                if (typeof DUOSHUO !== 'undefined') {
                    DUOSHUO.EmbedThread('.ds-thread');
                } else {
                    $.getScript("//static.duoshuo.com/embed.js");
                }
            }
        }).on('submit', '.search-form,.s-search', function (event) {
            event.preventDefault();
            $.pjax.submit(event, '#page', {
                fragment: '#page',
                timeout: 8000,
            });
            if ($('.js-search.is-visible').length > 0) {
                $('.js-toggle-search').toggleClass('is-active');
                $('.js-search').toggleClass('is-visible');
            }
        });
        window.addEventListener('popstate', function (e) {
            Siren.AH();
            Siren.PE();
            Siren.CE();
            timeSeriesReload(true);
        }, false);
    }
    $.fn.postLike = function () {
        if ($(this).hasClass('done')) {
            return false;
        } else {
            $(this).addClass('done');
            var id = $(this).data("id"),
                action = $(this).data('action'),
                rateHolder = $(this).children('.count');
            var ajax_data = {
                action: "specs_zan",
                um_id: id,
                um_action: action
            };
            $.post(Poi.ajaxurl, ajax_data, function (data) {
                $(rateHolder).html(data);
            });
            return false;
        }
    };
    $(document).on("click", ".specsZan", function () {
        $(this).postLike();
    });
    console.log("%c Mashiro %c", "background:#24272A; color:#ffffff", "", "https://2heng.xin/");
    console.log("%c Github %c", "background:#24272A; color:#ffffff", "", "https://github.com/mashirozx");
});
var isWebkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1,
    isOpera = navigator.userAgent.toLowerCase().indexOf('opera') > -1,
    isIe = navigator.userAgent.toLowerCase().indexOf('msie') > -1;
if ((isWebkit || isOpera || isIe) && document.getElementById && window.addEventListener) {
    window.addEventListener('hashchange', function () {
        var id = location.hash.substring(1),
            element;
        if (!(/^[A-z0-9_-]+$/.test(id))) {
            return;
        }
        element = document.getElementById(id);
        if (element) {
            if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                element.tabIndex = -1;
            }
            element.focus();
        }
    }, false);
}
