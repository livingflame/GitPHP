require(["ace/ace", "ace/ext/static_highlight","ace/lib/dom"], function(ace,highlight,dom) {
    function qsa(sel) {
        return Array.apply(null, document.querySelectorAll(sel));
    }

    qsa(".highlight").forEach(function (codeEl) {
        highlight(codeEl, {
            mode: codeEl.getAttribute("data-ace-mode"),
            theme: codeEl.getAttribute("data-ace-theme"),
            showGutter: codeEl.getAttribute("data-ace-gutter"),
            trim: false
        }, function (highlighted) {
            
        });
    });
});