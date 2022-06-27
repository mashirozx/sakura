function hasClass(el, className) {
    if (el.classList)
        var e = el.classList.contains(className);
    else
        var e = new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className);
    return e;
}