require(["showdown/showdown"], function(showdown) {
	var converter = new showdown.Converter();
    converter.setFlavor('github');
    converter.setOption('tables',true);
    converter.setOption('strikethrough',true);
    converter.setOption('simplifiedAutoLink',true);
    converter.setOption('tasklists',true);
    converter.setOption('ghMentions',true);
    converter.setOption('simpleLineBreaks',true);
    converter.setOption('excludeTrailingPunctuationFromURLs',true);
    function qsa(sel) {
        return Array.apply(null, document.querySelectorAll(sel));
    }

    qsa(".highlight").forEach(function (codeEl) {
		codeEl.innerHTML = converter.makeHtml(codeEl.textContent);
    });
	
});